<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Controller;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Component\HttpFoundation\Response;

class GenerateController
{
    /** @var BuilderRegistryInterface */
    private $builderRegistry;

    public function __construct(BuilderRegistryInterface $builderRegistry)
    {
        $this->builderRegistry = $builderRegistry;
    }

    public function __invoke(string $builder, string $data): Response
    {
        $builder = $this->builderRegistry->getBuilder($builder);

        if (!$builder instanceof Builder) {
            throw new \Exception('This controller only handles Builder instances');
        }

        return new QrCodeResponse($builder->data($data)->build());
    }
}
