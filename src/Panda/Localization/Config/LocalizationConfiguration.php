<?php

/*
 * This file is part of the Panda Localization Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Localization\Config;

use Panda\Config\SharedConfiguration;

/**
 * Class LocalizationConfiguration
 * @package Panda\Localization\Config
 */
class LocalizationConfiguration extends SharedConfiguration
{
    /**
     * @param string $basePath
     *
     * @return string
     */
    public function getTranslationsPath($basePath = null)
    {
        return $basePath . DIRECTORY_SEPARATOR . $this->get('localization.base_dir');
    }
}
