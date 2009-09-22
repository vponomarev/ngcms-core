<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: admin.php
// Description: administration panel
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");

//define('DEBUG', 1);

if (defined('DEBUG')) {
	print "HTTP CALL PARAMS: <pre>"; var_dump($_REQUEST); print "</pre><br>\n";
}

$PHP_SELF = "admin.php";

// We have only one admin panel skin
@require_once("./skins/default/index.php");

//
// Handle LOGIN
//
if ($_REQUEST['action'] == 'login') {
	include_once root.'cmodules.php';
	coreLogin();
}

//
// Handle LOGOUT
//
if ($_REQUEST['action'] == 'logout') {
	include_once root.'cmodules.php';
	coreLogout();
}


//
// Show LOGIN screen if user is not logged in
//
if (!is_array($userROW)) {
	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'redirect'		=>	$REQUEST_URI,
		'home_title'	=>	home_title,
		'error'			=>	($SYSTEM_FLAGS['auth_fail'])?$lang['msge_login']:'',
	);

	$tpl -> template('login', tpl_actions);
	$tpl -> vars('login', $tvars);
	echo $tpl -> show('login');
	exit;
}


// Not all of users are welcome here
if ($userROW['status'] > 3) {
	@header("Location: ".home);
	exit;
}



//
// Only admins can reach this location
//

define('ADMIN', 1);

// Administrative panel filters
$AFILTERS = array();

// Load library
require_once('./includes/inc/lib_admin.php');

// Load plugins, that need to make any changes during user in admin panel
load_extras('admin:init');

// Configure user's permissions (access to modules, depends on user's status)
$permissions = array(
	'configuration'	=>	'1',
	'dbo'			=>	'1',
	'extras'		=>	'1',
	'extra-config'	=>	'1',
	'statistics'	=>	'1',
	'templates'		=>	'1',
	'users'			=>	'1',
	'rewrite'		=>	'1',
	'static'		=>	'1',

	'categories'	=>	'2',
	'editcomments'	=>	'2',
	'ipban'			=>	'2',
	'options'		=>	'2',

	'addnews'		=>	'3',
	'editnews'		=>	'3',
	'files'			=>	'3',
	'images'		=>	'3',
	'pm'			=>	'3',
	'preview'		=>	'3',
);

exec_acts("admin_header");

// Print skin header (if we're not in preview mode)
if ($mod != 'preview') {
	echo $skin_header;
}

// Default action
if (!$mod) {
	$mod = ($userROW['status'] == 1)?'statistics':'editnews';
}

// Check requested module exists
if (isset($permissions[$mod]) && ($permissions[$mod])) {
	$level = $permissions[$mod];

	// If user's status fits - call module. Else - show an error
	if ($userROW['status'] <= $level) {
		// Load plugins, that need to make any changes in this mod
		load_extras('admin:mod:'.$mod);
		require("./actions/".$mod.".php");
	} else {
		msg(array("type" => "error", "text" => $lang['msge_mod']));
	}
}

// Print skin footer (if we're not in preview mode)
if ( !$mod || ($mod && $mod != "preview") ) { echo $skin_footer; }

if (defined('DEBUG')) {
	echo "SQL queries:<br />\n-------<br />\n ".implode("<br />\n",$mysql->query_list);
}

exec_acts("admin_footer");
