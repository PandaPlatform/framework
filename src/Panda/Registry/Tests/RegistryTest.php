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

use Panda\Registry\Registry;
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

        // Create configuration
        $this->registry = new Registry();
    }

    /**
     * @covers \Panda\Registry\AbstractRegistry::get
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
        $this->registry->setRegistry($registry);

        $this->assertEquals('val0-1', $this->registry->get('key0'));
        $this->assertEquals('val1-1', $this->registry->get('key1.key1-1'));
        $this->assertEquals('default_value', $this->registry->get('key1.key1-3', 'default_value'));
    }

    /**
     * @covers \Panda\Registry\AbstractRegistry::set
     * @throws \InvalidArgumentException
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
        $this->registry->setRegistry($registry);

        $this->registry->set('key0', 'val0-1-2');
        $this->assertEquals('val0-1-2', $this->registry->get('key0'));

        $this->registry->set('key1.key1-1', 'val1-1-2');
        $this->assertEquals('val1-1-2', $this->registry->get('key1.key1-1'));
        $this->assertEquals('val1-2', $this->registry->get('key1.key1-2'));

        $this->registry->set('key4', 'val4-1');
        $this->assertEquals('val4-1', $this->registry->get('key4'));

        $this->registry->set('key5.key5-1', 'val5-1');
        $this->assertEquals('val5-1', $this->registry->get('key5.key5-1'));
    }
}
