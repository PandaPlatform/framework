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
use Panda\Localization\Helpers\LocaleHelper;

/**
 * Class Translator
 * @package Panda\Localization
 */
class Translator
{
    /**
     * @var FileProcessor
     */
    protected $processor;

    /**
     * Translator constructor.
     *
     * @param FileProcessor $processor
     */
    public function __construct(FileProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Get a translation value.
     * If there is no value for the given locale, it tries to fallback to default locale.
     * If the default value is null and no translation is found, it throws Exception.
     *
     * @param string $key
     * @param string $package
     * @param string $locale
     * @param mixed  $default
     *
     * @return string
     *
     * @throws Exception
     */
    public function translate($key, $package = 'default', $locale = '', $default = null)
    {
        // Normalize locale to current if the given is empty
        $defaultLocale = Locale::getDefault();
        $locale = $locale ?: Locale::get();
        $locale = $locale ?: $defaultLocale;

        // Get locale fallback
        $translation = null;
        $fallbackList = LocaleHelper::getLocaleFallbackList($locale, $defaultLocale);
        foreach ($fallbackList as $locale) {
            $translation = ($translation ?: $this->processor->get($key, $locale, $package, null));
            if (!is_null($translation)) {
                break;
            }
        }

        // Check final translation
        if (is_null($default) && $translation === $default) {
            throw new Exception('The translation for [' . $package . ']->[' . $key . '] is not found in any locale [' . implode(', ', $fallbackList) . '].');
        }

        return $translation ?: $default;
    }

    /**
     * @param FileProcessor $processor
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }
}
