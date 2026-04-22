<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Tests;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\Writer\Result\SvgResult;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BuilderTest extends WebTestCase
{
    #[TestDox('The builder registry contains the configured builders')]
    public function testBuilderRegistry(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var BuilderRegistryInterface $builderRegistry */
        $builderRegistry = $container->get(BuilderRegistryInterface::class);

        $defaultBuilder = $builderRegistry->get('default');
        static::assertInstanceOf(Builder::class, $defaultBuilder);

        $customBuilder = $builderRegistry->get('custom');
        static::assertInstanceOf(Builder::class, $customBuilder);
    }

    #[TestDox('The builder can generate a QR code')]
    public function testBuilderDefault()
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var BuilderRegistryInterface $builderRegistry */
        $builderRegistry = $container->get(BuilderRegistryInterface::class);

        $customBuilder = $builderRegistry->get('custom');
        $result = $customBuilder->build();

        static::assertInstanceOf(SvgResult::class, $result);
    }
}
