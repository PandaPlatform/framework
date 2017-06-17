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
use Panda\Debug\Debugger;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Localization\DateTimer;
use Panda\Session\Session;

/**
 * Class Environment
 * @package Panda\Foundation\Bootstrap
 */
class Environment implements Bootstrapper
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Environment constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Run the initializer.
     *
     * @param Request $request
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function boot($request)
    {
        // Initialize environment
        $this->app->make(Debugger::class)->boot($request);
        $this->app->make(DateTimer::class)->boot($request);
        $this->app->make(Session::class)->boot($request);
    }
}
