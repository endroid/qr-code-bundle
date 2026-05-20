<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Builder\BuilderRegistry;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\BinaryWriter;
use Endroid\QrCode\Writer\DebugWriter;
use Endroid\QrCode\Writer\EpsWriter;
use Endroid\QrCode\Writer\GifWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCodeBundle\Controller\GenerateController;
use Endroid\QrCodeBundle\DependencyInjection\RouteLoaderCompilerPass;
use Endroid\QrCodeBundle\Routing\QrCodeRouteLoader;
use Endroid\QrCodeBundle\Twig\QrCodeExtension;
use Endroid\QrCodeBundle\Twig\QrCodeRuntime;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class EndroidQrCodeBundle extends AbstractBundle
{
    #[\Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
        $rootNode = $definition->rootNode();

        /** @var \Symfony\Component\Config\Definition\Builder\NodeBuilder $children */
        $children = $rootNode->children();

        $children->scalarNode('route_prefix')->defaultValue('/qr-code');
        $children->booleanNode('route_enabled')->defaultTrue();
        $children
            ->arrayNode('builders')
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->useAttributeAsKey('name')
            ->variablePrototype();
    }

    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RouteLoaderCompilerPass());
    }

    /** @param array<array-key, mixed> $config */
    #[\Override]
    public function loadExtension(array $config, ContainerConfigurator $configurator, ContainerBuilder $container): void
    {
        $services = $configurator->services();
        $services->defaults()->autowire()->autoconfigure();

        // Writers
        $services->set(BinaryWriter::class);
        $services->set(DebugWriter::class);
        $services->set(EpsWriter::class);
        $services->set(GifWriter::class);
        $services->set(PdfWriter::class);
        $services->set(PngWriter::class);
        $services->set(SvgWriter::class);
        $services->set(WebPWriter::class);

        // Builder
        $services->set(Builder::class);
        $services->alias(BuilderInterface::class, Builder::class);

        // Registry
        $services->set(BuilderRegistry::class);
        $services->alias(BuilderRegistryInterface::class, BuilderRegistry::class);

        // Twig
        $services->set(QrCodeExtension::class);
        $services->set(QrCodeRuntime::class);

        // Route prefix parameter (always set so the container never fails on an undefined parameter)
        $routePrefix = (string) ($config['route_prefix'] ?? '/qr-code');
        $configurator->parameters()->set('endroid_qr_code.route_prefix', $routePrefix);

        // Controller and route auto-registration (only when routes are enabled)
        /** @var bool $enableRoutes */
        $enableRoutes = $config['route_enabled'] ?? true;
        if ($enableRoutes) {
            $services->set(GenerateController::class)->tag('controller.service_arguments');

            // Route loader wraps the main router resource to inject the bundle's route
            // This ensures routes work on all Symfony versions without user configuration
            $services
                ->set('endroid_qr_code.route_loader', QrCodeRouteLoader::class)
                ->args(['', $routePrefix])
                ->tag('routing.route_loader');
        }

        // Builder configurations
        /** @var array<string, array<string, mixed>> $builders */
        $builders = $config['builders'] ?? [];
        if ($builders === []) {
            $builders = [
                'default' => [
                    'writer' => PngWriter::class,
                    'size' => 300,
                    'margin' => 10,
                    'encoding' => 'UTF-8',
                    'error_correction_level' => 'low',
                    'round_block_size_mode' => 'margin',
                    'validate_result' => false,
                ],
            ];
        }

        $registryDefinition = $container->findDefinition(BuilderRegistryInterface::class);
        foreach ($builders as $builderName => $builderConfig) {
            $builderDefinition = $this->createBuilderDefinition($builderName, $builderConfig, $container);
            $registryDefinition->addMethodCall('set', [$builderName, $builderDefinition]);
        }
    }

    /** @param array<string, mixed> $builderConfig */
    private function createBuilderDefinition(
        string $builderName,
        array $builderConfig,
        ContainerBuilder $container,
    ): Definition {
        $id = sprintf('endroid_qr_code.%s_builder', $builderName);

        $builderDefinition = new ChildDefinition(BuilderInterface::class);

        /** @var array<string, Reference|Definition|ErrorCorrectionLevel|RoundBlockSizeMode|LabelAlignment|string|int|bool> $arguments */
        $arguments = [];
        /** @var string|int|bool|list<int> $value */
        foreach ($builderConfig as $name => $value) {
            $name = $this->toCamelCase($name);
            switch ($name) {
                case 'writer':
                    /** @var string $value */
                    $arguments[$name] = new Reference($value);
                    break;
                case 'encoding':
                    $arguments[$name] = new Definition(Encoding::class, [$value]);
                    break;
                case 'errorCorrectionLevel':
                    /** @var string $value */
                    $arguments[$name] = ErrorCorrectionLevel::from($value);
                    break;
                case 'roundBlockSizeMode':
                    /** @var string $value */
                    $arguments[$name] = RoundBlockSizeMode::from($value);
                    break;
                case 'foregroundColor':
                case 'backgroundColor':
                case 'labelTextColor':
                    /** @var list<int> $value */
                    $arguments[$name] = new Definition(Color::class, $value);
                    break;
                case 'labelFontPath':
                    $labelFontSize = (int) ($builderConfig['label_font_size'] ?? 16);
                    $arguments['labelFont'] = new Definition(Font::class, [$value, $labelFontSize]);
                    break;
                case 'labelFontSize':
                    /** @var string $labelFontPath */
                    $labelFontPath = $builderConfig['label_font_path'] ?? new OpenSans()->getPath();
                    $arguments['labelFont'] = new Definition(Font::class, [$labelFontPath, $value]);
                    break;
                case 'labelAlignment':
                    /** @var string $value */
                    $arguments[$name] = LabelAlignment::from($value);
                    break;
                default:
                    $arguments[$name] = $value;
                    break;
            }
        }

        foreach ($arguments as $argumentName => $argumentValue) {
            $builderDefinition->setArgument('$' . $argumentName, $argumentValue);
        }

        $container->setDefinition($id, $builderDefinition);
        $container
            ->registerAliasForArgument($id, BuilderInterface::class, $builderName . 'QrCodeBuilder')
            ->setPublic(false);

        return $builderDefinition;
    }

    private function toCamelCase(string $anyCase): string
    {
        return lcfirst(str_replace(
            search: ' ',
            replace: '',
            subject: ucwords(str_replace(search: '_', replace: ' ', subject: $anyCase)),
        ));
    }
}
