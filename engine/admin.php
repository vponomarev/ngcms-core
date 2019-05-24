<?php

//
// Copyright (C) 2006-2016 Next Generation CMS (http://ngcms.ru)
// Name: admin.php
// Description: administration panel
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Administrative panel filters
$AFILTERS = array();

// Load core
@header('content-type: text/html; charset=utf-8');
@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");

// Pre-configure required global variables
global $action, $subaction, $mod;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';

// Activate output buffer
ob_start();

//define('DEBUG', 1);

if (defined('DEBUG')) {
	print "HTTP CALL PARAMS: <pre>";
	var_dump(array('GET' => $_GET, 'POST' => $_POST, 'COOKIE' => $_COOKIE));
	print "</pre><br>\n";
	print "SERVER PARAMS: <pre>";
	var_dump($_SERVER);
	print "</pre><br>\n";
}

$PHP_SELF = "admin.php";

// We have only one admin panel skin
//@require_once("./skins/default/index.php");

//
// Handle LOGIN
//
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'login')) {
	include_once root . 'cmodules.php';
	coreLogin();
}

//
// Handle LOGOUT
//
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout')) {
	include_once root . 'cmodules.php';
	coreLogout();
}

//
// Show LOGIN screen if user is not logged in
//
if (!is_array($userROW)) {
	$tVars = array(
		'php_self'		=> $PHP_SELF,
		'redirect'		=> $REQUEST_URI,
		'year'			=> date("Y"),
		'home_title'	=> home_title,
		'error'			=> ($SYSTEM_FLAGS['auth_fail']) ? $lang['msge_login'] : '',
		'is_error'		=> ($SYSTEM_FLAGS['auth_fail']) ? '$1' : '',
	);
	
	$xt = $twig->loadTemplate(tpl_actions . 'login.tpl');
	echo $xt->render($tVars);
	exit;
}

// Check if visitor has permissions to view admin panel
if (!checkPermission(array('plugin' => '#admin', 'item' => 'system'), null, 'admpanel.view')) {
	ngSYSLOG(array('plugin' => '#admin', 'item' => 'system'), array('action' => 'admpanel.view'), null, array(0, 'SECURITY.PERM'));
	@header("Location: " . home);
	exit;
}

//
// Only admins can reach this location
//

define('ADMIN', 1);

// Load library
require_once('./includes/inc/lib_admin.php');

// Load plugins, that need to make any changes during user in admin panel
load_extras('admin:init');

// Configure user's permissions (access to modules, depends on user's status)
$permissions = array(
	'perm'          => '1',
	'ugroup'        => 1,
	'configuration' => 99,
	'cron'          => 99,
	'dbo'           => 99,
	'extras'        => '1',
	'extra-config'  => '1',
	'statistics'    => '1',
	'templates'     => 99,
	'users'         => 99,
	'rewrite'       => '1',
	'static'        => '1',

	'editcomments' => '2',
	'ipban'        => 99,
	'options'      => '2',

	'categories' => 99,
	'news'       => 99,

	'files'   => '3',
	'images'  => '3',
	'pm'      => '3',
	'preview' => '3',
);

exec_acts("admin_header");

// Default action
if (!$mod) {
	$mod = ($userROW['status'] == 1) ? 'statistics' : 'news';
}

// Check requested module exists
if (isset($permissions[$mod]) && ($permissions[$mod])) {
	$level = $permissions[$mod];

	// If user's status fits - call module. Else - show an error
	if ($userROW['status'] <= $level) {
		// Load plugins, that need to make any changes in this mod
		load_extras('admin:mod:' . $mod);
		require("./actions/" . $mod . ".php");
	} else {
		$notify = msg(array("type" => "error", "text" => $lang['msge_mod']));
	}
} else {
	$notify = msg(array("type" => "error", "text" => $lang['msge_mod']));
}

$lang = LoadLang('index', 'admin');
if (is_array($userROW)) {
	$newpm = $mysql->result("SELECT count(pmid) FROM " . prefix . "_users_pm WHERE to_id = " . db_squote($userROW['id']) . " AND viewed = '0'");
	$newpm = ($newpm != "0") ? '<b>' . $newpm . '</b>' : '0';
	// Calculate number of un-approved news
	$unapproved = '';
	if ($userROW['status'] == 1 || $userROW['status'] == 2) {
		$unapp = $mysql->result("SELECT count(id) FROM " . prefix . "_news WHERE approve = '0'");
		if ($unapp)
			$unapproved = ' [ <a href="?mod=news&amp;status=2"><font color="red"><b>' . $unapp . '</b></font></a> ] ';
	}
}

$datetimepicker_lang_default = "
$.datepicker.setDefaults($.datepicker.regional['" . $lang['langcode'] . "']);
$.timepicker.setDefaults($.timepicker.regional['" . $lang['langcode'] . "']);
";
$datetimepicker_lang = ($lang['langcode'] == 'ru') ? $datetimepicker_lang_default : "";

$tVars = array(
	'php_self'				=> $PHP_SELF,
	'home_title'			=> $config['home_title'],
	'newpm'					=> $newpm,
	'unapproved'			=> $unapproved,
	'main_admin'			=> $main_admin,
	'notify'				=> $notify,
	'datetimepicker_lang'	=> $datetimepicker_lang,
	'h_active_options'		=> (in_array($mod, array('options', 'categories', 'static'))) ? ' class="active"' : '',
	'h_active_extras'		=> (($mod == 'extra-config') || ($mod == 'extras')) ? ' class="active"' : '',
	'h_active_addnews'		=> (($mod == 'news') && ($action == 'add')) ? ' class="active"' : '',
	'h_active_editnews'		=> (($mod == 'news') && ($action != 'add')) ? ' class="active"' : '',
	'h_active_images'		=> ($mod == 'images') ? ' class="active"' : '',
	'h_active_files'		=> ($mod == 'files') ? ' class="active"' : '',
	'h_active_pm'			=> ($mod == 'pm') ? ' class="active"' : '',
	'year' 					=> date("Y"),
);

if (!$mod || ($mod && $mod != "preview")) {
	$xt = $twig->loadTemplate(dirname(tpl_actions) . '/index.tpl');
	echo $xt->render($tVars);
}
if (defined('DEBUG')) {
	echo "SQL queries:<br />\n-------<br />\n " . implode("<br />\n", $mysql->query_list);
}

exec_acts("admin_footer");