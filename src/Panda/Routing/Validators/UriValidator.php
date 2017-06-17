<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Routing\Validators;

use Panda\Http\Request;
use Panda\Routing\Route;

/**
 * Class UriValidator
 * @package Panda\Routing\Validators
 */
class UriValidator implements ValidatorInterface
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param Route   $route
     * @param Request $request
     *
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        $path = $request->getPath() == '/' ? '/' : '/' . $request->getPath();

        return preg_match($route->getCompiled()->getRegex(), rawurldecode($path));
    }
}
