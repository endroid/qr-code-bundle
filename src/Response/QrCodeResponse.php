<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Response;

use Endroid\QrCode\Writer\Result\ResultInterface;
use Symfony\Component\HttpFoundation\Response;

final class QrCodeResponse extends Response
{
    public function __construct(ResultInterface $result)
    {
        parent::__construct($result->getString(), Response::HTTP_OK, ['Content-Type' => $result->getMimeType()]);
    }
}
