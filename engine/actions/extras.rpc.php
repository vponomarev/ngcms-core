<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: extras.rpc.php
// Description: Externally available library for EXTRAS manipulation
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


header("Content-Type: text/html; charset=utf-8");
setlocale(LC_ALL, 'ru_RU.UTF-8');

// Load library
@include_once root.'includes/classes/upload.class.php';
$lang = LoadLang('extras', 'admin');

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: show list of categories
// ///////////////////////////////////////////////////////////////////////////

function admExtrasGetConfig($params) {
	global $userROW, $mysql, $PLUGINS;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'extras'), null, 'modify')) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] != 1)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	// Check for security token
	if ($params['token'] != genUToken('admin.extras')) {
		return array('status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code');
	}

	plugins_load_config();

	//$confLine = arrayCharsetConvert(0, $PLUGINS['config']);
	$confLine = json_encode(arrayCharsetConvert(0, $PLUGINS['config']));
	//$confLine = preg_replace('#\\(\\u....)#', '$1', $confLine);
	$confLine = jsonFormatter($confLine);

	return (array('status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'content' => $confLine));
	//return (array('status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'content' => arrayCharsetConvert(0, $PLUGINS['config'])));

}


if (function_exists('rpcRegisterAdminFunction')) {
	rpcRegisterAdminFunction('admin.extras.getPluginConfig', 'admExtrasGetConfig');
}
