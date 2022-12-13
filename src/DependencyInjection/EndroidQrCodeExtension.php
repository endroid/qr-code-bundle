<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\DependencyInjection;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface;
use Endroid\QrCode\Label\Alignment\LabelAlignmentInterface;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class EndroidQrCodeExtension extends Extension
{
    /**
     * @param array<string, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration instanceof ConfigurationInterface) {
            throw new \Exception('Configuration not found');
        }

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $registryDefinition = $container->findDefinition(BuilderRegistryInterface::class);

        foreach ($config as $builderName => $builderConfig) {
            $builderDefinition = $this->createBuilderDefinition($builderName, $builderConfig, $container);
            $registryDefinition->addMethodCall('addBuilder', [$builderName, $builderDefinition]);
        }
    }

    /** @param array<mixed> $builderConfig */
    private function createBuilderDefinition(string $builderName, array $builderConfig, ContainerBuilder $container): Definition
    {
        $id = sprintf('endroid_qr_code.%s_builder', $builderName);

        $builderDefinition = new ChildDefinition(BuilderInterface::class);

        $options = [];
        foreach ($builderConfig as $name => $value) {
            switch ($name) {
                case 'writer':
                    $options[$name] = new Reference($value);
                    break;
                case 'encoding':
                    $options[$name] = new Definition(Encoding::class, [$value]);
                    break;
                case 'errorCorrectionLevel':
                    $options[$name] = new Definition(str_replace('Interface', ucfirst($value), ErrorCorrectionLevelInterface::class));
                    break;
                case 'roundBlockSizeMode':
                    $options[$name] = new Definition(str_replace('Interface', ucfirst($value), RoundBlockSizeModeInterface::class));
                    break;
                case 'foregroundColor':
                case 'backgroundColor':
                case 'labelTextColor':
                    $options[$name] = new Definition(Color::class, $value);
                    break;
                case 'labelFontPath':
                    $labelFontSize = $builderConfig['labelFontSize'] ?? 16;
                    $options['labelFont'] = new Definition(Font::class, [$value, $labelFontSize]);
                    break;
                case 'labelFontSize':
                    $labelFontPath = $builderConfig['labelFontPath'] ?? (new NotoSans())->getPath();
                    $options['labelFont'] = new Definition(Font::class, [$labelFontPath, $value]);
                    break;
                case 'labelAlignment':
                    $options[$name] = new Definition(str_replace('Interface', ucfirst($value), LabelAlignmentInterface::class));
                    break;
                default:
                    $options[$name] = $value;
                    break;
            }
        }

        foreach ($options as $name => $value) {
            $builderDefinition->addMethodCall($name, [$value]);
            $builderDefinition->setPublic(true);
        }

        $container->setDefinition($id, $builderDefinition);

        if (method_exists($container, 'registerAliasForArgument')) {
            $container->registerAliasForArgument($id, BuilderInterface::class, $builderName.'QrCodeBuilder')->setPublic(false);
        }

        return $builderDefinition;
    }
}
