<?php

/*
 * This file is part of the Panda Contracts Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Contracts\Database;

/**
 * Interface DatabaseAdapter
 * @package Panda\Contracts\Database
 */
interface DatabaseAdapter
{
    /**
     * Begin a database transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function beginTransaction();

    /**
     * Commit the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function commitTransaction();

    /**
     * Rollback the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function rollbackTransaction();

    /**
     * Execute a simple query to the database.
     *
     * @param string $query
     * @param bool   $commit
     *
     * @return bool False on failure. For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will
     *              return a mysqli_result object. For other successful queries mysqli_query() will return TRUE.
     */
    public function query($query, $commit = true);

    /**
     * @param resource $resource
     * @param bool     $full
     *
     * @return array The fetched array with one or all the rows.
     */
    public function fetch($resource, $full = false);

    /**
     * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of
     * the connection.
     *
     * @param resource $resource
     *
     * @return string The escaped string.
     */
    public function escape($resource);
}
