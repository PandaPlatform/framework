<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class Response
 * @package Panda\Http
 */
class Response extends SymfonyResponse
{
    /**
     * Create a redirect response.
     *
     * @param string $url
     * @param int    $status
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function redirect($url = '', $status = 302)
    {
        // Set headers
        $this->setStatusCode($status);
        $this->headers->set('Location', $url);

        return $this;
    }
}
