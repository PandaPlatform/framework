<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Http;

use InvalidArgumentException;
use Panda\Contracts\Http\Kernel as KernelInterface;
use Panda\Foundation\Application;
use Panda\Foundation\Bootstrap\Configuration;
use Panda\Foundation\Bootstrap\Environment;
use Panda\Foundation\Bootstrap\FacadeRegistry;
use Panda\Foundation\Bootstrap\Localization;
use Panda\Foundation\Bootstrap\Logging;
use Panda\Http\Request;
use Panda\Http\Response;
use Panda\Routing\Controller;
use Panda\Routing\Router;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class Kernel
 * @package Panda\Foundation\Http
 */
class Kernel implements KernelInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var string[]
     */
    protected $bootstrappers = [
        Environment::class,
        Configuration::class,
        Logging::class,
        FacadeRegistry::class,
        Localization::class,
    ];

    /**
     * Kernel constructor.
     *
     * @param Application $app
     * @param Router      $router
     */
    public function __construct(Application $app, Router $router)
    {
        $this->app = $app;
        $this->router = $router;
    }

    /**
     * Init the panda application and start all the interfaces that
     * are needed for runtime.
     *
     * @param Request|SymfonyRequest $request
     *
     * @throws InvalidArgumentException
     */
    public function boot($request)
    {
        // Check arguments
        if (empty($request) || !($request instanceof SymfonyRequest)) {
            throw new InvalidArgumentException('Request is empty or not valid.');
        }

        // Initialize application
        $this->app->boot($request, $this->bootstrappers);

        // Set base controller router
        Controller::setRouter($this->router);

        // Include routes
        include_once $this->app->getRoutesPath();
    }

    /**
     * Handle the incoming request and return a response.
     *
     * @param Request|SymfonyRequest $request
     *
     * @return Response
     * @throws InvalidArgumentException
     */
    public function handle(SymfonyRequest $request)
    {
        // Boot kernel
        $this->boot($request);

        // Dispatch the response
        return $this->router->dispatch($request);
    }

    /**
     * Terminate the kernel process finalizing response information.
     *
     * @param Request|SymfonyRequest   $request
     * @param Response|SymfonyResponse $response
     */
    public function terminate(SymfonyRequest $request, SymfonyResponse $response)
    {
    }

    /**
     * Add a bootstrapper to the application flow.
     *
     * @param string $bootstrapper
     *
     * @throws InvalidArgumentException
     */
    public function addExternalBootstrapper($bootstrapper)
    {
        // Add to the queue
        $this->bootstrappers[] = $bootstrapper;
    }

    /**
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @return Request
     */
    public function getCurrentRequest()
    {
        return $this->router->getCurrentRequest();
    }
}
