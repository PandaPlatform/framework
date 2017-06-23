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
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Panda\Config\ConfigurationHandler;
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Log\Logger;
use Panda\Support\Configuration\Handlers\StorageConfiguration;
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
     * @var ConfigurationHandler
     */
    private $config;

    /**
     * @var StorageConfiguration
     */
    private $storageConfiguration;

    /**
     * Environment constructor.
     *
     * @param Application          $app
     * @param ConfigurationHandler $config
     * @param StorageConfiguration $storageConfiguration
     */
    public function __construct(Application $app, ConfigurationHandler $config, StorageConfiguration $storageConfiguration)
    {
        $this->app = $app;
        $this->config = $config;
        $this->storageConfiguration = $storageConfiguration;
    }

    /**
     * Boot the bootstrapper.
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     */
    public function boot($request)
    {
        // Create the logger
        $logger = new Logger('application_logger');

        // Check if there are paths for the logger
        $loggerConfig = $this->config->get('paths.logger');
        if (empty($loggerConfig)) {
            return;
        }

        // Get base path storage
        $basePath = $this->storageConfiguration->getStorageBaseDirectory($this->app->getBasePath());

        // Add error handler
        $path = $basePath . DIRECTORY_SEPARATOR . $this->config->get('paths.logger.base_dir') . DIRECTORY_SEPARATOR . $this->config->get('paths.logger.error');
        $maxFilesCount = $this->config->get('paths.logger.max_files_count');
        $logger->pushHandler(new RotatingFileHandler($path, $maxFilesCount, Logger::ERROR));

        // Add debug handler
        $path = $basePath . DIRECTORY_SEPARATOR . $this->config->get('paths.logger.base_dir') . DIRECTORY_SEPARATOR . $this->config->get('paths.logger.debug');
        $maxFilesCount = $this->config->get('paths.logger.max_files_count');
        $logger->pushHandler(new RotatingFileHandler($path, $maxFilesCount, Logger::DEBUG));

        // Push other processors
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $logger->pushProcessor(new IntrospectionProcessor());
        $logger->pushProcessor(new WebProcessor());

        // Set application logger
        $this->setBindings($logger);
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
