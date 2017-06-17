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

use Panda\Container\Container;
use Panda\Routing\Traits\RouteDependencyResolverTrait;

/**
 * Class ControllerDispatcher
 * @package Panda\Routing
 */
class ControllerDispatcher
{
    use RouteDependencyResolverTrait;

    /**
     * @var Container
     */
    protected $container;

    /**
     * ControllerDispatcher constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Dispatch a request to a given controller and method.
     *
     * @param Route  $route
     * @param mixed  $controller
     * @param string $method
     *
     * @return mixed
     */
    public function dispatch(Route $route, $controller, $method)
    {
        $parameters = $this->resolveClassMethodDependencies(
            $route->getParametersWithoutNulls(), $controller, $method
        );

        if (method_exists($controller, 'callAction')) {
            return $controller->callAction($method, $parameters);
        }

        return call_user_func_array([$controller, $method], $parameters);
    }

    /**
     * Determine if the given options exclude a particular method.
     *
     * @param string $method
     * @param array  $options
     *
     * @return bool
     */
    protected static function methodExcludedByOptions($method, array $options)
    {
        return (isset($options['only']) && !in_array($method, (array)$options['only'])) ||
            (!empty($options['except']) && in_array($method, (array)$options['except']));
    }
}
