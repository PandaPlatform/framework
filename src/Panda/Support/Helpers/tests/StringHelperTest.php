<?php

namespace Panda\Support\Helpers\tests;

use Panda\Support\Helpers\StringHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class StringHelperTest
 * @package Panda\Support\Helpers\tests
 */
class StringHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Support\Helpers\StringHelper::contains
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testContains()
    {
        $this->assertTrue(StringHelper::contains('once upon a time', 'once'));
        $this->assertTrue(StringHelper::contains('once upon a time', ['once', 'upon']));

        $this->assertFalse(StringHelper::contains('once upon a time', 'once1'));
        $this->assertFalse(StringHelper::contains('once upon a time', ['once', 'upon', 'three']));
    }
}
