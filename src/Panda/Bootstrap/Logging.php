<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Bootstrap;

use Monolog\Handler\RotatingFileHandler;
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Log\Logger;
use Panda\Support\Configuration\Handlers\LoggerConfiguration;
use Psr\Log\LoggerInterface;

/**
 * Class Logging
 * @package Panda\Bootstrap
 */
class Logging implements BootLoader
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var LoggerConfiguration
     */
    protected $loggerConfiguration;

    /**
     * Environment constructor.
     *
     * @param Application         $app
     * @param LoggerConfiguration $loggerConfiguration
     */
    public function __construct(Application $app, LoggerConfiguration $loggerConfiguration)
    {
        $this->app = $app;
        $this->loggerConfiguration = $loggerConfiguration;
    }

    /**
     * @param Request $request
     */
    public function boot($request = null)
    {
        // Check if there are paths for the logger
        $loggerConfig = $this->loggerConfiguration->getLoggerConfig();
        if (empty($loggerConfig)) {
            return;
        }

        // Check if framework logger is enabled
        if ($loggerConfig['enabled']) {
            // Create logger
            $loggerName = $loggerConfig['name'] ?: 'panda';
            $logger = new Logger($loggerName);

            // Get base path storage
            if ($loggerConfig['path_is_relative']) {
                $basePath = $this->app->getBasePath() . DIRECTORY_SEPARATOR . $loggerConfig['base_dir'];
            } else {
                $basePath = $loggerConfig['base_dir'];
            }

            // Add debug handler
            if (!empty($loggerConfig['debug'])) {
                $path = $basePath . DIRECTORY_SEPARATOR . $loggerConfig['debug'];
                $logger->pushHandler(new RotatingFileHandler($path, $loggerConfig['max_files_count'], Logger::DEBUG));
            }

            // Add info handler
            if (!empty($loggerConfig['info'])) {
                $path = $basePath . DIRECTORY_SEPARATOR . $loggerConfig['info'];
                $logger->pushHandler(new RotatingFileHandler($path, $loggerConfig['max_files_count'], Logger::INFO));
            }

            // Add error handler
            if (!empty($loggerConfig['error'])) {
                $path = $basePath . DIRECTORY_SEPARATOR . $loggerConfig['error'];
                $logger->pushHandler(new RotatingFileHandler($path, $loggerConfig['max_files_count'], Logger::ERROR));
            }

            // Set application logger
            $this->setBindings($logger);
        }
    }

    /**
     * Set logging bindings.
     *
     * @param LoggerInterface $logger
     */
    private function setBindings($logger)
    {
        $this->app->set(LoggerInterface::class, $logger);
    }
}
