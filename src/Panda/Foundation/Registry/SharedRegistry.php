<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Registry;

use InvalidArgumentException;
use Panda\Contracts\Registry\RegistryInterface;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class SharedRegistry
 * @package Panda\Foundation\Registry
 */
class SharedRegistry implements RegistryInterface
{
    /**
     * @var array
     */
    protected static $registry;

    /**
     * @return array
     */
    public static function getRegistry(): array
    {
        return self::$registry;
    }

    /**
     * @param array $registry
     */
    public static function setRegistry(array $registry)
    {
        self::$registry = $registry;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get(static::$registry, $key, $default, $useDotSyntax = true);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function set($key, $value)
    {
        return $this->registry = ArrayHelper::set(static::$registry, $key, $value, $useDotSyntax = true);
    }
}
