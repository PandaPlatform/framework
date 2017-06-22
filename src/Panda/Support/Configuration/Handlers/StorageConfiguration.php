<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration\Handlers;

use Panda\Config\SharedConfiguration;

/**
 * Class StorageConfiguration
 * @package Panda\Support\Configuration\Handlers
 */
class StorageConfiguration extends SharedConfiguration
{
    /**
     * @param string $basePath
     *
     * @return string
     */
    public function getStorageBaseDirectory($basePath = null)
    {
        // Get base storage path configuration
        $storage = $this->get('paths.storage');

        // Check if storage is relative to the application path or not
        $relative = $storage['is_relative'] ?: true;

        return ($relative ? $basePath . DIRECTORY_SEPARATOR : '') . $storage['base_dir'];
    }
}
