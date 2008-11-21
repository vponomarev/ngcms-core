<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: plugin.php
// Description: Plugin personal pages exec manager
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Preload data
//

load_extras('ppages');

// call url: ?action=plugin&plugin=<name>&plugin_cmd=<cmd>&<other params>

$plugin_name	= $_REQUEST['plugin'];
$plugin_cmd	= $_REQUEST['plugin_cmd'];

$pcall = $PPAGES[$plugin_name][$plugin_cmd];

if (is_array($pcall) && function_exists($pcall['func'])) {
	// Make page title
	$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_plugin'];

	call_user_func($pcall['func']);
} else {
	msg(array('type' => 'error', 'text' => "Called method '$plugin_cmd' is not available for plugin '$plugin_name'"));
}
