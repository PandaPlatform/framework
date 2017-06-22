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
 * Class JsonParser
 * @package Panda\Config\Parsers
 */
class JsonParser implements ConfigurationParser
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
        return $configFile ? json_decode(file_get_contents($configFile), true) : [];
    }
}
