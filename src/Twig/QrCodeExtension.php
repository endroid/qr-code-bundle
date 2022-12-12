<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class QrCodeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('qr_code_path', [QrCodeRuntime::class, 'qrCodePathFunction']),
            new TwigFunction('qr_code_url', [QrCodeRuntime::class, 'qrCodeUrlFunction']),
            new TwigFunction('qr_code_data_uri', [QrCodeRuntime::class, 'qrCodeDataUriFunction']),
            new TwigFunction('qr_code_result', [QrCodeRuntime::class, 'qrCodeResultFunction']),
        ];
    }
}
