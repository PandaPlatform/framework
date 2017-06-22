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
 * Class Registry
 * @package Panda\Registry
 */
class Registry extends AbstractRegistry
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
}
