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

use InvalidArgumentException;
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
    public function getItems(): array
    {
        return ArrayHelper::get(parent::getItems(), self::CONTAINER, [], false);
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     */
    public function setItems(array $bootLoaders)
    {
        // Set items in container
        $items = ArrayHelper::set(parent::getItems(), self::CONTAINER, $bootLoaders, false);

        // Set registry back
        parent::setItems($items);
    }
}
