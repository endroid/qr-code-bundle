<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RouteLoaderCompilerPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('endroid_qr_code.route_loader')) {
            return;
        }

        if (!$container->hasParameter('router.resource')) {
            return;
        }

        // Only intercept when the router uses 'service' type (standard MicroKernel setup)
        $router = $container->findDefinition('router.default');
        /** @var array<string, mixed> $options */
        $options = $router->getArgument(2);
        if ('service' !== ($options['resource_type'] ?? null)) {
            return;
        }

        $originalResource = $container->getParameter('router.resource');
        $container->getDefinition('endroid_qr_code.route_loader')->setArgument(0, $originalResource);
        $container->setParameter('router.resource', 'endroid_qr_code.route_loader');
    }
}
