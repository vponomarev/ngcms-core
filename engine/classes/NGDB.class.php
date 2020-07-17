<?php

abstract class NGDB
{
    // Implement connection to DB
    abstract public function __construct($params);

    // Generate select request to DB [ ROWS are returned ]
    abstract public function query($query, $params = []);

    // Generate SQL request and return SINGLE line [ ROWS are returned ]
    abstract public function record($query, $params = []);

    // Generate SQL request and return SINGLE line
    abstract public function exec($query, $params = []);

    // Generate SQL request and return FIRST variable from SINGLE line
    abstract public function result($query, $params = []);

    // Quote string
    abstract public function quote($string);

    // Return engine type
    abstract public function getEngineType();

    // Return raw class of DB engine driver
    abstract public function getDriver();

    // Return version
    abstract public function getVersion();

    abstract public function createCursor($query, array $params = []);

    abstract public function fetchCursor($cursor);

    abstract public function closeCursor($cursor);

    abstract public function tableExists($name);
}
