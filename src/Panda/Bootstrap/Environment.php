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
use Panda\Foundation\Application;
use Panda\Http\Request;

/**
 * Class Environment
 * @package Panda\Bootstrap
 */
class Environment implements BootLoader
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @var Session
     */
    private $session;

    /**
     * Environment constructor.
     *
     * @param Application $app
     * @param Debugger    $debugger
     * @param Session     $session
     */
    public function __construct(Application $app, Debugger $debugger, Session $session)
    {
        $this->app = $app;
        $this->debugger = $debugger;
        $this->session = $session;
    }

    /**
     * @param Request $request
     */
    public function boot($request = null)
    {
        // Initialize environment
        $this->debugger->boot($request);
        $this->session->boot($request);
    }
}
