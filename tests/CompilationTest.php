<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCodeBundle\Tests;

use Endroid\QrCodeBundle\Tests\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompilationTest extends KernelTestCase
{
    protected static function createKernel(array $options = array())
    {
        return new TestKernel($options);
    }

    public function test(): void
    {
        self::bootKernel();

        $this->assertNotNull(self::$kernel->getContainer());
    }
}
