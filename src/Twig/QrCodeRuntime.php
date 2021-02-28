<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Twig;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class QrCodeRuntime implements RuntimeExtensionInterface
{
    private $builderRegistry;
    private $urlGenerator;

    public function __construct(BuilderRegistryInterface $builderRegistry, UrlGeneratorInterface $urlGenerator)
    {
        $this->builderRegistry = $builderRegistry;
        $this->urlGenerator = $urlGenerator;
    }

    public function qrCodeUrlFunction(string $data, string $builder = 'default'): string
    {
        return $this->getQrCodeReference($data, $builder, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function qrCodePathFunction(string $data, string $builder = 'default'): string
    {
        return $this->getQrCodeReference($data, $builder, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function getQrCodeReference(string $data, string $builder, int $referenceType): string
    {
        $options = ['data' => $data, 'builder' => $builder];

        return $this->urlGenerator->generate('qr_code_generate', $options, $referenceType);
    }

    public function qrCodeDataUriFunction(string $data, string $builder = 'default'): string
    {
        $builder = $this->builderRegistry->getBuilder($builder);

        if (!$builder instanceof Builder) {
            throw new \Exception('This twig extension only handles Builder instances');
        }

        return $builder->data($data)->build()->getDataUri();
    }
}
