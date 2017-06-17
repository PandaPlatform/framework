<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Bootstrap;

use Panda\Contracts\Bootstrap\Bootstrapper;
use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Foundation\Application;
use Panda\Helpers\ArrayHelper;
use Panda\Http\Request;
use Panda\Support\Configuration\Config;

/**
 * Class Configuration
 * @package Panda\Foundation\Bootstrap
 */
class Configuration implements Bootstrapper
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
     * Run the initializer.
     *
     * @param Request $request
     * @param string  $environment
     */
    public function boot($request, $environment = 'default')
    {
        // Load default configuration
        $defaultConfigFile = $this->getConfigFile('default');
        $defaultConfigArray = ($defaultConfigFile ? json_decode(file_get_contents($defaultConfigFile), true) : []);

        // Load environment configuration
        $envConfigFile = $this->getConfigFile($environment);
        $envConfigArray = ($envConfigFile ? json_decode(file_get_contents($envConfigFile), true) : []);

        // Merge environment config to default and set to application
        $configArray = ArrayHelper::merge($defaultConfigArray, $envConfigArray, $deep = true);
        if (!empty($configArray)) {
            // Create a new configuration
            $config = new Config($configArray);
            $this->app->set(ConfigurationHandler::class, $config);
        }
    }

    /**
     * Get the configuration file according to the current environment.
     *
     * @param string $environment
     *
     * @return string|null
     */
    private function getConfigFile($environment = 'default')
    {
        $configFile = $this->app->getConfigPath() . DIRECTORY_SEPARATOR . 'config-' . $environment . '.json';
        if (!file_exists($configFile) && $environment != 'default') {
            $configFile = $this->getConfigFile('default');
        }
        if (!file_exists($configFile)) {
            $configFile = null;
        }

        return $configFile;
    }
}
