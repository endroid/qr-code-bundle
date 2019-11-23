<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class QrCodeExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('qr_code_path', [QrCodeRuntime::class, 'qrCodePathFunction']),
            new TwigFunction('qr_code_url', [QrCodeRuntime::class, 'qrCodeUrlFunction']),
            new TwigFunction('qr_code_data_uri', [QrCodeRuntime::class, 'qrCodeDataUriFunction']),
        ];
    }
}
