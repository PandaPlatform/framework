<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Foundation\Bootstrap;

use Panda\Contracts\Bootstrap\Bootstrapper;
use Panda\Contracts\Configuration\ConfigurationHandler;
use Panda\Foundation\Application;
use Panda\Http\Request;
use Panda\Localization\DateTimer;
use Panda\Localization\Locale;

/**
 * Class Localization
 * @package Panda\Foundation\Bootstrap
 */
class Localization implements Bootstrapper
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
     * Environment constructor.
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
     * Boot the bootstrapper.
     *
     * @param Request $request
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \InvalidArgumentException
     */
    public function boot($request)
    {
        // Get default locale
        $defaultLocale = $this->config->get('localization.locale.default_locale');
        if (!empty($defaultLocale)) {
            Locale::setDefault($defaultLocale);
        }

        // Initialize DateTimer
        /** @var DateTimer $dateTimer */
        $dateTimer = $this->app->make(DateTimer::class);

        // Get default timezone
        $defaultTimeZone = $this->config->get('localization.datetime.default_timezone');
        if (!empty($defaultTimeZone)) {
            $dateTimer->setDefaultTimeZone($defaultTimeZone);
        }

        // Boot DateTimer
        $dateTimer->boot($request);
    }
}
