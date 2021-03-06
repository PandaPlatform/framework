<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Facades;

use Panda\Routing\Router;

/**
 * Class Route
 *
 * Facade methods:
 *
 * @method static Route get($uri, $action = null)
 * @method static Route post($uri, $action = null)
 * @method static Route put($uri, $action = null)
 * @method static Route patch($uri, $action = null)
 * @method static Route delete($uri, $action = null)
 * @method static Route options($uri, $action = null)
 * @method static Route all($uri, $action = null)
 * @method static Route any($methods, $uri, $action = null)
 *
 * @package Panda\Support\Facades
 */
class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeHandler()
    {
        return Router::class;
    }
}
