<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration;

use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Foundation\Registry\SharedRegistry;

/**
 * Class SharedConfigurationHandler
 * @package Panda\Support\Configuration
 */
class SharedConfigurationHandler extends SharedRegistry implements ConfigurationHandler
{
    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        self::setRegistry($config);
    }

    /**
     * @param array $config
     *
     * @return SharedConfigurationHandler
     */
    public function setConfig(array $config): SharedConfigurationHandler
    {
        static::setRegistry($config);

        return $this;
    }
}
