<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Configuration;

use Panda\Contracts\Registry\RegistryInterface;

/**
 * Interface ConfigurationHandler
 * @package Panda\Contracts\Configuration
 */
interface ConfigurationHandler extends RegistryInterface
{
    /**
     * Set the entire configuration array.
     *
     * @param array $config
     */
    public function setConfig(array $config);
}
