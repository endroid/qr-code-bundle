<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests\Fixtures;

use Psr\Log\NullLogger;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    private $config_file;

    public function __construct(array $options)
    {
        $this->config_file = $options['config_file'] ?? 'config.yaml';

        parent::__construct($options['environment'] ?? 'test', $options['debug'] ?? true);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Endroid\QrCodeBundle\EndroidQrCodeBundle(),
        ];
    }

    public function build(ContainerBuilder $container)
    {
        $container->register(NullLogger::class)->setDecoratedService('logger');
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
