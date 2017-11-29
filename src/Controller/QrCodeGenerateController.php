<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Controller;

use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class QrCodeGenerateController
{
    public function __invoke(Request $request, QrCodeFactoryInterface $qrCodeFactory, string $text, string $extension): Response
    {
        $options = $request->query->all();

        $qrCode = $qrCodeFactory->create($text, $options);
        $qrCode->setWriterByExtension($extension);

        return new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => $qrCode->getContentType()]);
    }
}
