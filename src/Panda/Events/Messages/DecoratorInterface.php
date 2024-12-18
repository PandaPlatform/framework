<?php

/*
 * This file is part of the Panda Events Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Events\Messages;

use Panda\Events\Channels\ChannelInterface;

/**
 * Interface DecoratorInterface
 *
 * @package Panda\Events\Messages
 */
interface DecoratorInterface
{
    /**
     * @param MessageInterface      $message
     * @param ChannelInterface|null $channel
     *
     * @return MessageInterface
     */
    public function decorate(MessageInterface $message, ChannelInterface $channel = null);
}
