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

/**
 * Interface ConfigurationParser
 * @package Panda\Contracts\Configuration
 */
interface ConfigurationParser
{
    /**
     * Parse the configuration file and get the configuration array.
     *
     * @return array
     */
    public function parse();
}
