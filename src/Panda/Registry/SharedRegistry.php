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
    protected static $registry = [];

    /**
     * @return array
     */
    public function getRegistry(): array
    {
        return self::$registry;
    }

    /**
     * @param array $registry
     */
    public function setRegistry(array $registry)
    {
        self::$registry = $registry;
    }
}
