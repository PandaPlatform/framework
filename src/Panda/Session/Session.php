<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Session;

use Panda\Contracts\Bootstrap\Bootstrapper;
use Panda\Http\Request;

/**
 * Session Manager. Handles all session storage processes.
 *
 * @package Panda\Session
 *
 * @version 0.1
 */
class Session implements Bootstrapper
{
    /**
     * @var SessionHandler
     */
    private $handler;

    /**
     * Session constructor.
     *
     * @param SessionHandler $handler
     */
    public function __construct(SessionHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Init session.
     *
     * @param Request $request
     */
    public function boot($request)
    {
        $this->handler->startSession();
    }

    /**
     * @return SessionHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param SessionHandler $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }
}
