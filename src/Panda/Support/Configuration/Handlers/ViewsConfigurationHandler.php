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
 * Class ViewsConfigurationHandler
 * @package Panda\Support\Configuration
 */
class ViewsConfigurationHandler extends SharedConfigurationHandler
{
    /**
     * @param string $basePath
     *
     * @return string
     */
    public function getViewsPath($basePath = null)
    {
        $views = $this->get('paths.views');
        if (empty($views)) {
            // Fallback to default
            return $basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        }

        return $basePath . DIRECTORY_SEPARATOR . $views['base_dir'];
    }
}