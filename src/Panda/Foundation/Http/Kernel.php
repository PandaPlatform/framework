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
use Panda\Bootstrap\Configuration;
use Panda\Bootstrap\Environment;
use Panda\Bootstrap\FacadeRegistry;
use Panda\Bootstrap\Localization;
use Panda\Bootstrap\Logging;
use Panda\Bootstrap\Session;
use Panda\Contracts\Http\Kernel as KernelInterface;
use Panda\Foundation\Application;
use Panda\Foundation\Bootstrap\BootstrapRegistry;
use Panda\Http\Request;
use Panda\Http\Response;
use Panda\Routing\Controller;
use Panda\Routing\Router;
use Panda\Support\Configuration\Handlers\RoutesConfiguration;
use Panda\Support\Helpers\ArrayHelper;
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
     * @var RoutesConfiguration
     */
    private $routesConfiguration;

    /**
     * @var BootstrapRegistry
     */
    private $bootstrapRegistry;

    /**
     * Kernel constructor.
     *
     * @param Application         $app
     * @param Router              $router
     * @param BootstrapRegistry   $bootstrapRegistry
     * @param RoutesConfiguration $routesConfiguration
     */
    public function __construct(Application $app, Router $router, BootstrapRegistry $bootstrapRegistry, RoutesConfiguration $routesConfiguration)
    {
        $this->app = $app;
        $this->router = $router;
        $this->bootstrapRegistry = $bootstrapRegistry;
        $this->routesConfiguration = $routesConfiguration;

        // Register BootLoaders
        $bootLoaders = $this->bootstrapRegistry->getItems();
        $frameworkBootLoaders = [
            Environment::class,
            Configuration::class,
            Localization::class,
            FacadeRegistry::class,
            Session::class,
            Logging::class,
        ];
        $this->bootstrapRegistry->setItems(ArrayHelper::merge($bootLoaders, $frameworkBootLoaders));
    }

    /**
     * Init the panda application and start all the interfaces that
     * are needed for runtime.
     *
     * @param Request|SymfonyRequest $request
     *
     * @throws InvalidArgumentException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function boot($request)
    {
        // Check arguments
        if (empty($request) || !($request instanceof SymfonyRequest)) {
            throw new InvalidArgumentException('Request is empty or not valid.');
        }

        // Initialize application
        $this->getApp()->boot($request, $this->bootstrapRegistry->getItems());

        // Set base controller router
        Controller::setRouter($this->getRouter());

        // Include routes
        include_once $this->routesConfiguration->getRoutesPath($this->getApp()->getBasePath());
    }

    /**
     * Handle the incoming request and return a response.
     *
     * @param Request|SymfonyRequest $request
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \UnexpectedValueException
     */
    public function handle(SymfonyRequest $request)
    {
        // Boot kernel
        $this->boot($request);

        // Dispatch the response
        return $this->getRouter()->dispatch($request);
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
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return Request
     */
    public function getCurrentRequest()
    {
        return $this->getRouter()->getCurrentRequest();
    }
}
