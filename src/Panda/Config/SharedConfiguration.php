<?php

/*
 * This file is part of the Panda Config Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Config;

use InvalidArgumentException;
use Panda\Registry\SharedRegistry;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class SharedConfiguration
 * @package Panda\Config
 */
class SharedConfiguration extends SharedRegistry implements ConfigurationHandler
{
    const CONTAINER = 'config';

    /**
     * Set the entire configuration array.
     *
     * @param array $config
     *
     * @throws InvalidArgumentException
     */
    public function setItems(array $config)
    {
        // Set config in registry
        $items = ArrayHelper::set(parent::getItems(), self::CONTAINER, $config, false);

        // Set registry back
        parent::setItems($items);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return ArrayHelper::get(parent::getItems(), self::CONTAINER, [], false);
    }
}
