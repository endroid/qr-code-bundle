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

class TwigControllerTest extends WebTestCase
{
    public function testDemoController()
    {
        $client = static::createClient();
        $client->request('GET', '/twig');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('data:image/png;base64,', $client->getResponse()->getContent());
    }
}
