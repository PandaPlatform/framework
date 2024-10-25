<?php

/*
 * This file is part of the Panda Panda Session Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Session;

use Panda\Config\SharedConfiguration;

/**
 * Class Session
 *
 * @package Panda\Session
 */
class Session
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
    private $sessionId = null;
    /**
     * @var SharedConfiguration
     */
    private $configuration;

    /**
     * @param SharedConfiguration $configuration
     */
    public function __construct(SharedConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Init session.
     */
    public function init()
    {
        // Check if session is active
        if (!$this->configuration->get('session.active', true)) {
            return;
        }

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
     * @return string|null
     */
    public function get($name, $default = null, $namespace = null)
    {
        // Get SESSION Namespace
        $namespace = $this->getNamespace($namespace);

        // Get session container
        $container = $_SESSION;
        if (!empty($namespace)) {
            if (!isset($_SESSION[$namespace])) {
                $_SESSION[$namespace] = [];
            }
            $container = $_SESSION[$namespace];
        }

        // Check and get session container value
        if (isset($container[$name])) {
            return $container[$name];
        }

        return $default;
    }

    /**
     * Set a session variable value.
     *
     * @param string $name      The name of the variable.
     * @param string $value     The value with which the variable will be set. Set to null to unset variable.
     * @param string $namespace The namespace of the session variable.
     *
     * @return mixed The old value of the variable, or null if not set.
     */
    public function set($name, $value = null, $namespace = null)
    {
        // Get the old value of the variable
        $old = $this->get($name, $namespace);

        // Get SESSION Namespace
        $namespace = $this->getNamespace($namespace);

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
    public function has($name, $namespace = null)
    {
        return !is_null($this->get($name, null, $namespace));
    }

    /**
     * Removes a session variable.
     *
     * @param string $name      The variable name.
     * @param string $namespace The namespace of the session variable.
     *
     * @return mixed The old value of the variable, or NULL if not set.
     */
    public function remove($name, $namespace = null)
    {
        // Get previous value
        $value = $this->get($name, null, $namespace);

        // Set value
        $this->set($name, null, $namespace);

        // Return old value
        return $value;
    }

    /**
     * Delete a set of session variables under the same namespace
     *
     * @param string $namespace The namespace to be cleared.
     */
    public function clearNamespace($namespace)
    {
        // Get SESSION Namespace
        $namespace = $this->getNamespace($namespace);

        unset($_SESSION[$namespace]);
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

        session_id($this->getSessionId());

        // Start session
        session_start();
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
     * Validate the session and reset if necessary.
     */
    protected function validate()
    {
        // Regenerate session if gone too long and reset timers
        if ((time() - $this->get('timer.start', null, 'session') > self::EXPIRE)) {
            session_regenerate_id(true);
            $this->setTimers(true);
        }

        // Destroy session if expired and start a new one
        if ((time() - $this->get('timer.last', null, 'session') > self::EXPIRE)) {
            $this->destroy();
            $this->start();
        }
    }

    /**
     * Create the namespace string.
     *
     * @param string $namespace The namespace of the session variable.
     *
     * @return string The namespace string value.
     */
    private function getNamespace($namespace)
    {
        // Add prefix to namespace to avoid collisions.
        return '__' . trim(strtoupper($namespace ?: 'default'), '_');
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
        // Get current session id
        $currentSessionId = session_id();

        // Check if session has started
        if (empty($currentSessionId)) {
            $this->sessionId = $sessionId;
        }
    }
}
