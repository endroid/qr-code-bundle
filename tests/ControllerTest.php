<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests;

use Endroid\QrCodeBundle\Tests\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase
{
    protected static function createKernel(array $options = array())
    {
        return new TestKernel($options);
    }

    public function testDemo(): void
    {
        self::bootKernel(['config_file' => 'controller.yaml']);

        $client = self::createClient(['config_file' => 'controller.yaml']);
        $crawler = $client->request('GET', '/demo');

        self::assertContains('data:image/svg+xml;base64,', $crawler->html());
    }

    public function testGenerate(): void
    {
        self::bootKernel(['config_file' => 'controller.yaml']);

        $client = self::createClient(['config_file' => 'controller.yaml']);
        $client->request('GET', '/foobar.png');

        $response = $client->getResponse();

        self::assertSame('image/png', $response->headers->get('content-type'));
        self::assertNotEmpty($response->getContent());
    }

    public function testGenerateInvalidExtension(): void
    {
        self::bootKernel(['config_file' => 'controller.yaml']);

        $client = self::createClient(['config_file' => 'controller.yaml']);
        $crawler = $client->request('GET', '/foobar.extension');

        $this->assertContains('Extension \'extension\' is not a supported extension.', $crawler->html());
    }
}
