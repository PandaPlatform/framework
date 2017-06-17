<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Storage;

/**
 * Interface StorageInterface
 * @package Panda\Contracts\Storage
 */
interface StorageInterface
{
    /**
     * Get a file's contents.
     *
     * @param string $path
     *
     * @return string
     */
    public function get($path);

    /**
     * Put contents to a given file.
     * Create the file if doesn't exist.
     *
     * @param string $path
     * @param string $contents
     * @param bool   $lock
     *
     * @return int
     */
    public function put($path, $contents, $lock = false);

    /**
     * Check if the given path is an existing file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function exists($path);

    /**
     * Delete the given file path.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path);

    /**
     * Move a file to a new location.
     *
     * @param string $path
     * @param string $target
     *
     * @return bool
     */
    public function move($path, $target);

    /**
     * Copy a file to a new location.
     *
     * @param string $path
     * @param string $target
     *
     * @return bool
     */
    public function copy($path, $target);
}
