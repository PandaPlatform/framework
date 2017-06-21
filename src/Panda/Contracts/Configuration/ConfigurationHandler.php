<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Configuration;

/**
 * Interface ConfigurationHandler
 * @package Panda\Contracts\Configuration
 */
interface ConfigurationHandler
{
    /**
     * Get a configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set a configuration value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public function set($key, $value);

    /**
     * Set the entire configuration array.
     *
     * @param array $config
     */
    public function setConfig(array $config);
}
