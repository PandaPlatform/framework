<?php

/*
 * This file is part of the Panda Localization Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Localization\Helpers;

use InvalidArgumentException;

/**
 * Class LocaleHelper
 * @package Panda\Localization\Helpers
 */
class LocaleHelper
{
    /**
     * Get a locale fallback list to check while translating.
     *
     * @param string $locale
     * @param string $fallbackLocale
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public static function getLocaleFallbackList($locale, $fallbackLocale = '')
    {
        // Check arguments
        if (empty($locale)) {
            throw new InvalidArgumentException(__METHOD__ . ': The given locale is empty');
        }

        // Create a priority fallback list to check for different locale.
        // This list includes also generic language codes separated from locale in case
        // there is generic language fallback.
        // If the base language for current and fallback locale is the same, then the language code
        // goes in the end as final fallback.
        $fallbackList = [];

        // Get fallback for normal locale
        $fallbackList = array_merge($fallbackList, self::getFallbackList($locale));

        // Add fallback locale, if different
        if ($fallbackLocale != $locale) {
            $fallbackList = array_merge($fallbackList, self::getFallbackList($fallbackLocale));
        }

        return $fallbackList;
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    private static function getFallbackList($locale)
    {
        $fallbackList = [];

        // Add different locale variations
        $fallbackList[] = strtolower(str_replace('_', '-', $locale));
        $fallbackList[] = str_replace('_', '-', $locale);
        $fallbackList[] = strtolower(str_replace('-', '_', $locale));
        $fallbackList[] = str_replace('-', '_', $locale);

        // Add plain language (without country)
        $fallbackList[] = explode('_', str_replace('-', '_', $locale))[0];

        // Remove unique and reset index
        $fallbackList = array_values(array_unique($fallbackList));

        return $fallbackList;
    }
}
