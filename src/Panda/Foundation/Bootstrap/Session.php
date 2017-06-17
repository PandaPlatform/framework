<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Bootstrap;

use Panda\Contracts\Bootstrap\Bootstrapper;
use Panda\Http\Request;
use Panda\Session\SessionHandler;

/**
 * Class Session
 * @package Panda\Foundation\Bootstrap
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
