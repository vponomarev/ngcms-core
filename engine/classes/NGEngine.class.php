<?php

// NGCMS :: Global ENGINE core container
class NGEngine
{
    protected static $instance = false;
    protected static $items = [];

    /**
     * @return NGEngine Return instance of NGEngine singleton
     */
    public static function getInstance(): NGEngine
    {
        if (static::$instance === false) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    // Set item
    public function set($e, $v)
    {
        static::$items[$e] = $v;
    }

    // Get item
    public function get($e)
    {
        return static::$items[$e];
    }

    // Check if item is set
    public function has($e)
    {
        return isset(static::$items[$e]);
    }

    // Get DB connection

    /**
     * @throws Exception
     *
     * @return NGPDO Current DB connection instance
     */
    public function getDB(): NGPDO
    {
        if (!isset(static::$items['db'])) {
            throw new Exception('NGEngine: getDB(): DB class is not initalized.');
        }

        return static::$items['db'];
    }

    // Get Legacy DB connection
    public function getLegacyDB(): NGLegacyDB
    {
        if (!isset(static::$items['legacyDB'])) {
            throw new Exception('NGEngine: getLegacyDB(): DB class is not initalized.');
        }

        return static::$items['legacyDB'];
    }

    // Get event handler

    /**
     * @throws Exception Init error
     *
     * @return NGEvents Return instance of NGEvents
     */
    public function getEvents(): NGEvents
    {
        if (!isset(static::$items['events'])) {
            throw new Exception('NGEngine: getEvents(): Event handler is not loaded.');
        }

        return static::$items['events'];
    }

    // Get error handler

    /**
     * @throws Exception Init error
     *
     * @return NGErrorHandler Return instance of NGErrorHandler
     */
    public function getErrorHandler(): NGErrorHandler
    {
        if (!isset(static::$items['errorHandler'])) {
            throw new Exception('NGEngine: getErrorHandler(): Error handler is not loaded.');
        }

        return static::$items['errorHandler'];
    }

    // Get configuration parameter
    public function getConfigParam($param = null, $defaultValue = null)
    {
        if (isset(static::$items['config'])) {
            if (is_null($param)) {
                return static::$items['config'];
            }

            if (isset(static::$items['config'][$param])) {
                return static::$items['config'][$param];
            }
        }

        return $defaultValue;
    }

    public function hasCurrentUser(): bool
    {
        return isset(static::$items['currentUser']);
    }

    /**
     * @return NGUser|null Return instance of NGUser for current user
     */
    public function getCurrentUser(): ?NGUser
    {
        return static::$items['currentUser'] ?? null;
    }

    // protect against creating new instance of class or creating a clone
    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
