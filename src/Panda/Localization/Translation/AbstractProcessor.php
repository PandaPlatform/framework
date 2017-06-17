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

use Exception;
use Panda\Support\Helpers\ArrayHelper;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class AbstractProcessor
 * @package Panda\Localization\Translation
 */
abstract class AbstractProcessor implements FileProcessor
{
    /**
     * @var array
     */
    protected static $translations;

    /**
     * @var string
     */
    protected $baseDirectory;

    /**
     * Load translations
     *
     * @param string $locale
     * @param string $package
     *
     * @throws FileNotFoundException
     */
    abstract public function loadTranslations($locale, $package = 'default');

    /**
     * JsonProcessor constructor.
     *
     * @param $baseDirectory
     */
    public function __construct($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * Get a translation value.
     * If the default value is null and no translation is found, it throws Exception.
     *
     * @param string $key
     * @param string $locale
     * @param string $package
     * @param mixed  $default
     *
     * @return mixed
     * @throws FileNotFoundException
     */
    public function get($key, $locale, $package = 'default', $default = null)
    {
        // Check key
        if (empty($key)) {
            return $default;
        }

        // Normalize group and load translations
        $package = ($package ?: 'default');
        try {
            $this->loadTranslations($locale, $package);

            // Return translation
            $array = (static::$translations[$locale] ?: []);

            $value = ArrayHelper::get($array, $key, $default, true);
        } catch (Exception $ex) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Get the base directory for the literals
     *
     * @return string
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * @param string $baseDirectory
     */
    public function setBaseDirectory(string $baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }
}
