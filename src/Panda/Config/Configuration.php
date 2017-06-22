<?php

/*
 * This file is part of the Panda Config Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Config;

use Panda\Registry\Registry;

/**
 * Class SharedConfiguration
 * @package Panda\Config
 */
class Configuration extends Registry implements ConfigurationHandler
{
    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        // Prefix key
        $key = 'config.' . $key;

        return parent::get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        // Prefix key
        $key = 'config.' . $key;

        return parent::set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistry(): array
    {
        $registry = parent::getRegistry();

        return $registry['config'];
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistry(array $registry)
    {
        // Get registry
        $thisRegistry = $this->getRegistry();

        // Set config in registry
        $thisRegistry['config'] = $registry;

        // Set registry back
        parent::setRegistry($thisRegistry);
    }
}