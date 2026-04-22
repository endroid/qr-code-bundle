<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Tests;

use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GenerateControllerTest extends WebTestCase
{
    #[TestDox('QR Codes can be generated via the url')]
    public function testGenerateController()
    {
        $client = static::createClient();

        $client->request('GET', '/qr-code/default/test');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('image/png', $client->getResponse()->headers->get('Content-Type'));

        $client->request('GET', '/qr-code/custom/test');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));

        $client->request('GET', '/qr-code/debug/test');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame('text/plain; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        static::assertStringContainsString('Encoding: ISO-8859-1', $client->getResponse()->getContent());
    }
}
