<?php

/*
 * This file is part of the Panda framework.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Session;

/**
 * File SessionHandler
 *
 * @package Panda\Session
 * @version 0.1
 */
class SessionHandler
{
    /**
     * The session's expiration time (in seconds).
     *
     * @var int
     */
    const EXPIRE = 18000;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * Init session.
     */
    public function startSession()
    {
        // Start session
        $this->start();

        // Initialise the session timers
        $this->setTimers();

        // Validate this session
        $this->validate();
    }

    /**
     * Get a session variable value.
     *
     * @param string $name      The name of the variable.
     * @param string $default   The value that will be returned if the variable doesn't exist.
     * @param string $namespace The namespace of the session variable.
     *
     * @return string
     */
    public function get($name, $default = null, $namespace = 'default')
    {
        // Get SESSION Namespace
        $namespace = $this->getNS($namespace);

        if (isset($_SESSION[$namespace][$name])) {
            return $_SESSION[$namespace][$name];
        }

        return $default;
    }

    /**
     * Set a session variable value.
     *
     * @param string $name      The name of the variable.
     * @param string $value     The value with which the variable will be set.
     * @param string $namespace The namespace of the session variable.
     *
     * @return mixed The old value of the variable, or NULL if not set.
     */
    public function set($name, $value = null, $namespace = 'default')
    {
        // Get SESSION Namespace
        $namespace = $this->getNS($namespace);

        $old = (isset($_SESSION[$namespace][$name]) ? $_SESSION[$namespace][$name] : null);

        if (is_null($value)) {
            unset($_SESSION[$namespace][$name]);
        } else {
            $_SESSION[$namespace][$name] = $value;
        }

        return $old;
    }

    /**
     * Check if there is a session variable
     *
     * @param string $name      The variable name.
     * @param string $namespace The namespace of the session variable.
     *
     * @return bool True if the variable is set, false otherwise.
     */
    public function has($name, $namespace = 'default')
    {
        // Get SESSION Namespace
        $namespace = $this->getNS($namespace);

        return isset($_SESSION[$namespace][$name]);
    }

    /**
     * Removes a session variable.
     *
     * @param string $name      The variable name.
     * @param string $namespace The namespace of the session variable.
     *
     * @return mixed The old value of the variable, or NULL if not set.
     */
    public function remove($name, $namespace = 'default')
    {
        // Get SESSION Namespace
        $namespace = $this->getNS($namespace);

        $value = null;
        if (isset($_SESSION[$namespace][$name])) {
            $value = $_SESSION[$namespace][$name];
            unset($_SESSION[$namespace][$name]);
        }

        return $value;
    }

    /**
     * Delete a set of session variables under the same namespace
     *
     * @param string $namespace The namespace to be cleared.
     *
     * @return bool True on success, false on failure.
     */
    public function clearSet($namespace)
    {
        // Get SESSION Namespace
        $namespace = $this->getNS($namespace);

        unset($_SESSION[$namespace]);

        return true;
    }

    /**
     * Get session name.
     *
     * @return string The session name.
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * Get session id
     *
     * @return int The session unique id.
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Destroy current session.
     */
    public function destroy()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Return the in-memory size of the session ($_SESSION) array.
     *
     * @return int The memory size in length.
     */
    public function getSize()
    {
        return strlen(serialize($_SESSION));
    }

    /**
     * Set the validation timers.
     *
     * @param bool $forceRegenerate Forces the timers to regenerate (in case of an expiration or something).
     */
    private function setTimers($forceRegenerate = false)
    {
        $start = time();

        // If there is no starting point, restart all over again
        if (!$this->has('timer.start', 'session') || $forceRegenerate) {
            $this->set('timer.start', $start, 'session');
            $this->set('timer.last', $start, 'session');
            $this->set('timer.now', $start, 'session');
        }

        // Set current timers
        $this->set('timer.last', $this->get('timer.now', null, 'session'), 'session');
        $this->set('timer.now', time(), 'session');
    }

    /**
     * Start the session.
     */
    private function start()
    {
        // Build environment
        register_shutdown_function('session_write_close');
        session_cache_limiter('none');
        ini_set('session.gc_maxlifetime', (string)self::EXPIRE);

        // Set Session cookie params
        $sessionCookieParams = session_get_cookie_params();
        session_set_cookie_params(
            $sessionCookieParams['lifetime'],
            $sessionCookieParams['path'],
            $sessionCookieParams['domain'],
            $sessionCookieParams['secure'],
            $sessionCookieParams['httponly']
        );

        // Start session
        session_start();
    }

    /**
     * Validate the session and reset if necessary.
     */
    protected function validate()
    {
        // Regenerate session if gone too long and reset timers
        if ((time() - $this->get('timer.start', null, 'session') > self::EXPIRE)) {
            session_regenerate_id(true);
            $this->setTimers(true);
        }

        // Destroy session if expired
        if ((time() - $this->get('timer.last', null, 'session') > self::EXPIRE)) {
            $this->destroy();
        }
    }

    /**
     * Create the namespace string.
     *
     * @param string $namespace The namespace of the session variable.
     *
     * @return string The namespace string value.
     */
    private function getNS($namespace)
    {
        // Add prefix to namespace to avoid collisions.
        return '__' . strtoupper($namespace);
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param mixed $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }
}
