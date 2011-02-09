<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru)
// Name: rpc.php
// Description: Service functions controller
// Author: Vitaly Ponomarev
//

@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Add json_decode() support for PHP < 5.2.0
//
if (!function_exists('json_decode')) {
	function json_decode($json, $assoc = false) {
		include_once root.'includes/classes/json.php';
		$jclass = new Services_JSON($assoc?SERVICES_JSON_LOOSE_TYPE:0);
		return $jclass->decode($json);
	}
}

// Load additional handlers [ common ]
loadActionHandlers('rpc');
loadActionHandlers('rpc:'.(is_array($userROW)?'active':'inactive'));


// Function to preload ADMIN rpc funcs
function loadAdminRPC($mod) {
	if (in_array($mod, array('categories'))) {
		@include_once('./actions/'.$mod.'.rpc.php');
		return true;
	}
	return false;
}

// Register RPC ADMIN function
function rpcRegisterAdminFunction($name, $instance, $permanent = false) {
	global $RPCADMFUNC;
	$RPCADMFUNC[$name] = $instance;
}



//
// We support two types of RPC calls: HTTP/JSON-RPC and XML-RPC
//

if (isset($_REQUEST['json'])) {
	processJSON();
}




//
// HTTP/JSON-RPC processor
//
function processJSON(){
	global $RPCFUNC, $RPCADMFUNC;

	// Decode passed params
	$params = json_decode($_POST['params'], true);

	$methodName = (isset($_POST['methodName']))?$_POST['methodName']:(isset($_GET['methodName'])?$_GET['methodName']:'');
	switch ($methodName) {
		case 'admin.rewrite.submit':	$out = rpcRewriteSubmit($params); break;
		case 'core.users.search':	$out = rpcAdminUsersSearch($params); break;
		default:
			if (isset($RPCFUNC[$methodName])) {
				$out = call_user_func($RPCFUNC[$methodName], $params);
			} else if (preg_match('#^plugin\.(.+?)\.#', $methodName, $m) && loadPlugin($m[1], 'rpc') && isset($RPCFUNC[$methodName])) {
				// If method "plugin.NAME.something" is called, try to load action "rpc" for plugin "NAME"
				$out = call_user_func($RPCFUNC[$methodName], $params);
			} else if (preg_match('#^admin\.(.+?)\.#', $methodName, $m) && loadAdminRPC($m[1]) && isset($RPCADMFUNC[$methodName])) {
				// If method "plugin.NAME.something" is called, try to load action "rpc" for plugin "NAME"
				$out = call_user_func($RPCADMFUNC[$methodName], $params);
			} else {
				$out = rpcDefault($params); break;
			}
	}
	//print "<pre>JSON OUTPUT: ".json_encode($out)."</pre>";
	// Print output
	print json_encode($out);
}

//
//
//
function rpcDefault() {
	return array('status' => 0, 'errorCode' => 1, 'errorText' => 'No command specified');
}

//
// RPC function: rewrite.submit
// Description : Submit changes into REWRITE library
function rpcRewriteSubmit($params) {
	global $userROW;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] != 1)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'rewrite'), null, 'modify')) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'rewrite'), array('action' => 'modify'), null, array(0, 'SECURITY.PERM'));
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied (perm)');
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.rewrite'))) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'rewrite'), array('action' => 'modify'), null, array(0, 'SECURITY.TOKEN'));
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied (token)');
	}

	@include_once 'includes/classes/uhandler.class.php';
	$ULIB = new urlLibrary();
	$ULIB->loadConfig();

	$UHANDLER = new urlHandler();
	$UHANDLER->loadConfig();

	// Scan incoming params
	if (!is_array($params)) {
		return array('status' => 0, 'errorCode' => 999, 'errorText' => 'Wrong params type');
	}

	$hList = array();

	// Scan all params
	foreach ($params as $pID => $pData) {
		// Skip empty elements
		if ($pData == NULL)
			continue;

		$rcall = $UHANDLER->populateHandler($ULIB, $pData);
		if ($rcall[0][0]) {
			// Error
			return array('status' => 0, 'errorCode' => 4, 'errorText' => 'Parser error: '.$rcall[0][1], 'recID' => $pID);
		}
		$hList[] = $rcall[1];
	}

	// Now let's overwrite current config
	$UHANDLER->hList = array();
	foreach ($hList as $handler) {
		$UHANDLER->registerHandler(-1, $handler);
	}
	$UHANDLER->saveConfig();

	return array('status' => 1, 'errorCode' => 0, 'errorText' => var_export($rcall[1], true));
}


// Admin panel: search for users
function rpcAdminUsersSearch($params){
	global $userROW, $mysql;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] > 3)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	$searchName = iconv('UTF-8', 'Windows-1251', $params);

	// Check search mode
	// ! - show TOP users by posts
	if ($searchName == '!') {
		$SQL = 'select name, news from '.uprefix.'_users where news > 0 order by news desc limit 20';
	} else {
		// Return a list of users
		$SQL = 'select name, news from '.uprefix.'_users where name like '.db_squote('%'.$searchName.'%').' and news > 0 order by news desc limit 20';
	}

	// Scan incoming params
	$output = array();
	foreach ($mysql->select($SQL) as $row) {
		$output[] = array(iconv('Windows-1251', 'UTF-8',$row['name']), iconv('Windows-1251', 'UTF-8', $row['news'].' новостей'));
	}

	return array('status' => 1, 'errorCode' => 0, 'data' => array($params, $output));
}

