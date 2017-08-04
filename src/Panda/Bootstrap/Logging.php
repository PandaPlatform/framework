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

use InvalidArgumentException;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
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
    private $app;

    /**
     * @var LoggerConfiguration
     */
    private $loggerConfiguration;

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
     *
     * @throws InvalidArgumentException
     */
    public function boot($request = null)
    {
        // Create the logger
        $logger = new Logger('application_logger');

        // Check if there are paths for the logger
        $loggerConfig = $this->loggerConfiguration->getLoggerConfig();
        if (empty($loggerConfig)) {
            return;
        }

        if ($loggerConfig['enabled']) {
            // Get base path storage
            if ($loggerConfig['path_is_relative']) {
                $basePath = $this->app->getBasePath() . DIRECTORY_SEPARATOR . $loggerConfig['base_dir'];
            } else {
                $basePath = $loggerConfig['base_dir'];
            }

            // Add error handler
            $path = $basePath . DIRECTORY_SEPARATOR . $loggerConfig['error'];
            $logger->pushHandler(new RotatingFileHandler($path, $loggerConfig['max_files_count'], Logger::ERROR));

            // Add debug handler
            $path = $basePath . DIRECTORY_SEPARATOR . $loggerConfig['debug'];
            $logger->pushHandler(new RotatingFileHandler($path, $loggerConfig['max_files_count'], Logger::DEBUG));

            // Push other processors
            $logger->pushProcessor(new PsrLogMessageProcessor());
            $logger->pushProcessor(new IntrospectionProcessor());
            $logger->pushProcessor(new WebProcessor());

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
