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

/**
 * Interface MessageInterface
 * @package Panda\Events\Messages
 */
interface MessageInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * @param mixed $message
     *
     * @return mixed
     */
    public function setMessage($message);

    /**
     * @return mixed
     */
    public function getSubject();

    /**
     * @param mixed $subject
     *
     * @return mixed
     */
    public function setSubject($subject);

    /**
     * @return $this
     */
    public function decorate();
}
