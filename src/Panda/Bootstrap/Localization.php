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
use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Localization\Locale;

/**
 * Class Localization
 * @package Panda\Bootstrap
 */
class Localization implements BootLoader
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
     * @var DateTimer
     */
    protected $dateTimer;

    /**
     * Environment constructor.
     *
     * @param Application          $app
     * @param ConfigurationHandler $config
     * @param DateTimer            $dateTimer
     */
    public function __construct(Application $app, ConfigurationHandler $config, DateTimer $dateTimer)
    {
        $this->app = $app;
        $this->config = $config;
        $this->dateTimer = $dateTimer;
    }

    /**
     * @param Request $request
     */
    public function boot($request = null)
    {
        // Get default locale
        $defaultLocale = $this->config->get('localization.locale.default_locale');
        if (!empty($defaultLocale)) {
            Locale::setDefault($defaultLocale);
        }

        // Get default timezone
        $defaultTimeZone = $this->config->get('localization.datetime.default_timezone');
        if (!empty($defaultTimeZone)) {
            $this->dateTimer->setDefaultTimeZone($defaultTimeZone);
        }

        // Boot DateTimer
        $this->dateTimer->boot($request);
    }
}
