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
use Panda\Support\Facades\Facade;

/**
 * Class FacadeRegistry
 * @package Panda\Bootstrap
 */
class FacadeRegistry implements BootLoader
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
     * @param Request $request
     */
    public function boot($request = null)
    {
        // Set facade application container
        Facade::setFacadeApp($this->app);
    }
}
