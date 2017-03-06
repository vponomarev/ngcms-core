<?php


// NGCMS :: Global ENGINE core container
class NGEngine {
    protected static $instance = false;
    protected static $items = array();

    /**
     * @return NGEngine Return instance of NGEngine singleton
     */
    static function getInstance() {
        if (NGEngine::$instance === false) {
            NGEngine::$instance = new static();
        }
        return NGEngine::$instance;
    }

    // Set item
    function set($e, $v) {
        NGEngine::$items[$e] = $v;
    }

    // Get item
    function get($e) {
        return NGEngine::$items[$e];
    }

    // Check if item is set
    function has($e) {
        return isset(NGEngine::$items[$e]);
    }

    // Get DB connection
    function getDB() {
        if (!isset(NGEngine::$items['db']))
            throw new Exception('NGEngine: getDB(): DB class is not initalized.');

        return NGEngine::$items['db'];
    }

    // Get Legacy DB connection
    function getLegacyDB() {
        if (!isset(NGEngine::$items['legacyDB']))
            throw new Exception('NGEngine: getLegacyDB(): DB class is not initalized.');

        return NGEngine::$items['legacyDB'];
    }

    // Get event handler
    function getEvents() {
        if (!isset(NGEngine::$items['events']))
            throw new Exception('NGEngine: getEvents(): Event handler is not loaded.');

        return NGEngine::$items['events'];
    }

    // Get error handler
    function getErrorHandler() {
        if (!isset(NGEngine::$items['errorHandler']))
            throw new Exception('NGEngine: getErrorHandler(): Error handler is not loaded.');

        return NGEngine::$items['errorHandler'];
    }

    // Get configurarion parameter
    function getConfigParam($param = null, $defaultValue = null) {
        if (isset(NGEngine::$items['config'])) {
            if (is_null($param))
                return NGEngine::$items['config'];

            if (isset(NGEngine::$items['config'][$param]))
                return NGEngine::$items['config'][$param];
        }
        return $defaultValue;
    }

    function hasCurrentUser() {
        return isset(NGEngine::$items['currentUser'])?true:false;
    }

    /**
     * @return NGUser Return instance of NGUser for current user
     */
    function getCurrentUser() {
        return isset(NGEngine::$items['currentUser'])?NGEngine::$items['currentUser']:null;
    }


    // protect against creating new instance of class or creating a clone
    private function __construct() { }
    private function __clone()     { }
}

