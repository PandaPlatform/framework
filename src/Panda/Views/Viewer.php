<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Views;

use InvalidArgumentException;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Support\Configuration\Handlers\ViewsConfiguration;
use Panda\Support\Helpers\StringHelper;

/**
 * Class Viewer
 * @package Panda\Views
 */
class Viewer
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $executable = false;

    /**
     * @var mixed
     */
    protected $output;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var ViewsConfiguration
     */
    private $viewsConfiguration;

    /**
     * Viewer constructor.
     *
     * @param Application        $app
     * @param Request            $request
     * @param ViewsConfiguration $viewsConfiguration
     */
    public function __construct(Application $app, Request $request, ViewsConfiguration $viewsConfiguration)
    {
        $this->app = $app;
        $this->request = $request;
        $this->viewsConfiguration = $viewsConfiguration;
    }

    /**
     * Load a view.
     *
     * @param string $name
     * @param string $extension
     *
     * @return $this
     */
    public function load($name, $extension = 'html')
    {
        // Get view file path
        $this->view = $this->getViewFilePath($name, $extension);

        return $this;
    }

    /**
     * Render the view output.
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function render()
    {
        // Try to load the view file and return the output
        if (empty($this->view)) {
            throw new InvalidArgumentException(__METHOD__ . ': The view file given is a not valid view.');
        }

        // Load the view file
        if ($this->executable) {
            $this->output = include $this->view;
        } else {
            $this->output = file_get_contents($this->view);
        }

        // Interpolate parameters on the view
        $this->output = StringHelper::interpolate($this->output, $this->parameters);

        return $this;
    }

    /**
     * Register route parameters to this view.
     *
     * @return $this
     */
    public function withParameters()
    {
        $this->parameters = func_get_args();

        return $this;
    }

    /**
     * Get the view output instead of sending it to buffer.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getOutput()
    {
        // Render the view
        $this->render();

        return $this->output;
    }

    /**
     * Get the view's base folder.
     *
     * @param string $name
     *
     * @return string
     */
    private function getViewFolder($name)
    {
        return $this->viewsConfiguration->getViewsPath($this->app->getBasePath()) . DIRECTORY_SEPARATOR . $name . '.view';
    }

    /**
     * @param string $name
     * @param string $extension
     *
     * @return string
     */
    private function getViewFile($name, $extension = 'html')
    {
        return $this->viewsConfiguration->getViewsPath($this->app->getBasePath()) . DIRECTORY_SEPARATOR . $name . '.' . $extension;
    }

    /**
     * Get the view file to be rendered for output.
     *
     * @param string $name
     * @param string $extension
     *
     * @return null|string
     */
    private function getViewFilePath($name, $extension = 'html')
    {
        // Check view file
        $viewFile = $this->getViewFile($name, $extension);
        if (!file_exists($viewFile)) {
            // Check for view folder
            $viewBaseName = $this->getViewFolder($name) . DIRECTORY_SEPARATOR . 'view';

            // Select the view file
            $viewFile = (file_exists($viewBaseName . '.php') ? $viewBaseName . '.php' : $viewBaseName . '.html');
            $viewFile = (file_exists($viewFile) ? $viewFile : null);
        }

        // Check if the file is executable (php)
        if (preg_match('/\.php$/', $viewFile)) {
            $this->executable = true;
        }

        return $viewFile;
    }
}
