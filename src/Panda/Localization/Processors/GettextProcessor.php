<?php

/*
 * This file is part of the Panda Localization Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Localization\Processors;

/**
 * Class GettextProcessor
 * @package Panda\Localization\Processors
 */
class GettextProcessor extends AbstractProcessor
{
    /**
     * Get a translation value.
     *
     * @param string $key
     * @param string $locale
     * @param string $package
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $locale, $package = 'default', $default = null)
    {
        return gettext($key);
    }

    /**
     * Load translations
     *
     * @param string $locale
     * @param string $package
     */
    public function loadTranslations($locale, $package = 'default')
    {
        // TODO: Implement loadTranslations() method.
    }
}
