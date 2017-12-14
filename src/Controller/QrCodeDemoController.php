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
    private $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(): Response
    {
        $renderedView = $this->templating->render('@EndroidQrCode/QrCode/demo.html.twig', ['message' => 'QR Code']);

        return new Response($renderedView, Response::HTTP_OK);
    }
}
