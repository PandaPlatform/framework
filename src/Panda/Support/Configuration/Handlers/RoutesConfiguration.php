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
 * Class RoutesConfiguration
 * @package Panda\Support\Configuration\Handlers
 */
class RoutesConfiguration extends SharedConfiguration
{
    /**
     * @param string $basePath
     *
     * @return string
     */
    public function getRoutesPath($basePath = null)
    {
        $routes = $this->get('paths.routes');
        if (empty($routes)) {
            // Fallback to default
            return $basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'main.php';
        }

        return $basePath . DIRECTORY_SEPARATOR . $routes['base_dir'] . DIRECTORY_SEPARATOR . $routes['base_file'];
    }
}
