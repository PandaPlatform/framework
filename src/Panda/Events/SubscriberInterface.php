<?php

/*
 * This file is part of the Panda Events Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Events;

use Panda\Events\Channels\ChannelInterface;

/**
 * Interface SubscriberInterface
 * @package Panda\Events
 */
interface SubscriberInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param ChannelInterface $channel
     *
     * @return string
     */
    public function getIdentifierByChannel(ChannelInterface $channel);
}
