<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WriterRegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('Endroid\QrCode\WriterRegistryInterface')) {
            return;
        }

        $writerRegistryDefinition = $container->findDefinition('Endroid\QrCode\WriterRegistryInterface');

        $taggedServices = $container->findTaggedServiceIds('endroid.qr_code.writer');
        foreach ($taggedServices as $id => $tags) {
            $writerRegistryDefinition->addMethodCall('addWriter', [new Reference($id)]);
        }
    }
}
