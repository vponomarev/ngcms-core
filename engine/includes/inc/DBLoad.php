<?php

function DBLoad(){
	global $lang;
	
	$lang =	LoadLang('common');
	if(extension_loaded('mysqli')){
		// ** Load MySQLi DB engine library
		@include_once root.'includes/classes/database/mysqli.class.php';
		$mysql = new _mysqli;
	} else if(extension_loaded('mysql')){
		// ** Load MySQL DB engine library
		@include_once root.'includes/classes/database/mysql.class.php';
		$mysql = new mysql;
	} else {
		print "<html>\n<head><title>FATAL EXECUTION ERROR</title></head>\n<body>\n<div style='font: 24px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>FATAL ERROR</span><br/><span style=\"font: 16px arial;\"> Cannot load file DBLoad libraries of <a href=\"http://ngcms.ru/\">NGCMS</a> (<b>engine/includes/inc/DBLoad.php</b>), PHP extension [mysql] or [mysqli] is not loaded!</span></div>\n</body>\n</html>\n";
		foreach (array('mysql' => 'mysql_connect', 'mysqli' => 'mysqli_connect') as $pModule => $pFunction){
			print str_replace(array('{extension}', '{function}'), array($pModule, $pFunction), $lang['fatal.lostlib']);
		}
		die();
	}
	return $mysql;
}