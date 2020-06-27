<?php

function DBLoad() {

	if (extension_loaded('mysqli')) {
		// ** Load MySQLi DB engine library
		@include_once root . 'includes/classes/database/mysqli.class.php';
		$mysql = new _mysqli;
	} else if (extension_loaded('mysql')) {
		// ** Load MySQL DB engine library
		@include_once root . 'includes/classes/database/mysql.class.php';
		$mysql = new mysql;
	}

	return $mysql;
}