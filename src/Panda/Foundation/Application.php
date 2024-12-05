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

use DI\DependencyException;
use DI\NotFoundException;
use InvalidArgumentException;
use Panda\Config\ConfigurationHandler;
use Panda\Config\SharedConfiguration;
use Panda\Container\Container;
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Contracts\Http\Kernel as KernelInterface;
use Panda\Foundation\Http\Kernel;
use Panda\Http\Request;
use Throwable;

/**
 * Class Application
 *
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
     * The application's config path.
     *
     * @var string
     */
    protected $configPath;

    /**
     * Set whether the application has been initialized or not.
     *
     * @var bool
     */
    protected $bootLoaded;

    /**
     * Create a new panda application instance.
     *
     * @param string|null $basePath
     * @param string|null $configPath
     * @param string      $environment
     */
    public function __construct($basePath = null, $configPath = null, $environment = '')
    {
        // Construct container
        parent::__construct();

        // Set object properties
        if (!empty($basePath)) {
            $this->setBasePath($basePath);
        }
        if (!empty($configPath)) {
            $this->setConfigPath($configPath);
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
     * @param mixed $default
     *
     * @return string|mixed
     */
    public function getEnvironment($default = null)
    {
        try {
            return $this->get('env');
        } catch (Throwable $ex) {
        }

        return $default;
    }

    /**
     * Register service bindings.
     */
    private function registerServiceBindings()
    {
        $this->set(KernelInterface::class, \DI\get(Kernel::class));
        $this->set(ConfigurationHandler::class, \DI\get(SharedConfiguration::class));
    }

    /**
     * Initialize the framework with the given BootLoaders.
     *
     * @param Request $request
     * @param array   $bootLoaders
     *
     * @throws DependencyException
     * @throws NotFoundException
     * @throws InvalidArgumentException
     */
    public function boot($request = null, $bootLoaders = [])
    {
        // Check if application has already initialized
        if ($this->bootLoaded) {
            return;
        }

        // Boot all the BootLoaders
        foreach ($bootLoaders as $bootLoader) {
            $this->make($bootLoader)->boot($request);
        }

        $this->bootLoaded = true;
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
        return $this->configPath ?: $this->getBasePath() . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * @param string $configPath
     */
    public function setConfigPath($configPath)
    {
        $this->configPath = $configPath;
    }
}
