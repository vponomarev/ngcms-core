<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: configuration.rpc.php
// Description: RPC library for CONFIGURATION module
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Load library
@include_once root.'includes/classes/upload.class.php';
$lang = LoadLang('configuration', 'admin');


function admConfigurationTestDB($params) {
	global $mysql, $userROW, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

	if (!is_array($userROW)) {
		return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
	}

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
		return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
	}

	if (!is_array($params) || !isset($params['ip']) || !isset($params['port']) || !isset($params['dbpasswd']) || !isset($params['dbname'])) {
		return array('status' => 0, 'errorCode' => 2, 'errorText' => 'Wrong params type');
	}

	if ($params['token'] != genUToken('admin.configuration')) {
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Wrong security code');
	}

	// Check if DB connection params are correct
	$sqlTest = new mysql();
	if (!$sqlTest->connect($params['dbhost'], $params['dbuser'], $params['dbpasswd'], $params['dbname'], 1)) {
		if ($sqlTest->error<2)
			return array('status' => 0, 'errorCode' => 4, 'errorText' => iconv('Windows-1251','UTF-8', $lang['dbcheck_noconnect']));
		return array('status' => 0, 'errorCode' => 5, 'errorText' => iconv('Windows-1251','UTF-8', $lang['dbcheck_nodb']));
	};

	return (array('status' => 1, 'errorCode' => 0, 'errorText' => iconv('Windows-1251','UTF-8', $lang['dbcheck_ok'])));
}

function admConfigurationTestMemcached($params) {
	global $mysql, $userROW, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

	if (!is_array($userROW)) {
		return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
	}

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
		return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
	}

	if (!is_array($params) || !isset($params['ip']) || !isset($params['port']) || !isset($params['prefix'])) {
		return array('status' => 0, 'errorCode' => 2, 'errorText' => 'Wrong params type');
	}

	if ($params['token'] != genUToken('admin.configuration')) {
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Wrong security code');
	}

	// Check if DB connection params are correct
	if (!extension_loaded('memcached') || !class_exists('Memcached')) {
		return array('status' => 0, 'errorCode' => 4, 'errorText' => iconv('Windows-1251','UTF-8',$lang['memcached_noextension']));
	}

	// Connect to Memcached
	$cacheTest = new cacheClassMemcached(array('prefix' => $params['prefix']));
	$cacheTest->connect($params['ip'], $params['port']);

	// Try to set some value
	$testValue = uniq(time());
	$cacheTest->set('#core', 'connTester', $testValue);

	// Check result code
	if ($cacheTest->getResultCode() != 0) {
		return array('status' => 0, 'errorCode' => 5, 'errorText' => iconv('Windows-1251','UTF-8', 'Memcached error ['.$cacheTest->getResultCode().']: '.$cacheTest->getResultMessage()));
	}

	// Compare SET == GET values
	if ($cacheTest->get('#core', 'connTest') != $testValue) {
		// Some problems
		return array('status' => 0, 'errorCode' => 6, 'errorText' => 'Unexpected error - GET/SET values are not equal');
	}

	return (array('status' => 1, 'errorCode' => 0, 'errorText' => iconv('Windows-1251','UTF-8', $lang['memcached_ok'])));
}



if (function_exists('rpcRegisterAdminFunction')) {
	rpcRegisterAdminFunction('admin.configuration.dbCheck', 'admConfigurationTestDB');
	rpcRegisterAdminFunction('admin.configuration.memcachedCheck', 'admConfigurationTestMemcached');
}
