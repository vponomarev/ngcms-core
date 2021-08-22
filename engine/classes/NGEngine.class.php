<?php

use Psr\Container\ContainerInterface;

/**
 * Global engine core container.
 */
final class NGEngine implements ContainerInterface
{
    /**
     * Singleton instance of NGEngine.
     *
     * @var NGEngine|null
     */
    private static $instance = null;

    /**
     * An array of the items that are already resolved.
     *
     * @var array
     */
    private static $items = [];

    /**
     * Gets the instance of NGEngine.
     *
     * @return NGEngine Return instance of NGEngine singleton.
     */
    public static function getInstance(): NGEngine
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Define an entry in the container.
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return void
     */
    public function set(string $id, $value): void
    {
        static::$items[$id] = $value;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        return static::$items[$id];
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset(static::$items[$id]);
    }

    /**
     * Get DB connection.
     *
     * @throws Exception Init error.
     *
     * @return NGPDO Current DB connection instance.
     */
    public function getDB(): NGPDO
    {
        if ($this->has('db')) {
            return $this->get('db');
        }

        throw new Exception('NGEngine: getDB(): DB class is not initalized.');
    }

    /**
     * Get Legacy DB connection.
     *
     * @throws Exception Init error.
     *
     * @return NGLegacyDB Legacy DB connection instance.
     */
    public function getLegacyDB(): NGLegacyDB
    {
        if ($this->has('legacyDB')) {
            return $this->get('legacyDB');
        }

        throw new Exception('NGEngine: getLegacyDB(): DB class is not initalized.');
    }

    /**
     * Get event handler.
     *
     * @throws Exception Init error.
     *
     * @return NGEvents Return instance of NGEvents.
     */
    public function getEvents(): NGEvents
    {
        if ($this->has('events')) {
            return $this->get('events');
        }

        throw new Exception('NGEngine: getEvents(): Event handler is not loaded.');
    }

    /**
     * Get error handler.
     *
     * @throws Exception Init error.
     *
     * @return NGErrorHandler Return instance of NGErrorHandler.
     */
    public function getErrorHandler(): NGErrorHandler
    {
        if ($this->has('errorHandler')) {
            return $this->get('errorHandler');
        }

        throw new Exception('NGEngine: getErrorHandler(): Error handler is not loaded.');
    }

    /**
     * Get configuration parameter.
     *
     * @param string $param
     * @param mixed  $defaultValue
     *
     * @throws Exception Init error.
     *
     * @return mixed
     */
    public function getConfigParam(string $param = null, $defaultValue = null)
    {
        if (!$this->has('config') || !is_array($config = $this->get('config'))) {
            throw new Exception('NGEngine: getConfigParam(): Configuration is not loaded.');
        }

        if (is_null($param)) {
            return $config;
        }

        if (isset($config[$param])) {
            return $config[$param];
        }

        return $defaultValue;
    }

    /**
     * Determine if the current user is defined.
     *
     * @return bool
     */
    public function hasCurrentUser(): bool
    {
        return $this->has('currentUser');
    }

    /**
     * Get the current user (if defined).
     *
     * @return NGUser|null Return instance of NGUser for current user.
     */
    public function getCurrentUser(): ?NGUser
    {
        return $this->hasCurrentUser()
            ? $this->get('currentUser')
            : null;
    }

    /**
     * Protect against creating new instance of class.
     */
    private function __construct()
    {
    }

    /**
     * Protect against creating a clone of class.
     */
    private function __clone()
    {
    }
}
