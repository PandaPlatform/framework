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

use Panda\Contracts\Bootstrap\BootLoader;
use Panda\Localization\GeoIp;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class DateTimer
 * @package Panda\Bootstrap
 */
class DateTimer implements BootLoader
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
     * @param Request $request
     */
    public function boot($request = null)
    {
        // Initialize with default timezone
        $timezone = $this->getDefaultTimeZone();

        // Detect timezone based on ip, if available
        try {
            if (!empty($request)) {
                $geoIp = new GeoIp($request);
                $timezone = $geoIp->getTimezoneByIP();
            }
        } catch (Throwable $ex) {
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
