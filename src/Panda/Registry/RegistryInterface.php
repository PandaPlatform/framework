<?php

/*
 * This file is part of the Panda Registry Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Registry;

use ArrayAccess;

/**
 * Interface RegistryInterface
 * @package Panda\Registry
 */
interface RegistryInterface extends ArrayAccess
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

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * @return array
     */
    public function getItems();

    /**
     * @param array $items
     */
    public function setItems(array $items);
}
