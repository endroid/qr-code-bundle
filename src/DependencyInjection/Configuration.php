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
        $treeBuilder = new TreeBuilder('endroid_qr_code');

        $treeBuilder
            ->getRootNode()
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->prototype('variable')
        ;

        return $treeBuilder;
    }
}
