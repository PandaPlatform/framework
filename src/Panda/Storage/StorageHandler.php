<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Storage;

use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Contracts\Storage\StorageInterface;
use Panda\Foundation\Application;
use Panda\Storage\Filesystem\Filesystem;
use RuntimeException;

/**
 * Class StorageHandler
 * @package Panda\Storage
 */
class StorageHandler
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ConfigurationHandler
     */
    protected $config;

    /**
     * @var string
     */
    protected $defaultDisk = 'local';

    /**
     * StorageHandler constructor.
     *
     * @param Application          $app
     * @param ConfigurationHandler $config
     */
    public function __construct(Application $app, ConfigurationHandler $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Get a storage interface.
     *
     * @param string $name
     *
     * @return StorageInterface
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function disk($name = '')
    {
        $name = ($name ?: $this->defaultDisk);
        switch ($name) {
            case 'local':
                // Get base directory
                $directory = $this->getFilesystemBaseDirectory();

                /** @var Filesystem $storageHandler */
                $storageHandler = $this->app->make(Filesystem::class);
                $storageHandler->setStorageDirectory($directory);

                return $storageHandler;
        }

        return null;
    }

    /**
     * @param string $defaultDisk
     */
    public function setDefaultDisk($defaultDisk)
    {
        $this->defaultDisk = $defaultDisk;
    }

    /**
     * Get the base storage directory.
     *
     * @return string
     */
    public function getFilesystemBaseDirectory()
    {
        return $this->app->getBasePath() . DIRECTORY_SEPARATOR . $this->config->get('paths.storage.base_dir');
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     * @throws RuntimeException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function __call($method, $args)
    {
        $instance = $this->disk();
        if (!$instance || empty($instance)) {
            throw new RuntimeException('The storage interface is invalid.');
        }

        return call_user_func_array([$instance, $method], $args);
    }
}
