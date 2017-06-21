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

use Panda\Contracts\Bootstrap\Bootstrapper;
use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Contracts\Configuration\ConfigurationParser;
use Panda\Foundation\Application;
use Panda\Http\Request;

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
     */
    public function __construct(Application $app, ConfigurationParser $parser, ConfigurationHandler $handler)
    {
        $this->app = $app;
        $this->parser = $parser;
        $this->handler = $handler;
    }

    /**
     * Run the initializer.
     *
     * @param Request $request
     * @param string  $environment
     */
    public function boot($request, $environment = 'default')
    {
        // Parse configuration
        $configArray = $this->parser->parse();

        // Set configuration
        $this->handler->setConfig($configArray);
    }
}
