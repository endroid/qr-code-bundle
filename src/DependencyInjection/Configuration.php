<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** @psalm-suppress PossiblyUndefinedMethod */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('endroid_qr_code');

        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();

        $node
            ->beforeNormalization()
                ->ifTrue(fn (array $config) => !$this->hasMultipleConfigurations($config))
                ->then(fn (array $value) => ['default' => $value]);

        $node->useAttributeAsKey('name');
        $node->prototype('array');
        $node->prototype('variable');

        return $treeBuilder;
    }

    /** @param array<mixed> $config */
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
