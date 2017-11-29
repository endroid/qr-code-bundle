<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class QrCodeDemoController
{
    public function __invoke(Environment $twig): Response
    {
        $renderedView = $twig->render('@EndroidQrCode/QrCode/demo.html.twig', ['message' => 'QR Code']);

        return new Response($renderedView, Response::HTTP_OK);
    }
}
