<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Routing;

use BadMethodCallException;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Controller
 * @package Panda\Routing
 */
abstract class Controller
{
    /**
     * @var Router The router instance.
     */
    protected static $router;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Get the router instance.
     *
     * @return Router
     */
    public static function getRouter()
    {
        return static::$router;
    }

    /**
     * Set the router instance.
     *
     * @param Router $router
     */
    public static function setRouter(Router $router)
    {
        static::$router = $router;
    }

    /**
     * Controller constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the current running request.
     *
     * @return Request
     */
    public function getCurrentRequest()
    {
        if (empty(self::getRouter())) {
            return null;
        }

        return self::getRouter()->getCurrentRequest();
    }

    /**
     * Execute an action on the controller.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return Response
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], $parameters);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @return mixed
     *
     * @throws NotFoundHttpException
     */
    public function missingMethod()
    {
        throw new NotFoundHttpException('Controller method not found.');
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException("Method [$method] does not exist.");
    }

    /**
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param Application $app
     */
    public function setApp($app)
    {
        $this->app = $app;
    }
}
