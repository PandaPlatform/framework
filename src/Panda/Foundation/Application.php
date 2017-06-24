<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation;

use Panda\Config\ConfigurationHandler;
use Panda\Config\SharedConfiguration;
use Panda\Container\Container;
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Contracts\Http\Kernel as KernelInterface;
use Panda\Foundation\Http\Kernel;
use Panda\Http\Request;

/**
 * Class Application
 * @package Panda\Foundation
 */
class Application extends Container implements BootLoader
{
    /**
     * The application's base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The application's storage path.
     *
     * @var string
     */
    protected $storagePath;

    /**
     * Create a new panda application instance.
     *
     * @param string $basePath
     * @param string $environment
     */
    public function __construct($basePath = null, $environment = '')
    {
        // Construct container
        parent::__construct();

        // Set object properties
        if (!empty($basePath)) {
            $this->setBasePath($basePath);
        }

        // Register all bindings
        $this->registerAppBindings($environment);
        $this->registerServiceBindings();
    }

    /**
     * @return Application|Container
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Register application bindings.
     *
     * @param string $environment
     *
     * @return $this
     */
    private function registerAppBindings($environment)
    {
        static::setInstance($this);

        // Set application environment
        $this->setEnvironment($environment);

        // Set container
        $this->set('app', $this);
        $this->set(self::class, $this);
        $this->set(Container::class, $this);

        return $this;
    }

    /**
     * Set the application environment.
     *
     * @param string $environment
     *
     * @return $this
     */
    public function setEnvironment($environment = '')
    {
        // Set the application environment
        $environment = ($environment ?: strtolower(getenv('APPLICATION_ENV')));
        $environment = ($environment ?: 'default');

        // Set the application environment
        $this->set('env', $environment);

        return $this;
    }

    /**
     * Get the current application environment.
     *
     * @return string
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function getEnvironment()
    {
        return $this->get('env');
    }

    /**
     * Register service bindings.
     */
    private function registerServiceBindings()
    {
        $this->set(KernelInterface::class, \DI\object(Kernel::class));
        $this->set(ConfigurationHandler::class, \DI\object(SharedConfiguration::class));
    }

    /**
     * Initialize the framework with the given BootLoaders.
     *
     * @param Request $request
     * @param array   $bootLoaders
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function boot($request, $bootLoaders = [])
    {
        // Boot all the BootLoaders
        foreach ($bootLoaders as $bootLoader) {
            $this->make($bootLoader)->boot($request);
        }
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     *
     * @return Application
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config';
    }
}
