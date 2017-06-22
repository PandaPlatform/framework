<?php

/*
 * This file is part of the Panda Config Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Config\Parsers;

use Panda\Config\ConfigurationParser;

/**
 * Class PhpParser
 * @package Panda\Config\Parsers
 */
class PhpParser implements ConfigurationParser
{
    /**
     * Parse the configuration file and get the configuration array.
     *
     * @param string $configFile
     *
     * @return array
     */
    public function parse($configFile = '')
    {
        return ($configFile ? include($configFile) : []);
    }
}
