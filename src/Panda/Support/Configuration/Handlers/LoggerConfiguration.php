<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration\Handlers;

use Panda\Config\SharedConfiguration;

/**
 * Class LoggerConfiguration
 * @package Panda\Support\Configuration\Handlers
 */
class LoggerConfiguration extends SharedConfiguration
{
    /**
     * @return array
     */
    public function getLoggerConfig()
    {
        return $this->get('logger');
    }
}
