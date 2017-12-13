<?php
declare(strict_types=1);

namespace Fixtures;

use Endroid\QrCodeBundle\Tests\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompilationTest extends KernelTestCase
{
    protected static function createKernel(array $options = array())
    {
        return new TestKernel($options);
    }

    public function test()
    {
        self::bootKernel();

        $this->assertNotNull(self::$kernel->getContainer());
    }
}
