<?php

/*
 * This file is part of the Panda Registry Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Registry\Tests;

use InvalidArgumentException;
use Panda\Registry\Registry;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_TestCase;

/**
 * Class RegistryTest
 * @package Panda\Registry\Tests
 */
class RegistryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        // Create registry
        $this->registry = new Registry();
    }

    /**
     * @covers \Panda\Registry\Registry::get
     */
    public function testGet()
    {
        $registry = [
            'key0' => 'val0-1',
            'key1' => [
                'key1-1' => 'val1-1',
                'key1-2' => 'val1-2',
            ],
            'key2' => [
                'key2-1' => 'val2-1',
                'key2-2' => 'val2-2',
            ],
            'key3.key3-1' => 'val3-1',
        ];
        $this->registry->setItems($registry);

        $this->assertEquals('val0-1', $this->registry->get('key0'));
        $this->assertEquals('val1-1', $this->registry->get('key1.key1-1'));
        $this->assertEquals('default_value', $this->registry->get('key1.key1-3', 'default_value'));

        // Array getters
        $this->assertEquals('val0-1', $this->registry['key0']);
        $this->assertEquals('val1-1', $this->registry['key1.key1-1']);
        $this->assertEquals(null, $this->registry['key1.key1-3']);
    }

    /**
     * @covers \Panda\Registry\Registry::set
     * @throws InvalidArgumentException
     */
    public function testSet()
    {
        $registry = [
            'key0' => 'val0-1',
            'key1' => [
                'key1-1' => 'val1-1',
                'key1-2' => 'val1-2',
            ],
            'key2' => [
                'key2-1' => 'val2-1',
                'key2-2' => 'val2-2',
            ],
            'key3.key3-1' => 'val3-1',
        ];
        $this->registry->setItems($registry);

        $this->registry->set('key0', 'val0-1-2');
        $this->assertEquals('val0-1-2', $this->registry->get('key0'));

        $this->registry->set('key1.key1-1', 'val1-1-2');
        $this->assertEquals('val1-1-2', $this->registry->get('key1.key1-1'));
        $this->assertEquals('val1-2', $this->registry->get('key1.key1-2'));

        $this->registry->set('key4', 'val4-1');
        $this->assertEquals('val4-1', $this->registry->get('key4'));

        $this->registry->set('key5.key5-1', 'val5-1');
        $this->assertEquals('val5-1', $this->registry->get('key5.key5-1'));

        // Array setters
        $this->registry['key6'] = 'val6-1';
        $this->assertEquals('val6-1', $this->registry['key6']);

        // Array setters using no key
        $this->registry[] = 'val0';
        $this->assertEquals('val0', $this->registry[0]);
        $this->registry[] = 'val1';
        $this->assertEquals('val1', $this->registry[1]);
        $this->registry[] = 'val2';
        $this->assertEquals('val2', $this->registry[2]);
    }

    /**
     * @covers \Panda\Registry\Registry::exists
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function testExists()
    {
        $registry = [
            'key0' => 'val0-1',
            'key1' => [
                'key1-1' => 'val1-1',
                'key1-2' => 'val1-2',
            ],
            'key2' => [
                'key2-1' => 'val2-1',
                'key2-2' => 'val2-2',
            ],
            'key3.key3-1' => 'val3-1',
        ];
        $this->registry->setItems($registry);

        $this->assertTrue($this->registry->exists('key0'));
        $this->assertTrue($this->registry->exists('key1'));
        $this->assertTrue($this->registry->exists('key1.key1-1'));

        $this->assertFalse($this->registry->exists('key4'));
        $this->assertFalse($this->registry->exists('key0.key0-1'));
        $this->assertFalse($this->registry->exists('key1.key1-1.key1-1-1'));
    }
}
