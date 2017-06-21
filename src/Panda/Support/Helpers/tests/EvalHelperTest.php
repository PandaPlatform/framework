<?php

namespace Panda\Support\Helpers\tests;

use Panda\Support\Helpers\EvalHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class EvalHelperTest
 * @package Panda\Support\Helpers\tests
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
