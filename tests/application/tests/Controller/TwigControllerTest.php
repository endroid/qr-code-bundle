<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TwigControllerTest extends WebTestCase
{
    public function testDemoController()
    {
        $client = static::createClient();

        $client->request('GET', '/twig');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('data:image/png;base64,', $client->getResponse()->getContent());
    }
}
