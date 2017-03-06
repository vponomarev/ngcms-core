<?php


// NGCMS :: Global ENGINE core container
class NGEngine {
    protected static $instance = false;
    protected static $items = array();
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
        return NGEngine::$items['db'];
    }

    // Get Legacy DB connection
    function getLegacyDB() {
        return NGEngine::$items['legacyDB'];
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

    function getCurrentUser() {
        return isset(NGEngine::$items['currentUser'])?NGEngine::$items['currentUser']:null;
    }


    // protect against creating new instance of class or creating a clone
    private function __construct() { }
    private function __clone()     { }
}

