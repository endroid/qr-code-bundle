<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** @psalm-suppress PossiblyUndefinedMethod */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        /** @psalm-suppress TooManyArguments */
        $treeBuilder = new TreeBuilder('endroid_qr_code');

        if (method_exists($treeBuilder, 'root')) {
            $rootNode = $treeBuilder->root('endroid_qr_code');
        } else {
            /** @psalm-suppress UndefinedMethod */
            $rootNode = $treeBuilder->getRootNode();
        }

        $rootNode
            ->beforeNormalization()
                ->ifTrue(fn (array $config) => !$this->hasMultipleConfigurations($config))
                ->then(fn (array $value) => ['default' => $value]);

        $rootNode->useAttributeAsKey('name');
        $rootNode->prototype('array');
        $rootNode->prototype('variable');

        return $treeBuilder;
    }

    /** @param array<string, mixed> $config */
    private function hasMultipleConfigurations(array $config): bool
    {
        foreach ($config as $value) {
            if (!is_array($value)) {
                return false;
            }
        }

        return true;
    }
}
