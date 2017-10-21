<?php

/*
 * This file is part of the Panda Events Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Events\Channels;

/**
 * Class ChannelFactory
 * @package Panda\Events\Channels
 */
abstract class ChannelFactory
{
    /**
     * @param string $identifier
     *
     * @return ChannelInterface
     */
    abstract public static function getChannel($identifier);
}
