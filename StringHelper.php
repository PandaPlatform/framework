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

/**
 * Class StringHelper
 * @package Panda\Support\Helpers
 */
class StringHelper
{
    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * @param string $str
     * @param string $suffix
     * @param string $separator
     *
     * @return string
     */
    public static function concatenate($str, $suffix, $separator)
    {
        if (empty($suffix)) {
            return $str;
        }
        if (empty($str)) {
            return $suffix;
        }

        return $str . $separator . $suffix;
    }

    /**
     * Look for parameters in the given string and replace them with the given values.
     * This function works using the annotation %{parameter_name} and {parameter_name}.
     * It is advised to use the first one.
     *
     * @param string $string     The string containing the variables.
     * @param array  $parameters An array of variables to be replaced by key.
     * @param string $openingTag The opening tag of the variable.
     * @param string $closingTag The closing tag of the variable.
     *
     * @return string
     */
    public static function interpolate($string, $parameters = array(), $openingTag = "%{", $closingTag = "}")
    {
        // Check for parameters and replace the values
        foreach ($parameters as $pKey => $pValue) {
            // Replace variable
            $string = str_replace($openingTag . $pKey . $closingTag, $pValue, $string);

            // Generic Fallback
            $string = str_replace("{" . $pKey . "}", $pValue, $string);
        }

        return $string;
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @param bool   $groupQuotes
     *
     * @return array
     */
    public static function explode($string, $delimiter = ' ', $groupQuotes = false)
    {
        if (!$groupQuotes) {
            return explode($delimiter, $string);
        } else {
            preg_match_all('([^\s"\'“”]+|"[^"]*"|\'[^\']*\'|“[^”]*”|“[^“]*“|”[^”]*”|”[^“]*“)', $string, $matches);

            return $matches[0];
        }
    }

    /**
     * Checks if a string is empty or not. If the $value parameter is not a string, the method will always return false
     *
     * @param string $value
     *
     * @return bool true if string is null or has 0 length; otherwise false
     */
    public static function emptyString($value)
    {
        if (is_string($value)) {
            return is_null($value) || strlen($value) === 0;
        }

        return false;
    }

    /**
     * Check if a given string contains a given substring.
     *
     * @param string       $haystack
     * @param string|array $needle
     *
     * @return bool
     */
    public static function contains($haystack, $needle)
    {
        // Check arguments
        if (empty($haystack) || empty($needle)) {
            return false;
        }

        // Needle is string
        if (!is_array($needle)) {
            return mb_strpos($haystack, $needle) !== false;
        }

        // Needle is array, check if haystack contains all items
        foreach ((array)$needle as $str_needle) {
            if (!empty($str_needle) && mb_strpos($haystack, $str_needle) === false) {
                return false;
            }
        }

        return true;
    }
}
