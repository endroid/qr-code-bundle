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
    public function testGenerateController()
    {
        $client = static::createClient();
        $client->request('GET', '/qr-code/qr-code.png');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('image/png', $client->getResponse()->headers->get('Content-Type'));
    }
}
