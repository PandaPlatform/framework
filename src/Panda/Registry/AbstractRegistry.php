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
use Panda\Support\Helpers\StringHelper;

/**
 * Class AbstractRegistry
 * @package Panda\Registry
 */
abstract class AbstractRegistry implements RegistryInterface
{
    /**
     * @return array
     */
    abstract public function getItems();

    /**
     * @param array $items
     */
    abstract public function setItems(array $items);

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get($this->getItems(), $key, $default, $useDotSyntax = true);
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
        $registry = ArrayHelper::set($this->getItems(), $key, $value, $useDotSyntax = true);
        $this->setItems($registry);

        return $this->getItems();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return ArrayHelper::exists($this->getItems(), $key, $useDotSyntax = true);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        // Normalize offset
        if (StringHelper::emptyString($offset, true)) {
            // Get next key
            $numericItems = ArrayHelper::filter($this->getItems(), function ($key) {
                if (is_numeric($key) && is_int($key) && $key >= 0) {
                    return true;
                }

                return false;
            }, []);
            $keys = array_keys($numericItems);
            $offset = count($keys) > 0 ? max($keys) + 1 : 0;
        }

        // Set
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->set($offset, null);
    }
}
