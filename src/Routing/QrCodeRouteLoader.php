<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Routing;

use Endroid\QrCodeBundle\Controller\GenerateController;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final readonly class QrCodeRouteLoader
{
    public function __construct(
        private string $originalResource,
        private string $routePrefix,
    ) {}

    public function __invoke(LoaderInterface $loader, ?string $_env): RouteCollection
    {
        /** @var RouteCollection $collection */
        $collection = $loader->load($this->originalResource);

        // Only add the route if it wasn't already discovered
        // (e.g. via routing.controllers on Symfony 7.4+ or explicit import)
        if (!$collection->get('qr_code_generate')) {
            $route = new Route(
                $this->routePrefix . '/{builder}/{data}',
                ['_controller' => GenerateController::class],
                ['data' => '[\w\W]+'],
            );
            $route->setOption('utf8', true);
            $collection->add('qr_code_generate', $route);
        }

        return $collection;
    }
}
