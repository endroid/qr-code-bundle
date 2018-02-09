<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests;

use Endroid\BundleTest\BundleTestCase;

class DemoControllerTest extends BundleTestCase
{
    public function testDemoController()
    {
        $client = static::createClient();
        $client->request('GET', '/qr-code/demo');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('data:image/svg+xml;base64,', $client->getResponse()->getContent());
    }
}
