<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration\Parsers;

use Panda\Contracts\Configuration\ConfigurationParser;
use Panda\Foundation\Application;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class PhpParser
 * @package Panda\Support\Configuration\Parsers
 */
class PhpParser implements ConfigurationParser
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
     * Parse the configuration file and get the configuration array.
     *
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function parse()
    {
        // Load default configuration
        $defaultConfigFile = $this->getConfigFile('default');
        $defaultConfigArray = ($defaultConfigFile ? include($defaultConfigFile) : []);

        // Load environment configuration
        $envConfigFile = $this->getConfigFile($this->app->getEnvironment());
        $envConfigArray = ($envConfigFile ? include($envConfigFile) : []);

        // Merge environment config to default and set to application
        return ArrayHelper::merge($defaultConfigArray, $envConfigArray, $deep = true);
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
        $configFile = $this->app->getConfigPath() . DIRECTORY_SEPARATOR . 'config-' . $environment . '.php';
        if (!file_exists($configFile) && $environment != 'default') {
            $configFile = $this->getConfigFile('default');
        }
        if (!file_exists($configFile)) {
            $configFile = null;
        }

        return $configFile;
    }
}
