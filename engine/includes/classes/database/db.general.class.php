<?php

abstract class NGDB {
    // Implement connection to DB
    abstract function __construct($params);

    // Generate select request to DB [ ROWS are returned ]
    abstract function query($query, $params = array());

    // Generate SQL request and return SINGLE line [ ROWS are returned ]
    abstract function record($query, $params = array());

    // Generate SQL request and return SINGLE line
    abstract function exec($query, $params = array());

    // Generate SQL request and return FIRST variable from SINGLE line
    abstract function result($query, $params = array());

    // Return engine type
    abstract function getEngineType();

    // Return raw class of DB engine driver
    abstract function getDriver();

}