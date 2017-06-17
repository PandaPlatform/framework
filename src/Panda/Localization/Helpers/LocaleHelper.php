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
            throw new InvalidArgumentException('The given locale is empty');
        }

        // Check if locale is using - or _
        $locale = str_replace('-', '_', $locale);
        list($language, $country) = explode('_', $locale);

        // Do the same for fallback
        $fallbackLocale = str_replace('-', '_', $fallbackLocale);
        list($fallbackLanguage, $fallbackCountry) = explode('_', $fallbackLocale);

        // Create a priority fallback list to check for different locale.
        // This list includes also generic language codes separated from locale in case
        // there is generic language fallback.
        // If the base language for current and fallback locale is the same, then the language code
        // goes in the end as final fallback.
        $fallbackList = [];
        $fallbackList[] = $locale;
        if ($language != $fallbackLanguage || $locale == $fallbackLocale) {
            $fallbackList[] = $language;
        }
        if (!empty($fallbackLocale) && $locale != $fallbackLocale) {
            $fallbackList[] = $fallbackLocale;
            $fallbackList[] = $fallbackLanguage;
        }

        return $fallbackList;
    }
}
