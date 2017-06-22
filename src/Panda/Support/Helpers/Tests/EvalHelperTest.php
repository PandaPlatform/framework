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

use Panda\Support\Helpers\EvalHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class EvalHelperTest
 * @package Panda\Support\Helpers\Tests
 */
class EvalHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Support\Helpers\EvalHelper::evaluate
     */
    public function testEvaluate()
    {
        // Normal values
        $this->assertEquals('value', EvalHelper::evaluate('value'));
        $this->assertEquals('', EvalHelper::evaluate(''));
        $this->assertEquals(123, EvalHelper::evaluate(123));
        $array = [12, 34, 45 => 12];
        $this->assertEquals($array, EvalHelper::evaluate($array));

        // Closures
        $this->assertEquals('value', EvalHelper::evaluate(function () {
            return 'value';
        }));
        $this->assertEquals(123, EvalHelper::evaluate(function () {
            return 123;
        }));
        $this->assertEquals([12, 34, 45 => 12], EvalHelper::evaluate(function () {
            return [12, 34, 45 => 12];
        }));
    }
}
