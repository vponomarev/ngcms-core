<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: activation.php
// Description: activates registered users
// Author: NGCMS project team
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_activation'];

$lang = LoadLang('activation', 'site');

$userid = $_REQUEST['userid'];
$code	= $_REQUEST['code'];

// All checks are done via mysql
if (is_array($urow = $mysql->record("select * from ".prefix."_users where id=".db_squote($userid)." and activation =".db_squote($code)))) {
	$mysql->query("update `".uprefix."_users` set activation = '' where id = ".db_squote($userid));
	msg(array("text" => $lang['msgo_activated'], "info" => sprintf($lang['msgi_activated'], admin_url)));
} else {
	msg(array("type" => "error", "text" => $lang['msge_activation'], "info" => $lang['msgi_activation']));
}

