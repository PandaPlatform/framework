<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Panda\Support\Helpers;

/**
 * Class NumberHelper
 * @package Panda\Support\Helpers
 */
class NumberHelper
{
    /**
     * Calculate the floor of a number including decimals.
     * It has the same functionality as floor() but it can also include decimals.
     *
     * @param float $number    The float number to round.
     * @param int   $precision The number of decimals to include.
     *
     * @return float
     */
    public static function floor($number, $precision = 0)
    {
        $decimals = pow(10, $precision);

        return floor($number * $decimals) / $decimals;
    }

    /**
     * @param $array
     *
     * @return bool|float
     */
    public static function average($array)
    {
        if (!is_array($array) || empty($array)) {
            return false;
        }

        return array_sum($array) / count($array);
    }

    /**
     * Checks if of two numbers are equal.
     * Optional to set a required precision
     *
     * @param float $value1
     * @param float $value2
     * @param int   $roundPrecision
     *
     * @return bool
     */
    public static function isEqual($value1, $value2, $roundPrecision = 0)
    {
        $equal = false;

        if (round($value1, $roundPrecision) == round($value2, $roundPrecision)) {
            $equal = true;
        }

        return $equal;
    }
}
