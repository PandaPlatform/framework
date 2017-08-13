<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Storage\Filesystem;

use Panda\Contracts\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Class Filesystem
 * @package Panda\Storage\Filesystem
 */
class Filesystem implements StorageInterface
{
    /**
     * @var string
     */
    protected $storageDirectory;

    /**
     * Get a file's contents.
     *
     * @param string $path
     *
     * @throws FileNotFoundException
     *
     * @return string
     */
    public function get($path)
    {
        // Check if it's file
        if ($this->isFile($path)) {
            // Get storage path
            $path = $this->storagePath($path);

            return file_get_contents($path);
        }

        // Throw exception if file not found
        throw new FileNotFoundException($path);
    }

    /**
     * Put contents to a given file. Create the file if doesn't exist.
     *
     * @param string $path     The file's path to put contents to.
     * @param string $contents The file's contents.
     * @param bool   $lock     Acquire an exclusive lock on the file while proceeding to the writing.
     *
     * @return mixed The number of bytes that were written to the file, or False on failure.
     */
    public function put($path, $contents, $lock = false)
    {
        // Get storage path
        $path = $this->storagePath($path);

        // Check if folder exists
        if (!$this->exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Check if the given path is an existing file.
     *
     * @param string $path The file's path to check.
     *
     * @return bool True if file exists, false otherwise.
     */
    public function exists($path)
    {
        // Get storage path
        $path = $this->storagePath($path);

        return file_exists($path);
    }

    /**
     * Delete the given file path.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        // Get storage path
        $path = $this->storagePath($path);

        return unlink($path);
    }

    /**
     * Move a file to a new location.
     *
     * @param string $path   The current file path.
     * @param string $target The new file path.
     *
     * @return bool True on success, false on failure.
     */
    public function move($path, $target)
    {
        // Get storage path
        $path = $this->storagePath($path);
        $target = $this->storagePath($target);

        return rename($path, $target);
    }

    /**
     * Copy a file to a new location.
     *
     * @param string $path   The current file path.
     * @param string $target The copy file path.
     *
     * @return bool True on success, false on failure.
     */
    public function copy($path, $target)
    {
        // Get storage path
        $path = $this->storagePath($path);
        $target = $this->storagePath($target);

        return copy($path, $target);
    }

    /**
     * Checks if the given path is a file.
     *
     * @param string $path
     *
     * @return bool Returns True if the filename exists and is a regular file, False otherwise.
     */
    public function isFile($path)
    {
        // Get storage path
        $path = $this->storagePath($path);

        return is_file($path);
    }

    /**
     * Append to a file.
     *
     * @param string $path     The file path.
     * @param string $contents The contents to be appended.
     *
     * @return mixed The number of bytes that were written to the file, or False on failure.
     */
    public function append($path, $contents)
    {
        // Get storage path
        $path = $this->storagePath($path);

        return file_put_contents($path, $contents, FILE_APPEND);
    }

    /**
     * Prepend to a file.
     *
     * @param string $path     The file path.
     * @param string $contents The contents to be prepended.
     *
     * @return mixed The number of bytes that were written to the file, or False on failure.
     * @throws FileNotFoundException
     */
    public function prepend($path, $contents)
    {
        // Get storage path
        $path = $this->storagePath($path);

        if ($this->exists($path)) {
            return $this->put($path, $contents . $this->get($path));
        }

        return $this->put($path, $contents);
    }

    /**
     * Get the storage full file path.
     *
     * @param string $path
     *
     * @return string
     */
    public function storagePath($path)
    {
        return $this->getStorageDirectory() . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @return string
     */
    public function getStorageDirectory()
    {
        return $this->storageDirectory;
    }

    /**
     * Set the storage base directory.
     *
     * @param string $directory
     *
     * @return $this
     */
    public function setStorageDirectory($directory)
    {
        $this->storageDirectory = $directory;

        return $this;
    }
}
