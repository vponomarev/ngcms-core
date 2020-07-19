<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: cache.class.php
// Description: Cache manager
// Author: Vitaly Ponomarev
//

class cacheClassAbstract
{
    public function get($plugin, $key, $expire = -1)
    {
        return false;
    }

    public function set($plugin, $key, $value, $expire = -1)
    {
        return false;
    }

    public function del($plugin, $key)
    {
        return false;
    }

    public function getMulti($plugin, $keyList, $expire = -1)
    {
        return false;
    }

    public function setMulti($plugin, $dataList, $expire = -1)
    {
        return false;
    }

    public function increment($plugin, $key, $offset = 1)
    {
        return false;
    }

    public function decrement($plugin, $key, $offset = 1)
    {
        return false;
    }
}

class cacheClassFile extends cacheClassAbstract
{
    public function get($plugin, $key, $expire = -1)
    {

        // Default expiration time = 120 sec
        if ($expire < 0) {
            $expire = 120;
        }

        // Try to get cache directory name. Return false if it's not possible
        if (!($dir = get_plugcache_dir($plugin))) {
            return false;
        }

        // Try to open file with data
        if (($fn = @fopen($dir.$fname, 'r')) == false) {
            return false;
        }

        // Check if file is expired. Return if it's so.
        $stat = fstat($fn);
        if (!is_array($stat) || ($stat[9] + $expire < time())) {
            return false;
        }

        // Try to make shared file lock. Return if failed
        if (@flock($fn, LOCK_SH) == false) {
            fclose($fn);

            return false;
        }

        // Return if file is empty
        if ($stat[7] < 1) {
            fclose($fn);

            return false;
        }

        // Read data from file
        $data = fread($fn, $stat[7]);

        // Unlock and close file
        flock($fn, LOCK_UN);
        fclose($fn);

        // Return data
        return $data;
    }

    public function getMulti($plugin, $keyList, $expire = -1)
    {
        $res = [];
        foreach ($keyList as $key) {
            $res[$key] = $this->get($plugin, $key, $expire);
        }

        return $res;
    }

    public function set($plugin, $key, $value, $expire = -1)
    {

        // Default expiration time = 120 sec
        if ($expire < 0) {
            $expire = 120;
        }

        // Try to get cache directory name. Return false if it's not possible
        if (!($dir = get_plugcache_dir($plugin))) {
            return false;
        }

        // Try to create file
        if (($fn = @fopen($dir.$fname, 'w')) == false) {
            return false;
        }

        // Try to make exclusive file lock. Return if failed
        if (@flock($fn, LOCK_EX) == false) {
            fclose($fn);

            return false;
        }

        // Write into file
        if (@fwrite($fn, $data) == -1) {
            // Failed.
            flock($fn, LOCK_UN);
            fclose($fn);

            return false;
        }

        flock($fn, LOCK_UN);
        fclose($fn);

        return true;
    }

    public function setMulti($plugin, $keyList, $expire = -1)
    {
        $res = [];
        foreach ($keyList as $key) {
            $res[$key] = $this->set($plugin, $key, $expire);
        }

        return $res;
    }
}

class cacheClassMemcached extends cacheClassAbstract
{
    public $cache;
    public $params;

    public function __construct($params = [])
    {
        $this->cache = new Memcached();

        if (!is_array($params)) {
            $params = [];
        }

        if (!isset($params['prefix'])) {
            $params['prefix'] = 'ng';
        }

        if (!isset($params['expiration'])) {
            $params['expiration'] = 60;
        }

        $this->params = $params;
    }

    public function connect($host, $port)
    {
        return $this->cache->addServer($host, $port);
    }

    public function get($plugin, $key, $expire = -1)
    {
        return $this->cache->get($this->params['prefix'].':'.$plugin.':'.$key);
    }

    public function getMulti($plugin, $keyList, $expire = -1)
    {
        $keyResult = [];
        if (!is_array($keyList)) {
            return false;
        }

        foreach ($keyList as $k) {
            $keyResult[] = $this->params['prefix'].':'.$plugin.':'.$k;
        }

        return $this->cache->getMulti($keyResult);
    }

    public function set($plugin, $key, $value, $expiration = -1)
    {
        return $this->cache->set($this->params['prefix'].':'.$plugin.':'.$key, $value, ($expiration >= 0) ? $expiration : $this->params['expiration']);
    }

    public function setMulti($plugin, $keyList, $expiration = 0)
    {
        $keyResult = [];
        if (!is_array($keyList)) {
            return false;
        }

        foreach ($keyList as $k => $v) {
            $keyResult[$this->params['prefix'].':'.$plugin.':'.$k] = $v;
        }

        return $this->cache->setMulti($keyResult, ($expiration >= 0) ? $expiration : $this->params['expiration']);
    }

    public function getResultCode()
    {
        return $this->cache->getResultCode();
    }

    public function getResultMessage()
    {
        return $this->cache->getResultMessage();
    }

    public function getResult()
    {
        return [$this->cache->getResultCode(), $this->cache->getResultMessage()];
    }

    public function touch($plugin, $key, $expiration)
    {
        return $this->cache->touch($this->params['prefix'].':'.$plugin.':'.$key, $value, $expiration);
    }

    public function increment($plugin, $key, $offset = 1)
    {
        return $this->cache->increment($this->params['prefix'].':'.$plugin.':'.$key, $offset);
    }

    public function decrement($plugin, $key, $offset = 1)
    {
        return $this->cache->decrement($this->params['prefix'].':'.$plugin.':'.$key, $offset);
    }

    public function del($plugin, $key)
    {
        return $this->cache->del($this->params['prefix'].':'.$plugin.':'.$key);
    }
}
