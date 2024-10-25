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
 * Interface EventInterface
 * @package Panda\Events
 */
interface EventInterface
{
    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @param ChannelInterface    $channel
     * @param SubscriberInterface $subscriber
     */
    public function subscribe(ChannelInterface $channel, SubscriberInterface $subscriber);

    /**
     * Dispatch the event to its subscribers
     */
    public function dispatch();
}
