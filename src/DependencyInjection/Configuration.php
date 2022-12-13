<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('endroid_qr_code');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

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
