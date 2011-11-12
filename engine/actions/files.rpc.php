<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: files.rpc.php
// Description: Externally available library for File/Image management
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Load library
@include_once root.'includes/classes/upload.class.php';

//
// Manage upload request from external uploadify script
function admRPCFilesUpload($params){
	global $mysql, $AUTH_METHOD, $config, $userROW;

	// Don't allow to do anything by guests
	if (!is_array($userROW)) {
		// Not authenticated, return.
		return array('status' => 0, 'errorCode' => 1, 'errorText' => '[RPC] You are not logged in');
	}

	// Now user is authenticated.
	$fmanager = new file_managment();

	// Check parameters:
	// - type: file / image
	$uploadType = $_POST['uploadType'];
	if (($uploadType != 'file') && ($uploadType != 'image')) {
		@header('HTTP/1.1 404 Wrong upload type');
		return;
	}

	$ures = $fmanager->file_upload(array(
		'rpc'		=> 1,
		'dsn'		=> 0,
		'category'	=> ($_REQUEST['category'] == '')?'default':$_REQUEST['category'],
		'type'		=> 'file',
		'replace'	=> $_REQUEST['replace'],
		'randprefix'=> $_REQUEST['rand'],
		'http_var'	=> 'Filedata',
	));

	return $ures;

	//return array('status' => 1, 'errorCode' => 1, 'errorText' => '[RPC] Under development');
	return array('status' => 0, 'errorCode' => 1, 'errorText' => '[RPC] Under development');




	//@header('HTTP/1.1 404 Not found');


}


if (function_exists('rpcRegisterAdminFunction')) {
	rpcRegisterAdminFunction('admin.files.upload', 'admRPCFilesUpload');
}
