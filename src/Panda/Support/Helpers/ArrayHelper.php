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
    public static function get($array, $key, $default = null, $useDotSyntax = false)
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
     * Filter array elements with a given callback function.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed         $default
     *
     * @return mixed
     */
    public static function filter(array $array, callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($array) ? EvalHelper::evaluate($default) : reset($array);
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                return $value;
            }
        }

        return EvalHelper::evaluate($default);
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

    /**
     * Return the first element in an array that matches the given filter.
     *
     * @param array         $array
     * @param callable|null $filter
     * @param mixed         $default
     *
     * @return mixed
     */
    public static function match($array, callable $filter = null, $default = null)
    {
        if (is_null($filter)) {
            if (empty($array)) {
                return $default;
            }
            foreach ($array as $item) {
                return $item;
            }
        }

        foreach ($array as $key => $value) {
            if (call_user_func($filter, $value, $key)) {
                return $value;
            }
        }

        return $default;
    }
}
