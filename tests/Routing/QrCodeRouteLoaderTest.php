<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Tests\Routing;

use Endroid\QrCodeBundle\Routing\QrCodeRouteLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class QrCodeRouteLoaderTest extends TestCase
{
    #[DataProvider('providePriorities')]
    public function testRoutePriority(?int $priority, int $catchAllPriority, string $expectedRoute): void
    {
        $routes = new RouteCollection();
        $routes->add('catch_all', new Route('/{slug}', [], ['slug' => '.*']), $catchAllPriority);

        $loader = $this->createStub(LoaderInterface::class);
        $loader->method('load')->willReturn($routes);

        $routeLoader = $priority === null
            ? new QrCodeRouteLoader('app', '/2fa/qr')
            : new QrCodeRouteLoader('app', '/2fa/qr', $priority);
        $matcher = new UrlMatcher($routeLoader($loader, null), new RequestContext());

        self::assertSame($expectedRoute, $matcher->match('/2fa/qr/default/test')['_route']);
        self::assertSame('catch_all', $matcher->match('/other/page')['_route']);
    }

    public static function providePriorities(): iterable
    {
        yield 'omitted priority preserves application precedence' => [null, 0, 'catch_all'];
        yield 'equal priority preserves application precedence' => [0, 0, 'catch_all'];
        yield 'higher priority beats catch-all' => [1, 0, 'qr_code_generate'];
        yield 'higher application priority still wins' => [1, 2, 'catch_all'];
        yield 'negative priorities are respected' => [-2, -1, 'catch_all'];
    }

    public function testExplicitRouteIsPreserved(): void
    {
        $routes = new RouteCollection();
        $routes->add('catch_all', new Route('/{slug}', [], ['slug' => '.*']));
        $routes->add('qr_code_generate', new Route('/manual/{data}'), 1);

        $loader = $this->createStub(LoaderInterface::class);
        $loader->method('load')->willReturn($routes);

        $routeLoader = new QrCodeRouteLoader('app', '/2fa/qr', -1);
        $matcher = new UrlMatcher($routeLoader($loader, null), new RequestContext());

        self::assertSame('qr_code_generate', $matcher->match('/manual/test')['_route']);
        self::assertSame('catch_all', $matcher->match('/2fa/qr/default/test')['_route']);
    }
}
