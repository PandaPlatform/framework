<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Bootstrap;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface BootLoader
 * @package Panda\Contracts\Bootstrap
 */
interface BootLoader
{
    /**
     * Boot the BootLoader.
     *
     * @param Request $request
     *
     * @throws InvalidArgumentException
     */
    public function boot($request);
}
