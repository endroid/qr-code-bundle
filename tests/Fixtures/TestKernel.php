<?php
namespace Endroid\QrCodeBundle\Tests\Fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    private $config_file;

    public function __construct(array $options)
    {
        $this->config_file = $options['config_file'] ?? 'config.yml';

        parent::__construct($options['environment'] ?? 'test', $options['debug'] ?? true);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Endroid\QrCodeBundle\EndroidQrCodeBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__."/config/{$this->config_file}");
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return __DIR__.'/../../var/cache/'.md5($this->getEnvironment().$this->config_file);
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return __DIR__.'/../../var/logs';
    }
}
