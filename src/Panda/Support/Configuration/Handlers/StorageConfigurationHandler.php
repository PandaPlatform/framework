<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Configuration;

/**
 * Class StorageConfigurationHandler
 * @package Panda\Support\Configuration
 */
class StorageConfigurationHandler extends SharedConfigurationHandler
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

        return ($basePath ? $basePath . DIRECTORY_SEPARATOR : '') . $storage['base_dir'];
    }
}
