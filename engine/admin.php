<?php

//
// Copyright (C) 2006-2016 Next Generation CMS (http://ngcms.ru)
// Name: admin.php
// Description: administration panel
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Administrative panel filters
$AFILTERS = [];

// Load core
@header('content-type: text/html; charset=utf-8');
@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');

// Pre-configure required global variables
global $action, $subaction, $mod;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';

// Activate output buffer
ob_start();

//define('DEBUG', 1);

if (defined('DEBUG')) {
    echo 'HTTP CALL PARAMS: <pre>';
    var_dump(['GET' => $_GET, 'POST' => $_POST, 'COOKIE' => $_COOKIE]);
    echo "</pre><br>\n";
    echo 'SERVER PARAMS: <pre>';
    var_dump($_SERVER);
    echo "</pre><br>\n";
}

$PHP_SELF = 'admin.php';

// We have only one admin panel skin
//@require_once("./skins/default/index.php");

//
// Handle LOGIN
//
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'login')) {
    include_once root.'cmodules.php';
    coreLogin();
}

//
// Handle LOGOUT
//
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout')) {
    include_once root.'cmodules.php';
    coreLogout();
}

//
// Show LOGIN screen if user is not logged in
//
if (!is_array($userROW)) {
    $tVars = [
        'php_self'   => $PHP_SELF,
        'redirect'   => $REQUEST_URI,
        'year'       => date('Y'),
        'home_title' => home_title,
        'error'      => ($SYSTEM_FLAGS['auth_fail']) ? $lang['msge_login'] : '',
        'is_error'   => ($SYSTEM_FLAGS['auth_fail']) ? '$1' : '',
    ];

    $xt = $twig->loadTemplate(tpl_actions.'login.tpl');
    echo $xt->render($tVars);
    exit;
}

// Check if visitor has permissions to view admin panel
if (!checkPermission(['plugin' => '#admin', 'item' => 'system'], null, 'admpanel.view')) {
    ngSYSLOG(['plugin' => '#admin', 'item' => 'system'], ['action' => 'admpanel.view'], null, [0, 'SECURITY.PERM']);
    @header('Location: '.home);
    exit;
}

//
// Only admins can reach this location
//

define('ADMIN', 1);

// Load library
require_once './includes/inc/lib_admin.php';

// Check if DB upgrade is required
if (dbCheckUpgradeRequired()) {
    echo "<html><body><div>Error: DB Upgrade is required! Please upgrade DB before proceed.<br/><a href='upgrade.php'>Upgrade now!</a></div></body></html>";

    return;
}

// Load plugins, that need to make any changes during user in admin panel
load_extras('admin:init');

// Configure user's permissions (access to modules, depends on user's status)
$permissions = [
    'perm'          => checkPermission(['plugin' => '#admin', 'item' => 'perm'], null, 'details'),
    'ugroup'        => checkPermission(['plugin' => '#admin', 'item' => 'ugroup'], null, 'details'),
    'configuration' => checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'details'),
    'cron'          => checkPermission(['plugin' => '#admin', 'item' => 'cron'], null, 'details'),
    'dbo'           => checkPermission(['plugin' => '#admin', 'item' => 'dbo'], null, 'details'),
    'extras'        => checkPermission(['plugin' => '#admin', 'item' => 'extras'], null, 'details'),
    'extra-config'  => checkPermission(['plugin' => '#admin', 'item' => 'extras-config'], null, 'details'),
    'docs'          => checkPermission(['plugin' => '#admin', 'item' => 'docs'], null, 'details'),
    'statistics'    => checkPermission(['plugin' => '#admin', 'item' => 'statistics'], null, 'details'),
    'templates'     => checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'details'),
    'users'         => checkPermission(['plugin' => '#admin', 'item' => 'users'], null, 'view'),
    'rewrite'       => checkPermission(['plugin' => '#admin', 'item' => 'rewrite'], null, 'details'),
    'static'        => checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'details'),
    'editcomments'  => checkPermission(['plugin' => '#admin', 'item' => 'editcomments'], null, 'details'),
    'ipban'         => checkPermission(['plugin' => '#admin', 'item' => 'ipban'], null, 'view'),
    'categories'    => checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'view'),
    'news'          => checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'view'),
    'files'         => checkPermission(['plugin' => '#admin', 'item' => 'files'], null, 'details'),
    'images'        => checkPermission(['plugin' => '#admin', 'item' => 'images'], null, 'details'),
    'pm'            => checkPermission(['plugin' => '#admin', 'item' => 'pm'], null, 'details'),
    'preview'       => checkPermission(['plugin' => '#admin', 'item' => 'preview'], null, 'details'),
];

exec_acts('admin_header');

// Default action
if (!$mod) {
    $mod = $permissions['statistics'] ? 'statistics' : 'news';
}

// Check requested module exists
if (isset($permissions[$mod]) && $permissions[$mod]) {
    // Load plugins, that need to make any changes in this mod
    load_extras('admin:mod:'.$mod);
    require './actions/'.$mod.'.php';
} else {
    $notify = msg(['type' => 'error', 'text' => $lang['msge_mod']]);
}

$lang = LoadLang('index', 'admin');
if (is_array($userROW)) {
    $unnAppCount = '0';
    $newpm = '';
    $unapp1 = '';
    $unapp2 = '';

    $newpm = $mysql->result('SELECT count(pmid) FROM '.prefix.'_users_pm WHERE to_id = '.db_squote($userROW['id']).' AND viewed = "0"');
    $newpmText = ($newpm != '0') ? $newpm.' '.Padeg($newpm, $lang['head_pm_skl']) : $lang['head_pm_no'];

    // Calculate number of un-approved news
    if ($userROW['status'] == 1 || $userROW['status'] == 2) {
        $unapp1 = $mysql->result('SELECT count(id) FROM '.prefix."_news WHERE approve = '-1'");
        $unapp2 = $mysql->result('SELECT count(id) FROM '.prefix."_news WHERE approve = '0'");
        $unapp3 = $mysql->result('SELECT count(id) FROM '.prefix."_static WHERE approve = '0'");
        if ($unapp1) {
            $unapproved1 = '<a class="dropdown-item" href="'.$PHP_SELF.'?mod=news&status=1"><i class="fa fa-ban"></i> '.$unapp1.' '.Padeg($unapp1, $lang['head_news_draft_skl']).'</a>';
		}
        if ($unapp2) {
            $unapproved2 = '<a class="dropdown-item" href="'.$PHP_SELF.'?mod=news&status=2"><i class="fa fa-times"></i> '.$unapp2.' '.Padeg($unapp2, $lang['head_news_pending_skl']).'</a>';
		}
        if ($unapp3) {
            $unapproved3 = '<a class="dropdown-item" href="'.$PHP_SELF.'?mod=static"><i class="fa fa-times"></i> '.$unapp3.' '.Padeg($unapp3, $lang['head_stat_pending_skl']).'</a>';
        }
	}

    $unnAppCount = (int) $newpm + (int) $unapp1 + (int) $unapp2 + (int) $unapp3;
    $unnAppLabel = ($unnAppCount != '0') ? '<span class="label label-danger">'.$unnAppCount.'</span>' : '';
    $unnAppText = $lang['head_notify'].(($unnAppCount != '0') ? $unnAppCount.' '.Padeg($unnAppCount, $lang['head_notify_skl']) : $lang['head_notify_no']);
}

$datetimepicker_lang_default = "
$.datepicker.setDefaults($.datepicker.regional['".$lang['langcode']."']);
$.timepicker.setDefaults($.timepicker.regional['".$lang['langcode']."']);
";
$datetimepicker_lang = ($lang['langcode'] == 'ru') ? $datetimepicker_lang_default : '';

$tVars = [
    'php_self'            => $PHP_SELF,
    'home_title'          => $config['home_title'],
    'newpm'               => $newpm,
    'unapproved'          => $unapproved,
    'main_admin'          => $main_admin,
    'notify'              => $notify,
    'datetimepicker_lang' => $datetimepicker_lang,
    'h_active_options'    => (in_array($mod, ['options', 'categories', 'static', 'news', 'images', 'files'])) ? ' class="active"' : '',
    'h_active_system'     => (in_array($mod, ['configuration', 'dbo', 'rewrite', 'cron', 'statistics'])) ? ' class="active"' : '',
    'h_active_userman'    => (in_array($mod, ['users', 'ipban', 'ugroup', 'perm'])) ? ' class="active"' : '',
    'h_active_templates'  => (in_array($mod, ['templates'])) ? ' class="active"' : '',
    'h_active_extras'     => (in_array($mod, ['extras'])) ? ' class="active"' : '',
    'h_active_pm'         => ($mod == 'pm') ? ' class="active"' : '',
    'year'                => date('Y'),
    'unapproved1'         => $unapproved1,
    'unapproved2'         => $unapproved2,
    'unapproved3'         => $unapproved3,
    'unnAppText'          => $unnAppText,
    'unnAppLabel'         => $unnAppLabel,
    'newpmText'           => $newpmText,
    'perm'                => [
        'static'        => checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'view'),
        'categories'    => checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'view'),
        'addnews'       => checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'add'),
        'editnews'      => (checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'personal.list') || checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'other.list')),
        'configuration' => checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'details'),
        'dbo'           => checkPermission(['plugin' => '#admin', 'item' => 'dbo'], null, 'details'),
        'cron'          => checkPermission(['plugin' => '#admin', 'item' => 'cron'], null, 'details'),
        'rewrite'       => checkPermission(['plugin' => '#admin', 'item' => 'rewrite'], null, 'details'),
        'templates'     => checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'details'),
        'ipban'         => checkPermission(['plugin' => '#admin', 'item' => 'ipban'], null, 'view'),
        'users'         => checkPermission(['plugin' => '#admin', 'item' => 'users'], null, 'view'),
    ],
];

// Register global vars
$twigGlobal['action'] = $action;
$twigGlobal['subaction'] = $subaction;
$twigGlobal['mod'] = $mod;

if (!$mod || ($mod && $mod != 'preview')) {
    $xt = $twig->loadTemplate(dirname(tpl_actions).'/index.tpl');
    echo $xt->render($tVars);
}
if (defined('DEBUG')) {
    echo "SQL queries:<br />\n-------<br />\n ".implode("<br />\n", $mysql->query_list);
}

exec_acts('admin_footer');
