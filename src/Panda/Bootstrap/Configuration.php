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

use Panda\Config\ConfigurationHandler;
use Panda\Config\Parsers\ConfigurationParser;
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Foundation\Application;
use Panda\Support\Configuration\Parsers\JsonParser;

/**
 * Class Configuration
 * @package Panda\Bootstrap
 */
class Configuration implements BootLoader
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var ConfigurationParser
     */
    private $parser;

    /**
     * @var ConfigurationHandler
     */
    private $handler;

    /**
     * Environment constructor.
     *
     * @param Application          $app
     * @param ConfigurationParser  $parser
     * @param ConfigurationHandler $handler
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function __construct(Application $app, ConfigurationParser $parser, ConfigurationHandler $handler)
    {
        $this->app = $app;
        $this->parser = $parser ?: $app->make(JsonParser::class);
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function boot($request)
    {
        // Parse configuration
        $configArray = $this->parser->parse();

        // Set configuration
        $this->handler->setConfig($configArray);
    }
}
