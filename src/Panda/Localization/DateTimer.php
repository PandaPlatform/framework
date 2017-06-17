<?php

/*
 * This file is part of the Panda Localization Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Localization;

use Exception;
use Panda\Contracts\Bootstrap\Bootstrapper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DateTimer
 * @package Panda\Localization
 */
class DateTimer implements Bootstrapper
{
    /**
     * The default timezone for the framework.
     */
    const DEFAULT_TIMEZONE = 'GMT';

    /**
     * @var string
     */
    protected $defaultTimeZone = null;

    /**
     * Init session.
     *
     * @param Request $request
     */
    public function boot($request)
    {
        try {
            // Try to get timezone by ip
            $geoIp = new GeoIp($request);
            $timezone = $geoIp->getTimezoneByIP();
        } catch (Exception $ex) {
            $timezone = $this->getDefaultTimeZone();
        }

        $this->setTimezone($timezone);
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->defaultTimeZone ?: static::DEFAULT_TIMEZONE;
    }

    /**
     * @param string $defaultTimeZone
     */
    public function setTimezone($defaultTimeZone)
    {
        $this->defaultTimeZone = $defaultTimeZone;
        $this->setDefaultTimeZone($this->defaultTimeZone);
    }

    /**
     * Sets the current runtime timezone for the system and for the user.
     *
     * @param string $timezone
     */
    public function setDefaultTimeZone($timezone)
    {
        // Set php timezone
        date_default_timezone_set($timezone);
    }

    /**
     * Get the current default runtime timezone.
     *
     * @return string The current timezone.
     */
    public function getDefaultTimeZone()
    {
        return date_default_timezone_get();
    }
}
