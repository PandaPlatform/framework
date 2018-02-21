<?php

/*
 * This file is part of the Panda Helpers Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Helpers\Tests;

use Panda\Support\Helpers\StringHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class StringHelperTest
 * @package Panda\Support\Helpers\Tests
 */
class StringHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Support\Helpers\StringHelper::startsWith
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testStartsWith()
    {
        // Valid cases
        $this->assertTrue(StringHelper::startsWith('once upon a time', 'once'));
        $this->assertTrue(StringHelper::startsWith('once upon a time', 'once '));

        // Invalid cases
        $this->assertFalse(StringHelper::startsWith('once upon a time', 'once  '));
        $this->assertFalse(StringHelper::startsWith('once upon a time', 'twice'));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::endsWith
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testEndsWith()
    {
        // Valid cases
        $this->assertTrue(StringHelper::endsWith('once upon a time', 'time'));
        $this->assertTrue(StringHelper::endsWith('once upon a time', ' time'));

        // Invalid cases
        $this->assertFalse(StringHelper::endsWith('once upon a time', '  time'));
        $this->assertFalse(StringHelper::endsWith('once upon a time', 'year'));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::concatenate
     */
    public function testConcatenate()
    {
        $this->assertEquals('a', StringHelper::concatenate('a', '', 'b'));
        $this->assertEquals('c', StringHelper::concatenate('', 'c', 'b'));
        $this->assertEquals('abc', StringHelper::concatenate('a', 'c', 'b'));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::interpolate
     */
    public function testInterpolate()
    {
        $string = 'Hello %{first_name} %{last_name}';
        $parameters = [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'parents' => [
                'dad' => [
                    'first_name' => 'Bill',
                    'last_name' => 'Smith',
                ],
                'mom' => [
                    'first_name' => 'Sarah',
                    'last_name' => 'Smith',
                ],
            ],
        ];

        $this->assertEquals('Hello John Smith', StringHelper::interpolate($string, $parameters, '%{', '}'));

        // Change opening and closing tags (fallback)
        $string = 'Hello {first_name} {last_name}';
        $this->assertEquals('Hello John Smith', StringHelper::interpolate($string, $parameters, '%{', '}', true));

        // Change opening and closing tags
        $string = 'Hello $[first_name] $[last_name]';
        $this->assertEquals('Hello John Smith', StringHelper::interpolate($string, $parameters, '$[', ']', false));

        // Change opening and closing tags (plus fallback)
        $string = 'Hello $[first_name] {last_name}';
        $this->assertEquals('Hello John Smith', StringHelper::interpolate($string, $parameters, '$[', ']', true));

        // Check for dot syntax
        $string = 'Hello %{first_name}, son of %{parents.dad.first_name} and %{parents.mom.first_name}';
        $this->assertEquals('Hello John, son of Bill and Sarah', StringHelper::interpolate($string, $parameters));

        // Check for empty replace values
        $string = 'Hello %{first_name}, son of %{parents.dad.first_name} and %{parents.mom.first_name}, from %{location}.';
        $this->assertEquals('Hello John, son of Bill and Sarah, from %{location}.', StringHelper::interpolate($string, $parameters));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::explode
     */
    public function testExplode()
    {
        $string = 'Once upon a time';
        $array = ['Once', 'upon', 'a', 'time'];

        // Simple call
        $this->assertEquals($array, StringHelper::explode($string, ' ', false));

        // Other delimiter
        $string = 'Once,upon,a,time';
        $this->assertEquals($array, StringHelper::explode($string, ',', false));

        // Group quotes
        $string = 'Once upon "a time"';
        $array = ['Once', 'upon', 'a time'];
        $this->assertEquals($array, StringHelper::explode($string, ' ', true));

        // Group quotes, different delimiter
        // todo: Improve the explode() function to check for all given delimiters
        // $string = 'Once,upon,"a,time"';
        // $array = ['Once', 'upon', 'a,time'];
        // $this->assertEquals($array, StringHelper::explode($string, ',', true));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::emptyString
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testEmptyString()
    {
        $this->assertTrue(StringHelper::emptyString(''));
        $this->assertTrue(StringHelper::emptyString(null, true));

        $this->assertFalse(StringHelper::emptyString(null, false));
        $this->assertFalse(StringHelper::emptyString('0'));
        $this->assertFalse(StringHelper::emptyString(0));
    }

    /**
     * @covers \Panda\Support\Helpers\StringHelper::contains
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function testContains()
    {
        // Valid cases
        $this->assertTrue(StringHelper::contains('once upon a time', 'once'));
        $this->assertTrue(StringHelper::contains('once upon a time', ['once', 'upon']));

        // Invalid cases
        $this->assertFalse(StringHelper::contains('once upon a time', 'once1'));
        $this->assertFalse(StringHelper::contains('once upon a time', ['once', 'upon', 'three']));
    }
}
