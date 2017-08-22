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
use Throwable;

/**
 * Class Debugger
 * @package Panda\Bootstrap
 */
class Debugger implements BootLoader
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var bool
     */
    protected static $active = false;

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
        // Set error reporting
        error_reporting(E_ALL & ~(E_NOTICE | E_WARNING | E_DEPRECATED));

        // Set framework to display errors
        try {
            if ($this->isActive($request)) {
                ini_set('display_errors', true);
            } else {
                ini_set('display_errors', false);
            }
        } catch (Throwable $ex) {
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function isActive(Request $request)
    {
        // Check request arguments
        $debugger = $request ? $request->get($key = 'pdebug', $default = null, $includeCookies = true) : false;

        // Reload status
        $this->setActive(self::$active || $debugger || $this->app->getEnvironment() == 'development');

        return self::$active;
    }

    /**
     * @param bool $active
     *
     * @return Debugger
     */
    public function setActive(bool $active)
    {
        self::$active = $active;

        return $this;
    }
}
