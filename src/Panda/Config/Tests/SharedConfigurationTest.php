<?php

/*
 * This file is part of the Panda Config Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Config\Tests;

use Panda\Config\SharedConfiguration;
use Panda\Registry\SharedRegistry;
use PHPUnit_Framework_TestCase;

/**
 * Class SharedConfigurationTest
 * @package Panda\Config\Tests
 */
class SharedConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SharedConfiguration
     */
    private $configuration;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        // Create configuration
        $this->configuration = new SharedConfiguration();
    }

    /**
     * @covers \Panda\Config\SharedConfiguration::get
     */
    public function testGet()
    {
        $config = [
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
        $this->configuration->setItems($config);
        $this->assertEquals('val0-1', $this->configuration->get('key0'));
        $this->assertEquals('val1-1', $this->configuration->get('key1.key1-1'));
        $this->assertEquals('default_value', $this->configuration->get('key1.key1-3', 'default_value'));

        // Create second SharedRegistry
        $config2 = new SharedConfiguration();

        // Check already existing values
        $this->assertEquals('val0-1', $config2->get('key0'));
        $this->assertEquals('val1-1', $config2->get('key1.key1-1'));
        $this->assertEquals('default_value', $config2->get('key1.key1-3', 'default_value'));
    }

    /**
     * @covers \Panda\Registry\AbstractRegistry::set
     * @throws \InvalidArgumentException
     */
    public function testSet()
    {
        $config = [
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
        $this->configuration->setItems($config);

        // Check with global shared registry
        $sharedRegistry = new SharedRegistry();
        $this->assertEquals($sharedRegistry->getItems()[SharedConfiguration::CONTAINER], $this->configuration->getItems());

        // Create second SharedRegistry
        $config2 = new SharedConfiguration();

        // Change second registry and check first to see if affected
        $config2->set('key4', 'val4');
        $this->assertEquals('val4', $config2->get('key4'));
        $this->assertEquals('val4', $this->configuration->get('key4'));

        // Array setter
        $config2['key5'] = 'val5';
        $this->assertEquals('val5', $config2['key5']);

        // Array setters using no key
        $config2[] = 'array_access_0';
        $this->assertEquals('array_access_0', $config2[0]);
        $config2[] = 'array_access_1';
        $this->assertEquals('array_access_1', $config2[1]);
        $config2[] = 'array_access_2';
        $this->assertEquals('array_access_2', $config2[2]);
        $config2[] = 'array_access_3';
        $this->assertEquals('array_access_3', $config2[3]);
        $config2[] = 'array_access_4';
        $this->assertEquals('array_access_4', $config2[4]);
    }
}
