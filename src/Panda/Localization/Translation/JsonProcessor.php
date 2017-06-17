<?php

/*
 * This file is part of the Panda Localization Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Localization\Translation;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class JsonProcessor
 * @package Panda\Localization\Translation
 */
class JsonProcessor extends AbstractProcessor implements FileProcessor
{
    /**
     * Load translations from file.
     *
     * @param string $locale
     * @param string $package
     *
     * @throws FileNotFoundException
     */
    public function loadTranslations($locale, $package = 'default')
    {
        $package = $package ?: 'default';
        if (empty(static::$translations[$locale])) {
            // Get full file path
            $fileName = $locale . DIRECTORY_SEPARATOR . $package . '.json';
            $filePath = $this->getBaseDirectory() . DIRECTORY_SEPARATOR . $fileName;

            // Check if is valid and load translations
            if (is_file($filePath)) {
                $fileContents = file_get_contents($filePath);
                static::$translations[$locale] = json_decode($fileContents, true);
            } else {
                throw new FileNotFoundException($fileName);
            }
        }
    }
}
