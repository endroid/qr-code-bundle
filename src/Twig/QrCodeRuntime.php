<?php

declare(strict_types=1);

namespace Endroid\QrCodeBundle\Twig;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class QrCodeRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private BuilderRegistryInterface $builderRegistry,
        private UrlGeneratorInterface $urlGenerator
    ) {
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

    public function qrCodeDataUriFunction(string $data, string $builder = 'default', array $options = []): string
    {
        $result = $this->qrCodeResultFunction($data, $builder, $options);

        return $result->getDataUri();
    }

    public function qrCodeResultFunction(string $data, string $builder = 'default', array $options = []): ResultInterface
    {
        $builder = $this->builderRegistry->getBuilder($builder);
        foreach ($options as $option => $value) {
            if (!method_exists($builder, $option)) {
                \trigger_error(
                    "$option is not a known option of the $builder builder.",
                    \E_USER_WARNING
                );

                continue;
            }
            $builder->$option($value);
        }

        if (!$builder instanceof Builder) {
            throw new \Exception('This twig extension only handles Builder instances');
        }

        return $builder->data($data)->build();
    }
}
