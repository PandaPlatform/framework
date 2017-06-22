<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Bootstrap;

use Panda\Registry\Registry;

/**
 * Class BootstrapRegistry
 * @package Panda\Foundation\Bootstrap
 */
class BootstrapRegistry extends Registry
{
    const CONTAINER = 'bootstrap';

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        // Prefix key
        $key = self::CONTAINER . '.' . $key;

        return parent::get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        // Prefix key
        $key = self::CONTAINER . '.' . $key;

        return parent::set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistry(): array
    {
        $registry = parent::getRegistry();

        return $registry[self::CONTAINER];
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistry(array $registry)
    {
        // Get registry
        $thisRegistry = $this->getRegistry();

        // Set config in registry
        $thisRegistry[self::CONTAINER] = $registry;

        // Set registry back
        parent::setRegistry($thisRegistry);
    }
}
