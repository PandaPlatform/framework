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
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GeoIp
 * @package Panda\Localization
 */
class GeoIp
{
    /**
     * @var Request
     */
    private $request;

    /**
     * GeoIp constructor.
     *
     * @param Request $request
     */
    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * Get the corresponding timezone according to ip address.
     *
     * @param string $ipAddress The ip address to get the timezone for.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTimezoneByIP($ipAddress = '')
    {
        // Get remote ip address if empty
        $ipAddress = (empty($ipAddress) ? $this->request->server->get('REMOTE_ADDR') : $ipAddress);

        // Get country code
        // In the future should be replaced with full region info
        // to support countries with more than 1 timezones
        $countryCode = $this->getCountryCode2ByIP($ipAddress);

        // Check implementation
        if (!function_exists('geoip_time_zone_by_country_and_region')) {
            throw new Exception('geoip_time_zone_by_country_and_region() function is not available.');
        }

        // Return timezone
        return geoip_time_zone_by_country_and_region($countryCode);
    }

    /**
     * Get the country ISO2A code by ip.
     *
     * @param string $ipAddress The ip address to get the country code for.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getCountryCode2ByIP($ipAddress = '')
    {
        // Get remote ip address if empty
        $ipAddress = (empty($ipAddress) ? $this->request->server->get('REMOTE_ADDR') : $ipAddress);

        // Check implementation
        if (!function_exists('geoip_country_code_by_name')) {
            throw new Exception('geoip_country_code_by_name() function is not available.');
        }

        // Return country code
        return geoip_country_code_by_name($ipAddress);
    }

    /**
     * Get the country ISO3A code by ip.
     *
     * @param string $ipAddress The ip address to get the country code for.
     *
     * @return string
     *
     * @throws Exception
     */
    public function getCountryCode3ByIP($ipAddress = '')
    {
        // Get remote ip address if empty
        $ipAddress = (empty($ipAddress) ? $this->request->server->get('REMOTE_ADDR') : $ipAddress);

        // Check implementation
        if (!function_exists('geoip_country_code3_by_name')) {
            throw new Exception('geoip_country_code3_by_name() function is not available.');
        }

        // Return country code
        return geoip_country_code3_by_name($ipAddress);
    }
}
