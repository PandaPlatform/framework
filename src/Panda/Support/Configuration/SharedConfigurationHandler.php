<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration;

use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class MainConfigurationHandler
 * @package Panda\Support\Configuration
 */
class MainConfigurationHandler implements ConfigurationHandler
{
    /**
     * @var array
     */
    protected static $config;

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        static::$config = $config;
    }

    /**
     * Get a configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get(static::$config, $key, $default, $useDotSyntax = true);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function set($key, $value)
    {
        return static::$config = ArrayHelper::set(static::$config, $key, $value, $useDotSyntax = true);
    }

    /**
     * @param array $config
     *
     * @return MainConfigurationHandler
     */
    public function setConfig(array $config): MainConfigurationHandler
    {
        static::$config = $config;

        return $this;
    }
}
