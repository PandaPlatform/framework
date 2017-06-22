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

use Panda\Config\Parsers\PhpParser as ConfigPhpParser;
use Panda\Foundation\Application;
use Panda\Support\Helpers\ArrayHelper;

/**
 * Class PhpParser
 * @package Panda\Support\Configuration\Parsers
 */
class PhpParser extends ConfigPhpParser
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
     * @param string $configFile
     *
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function parse($configFile = '')
    {
        // Load default configuration
        $defaultConfigFile = $this->getConfigFile($configFile, 'default');
        $defaultConfigArray = parent::parse($defaultConfigFile);

        // Load environment configuration
        $envConfigFile = $this->getConfigFile($configFile, $this->app->getEnvironment());
        $envConfigArray = parent::parse($envConfigFile);

        // Merge environment config to default and set to application
        return ArrayHelper::merge($defaultConfigArray, $envConfigArray, $deep = true);
    }

    /**
     * Get the configuration file according to the given environment, if any.
     *
     * @param string $configFile
     * @param string $environment
     *
     * @return string
     */
    private function getConfigFile($configFile, $environment = 'default')
    {
        // Normalize config file
        $configFile = $configFile ?: 'config';

        // Check if there is an environment-specific config file
        $envConfigFile = $this->app->getConfigPath() . DIRECTORY_SEPARATOR . $configFile . '-' . $environment . '.php';
        if (!is_file($envConfigFile)) {
            $envConfigFile = $this->app->getConfigPath() . DIRECTORY_SEPARATOR . $configFile . '.php';
        }

        return $envConfigFile;
    }
}
