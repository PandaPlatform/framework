<?php

namespace Panda\Support\Helpers\tests;

use Panda\Support\Helpers\ArrayHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class ArrayHelperTest
 * @package Panda\Support\Helpers\tests
 */
class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Panda\Support\Helpers\ArrayHelper::get
     */
    public function testGet()
    {
        // Plain getter
        $this->assertEquals('test', ArrayHelper::get(['test' => 'test'], 'test'));
        $this->assertEquals('test', ArrayHelper::get(['test1' => 'test'], 'test1'));

        // Getter with no key
        $array = ['test1' => 'test'];
        $this->assertEquals($array, ArrayHelper::get($array));

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

    /**
     * @covers \Panda\Support\Helpers\ArrayHelper::merge
     */
    public function testMerge()
    {

        $helper1 = [
            'h11' => 'v11',
            'h12' => 'v12',
        ];
        $array1 = [
            't11' => 'v11',
            't12' => 'v12',
            't3' => $helper1,
        ];
        $helper2 = [
            'h21' => 'v21',
            'h22' => 'v22',
        ];
        $array2 = [
            't21' => 'v21',
            't22' => 'v22',
            't3' => $helper2,
        ];

        // Merge (not deep)
        $result = ArrayHelper::merge($array1, $array2, false);
        $this->assertEquals('v11', $result['t11']);
        $this->assertEquals('v12', $result['t12']);
        $this->assertEquals('v21', $result['t21']);
        $this->assertEquals('v22', $result['t22']);
        $this->assertNotEquals($helper1, $result['t3']);
        $this->assertEquals($helper2, $result['t3']);

        // Merge deep
        $result = ArrayHelper::merge($array1, $array2, true);
        $this->assertEquals('v11', $result['t11']);
        $this->assertEquals('v12', $result['t12']);
        $this->assertEquals('v21', $result['t21']);
        $this->assertEquals('v22', $result['t22']);
        $this->assertEquals('v11', $result['t3']['h11']);
        $this->assertEquals('v12', $result['t3']['h12']);
        $this->assertEquals('v21', $result['t3']['h21']);
        $this->assertEquals('v22', $result['t3']['h22']);
    }
}
