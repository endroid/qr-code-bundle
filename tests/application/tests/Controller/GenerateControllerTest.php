<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GenerateControllerTest extends WebTestCase
{
    /**
     * @testdox QR Codes can be generated via the url
     */
    public function testGenerateController()
    {
        $client = static::createClient();

        $client->request('GET', '/qr-code/default/test');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('image/png', $client->getResponse()->headers->get('Content-Type'));

        $client->request('GET', '/qr-code/custom/test');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));

        $client->request('GET', '/qr-code/debug/test');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('text/plain; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertStringContainsString('Encoding: ISO-8859-1', $client->getResponse()->getContent());
    }
}
