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

use Panda\Events\Channels\ChannelFactory;
use Panda\Events\Channels\ChannelInterface;
use Panda\Events\Messages\DecoratorInterface;
use Panda\Events\Messages\MessageInterface;

/**
 * Class Event
 * @package Panda\Events
 */
abstract class Event implements EventInterface, DecoratorInterface
{
    /**
     * @var SubscriberInterface[]
     */
    protected $subscribers;

    /**
     * @var MessageInterface[]
     */
    protected $messages;

    /**
     * @return ChannelFactory
     */
    abstract public function getChannelFactory();

    /**
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    abstract public function decorate(MessageInterface $message);

    /**
     * @param ChannelInterface    $channel
     * @param SubscriberInterface $subscriber
     *
     * @return $this
     */
    public function subscribe(ChannelInterface $channel, SubscriberInterface $subscriber)
    {
        $this->subscribers[$channel->getIdentifier()][$subscriber->getIdentifier()] = $subscriber;

        return $this;
    }

    /**
     * Dispatch the event to its subscribers through the subscribed channels.
     *
     * @throws Exceptions\MessageNotSupportedException
     */
    public function dispatch()
    {
        foreach ($this->getSubscribers() as $channelIdentifier => $subscribers) {
            // Get channel from ChannelFactory
            $channel = $this->getChannelFactory()->getChannel($channelIdentifier);

            // Get proper message for the given channel
            $message = $this->getMessage($channel);

            // Dispatch to all subscribers
            /** @var SubscriberInterface $subscriber */
            foreach ($subscribers as $subscriber) {
                // Decorate the message
                $message->decorate();

                // Dispatch to the subscriber
                $channel->dispatch($subscriber, $message);
            }
        }
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return MessageInterface
     */
    public function getMessage(ChannelInterface $channel)
    {
        // Get channel identifier
        $channelIdentifier = $channel->getIdentifier();

        // Get channel-specific message
        return $this->getMessages()[$channelIdentifier];
    }

    /**
     * @param ChannelInterface $channel
     * @param MessageInterface $message
     *
     * @return $this
     */
    public function setMessage(ChannelInterface $channel, MessageInterface $message)
    {
        $this->messages[$channel->getIdentifier()] = $message;

        return $this;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return SubscriberInterface
     */
    public function getSubscribersPerChannel(ChannelInterface $channel)
    {
        // Get channel identifier
        $channelIdentifier = $channel->getIdentifier();

        // Get subscribers
        return $this->getSubscribers()[$channelIdentifier];
    }

    /**
     * @return SubscriberInterface[]
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @return MessageInterface[]
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
