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

/**
 * Class SharedRegistry
 * @package Panda\Registry
 */
class SharedRegistry extends AbstractRegistry
{
    /**
     * @var array
     */
    protected static $items = [];

    /**
     * @return array
     */
    public function getItems(): array
    {
        return self::$items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        self::$items = $items;
    }
}
