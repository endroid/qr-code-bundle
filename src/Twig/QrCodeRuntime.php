<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Twig;

use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Endroid\QrCode\QrCode;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class QrCodeRuntime implements RuntimeExtensionInterface
{
    private $qrCodeFactory;
    private $urlGenerator;

    public function __construct(QrCodeFactoryInterface $qrCodeFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->qrCodeFactory = $qrCodeFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /** @param array<mixed> $options */
    public function qrCodeUrlFunction(string $text, array $options = []): string
    {
        return $this->getQrCodeReference($text, $options, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /** @param array<mixed> $options */
    public function qrCodePathFunction(string $text, array $options = []): string
    {
        return $this->getQrCodeReference($text, $options, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /** @param array<mixed> $options */
    public function getQrCodeReference(string $text, array $options = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        $qrCode = $this->qrCodeFactory->create($text, $options);

        if ($qrCode instanceof QrCode) {
            $supportedExtensions = $qrCode->getWriter()->getSupportedExtensions();
            $options['extension'] = current($supportedExtensions);
        }

        $options['text'] = $text;

        return $this->urlGenerator->generate('qr_code_generate', $options, $referenceType);
    }

    /** @param array<mixed> $options */
    public function qrCodeDataUriFunction(string $text, array $options = []): string
    {
        return $this->qrCodeFactory->create($text, $options)->writeDataUri();
    }
}
