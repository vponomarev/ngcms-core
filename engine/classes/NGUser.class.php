<?php

class NGUser implements ArrayAccess
{
    protected $keys = null;

    function __construct($k = null)
    {
        $this->keys = $k;
    }

    function setKeys($k)
    {
        $this->keys = $k;
    }

    function getKeys()
    {
        return $this->keys;
    }

    function get($k, $defaultValue = null)
    {
        return (!is_null($this->keys) && is_array($this->keys) && array_key_exists($k, $this->keys))?$this->keys[$k]:$defaultValue;
    }

    function set($k, $v)
    {
        if (!is_null($this->keys) && is_array($this->keys)) {
            $this->keys[$k] = $v;
        }
    }

    function __get($k)
    {
        return $this->get($k);
    }

    function __set($k, $v)
    {
        $this->set($k, $v);
    }

    function isAdmin()
    {
        return ($this->get('status', 0) == 1)?true:false;
    }

    // ============================================
    // AccessArray implementation
    // ============================================
    public function offsetExists($offset)
    {
        return isset($this->keys[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        if (!is_null($this->keys) && is_array($this->keys) && isset($this->keys[$offset])) {
            unset($this->keys[$offset]);
        }
    }
}
