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

use Closure;
use Exception;
use HttpResponseException;
use LogicException;
use Panda\Container\Container;
use Panda\Foundation\Application;
use Panda\Helpers\ArrayHelper;
use Panda\Helpers\StringHelper;
use Panda\Http\Request;
use Panda\Routing\Traits\RouteDependencyResolverTrait;
use Panda\Routing\Validators\HostValidator;
use Panda\Routing\Validators\MethodValidator;
use Panda\Routing\Validators\UriValidator;
use ReflectionFunction;
use Symfony\Component\Routing\CompiledRoute;
use Symfony\Component\Routing\Route as SymfonyRoute;
use UnexpectedValueException;

/**
 * Class Route
 * @package Panda\Routing
 */
class Route
{
    use RouteDependencyResolverTrait;

    /**
     * @var array
     */
    private $methods;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var Closure
     */
    private $action;

    /**
     * The default values for the route.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * The array of matched parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * The parameter names for the route.
     *
     * @var array|null
     */
    protected $parameterNames;

    /**
     * @var CompiledRoute
     */
    private $compiled;

    /**
     * @var array
     */
    private static $validators;

    /**
     * @var mixed
     */
    protected $controller;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Create a new Route instance
     *
     * @param array         $methods An array of all the methods that this route should listen to.
     * @param string        $uri     The uri to match for this route to execute the callback.
     * @param Closure|array $action  A callback function to be executed when this route is matched by the request.
     *
     * @throws UnexpectedValueException
     */
    public function __construct($methods, $uri, $action)
    {
        // Initialize Route properties
        $this->methods = (array)$methods;
        $this->uri = $uri;
        $this->action = $this->parseAction($action);
    }

    /**
     * Determine if the route matches given request.
     *
     * @param Request $request
     * @param bool    $includingMethod
     *
     * @return bool
     */
    public function matches(Request $request, $includingMethod = true)
    {
        // Create compiled route
        $this->compileRoute();

        foreach ($this->getValidators() as $validator) {
            // Check for method validator explicitly
            if (!$includingMethod && $validator instanceof MethodValidator) {
                continue;
            }

            // Check if validator matches
            if (!$validator->matches($this, $request)) {
                return false;
            }
        }

        // All match
        return true;
    }

    /**
     * Bind the route to a given request for execution.
     *
     * @param Request $request
     *
     * @return $this
     * @throws UnexpectedValueException
     */
    public function bind(Request $request)
    {
        $this->compileRoute();
        $this->bindParameters($request);

        return $this;
    }

    /**
     * Extract the parameter list from the request.
     *
     * @param Request $request
     *
     * @return array
     * @throws UnexpectedValueException
     */
    public function bindParameters(Request $request)
    {
        // If the route has a regular expression for the host part of the URI, we will
        // compile that and get the parameter matches for this domain. We will then
        // merge them into this parameters array so that this array is completed.
        $params = $this->matchToKeys(
            array_slice($this->bindPathParameters($request), 1)
        );
        // If the route has a regular expression for the host part of the URI, we will
        // compile that and get the parameter matches for this domain. We will then
        // merge them into this parameters array so that this array is completed.
        if (!is_null($this->compiled->getHostRegex())) {
            $params = $this->bindHostParameters(
                $request, $params
            );
        }

        return $this->parameters = $this->replaceDefaults($params);
    }

    /**
     * Get the parameter matches for the path portion of the URI.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function bindPathParameters(Request $request)
    {
        preg_match($this->compiled->getRegex(), '/' . $request->getDecodedPath(), $matches);

        return $matches;
    }

    /**
     * Extract the parameter list from the host part of the request.
     *
     * @param Request $request
     * @param array   $parameters
     *
     * @return array
     * @throws UnexpectedValueException
     */
    protected function bindHostParameters(Request $request, $parameters)
    {
        preg_match($this->compiled->getHostRegex(), $request->getHost(), $matches);

        return array_merge($this->matchToKeys(array_slice($matches, 1)), $parameters);
    }

    /**
     * Combine a set of parameter matches with the route's keys.
     *
     * @param array $matches
     *
     * @return array
     */
    protected function matchToKeys(array $matches)
    {
        if (empty($parameterNames = $this->parameterNames())) {
            return [];
        }
        $parameters = array_intersect_key($matches, array_flip($parameterNames));

        return array_filter($parameters, function ($value) {
            return is_string($value) && strlen($value) > 0;
        });
    }

    /**
     * Replace null parameters with their defaults.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function replaceDefaults(array $parameters)
    {
        foreach ($parameters as $key => &$value) {
            $value = isset($value) ? $value : ArrayHelper::get($this->defaults, $key);
        }
        foreach ($this->defaults as $key => $value) {
            if (!isset($parameters[$key])) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    /**
     * Get all of the parameter names for the route.
     *
     * @return array
     */
    public function getParameterNames()
    {
        if (isset($this->parameterNames)) {
            return $this->parameterNames;
        }

        return $this->parameterNames = $this->compileParameterNames();
    }

    /**
     * Get the parameter names for the route.
     *
     * @return array
     */
    protected function compileParameterNames()
    {
        preg_match_all('/\{(.*?)\}/', $this->getDomain() . $this->uri, $matches);

        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    /**
     * Get a given parameter from the route.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return string|object
     */
    public function getParameter($name, $default = null)
    {
        try {
            return ArrayHelper::get($this->getParameters(), $name, $default);
        } catch (Exception $ex) {
            return $default;
        }
    }

    /**
     * Set a parameter to the given value.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     * @throws LogicException
     */
    public function setParameter($name, $value)
    {
        $this->getParameters();
        $this->parameters[$name] = $value;
    }

    /**
     * Get the key / value list of parameters for the route.
     *
     * @throws LogicException
     *
     * @return array
     */
    public function getParameters()
    {
        if (isset($this->parameters)) {
            return array_map(function ($value) {
                return is_string($value) ? rawurldecode($value) : $value;
            }, $this->parameters);
        }
        throw new LogicException('Route is not bound.');
    }

    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     * @throws LogicException
     */
    public function getParametersWithoutNulls()
    {
        return array_filter($this->getParameters(), function ($p) {
            return !is_null($p);
        });
    }

    /**
     * Get all of the parameter names for the route.
     *
     * @return array
     */
    public function parameterNames()
    {
        if (isset($this->parameterNames)) {
            return $this->parameterNames;
        }

        return $this->parameterNames = $this->compileParameterNames();
    }

    /**
     * Get the domain defined for the route.
     *
     * @return string|null
     */
    public function getDomain()
    {
        return isset($this->action['domain']) ? $this->action['domain'] : null;
    }

    /**
     * Compile the current route.
     */
    protected function compileRoute()
    {
        $uri = preg_replace('/\{(\w+?)\?\}/', '{$1}', $this->uri);

        $this->compiled = (new SymfonyRoute($uri, $optionals = [], $requirements = [], [], $domain = ''))->compile();
    }

    /**
     * Get the route validators for the instance.
     *
     * @return array
     */
    public static function getValidators()
    {
        if (isset(static::$validators)) {
            return static::$validators;
        }

        // Set a series of validators to validate whether a route matches some criteria.
        return static::$validators = [
            new MethodValidator(),
            new HostValidator(),
            new UriValidator(),
        ];
    }

    /**
     * Run the route action and return the response.
     *
     * @param Request $request
     *
     * @return mixed
     * @throws LogicException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function run(Request $request)
    {
        $this->container = $this->container ?: Application::getInstance();
        $this->container->set(Request::class, $request);

        try {
            // Check and run controller
            if ($this->isControllerAction()) {
                return $this->runController();
            }

            return $this->runCallable();
        } catch (HttpResponseException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Checks whether the route's action is a controller.
     *
     * @return bool
     */
    protected function isControllerAction()
    {
        return is_string($this->action['uses']);
    }

    /**
     * Run the route action as callable and return the response.
     *
     * @return mixed
     * @throws LogicException
     */
    protected function runCallable()
    {
        $parameters = $this->resolveMethodDependencies(
            $this->getParametersWithoutNulls(), new ReflectionFunction($this->action['uses'])
        );

        $callable = $this->action['uses'];

        return call_user_func_array($callable, $parameters);
    }

    /**
     * Run the route action and return the response.
     *
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    protected function runController()
    {
        return (new ControllerDispatcher($this->container))->dispatch(
            $this, $this->getController(), $this->getControllerMethod()
        );
    }

    /**
     * Get the controller instance for the route.
     *
     * @return mixed
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    protected function getController()
    {
        list($class) = explode('@', $this->action['uses']);
        if (!$this->controller) {
            $this->controller = $this->container->make($class);
        }

        return $this->controller;
    }

    /**
     * Get the controller method used for the route.
     *
     * @return string
     */
    protected function getControllerMethod()
    {
        return explode('@', $this->action['uses'])[1];
    }

    /**
     * Parse the route action into a standard array.
     *
     * @param callable|array|null $action
     *
     * @throws UnexpectedValueException
     *
     * @return array
     */
    protected function parseAction($action)
    {
        // No action has been set for this route
        if (is_null($action)) {
            return ['uses' => function () {
                throw new LogicException("Route for [{$this->uri}] has no action.");
            }];
        }

        // The action is a normal Closure instance (callable)
        if (is_callable($action)) {
            return ['uses' => $action];
        }
        // If no "uses" property has been set, we will dig through the array to find a
        // Closure instance within this list. We will set the first Closure we come
        // across into the "uses" property that will get fired off by this route.
        elseif (!isset($action['uses'])) {
            $action['uses'] = $this->findCallable($action);
        }

        if (is_string($action['uses']) && !StringHelper::contains($action['uses'], '@')) {
            throw new UnexpectedValueException('Invalid route action: ' . $action['uses']);
        }

        return $action;
    }

    /**
     * Find the callable in an action array.
     *
     * @param array $action
     *
     * @return callable
     */
    protected function findCallable(array $action)
    {
        return ArrayHelper::filter($action, function ($key, $value) {
            return is_callable($value) && is_numeric($key);
        });
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return CompiledRoute
     */
    public function getCompiled()
    {
        return $this->compiled;
    }
}
