<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Registry;

/**
 * Interface RegistryInterface
 * @package Panda\Contracts\Registry
 */
interface RegistryInterface
{
    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public function set($key, $value);
}
