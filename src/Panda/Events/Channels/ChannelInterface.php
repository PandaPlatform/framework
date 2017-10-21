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

use Panda\Events\Messages\MessageInterface;
use Panda\Events\SubscriberInterface;

/**
 * Interface ChannelInterface
 * @package Panda\Events\Channels
 */
interface ChannelInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param SubscriberInterface $subscriber
     * @param MessageInterface    $message
     *
     * @return bool
     */
    public function dispatch(SubscriberInterface $subscriber, MessageInterface $message);
}
