<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TwigControllerTest extends WebTestCase
{
    public function testDemoController()
    {
        $client = static::createClient();

        $client->request('GET', '/twig');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertStringContainsString('data:image/png;base64,', $client->getResponse()->getContent());

        $client->request('GET', '/twig-invalid-option');
        static::assertSame(500, $client->getResponse()->getStatusCode());
        static::assertMatchesRegularExpression(
            '#Unknown named parameter \$invalidOption#',
            $client->getResponse()->getContent(),
        );
    }
}
