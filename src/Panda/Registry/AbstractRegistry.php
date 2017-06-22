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

use InvalidArgumentException;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class AbstractRegistry
 * @package Panda\Registry
 */
abstract class AbstractRegistry implements RegistryInterface
{
    /**
     * @return array
     */
    abstract public function getRegistry();

    /**
     * @param array $registry
     */
    abstract public function setRegistry(array $registry);

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get($this->getRegistry(), $key, $default, $useDotSyntax = true);
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
        $registry = ArrayHelper::set($this->getRegistry(), $key, $value, $useDotSyntax = true);
        $this->setRegistry($registry);

        return $this->getRegistry();
    }
}
