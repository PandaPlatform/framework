<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Http;

use Panda\Contracts\Bootstrap\BootLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface Kernel
 * @package Panda\Contracts\Http
 */
interface Kernel extends BootLoader
{
    /**
     * Handle an incoming HTTP request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request);

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param Request  $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response);
}
