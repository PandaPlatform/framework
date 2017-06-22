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

use Panda\Support\Helpers\ArrayHelper;
use PHPUnit_Framework_TestCase;

/**
 * Class ArrayHelperTest
 * @package Panda\Support\Helpers\Tests
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
            'arr3.arr3-1' => 'val3-1',
        ];
        $this->assertEquals('val1-1', ArrayHelper::get($array, 'arr1.arr1-1', 'not_exists', true));
        $this->assertEquals('val1-2', ArrayHelper::get($array, 'arr1.arr1-2', 'not_exists', true));
        $this->assertEquals('val2-1', ArrayHelper::get($array, 'arr2.arr2-1', 'not_exists', true));
        $this->assertEquals('val3-1', ArrayHelper::get($array, 'arr3.arr3-1', 'not_exists', true));
        $this->assertEquals('not_exists', ArrayHelper::get($array, 'arr4.arr4-1', 'not_exists', true));
    }

    /**
     * @covers \Panda\Support\Helpers\ArrayHelper::set
     */
    public function testSet()
    {
        $array = [
            't1' => [
                't2' => [
                    't3' => [],
                ],
            ],
        ];

        // Simple assignment
        $array = ArrayHelper::set($array, 't11', 'test_value', false);
        $this->assertEquals('test_value', $array['t11']);

        // Simple assignment, using dot syntax
        $array = ArrayHelper::set($array, 't11', 'test_value', true);
        $this->assertEquals('test_value', $array['t11']);

        // Dot syntax assignment (depth = 2)
        $array = ArrayHelper::set($array, 't1.t22', 'test_value', true);
        $this->assertEquals('test_value', $array['t1']['t22']);

        // Dot syntax assignment (depth = 3)
        $array = ArrayHelper::set($array, 't1.t2.t33', 'test_value', true);
        $this->assertEquals('test_value', $array['t1']['t2']['t33']);

        // Dot syntax assignment (depth = 4)
        $array = ArrayHelper::set($array, 't1.t2.t3.t4', 'test_value', true);
        $this->assertEquals('test_value', $array['t1']['t2']['t3']['t4']);
    }

    /**
     * @covers \Panda\Support\Helpers\ArrayHelper::filter
     */
    public function testFilter()
    {
        $array = [
            't11' => 'v11',
            't12' => 'v12',
            't13' => 'v13',
            't14' => 'v14',
            't21' => 'v21',
            't22' => 'v22',
        ];

        // Empty callback
        $result = ArrayHelper::filter($array, null, null);
        $this->assertEquals($array, $result);

        // Empty array --> default
        $result = ArrayHelper::filter([], null, 'default_value');
        $this->assertEquals('default_value', $result);

        // Filter function with some matches
        $result = ArrayHelper::filter($array, [ArrayHelperTest::class, 'filterCallback1'], 'default_value');
        $this->assertEquals([
            't11' => 'v11',
            't12' => 'v12',
            't13' => 'v13',
            't14' => 'v14',
        ], $result);

        // Filter function with some matches and length
        $result = ArrayHelper::filter($array, [ArrayHelperTest::class, 'filterCallback1'], 'default_value', 2);
        $this->assertEquals([
            't11' => 'v11',
            't12' => 'v12',
        ], $result);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     *
     * @return bool
     */
    public function filterCallback1($key, $value)
    {
        if (substr($key, 0, 2) == 't1') {
            return true;
        }

        return false;
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
