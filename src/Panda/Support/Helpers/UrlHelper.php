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
use Panda\Support\Exceptions\NotImplementedException;

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
     * @return string
     * @throws InvalidArgumentException
     */
    public static function get($url, $parameters = [], $host = null, $protocol = null)
    {
        // Check url arguments
        if (empty($url)) {
            throw new InvalidArgumentException(__METHOD__ . ': The given url is empty.');
        }

        // Get current url info
        $urlInfo = static::info($url);
        $finalUrl = ArrayHelper::get($urlInfo, 'path.plain', '', true);

        // Build url query
        $urlParameters = ArrayHelper::get($urlInfo, 'path.parameters', [], true);
        $parameters = ArrayHelper::merge($parameters, $urlParameters);

        if (!empty($parameters)) {
            $finalUrl .= '?' . http_build_query($parameters);
        }

        // Set protocol
        $host = $host ?: $urlInfo['host'];
        $protocol = $protocol ?: $urlInfo['protocol'];

        // Resolve URL according to system configuration
        return (empty($host) ? '' : $protocol . '://') . static::normalize($host . '/' . $finalUrl);
    }

    /**
     * @param string $url
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public static function getHost($url)
    {
        return static::info($url)['host'];
    }

    /**
     * @param string $url
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public static function getDomain($url)
    {
        $urlInfo = static::info($url);

        return ArrayHelper::get($urlInfo, 'domain', null, true);
    }

    /**
     * @param string $url
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public static function getSubDomain($url)
    {
        $urlInfo = static::info($url);

        return ArrayHelper::get($urlInfo, 'sub', null, true);
    }

    /**
     * @param string $url
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public static function getProtocol($url)
    {
        $urlInfo = static::info($url);

        return ArrayHelper::get($urlInfo, 'protocol', null, true);
    }

    /**
     * @param string $url
     * @param bool   $withParameters
     *
     * @return string|null
     * @throws InvalidArgumentException
     */
    public static function getPath($url, $withParameters = false)
    {
        $urlInfo = static::info($url);

        return $withParameters ? ArrayHelper::get($urlInfo, 'path.with_parameters', null, true) : ArrayHelper::get($urlInfo, 'path.plain', null, true);
    }

    /**
     * Gets the info of the url in an array.
     *
     * @param string $url    The url to get the information from.
     * @param string $domain The url domain. This is given to distinguish the sub-domains on the front.
     *
     * @return array The url info as follows:
     *                  ['referrer'] = The referrer value, if exists.
     *                  ['origin'] = The host origin value, if exists.
     *                  ['url'] = The full url page.
     *                  ['protocol'] = The server protocol.
     *                  ['host'] = The full host.
     *                  ['sub'] = The navigation subdomain.
     *                  ['domain'] = The host domain.
     *                  ['path'] = Path information as follows:
     *                  ['path']['with_parameters'] = The full path including the parameters
     *                  ['path']['plain'] = Only the path, without parameters
     *                  ['path']['parameters'] = An array of all url parameters by name and value.
     * @throws InvalidArgumentException
     */
    public static function info($url, $domain = '')
    {
        // Initialize url info array
        $info = [];

        // If no given url, get current
        if (empty($url)) {
            throw new InvalidArgumentException(__METHOD__ . ': The given url is empty.');
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
        if (static::isIP($host)) {
            $sub = '';
            $domain = $host;
        } else {
            // Check if the url from the given domain
            $pattern = '/.*' . str_replace('.', '\.', $domain) . '$/i';
            if (!empty($domain) && preg_match($pattern, $host) == 1) {
                // Get sub-domain
                $sub = str_replace($domain, '', $host);
                $sub = trim($sub, '.');
            } else {
                // Get host parts
                $parts = explode('.', $host);

                // Host must has at least 2 parts
                if (count($parts) < 3) {
                    $sub = '';
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
            $info['path']['with_parameters'] = static::normalize('/' . implode('/', $pathParts));

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
        // Check if given url is ip, v4 for now
        return static::isIPv4($url);
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
     * @throws NotImplementedException
     */
    private static function isIPv6($url)
    {
        throw new NotImplementedException(__METHOD__ . ': This function is not implemented yet.');
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
