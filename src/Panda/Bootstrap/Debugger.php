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
        // Set error reporting
        error_reporting(E_ALL & ~(E_NOTICE | E_WARNING | E_DEPRECATED));

        // Set framework to display errors
        try {
            if (!empty($request)) {
                if ($request->get($key = 'pdebug', $default = null, $includeCookies = true) || $this->app->getEnvironment() == 'development') {
                    ini_set('display_errors', true);
                } else {
                    ini_set('display_errors', false);
                }
            }
        } catch (Throwable $ex) {
        }
    }
}
