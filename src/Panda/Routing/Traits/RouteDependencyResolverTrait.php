<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Routing\Traits;

use Panda\Helpers\ArrayHelper;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Trait RouteDependencyResolverTrait
 * @package Panda\Routing\Traits
 */
trait RouteDependencyResolverTrait
{
    /**
     * Call a class method with the resolved dependencies.
     *
     * @param object $instance
     * @param string $method
     *
     * @return mixed
     */
    protected function callWithDependencies($instance, $method)
    {
        return call_user_func_array(
            [$instance, $method], $this->resolveClassMethodDependencies([], $instance, $method)
        );
    }

    /**
     * Resolve the object method's type-hinted dependencies.
     *
     * @param array  $parameters
     * @param object $instance
     * @param string $method
     *
     * @return array
     */
    protected function resolveClassMethodDependencies(array $parameters, $instance, $method)
    {
        if (!method_exists($instance, $method)) {
            return $parameters;
        }

        return $this->resolveMethodDependencies(
            $parameters, new ReflectionMethod($instance, $method)
        );
    }

    /**
     * Resolve the given method's type-hinted dependencies.
     *
     * @param array                      $parameters
     * @param ReflectionFunctionAbstract $reflector
     *
     * @return array
     */
    public function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector)
    {
        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = $this->transformDependency($parameter, $parameters);

            if (!is_null($instance)) {
                $this->spliceIntoParameters($parameters, $key, $instance);
            }
        }

        return $parameters;
    }

    /**
     * Transform the given parameter into a class instance.
     *
     * @param ReflectionParameter $parameter
     * @param array               $parameters
     *
     * @return mixed
     */
    protected function transformDependency(ReflectionParameter $parameter, $parameters)
    {
        // Get owner class
        $class = $parameter->getClass();

        // We skip parameters with a type hint to avoid replacing model bindings
        if ($class && !$this->alreadyInParameters($class->name, $parameters)) {
            return $this->container->make($class->name);
        }
    }

    /**
     * Check if an object of the given class is in a list of parameters.
     *
     * @param string $class
     * @param array  $parameters
     *
     * @return bool
     */
    protected function alreadyInParameters($class, array $parameters)
    {
        return !is_null(ArrayHelper::match($parameters, function ($value) use ($class) {
            return $value instanceof $class;
        }));
    }

    /**
     * Splice the given value into the parameter list.
     *
     * @param array  $parameters
     * @param string $key
     * @param mixed  $instance
     *
     * @return void
     */
    protected function spliceIntoParameters(array &$parameters, $key, $instance)
    {
        array_splice($parameters, $key, 0, [$instance]);
    }
}
