<?php

/*
 * This file is part of the Panda Helpers Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Helpers;

use InvalidArgumentException;

/**
 * Class UrlHelper
 * @package Panda\Support\Helpers
 */
class UrlHelper
{
    /**
     * Creates and returns a url with parameters in url encoding.
     *
     * @param string $url        The base url.
     * @param array  $parameters An associative array of parameters as key => value.
     * @param string $host
     * @param string $protocol
     *
     * @return string A well formed url.
     * @throws InvalidArgumentException
     */
    public static function get($url, $parameters = [], $host = null, $protocol = null)
    {
        // Check url arguments
        if (empty($url)) {
            throw new InvalidArgumentException(__METHOD__ . ': The given url is empty.');
        }

        // Get current url info
        $urlInfo = self::info($url);
        $finalUrl = $finalUrl = $urlInfo['path']['plain'];

        // Build url query
        $urlParameters = $urlInfo['path']['parameters'] ?: [];
        $parameters = ArrayHelper::merge($parameters, $urlParameters);

        if (!empty($parameters)) {
            $finalUrl .= '?' . http_build_query($parameters);
        }

        // Set protocol
        $host = $host ?: $urlInfo['host'];
        $protocol = $protocol ?: $urlInfo['protocol'];

        // Resolve URL according to system configuration
        return (empty($host) ? '' : $protocol . '://') . self::normalize($host . '/' . $finalUrl);
    }

    /**
     * Get current domain.
     *
     * @param string $url       Set url or leave empty to get current
     * @param bool   $useOrigin Set True to use origin value if exists.
     *
     * @return string
     */
    public static function getDomain($url = '', $useOrigin = false)
    {
        $urlInfo = self::info($url);

        // Check if there is an origin value and use that
        if (isset($urlInfo['origin']) && $useOrigin) {
            $urlInfo = self::info($urlInfo['origin']);
        }

        return $urlInfo['domain'];
    }

    /**
     * Gets the current navigation sub-domain.
     *
     * @param string $url       Set url or leave empty to get current
     * @param bool   $useOrigin Set True to use origin value if exists.
     *
     * @return string
     */
    public static function getSubDomain($url = '', $useOrigin = false)
    {
        // Get current url info
        $urlInfo = self::info($url);

        // Check if there is an origin value and use that
        if (isset($urlInfo['origin']) && $useOrigin) {
            $urlInfo = self::info($urlInfo['origin']);
        }

        // Return subdomain value
        return $urlInfo['sub'];
    }

    /**
     * Gets the info of the url in an array.
     *
     * @param string $url    The url to get the information from. If the url is empty, get the current request url.
     * @param string $domain The url domain. This is given to distinguish the subdomains on the front.
     *
     * @return array The url info as follows:
     *               ['referer'] = The referer value, if exists.
     *               ['origin'] = The host origin value, if exists.
     *               ['url'] = The full url page.
     *               ['protocol'] = The server protocol.
     *               ['host'] = The full host.
     *               ['sub'] = The navigation subdomain.
     *               ['domain'] = The host domain.
     *               ['path'] = Path information as follows:
     *               ['path']['with_parameters'] = The full path including the parameters
     *               ['path']['plain'] = Only the path, without parameters
     *               ['path']['parameters'] = An array of all url parameters by name and value.
     */
    public static function info($url = '', $domain = '')
    {
        // Initialize url info array
        $info = [];

        // If no given url, get current
        if (empty($url)) {
            // Get protocol
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';

            // Set full url
            $url = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . trim($_SERVER['REQUEST_URI'], '/');

            // Set extra attributes (if any)
            $info['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
            $info['origin'] = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
        }

        // Get protocol
        $protocol = (strpos($url, 'https') === 0 ? 'https' : 'http');

        // Normalize url
        $url = str_replace($protocol . '://', '', $url);
        $info['url'] = $protocol . '://' . $url;
        $info['protocol'] = $protocol;
        $info['host'] = null;
        $info['sub'] = null;
        $info['domain'] = null;

        // Get url path
        $pathParts = explode('/', $url);
        $host = $pathParts[0];
        unset($pathParts[0]);

        // Check if this is an ip or a domain
        if (self::isIP($host)) {
            $sub = '';
            $domain = $host;
        } else {
            // Check if the url from the given domain
            $pattern = '/.*' . str_replace('.', '\.', $domain) . '$/i';
            if (!empty($domain) && preg_match($pattern, $host) == 1) {
                // Get sub-domain
                $sub = str_replace($domain, '', $host);
                $sub = (empty($sub) ? 'www' : $sub);
                $sub = trim($sub, '.');
            } else {
                // Get host parts
                $parts = explode('.', $host);

                // Host must has at least 2 parts
                if (count($parts) < 3) {
                    $sub = 'www';
                } else {
                    $sub = $parts[0];
                    unset($parts[0]);
                }

                // Set domain
                $domain = implode('.', $parts);
            }
        }

        // Set info
        $info['host'] = $host;
        $info['sub'] = $sub;
        $info['domain'] = $domain;

        // Get and check for url path
        $fullPath = implode('/', $pathParts);
        $info['path'] = [
            'with_parameters' => '',
            'plain' => '',
            'parameters' => [],
        ];
        if (!empty($fullPath)) {
            $info['path']['with_parameters'] = '/' . implode('/', $pathParts);

            // Split for domain and subdomain
            @list($plain, $params) = explode('?', $info['path']['with_parameters']);
            $info['path']['plain'] = $plain;

            // Get parameters
            $urlParams = explode('&', $params);
            foreach ($urlParams as $up) {
                $pparts = explode('=', $up);
                if (!empty($pparts) && !empty($pparts[0])) {
                    $info['path']['parameters'][$pparts[0]] = urldecode($pparts[1]);
                }
            }
        }


        return $info;
    }

    /**
     * Check if the given url is in IP format.
     *
     * @param string $url
     *
     * @return bool
     */
    public static function isIP($url)
    {
        // Check if given url is ip (v4 or v6)
        return self::isIPv4($url) || self::isIPv6($url);
    }

    /**
     * Check if the given url is an IPv4.
     *
     * @param string $url
     *
     * @return bool
     */
    private static function isIPv4($url)
    {
        return preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $url) === 1;
    }

    /**
     * Check if the given url is an IPv6.
     *
     * @param $url
     *
     * @return bool
     */
    private static function isIPv6($url)
    {
        return false;
    }

    /**
     * Normalizes a path by collapsing redundant slashes.
     *
     * @param string $url
     *
     * @return mixed
     */
    private static function normalize($url)
    {
        return preg_replace('/\/{2,}/', '/', $url);
    }
}
