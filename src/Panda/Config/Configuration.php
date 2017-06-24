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

use Panda\Registry\Registry;

/**
 * Class SharedConfiguration
 * @package Panda\Config
 */
class Configuration extends Registry implements ConfigurationHandler
{
    /**
     * Set the entire configuration array.
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->setItems($config);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->getItems();
    }
}
