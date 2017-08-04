<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Bootstrap;

use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Http\Request;
use Panda\Session\Session as SessionHandler;

/**
 * Class Session
 * @package Panda\Bootstrap
 */
class Session implements BootLoader
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
     * @param Request $request
     */
    public function boot($request = null)
    {
        $this->handler->init();
    }

    /**
     * @return SessionHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param Session $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }
}
