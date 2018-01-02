<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests\Twig;

use Endroid\QrCode\Factory\QrCodeFactory;
use Endroid\QrCode\QrCodeInterface;
use Endroid\QrCodeBundle\Twig\QrCodeRoutingExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class QrCodeRoutingExtensionTest extends TestCase
{
    public function testQrCodeUrlFunction()
    {
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $extension = new QrCodeRoutingExtension(new QrCodeFactory(), $urlGenerator->reveal());

        $urlGenerator
            ->generate('endroid_qr_code_generate', ['extension' => 'png', 'text' => 'Foobar'], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('https://some-qr-code-url');

        $this->assertSame('https://some-qr-code-url', $extension->qrCodeUrlFunction('Foobar'));
    }

    public function testQrCodePathFunction()
    {
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $extension = new QrCodeRoutingExtension(new QrCodeFactory(), $urlGenerator->reveal());

        $urlGenerator
            ->generate('endroid_qr_code_generate', ['extension' => 'png', 'text' => 'Foobar'], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/some-qr-code-path');

        $this->assertSame('/some-qr-code-path', $extension->qrCodePathFunction('Foobar'));
    }

    public function testGetQrCodeReferenceDifferentImplementation()
    {
        $code = $this->createMock(QrCodeInterface::class);
        $factory = $this->prophesize(QrCodeFactory::class);
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $extension = new QrCodeRoutingExtension($factory->reveal(), $urlGenerator->reveal());

        $factory->create('Foobar', [])->willReturn($code);

        $urlGenerator
            ->generate('endroid_qr_code_generate', ['text' => 'Foobar'], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn('/some-qr-code-path');

        $this->assertSame('/some-qr-code-path', $extension->getQrCodeReference('Foobar', [], UrlGeneratorInterface::ABSOLUTE_PATH));
    }
}
