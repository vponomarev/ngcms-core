<?php

class NGUser implements ArrayAccess
{
    protected $keys = null;

    public function __construct($k = null)
    {
        $this->keys = $k;
    }

    public function setKeys($k)
    {
        $this->keys = $k;
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function get($k, $defaultValue = null)
    {
        return (!is_null($this->keys) && is_array($this->keys) && array_key_exists($k, $this->keys)) ? $this->keys[$k] : $defaultValue;
    }

    public function set($k, $v)
    {
        if (!is_null($this->keys) && is_array($this->keys)) {
            $this->keys[$k] = $v;
        }
    }

    public function __get($k)
    {
        return $this->get($k);
    }

    public function __set($k, $v)
    {
        $this->set($k, $v);
    }

    public function isAdmin()
    {
        return ($this->get('status', 0) == 1) ? true : false;
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
