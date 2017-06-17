<?php

namespace Panda\Helpers\tests;

use Panda\Helpers\ArrayHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class ArrayHelperTest
 * @package Panda\Helpers\tests
 */
class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Helpers\ArrayHelper::get
     */
    public function testGet()
    {
        // Plain getter
        $this->assertEquals('test', ArrayHelper::get(['test' => 'test'], 'test'));
        $this->assertEquals('test', ArrayHelper::get(['test1' => 'test'], 'test1'));

        // Default
        $this->assertEquals('test', ArrayHelper::get(['test1' => 'test'], 'test1', 'not_exists'));
        $this->assertEquals('not_exists', ArrayHelper::get(['test1' => 'test'], 'test2', 'not_exists'));

        // Dot syntax
        $array = [
            'arr1' => [
                'arr1-1' => 'val1-1',
                'arr1-2' => 'val1-2',
            ],
            'arr2' => [
                'arr2-1' => 'val2-1',
                'arr2-2' => 'val2-2',
            ],
        ];
        $this->assertEquals('val1-1', ArrayHelper::get($array, 'arr1.arr1-1', 'not_exists', true));
        $this->assertEquals('val1-2', ArrayHelper::get($array, 'arr1.arr1-2', 'not_exists', true));
        $this->assertEquals('val2-1', ArrayHelper::get($array, 'arr2.arr2-1', 'not_exists', true));
        $this->assertEquals('not_exists', ArrayHelper::get($array, 'arr3.arr3-1', 'not_exists', true));
    }
}
