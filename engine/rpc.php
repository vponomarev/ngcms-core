<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: robotic.php
// Description: Service functions controller
// Author: Vitaly Ponomarev
//

@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


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
	// Decode passed params
	$params = json_decode($_POST['params'], true);

	switch ($_POST['methodName']) {
		case 'rewrite.submit':	$out = rpcRewriteSubmit($params); break;
		default:				$out = rpcDefault(); break;
	}

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
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Wrong params type');
	}

	@include_once 'includes/classes/uhandler.class.php';
	$ULIB = new urlLibrary();
	$ULIB->loadConfig();

	$UHANDLER = new urlHandler();
	$UHANDLER->loadConfig();

	// Scan incoming params
	if (!is_array($params)) {
		return array('status' => 0, 'errorCode' => 999, 'errorText' => 'Access denied');
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


