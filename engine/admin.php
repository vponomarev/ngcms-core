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

//if ( !in_array($config['default_lang'], array('russian', 'english'))) {
//	$config['default_lang'] = 'english';
//}
//
// Load skin language
//function LoadLang_Askin() {
//	global $config;
//
//	$filename = root . 'skins/default/lang/' . $config['default_lang'] . '/admin/index.ini';
//
//	if (!$content = parse_ini_file($filename, true)) {
//		$filename = root . 'skins/default/lang/english/admin/index.ini';
//		$content = parse_ini_file($filename, true);
//	}

//	return $content;
//}

//$lang = array_merge(LoadLang('index', 'admin'), LoadLang_Askin());
$lang = LoadLang('index', 'admin');
$skins_url = skins_url;

//////////////
LoadPluginLibrary('uprofile', 'lib');
$skin_UAvatar = ( isset($userROW['avatar']) and !empty($userROW['avatar']) and function_exists('userGetAvatar'))? userGetAvatar($userROW)[1] : $skins_url . '/assets/img/default-avatar.jpg';
$skin_UStatus = $UGROUP[$userROW['status']]['langName'][$config['default_lang']];
///////////////////

// switchTheme
if ( isset($_COOKIE['theme-style'] )
	and $_COOKIE['theme-style'] != 'default'
	and $_COOKIE['theme-style'] != 'undefined'
	and !empty($_COOKIE['theme-style']) ) {
	$themeStyle = $skins_url . '/assets/css/themes/' . $_COOKIE["theme-style"] . '.css';
} else {
	$themeStyle = $skins_url . '/assets/css/bootstrap.css';
}
$lang = LoadLang('index', 'admin');
if (is_array($userROW)) {
	$unnAppCount = '0';
	$newpm = '';
	$unapp1 = '';
	$unapp2 = '';

	$newpm = $mysql->result("SELECT count(pmid) FROM ".prefix."_users_pm WHERE to_id = ".db_squote($userROW['id'])." AND viewed = '0'");
	$newpmText = ($newpm != "0") ? $newpm . ' ' . Padeg($newpm, $lang['head_pm_skl']) : $lang['head_pm_no'];

	// Calculate number of un-approved news
	if ( $userROW['status'] == 1 || $userROW['status'] == 2 ) {
		$unapp1 = $mysql->result("SELECT count(id) FROM ".prefix."_news WHERE approve = '-1'");
		$unapp2 = $mysql->result("SELECT count(id) FROM ".prefix."_news WHERE approve = '0'");
		$unapp3 = $mysql->result("SELECT count(id) FROM ".prefix."_static WHERE approve = '0'");
		if ($unapp1)
			$unapproved1 = '<li><a href="'.$PHP_SELF.'?mod=news&status=1"><i class="fa fa-ban"></i> ' . $unapp1 . ' ' . Padeg($unapp1, $lang['head_news_draft_skl']) . '</a></li>';
		if ($unapp2)
			$unapproved2 = '<li><a href="'.$PHP_SELF.'?mod=news&status=2"><i class="fa fa-times"></i> ' . $unapp2 . ' ' . Padeg($unapp2, $lang['head_news_pending_skl']) . '</a></li>';
		if ($unapp3)
			$unapproved3 = '<li><a href="'.$PHP_SELF.'?mod=static"><i class="fa fa-times"></i> ' . $unapp3 . ' ' . Padeg($unapp3, $lang['head_stat_pending_skl']) . '</a></li>';
	}

	$unnAppCount = (int)$newpm + (int)$unapp1 + (int)$unapp2 + (int)$unapp3;
	$unnAppLabel = ($unnAppCount != "0" ) ? '<span class="label label-danger">' . $unnAppCount . '</span>' : '';
	$unnAppText = $lang['head_notify'] . (($unnAppCount != "0") ? $unnAppCount . ' ' . Padeg($unnAppCount, $lang['head_notify_skl']) : $lang['head_notify_no'] );
}

$mod = $_REQUEST['mod']? $_REQUEST['mod'] : 'statistics';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';


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
	'h_active_users'        => (($mod=='users')||($mod=='ipban')||($mod=='ugroup')||($mod=='perm'))?' class="active"':'',
	'h_active_content'      => ( $mod=='news' || $mod=='categories' || $mod=='static' || $mod=='images' || $mod=='files' )?' class="active"':'',
	'h_active_options'		=> (($mod=='')||($mod=='options')||($mod=='configuration')||($mod=='statistics')||($mod=='dbo')||($mod=='rewrite')||($mod=='cron'))?' class="active"':'',
	'h_active_extras'		=> (($mod == 'extra-config') || ($mod == 'extras')) ? ' class="active"' : '',
	'h_active_addnews'		=> (($mod == 'news') && ($action == 'add')) ? ' class="active"' : '',
	'h_active_editnews'		=> (($mod == 'news') && ($action != 'add')) ? ' class="active"' : '',
	'h_active_images'		=> ($mod == 'images') ? ' class="active"' : '',
	'h_active_files'		=> ($mod == 'files') ? ' class="active"' : '',
	'h_active_templates'    => ($mod=='templates')?' class="active"':'',
	'h_active_pm'			=> ($mod == 'pm') ? ' class="active"' : '',
	'year' 					=> date("Y"),
	'themeStyle'            => $themeStyle,
	'skin_UAvatar'          => $skin_UAvatar,
	'skin_UStatus'          => $skin_UStatus,
	'unapproved1'           => $unapproved1,
	'unapproved2'           => $unapproved2,
	'unapproved3'           => $unapproved3,
	'unnAppText'            => $unnAppText,
	'unnAppLabel'           => $unnAppLabel,
	'user' => array(
            'id' => $userROW['id'],
            'name' => $userROW['name'],
            'status' => $status,
            'avatar' => $userAvatar,
            'flags' => array(
                'hasAvatar' => $config['use_avatars'] and $userAvatar,
            ),
        ),
	'newpmText' => $newpmText,
);

if (!$mod || ($mod && $mod != "preview")) {
	$xt = $twig->loadTemplate(dirname(tpl_actions) . '/index.tpl');
	echo $xt->render($tVars);
}
if (defined('DEBUG')) {
	echo "SQL queries:<br />\n-------<br />\n " . implode("<br />\n", $mysql->query_list);
}

exec_acts("admin_footer");
