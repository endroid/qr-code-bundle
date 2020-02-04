<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\DependencyInjection;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** @psalm-suppress PossiblyUndefinedMethod */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        /** @psalm-suppress TooManyArguments */
        $treeBuilder = new TreeBuilder('endroid_qr_code');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            /** @psalm-suppress UndefinedMethod */
            $rootNode = $treeBuilder->root('endroid_qr_code');
        }

        $rootNode
            ->children()
                ->scalarNode('writer')->defaultValue('png')->end()
                ->arrayNode('writer_options')
                    ->prototype('scalar')->end()
                ->end()
                ->integerNode('size')->min(0)->end()
                ->integerNode('margin')->min(0)->end()
                ->scalarNode('encoding')->defaultValue('UTF-8')->end()
                ->scalarNode('error_correction_level')
                    ->validate()
                        ->ifNotInArray(ErrorCorrectionLevel::toArray())
                        ->thenInvalid('Invalid error correction level %s')
                    ->end()
                ->end()
                ->arrayNode('foreground_color')
                    ->children()
                        ->scalarNode('r')->isRequired()->end()
                        ->scalarNode('g')->isRequired()->end()
                        ->scalarNode('b')->isRequired()->end()
                        ->scalarNode('a')->defaultValue(0)->end()
                    ->end()
                ->end()
                ->arrayNode('background_color')
                    ->children()
                        ->scalarNode('r')->isRequired()->end()
                        ->scalarNode('g')->isRequired()->end()
                        ->scalarNode('b')->isRequired()->end()
                        ->scalarNode('a')->defaultValue(0)->end()
                    ->end()
                ->end()
                ->scalarNode('logo_path')->end()
                ->integerNode('logo_width')->end()
                ->integerNode('logo_height')->end()
                ->scalarNode('label')->end()
                ->integerNode('label_font_size')->end()
                ->scalarNode('label_font_path')->end()
                ->scalarNode('label_alignment')
                    ->validate()
                        ->ifNotInArray(LabelAlignment::toArray())
                        ->thenInvalid('Invalid label alignment %s')
                    ->end()
                ->end()
                ->arrayNode('label_margin')
                    ->children()
                        ->scalarNode('t')->end()
                        ->scalarNode('r')->end()
                        ->scalarNode('b')->end()
                        ->scalarNode('l')->end()
                    ->end()
                ->end()
                ->booleanNode('validate_result')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
