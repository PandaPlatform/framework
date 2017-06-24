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

use InvalidArgumentException;
use Panda\Registry\SharedRegistry;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class SharedConfiguration
 * @package Panda\Config
 */
class SharedConfiguration extends SharedRegistry implements ConfigurationHandler
{
    const CONTAINER = 'config';

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
     * Set the entire configuration array.
     *
     * @param array $config
     *
     * @throws InvalidArgumentException
     */
    public function setConfig(array $config)
    {
        // Set config in registry
        $items = ArrayHelper::set($this->getItems(), self::CONTAINER, $config, false);

        // Set registry back
        parent::setItems($items);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return ArrayHelper::get(parent::getItems(), self::CONTAINER, [], false);
    }
}
