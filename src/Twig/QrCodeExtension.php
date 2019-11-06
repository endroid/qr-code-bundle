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
use Twig\TwigFunction;
use Twig_Extension;

final class QrCodeExtension extends Twig_Extension
{
    private $qrCodeFactory;
    private $urlGenerator;

    public function __construct(QrCodeFactoryInterface $qrCodeFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->qrCodeFactory = $qrCodeFactory;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('qr_code_path', [$this, 'qrCodePathFunction']),
            new TwigFunction('qr_code_url', [$this, 'qrCodeUrlFunction']),
            new TwigFunction('qr_code_data_uri', [$this, 'qrCodeDataUriFunction']),
        ];
    }

    public function qrCodeUrlFunction(string $text, array $options = []): string
    {
        return $this->getQrCodeReference($text, $options, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function qrCodePathFunction(string $text, array $options = []): string
    {
        return $this->getQrCodeReference($text, $options, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function getQrCodeReference(string $text, array $options = [], int $referenceType): string
    {
        $qrCode = $this->qrCodeFactory->create($text, $options);

        if ($qrCode instanceof QrCode) {
            $supportedExtensions = $qrCode->getWriter()->getSupportedExtensions();
            $options['extension'] = current($supportedExtensions);
        }

        $options['text'] = $text;

        return $this->urlGenerator->generate('qr_code_generate', $options, $referenceType);
    }

    public function qrCodeDataUriFunction(string $text, array $options = []): string
    {
        return $this->qrCodeFactory->create($text, $options)->writeDataUri();
    }
}
