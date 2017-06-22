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
 * Class Registry
 * @package Panda\Foundation\Registry
 */
class Registry implements RegistryInterface
{
    /**
     * @var array
     */
    protected $registry;

    /**
     * @return array
     */
    public function getRegistry(): array
    {
        return $this->registry;
    }

    /**
     * @param array $registry
     *
     * @return Registry
     */
    public function setRegistry(array $registry): Registry
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get($this->registry, $key, $default, $useDotSyntax = true);
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
        return $this->registry = ArrayHelper::set($this->registry, $key, $value, $useDotSyntax = true);
    }
}
