<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Bootstrap;

use Panda\Registry\SharedRegistry;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class BootstrapRegistry
 * @package Panda\Foundation\Bootstrap
 */
class BootstrapRegistry extends SharedRegistry
{
    const CONTAINER = 'bootstrap';

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
     * {@inheritdoc}
     */
    public function getBootLoaders(): array
    {
        return ArrayHelper::get(parent::getItems(), self::CONTAINER, [], false);
    }

    /**
     * {@inheritdoc}
     */
    public function setBootLoaders(array $bootLoaders)
    {
        // Set items in registry
        $items = ArrayHelper::set($this->getItems(), self::CONTAINER, $bootLoaders, false);

        // Set registry back
        parent::setItems($items);
    }
}
