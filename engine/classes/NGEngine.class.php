<?php


// NGCMS :: Global ENGINE core container
class NGEngine
{
    protected static $instance = false;
    protected static $items = array();

    /**
     * @return NGEngine Return instance of NGEngine singleton
     */
    static function getInstance()
    {
        if (static::$instance === false) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    // Set item
    function set($e, $v)
    {
        static::$items[$e] = $v;
    }

    // Get item
    function get($e)
    {
        return static::$items[$e];
    }

    // Check if item is set
    function has($e)
    {
        return isset(static::$items[$e]);
    }

    // Get DB connection
    /**
     * @return NGDB Current DB connection instance
     * @throws Exception
     */
    function getDB()
    {
        if (!isset(static::$items['db'])) {
            throw new Exception('NGEngine: getDB(): DB class is not initalized.');
        }

        return static::$items['db'];
    }

    // Get Legacy DB connection
    function getLegacyDB()
    {
        if (!isset(static::$items['legacyDB'])) {
            throw new Exception('NGEngine: getLegacyDB(): DB class is not initalized.');
        }

        return static::$items['legacyDB'];
    }

    // Get event handler
    /**
     * @return NGEvents Return instance of NGEvents
     * @throws Exception Init error
     */
    function getEvents()
    {
        if (!isset(static::$items['events'])) {
            throw new Exception('NGEngine: getEvents(): Event handler is not loaded.');
        }

        return static::$items['events'];
    }

    // Get error handler
    /**
     * @return NGErrorHandler Return instance of NGErrorHandler
     * @throws Exception Init error
     */
    function getErrorHandler()
    {
        if (!isset(static::$items['errorHandler'])) {
            throw new Exception('NGEngine: getErrorHandler(): Error handler is not loaded.');
        }

        return static::$items['errorHandler'];
    }

    // Get configuration parameter
    function getConfigParam($param = null, $defaultValue = null)
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

    function hasCurrentUser()
    {
        return isset(static::$items['currentUser'])?true:false;
    }

    /**
     * @return NGUser Return instance of NGUser for current user
     */
    function getCurrentUser()
    {
        return isset(static::$items['currentUser'])?static::$items['currentUser']:null;
    }


    // protect against creating new instance of class or creating a clone
    private function __construct()
    {
    }
    private function __clone()
    {
    }
}
