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
use Panda\Registry\SharedRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Class SharedRegistryTest
 *
 * @package Panda\Registry\Tests
 */
class SharedRegistryTest extends TestCase
{
    /**
     * @var SharedRegistry
     */
    private $registry;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create registry
        $this->registry = new SharedRegistry();
    }

    /**
     * @covers \Panda\Registry\SharedRegistry::get
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

        // Normal getters
        $this->assertEquals('val0-1', $this->registry->get('key0'));
        $this->assertEquals('val1-1', $this->registry->get('key1.key1-1'));
        $this->assertEquals('default_value', $this->registry->get('key1.key1-3', 'default_value'));

        // Array getters
        $this->assertEquals('val0-1', $this->registry['key0']);
        $this->assertEquals('val1-1', $this->registry['key1.key1-1']);
        $this->assertEquals(null, $this->registry['key1.key1-3']);

        // Create second SharedRegistry
        $registry2 = new SharedRegistry();

        // Check already existing values
        $this->assertEquals('val0-1', $registry2->get('key0'));
        $this->assertEquals('val1-1', $registry2->get('key1.key1-1'));
        $this->assertEquals('default_value', $registry2->get('key1.key1-3', 'default_value'));
    }

    /**
     * @covers \Panda\Registry\SharedRegistry::set
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

        // Create second SharedRegistry
        $registry2 = new SharedRegistry();

        // Change second registry and check first to see if affected
        $registry2->set('key4', 'val4');
        $this->assertEquals('val4', $registry2->get('key4'));
        $this->assertEquals('val4', $this->registry->get('key4'));

        // Array setters
        $registry2['key6'] = 'val6-1';
        $this->assertEquals('val6-1', $registry2['key6']);

        // Array setters using no key
        $registry2[] = 'array_access_0';
        $this->assertEquals('array_access_0', $registry2[0]);
        $registry2[] = 'array_access_1';
        $this->assertEquals('array_access_1', $registry2[1]);
        $registry2[] = 'array_access_2';
        $this->assertEquals('array_access_2', $registry2[2]);
        $registry2[] = 'array_access_3';
        $this->assertEquals('array_access_3', $registry2[3]);
        $registry2[] = 'array_access_4';
        $this->assertEquals('array_access_4', $registry2[4]);
    }

    /**
     * @covers \Panda\Registry\SharedRegistry::exists
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

        // Create second SharedRegistry
        $registry2 = new SharedRegistry();

        $this->assertTrue($registry2->exists('key0'));
        $this->assertTrue($registry2->exists('key1'));
        $this->assertTrue($registry2->exists('key1.key1-1'));

        $this->assertFalse($registry2->exists('key4'));
        $this->assertFalse($registry2->exists('key0.key0-1'));
        $this->assertFalse($registry2->exists('key1.key1-1.key1-1-1'));
    }
}
