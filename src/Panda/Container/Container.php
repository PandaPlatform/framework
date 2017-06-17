<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Container;

use DI\Container as DIContainer;
use DI\ContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use DI\Definition\Helper\ObjectDefinitionHelper;
use DI\NotFoundException;
use InvalidArgumentException;

/**
 * Class Container
 * @package Panda\Container
 */
class Container extends ContainerBuilder
{
    /**
     * @var DIContainer
     */
    private $containerHandler;

    /**
     * @var Container
     */
    protected static $instance;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        // Create the Container Builder
        parent::__construct($containerClass = 'DI\Container');

        $this->containerHandler = $this->build();
    }

    /**
     * @return Container
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * @param Container $instance
     */
    public static function setInstance($instance)
    {
        static::$instance = $instance;
    }

    /**
     * Define an object or a value in the container.
     *
     * @param string                 $name       Entry name
     * @param mixed|DefinitionHelper $definition Value, use definition helpers to define objects
     */
    public function set($name, $definition)
    {
        $this->containerHandler->set($name, $definition);
    }

    /**
     * Get an entry of the container by its name.
     *
     * @param string $name Entry name or a class name.
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws \DI\DependencyException
     */
    public function get($name)
    {
        return $this->containerHandler->get($name);
    }

    /**
     * Build an entry of the container by its name.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws NotFoundException
     * @throws \DI\DependencyException
     */
    public function make($name, $parameters = [])
    {
        return $this->containerHandler->make($name, $parameters);
    }

    /**
     * Test if the container can provide something for the given name.
     *
     * @param string $name
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function has($name)
    {
        return $this->containerHandler->has($name);
    }

    /**
     * Call the given function using the given parameters.
     *
     * @param callable $name
     * @param array    $parameters
     *
     * @return bool
     */
    public function call($name, $parameters)
    {
        return $this->containerHandler->call($name, $parameters);
    }

    /**
     * @param string|null $className Class name of the object.
     *                               If null, the name of the entry (in the container) will be used as class name.
     *
     * @return ObjectDefinitionHelper
     */
    public function object($className = null)
    {
        return \DI\object($className);
    }

    /**
     * @return DIContainer
     */
    public function getContainerHandler()
    {
        return $this->containerHandler;
    }
}
