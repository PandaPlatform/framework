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
 * Class ArrayHelper
 * @package Panda\Support\Helpers
 */
class ArrayHelper
{
    /**
     * Get an item from an array.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     * @param bool   $useDotSyntax
     *
     * @return mixed
     */
    public static function get($array, $key = null, $default = null, $useDotSyntax = false)
    {
        // Check arguments
        if (empty($array)) {
            return $default;
        }

        // Check if key is empty
        if (is_null($key)) {
            return $array;
        }

        // Check if value exists as is
        if (isset($array[$key])) {
            return $array[$key];
        }

        // Return default value, without dot syntax
        if (!$useDotSyntax) {
            return $default;
        }

        // Split name using dots
        $keyParts = explode('.', $key);
        if (count($keyParts) == 1) {
            return $array[$key];
        }

        // Recursive call
        $base = $keyParts[0];
        unset($keyParts[0]);

        // Check if the base array exists
        if (!isset($array[$base])) {
            return $default;
        }

        // Get key, base array and continue
        $key = implode('.', $keyParts);
        $array = $array[$base];

        return static::get($array, $key, $default, $useDotSyntax);
    }

    /**
     * Set an item in the given array.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     * @param bool   $useDotSyntax
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public static function set($array, $key, $value = null, $useDotSyntax = false)
    {
        // Normalize array
        $array = $array ?: [];

        // Check if key is empty
        if (empty($key)) {
            throw new InvalidArgumentException(__METHOD__ . ': Key cannot be empty');
        }

        /**
         * Split name using dots.
         *
         * Just checking whether the key doesn't have any dots
         * but $useDotSyntax is true by default.
         */
        $keyParts = explode('.', $key);
        $useDotSyntax = $useDotSyntax && count($keyParts) > 1;

        // Set simple value, without dot syntax
        if (!$useDotSyntax) {
            if (is_null($value) && isset($array[$key])) {
                unset($array[$key]);
            } else if (!is_null($value)) {
                $array[$key] = $value;
            }
        } else {
            // Recursive call
            $base = $keyParts[0];
            unset($keyParts[0]);

            // Check if the base array exists
            if (!isset($array[$base])) {
                throw new InvalidArgumentException(__METHOD__ . ': The given key does not exist in the given array (using dot syntax).');
            }

            // Get key, base array and continue
            $key = implode('.', $keyParts);
            $innerArray = $array[$base];

            $array[$base] = static::set($innerArray, $key, $value, $useDotSyntax);
        }

        return $array;
    }

    /**
     * Filter array elements with a given callback function.
     *
     * It returns the item that matches the callback function.
     * If the callback function is empty, it will return the array as is.
     *
     * If the array is empty or no element matches the callback, it will return the default value.
     *
     * The callback function should accept as parameters the key and the value of the array
     * and it should return true or false if the element matches the purpose of the filter.
     *
     * @param array         $array    The array to filter elements
     * @param callable|null $callback The filter as callback
     * @param mixed         $default  The default value in case no element is found
     * @param int|null      $length   The length of matched elements to return, as limit
     *
     * @return mixed
     */
    public static function filter(array $array, $callback = null, $default = [], $length = null)
    {
        // Set result array
        $result = [];

        // Check callback
        if (is_callable($callback)) {
            // Filter array elements
            foreach ($array as $key => $value) {
                if (call_user_func($callback, $key, $value)) {
                    $result[$key] = $value;
                }
            }
        } else {
            reset($array);

            $result = empty($array) ? null : $array;
        }

        // Return result array or default value
        return $result ? array_slice($result, 0, $length) : EvalHelper::evaluate($default);
    }

    /**
     * Perform a merge on the given two arrays.
     * Deep merge will merge the two arrays in full depth.
     *
     * @param array $array1
     * @param array $array2
     * @param bool  $deep
     *
     * @return array
     */
    public static function merge(array &$array1, array &$array2, $deep = false)
    {
        // Normal array merge
        if (!$deep) {
            return array_merge($array1, $array2);
        }

        // Perform a deep merge
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::merge($merged[$key], $value, $deep);
            } elseif (is_numeric($key)) {
                if (!in_array($value, $merged)) {
                    $merged[] = $value;
                }
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
