<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\Writer\Result\SvgResult;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Kernel;

class BuilderTest extends WebTestCase
{
    /**
     * @testdox The builder registry contains the configured builders
     */
    public function testBuilderRegistry()
    {
        if (Kernel::VERSION_ID < 41000) {
            $this->markTestSkipped('This test can be performed from Symfony 4.1');
        }

        self::bootKernel();

        /** @var BuilderRegistryInterface $builderRegistry */
        $builderRegistry = self::$container->get(BuilderRegistryInterface::class);

        $defaultBuilder = $builderRegistry->getBuilder('default');
        $this->assertInstanceOf(Builder::class, $defaultBuilder);

        $customBuilder = $builderRegistry->getBuilder('custom');
        $this->assertInstanceOf(Builder::class, $customBuilder);
    }

    /**
     * @testdox Builder can generate QR code
     */
    public function testBuilderDefault()
    {
        if (Kernel::VERSION_ID < 41000) {
            $this->markTestSkipped('This test can be performed from Symfony 4.1');
        }

        self::bootKernel();

        /** @var BuilderRegistryInterface $builderRegistry */
        $builderRegistry = self::$container->get(BuilderRegistryInterface::class);

        $customBuilder = $builderRegistry->getBuilder('custom');
        $result = $customBuilder->build();

        $this->assertInstanceOf(SvgResult::class, $result);
    }
}
