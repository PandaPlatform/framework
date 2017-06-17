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
     * Viewer constructor.
     *
     * @param Application $app
     * @param Request     $request
     */
    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Load a view.
     *
     * @param string $name
     *
     * @return $this
     */
    public function load($name)
    {
        // Get view full path
        $viewFolder = $this->getViewFolder($name);

        // Check view file
        $this->view = $this->getViewFile($viewFolder);

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
            throw new InvalidArgumentException('The view file given is a not valid view.');
        }

        // Load the view file
        if ($this->executable) {
            $this->output = include $this->view;
        } else {
            $this->output = file_get_contents($this->view);
        }

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
        return $this->app->getViewsPath() . DIRECTORY_SEPARATOR . $name . '.view';
    }

    /**
     * Get the view file to be rendered for output.
     *
     * @param string $viewFolder
     *
     * @return null|string
     */
    private function getViewFile($viewFolder)
    {
        // Set base name
        $baseName = $viewFolder . DIRECTORY_SEPARATOR . 'view';

        // Select the view file
        $viewFile = (file_exists($baseName . '.php') ? $baseName . '.php' : $baseName . '.html');
        $viewFile = (file_exists($viewFile) ? $viewFile : null);

        // Check if the file is executable (php)
        if (preg_match('/\.php$/', $viewFile)) {
            $this->executable = true;
        }

        return $viewFile;
    }
}
