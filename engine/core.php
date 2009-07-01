<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: core.php
// Description: core
// Author: NGCMS project team
//



//
// Global variables definition
//
global $EXTRA_CONFIG, $EXTRA_CONFIG_loaded, $EXTRA_ACTIVE, $EXTRA_ACTIVE_loaded, $EXTRA_ACTIVATED, $EXTRA_FILES_LOADED, $EXTRA_HTML_VARS, $EXTRA_CSS;
global $AUTH_METHOD, $AUTH_CAPABILITIES, $PPAGES, $PFILTERS, $SUPRESS_TEMPLATE_SHOW, $SUPRESS_MAINBLOCK_SHOW, $SYSTEM_FLAGS;
global $timer, $mysql, $ip, $parse, $tpl, $lang;

$EXTRA_CONFIG			= array();				// configuration params for extras
$EXTRA_CONFIG_loaded	= 0;					// flag: are config params loaded
$EXTRA_ACTIVE			= array();				// list of active modules (for each action)
$EXTRA_ACTIVE_loaded	= 0;					// flag is active module list is loaded
$EXTRA_ACTIVATED		= array();				// list of already loaded modules [ flag is set if one or more files for this plugin are loaded ]
$EXTRA_FILES_LOADED		= array();				// a list of files [parts of plugins] that are loaded
$EXTRA_HTML_VARS		= array();			// a list of added HTML vars in <head> block
$EXTRA_CSS				= array();

$AUTH_METHOD = array();
$AUTH_CAPABILITIES = array();

$PPAGES = array();			// plugin's pages
$PFILTERS = array();		// filtering plugins

$SUPRESS_TEMPLATE_SHOW	= 0;
$SUPRESS_MAINBLOCK_SHOW	= 0;

$SYSTEM_FLAGS = array();	// internal system global flags

// Configure error display mode
@error_reporting (E_ALL ^ E_NOTICE);
//@error_reporting (E_ALL);

define('root', dirname(__FILE__).'/');
define('site_root', dirname(dirname(__FILE__)).'/');

// Initialize time measurement functions
include_once root.'includes/classes/timer.class.php';
$timer = new microTimer;
$timer -> start();

@include_once root.'includes/inc/fix_magic_quotes.php';

@session_start();

// Define default HTTP flags
$SYSTEM_FLAGS['http.headers']['content-type']	= 'text/html; charset=Windows-1251';
$SYSTEM_FLAGS['http.headers']['cache-control']	= 'private';


//
// Global variables configuration arrays
//

// Configuration array
$confArray = array (
	// Pre-defined init values
	'predefined' => array(
		'HTTP_REFERER'	=> isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'',
		'PHP_SELF'		=> isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:'',
		'REQUEST_URI'	=> isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'',
		'config'	=> array(),
		'catz'		=> array(),
		'catmap'	=> array(),
		'is_logged'	=> false,
		'result'	=> false,
	),

	// HTTP GET/POST variables
	'htvars' => array('action', 'subaction', 'mod', 'id', 'pmid', 'category', 'altname', 'user', 'userid', 'code', 'vcode', 'story', 'rating', 'post_id', 'newsid', 'mail', 'name', 'url', 'regusername', 'regemail', 'regpassword', 'title', 'text', 'author', 'editsite', 'email', 'editicq', 'editlj', 'editfrom', 'editabout', 'icon', 'alt_url', 'orderby', 'contentshort', 'contentfull', 'alt_name', 'c_day', 'c_month', 'c_year', 'c_hour', 'c_minute', 'mainpage', 'allow_com', 'approve', 'favorite', 'pinned', 'description', 'keywords'),

	// HTTP GET/POST values that are integer ( intval() )
	'htvars.int' => array('start_from', 'news_per_page', 'per_page', 'cstart', 'page', 'year', 'month', 'day', 'raw', 'comid'),
);

// Load pre-defined variables
foreach ($confArray['predefined'] as $key => $value) {
	unset($_GET[$key], $_POST[$key], $_SESSION[$key], $_COOKIE[$key], $_ENV[$key]);
	$$key = $value;

	//print "INIT (".$key."): ".$$key."<br/>\n";
}

// Load HTTP GET/POST variables
foreach ($confArray['htvars'] as $vname) {
	$$vname	= isset($_REQUEST[$vname])?$_REQUEST[$vname]:'';
	//print "INIT.VAR (".$vname."): ".$$vname."<br/>\n";
}

// Load HTTP GET/POST integer variables
foreach ($confArray['htvars.int'] as $vname) {
	$$vname	= isset($_REQUEST[$vname])?intval($_REQUEST[$vname]):'';
	//print "INIT.VAR.INT (".$vname."): ".$$vname."<br/>\n";
}

//print "GLOBAL SET VARIABLE (id): ".$id."<br/>\n";

@include_once root.'includes/inc/multimaster.php';

multi_multisites();
@define('confroot',root.'conf/'.($multiDomainName && $multimaster && ($multiDomainName != $multimaster)?'multi/'.$multiDomainName.'/':''));
@include_once confroot.'config.php';

$timer->registerEvent('Config file is loaded');

// Call multidomains processor
multi_multidomains();

@include_once root.'includes/inc/consts.inc.php';
@include_once root.'includes/inc/functions.inc.php';
@include_once root.'includes/inc/extras.inc.php';
@include_once confroot.'links.inc.php';

@include_once 'includes/classes/templates.class.php';
@include_once 'includes/classes/parse.class.php';

// Activate URL processing engine
@include_once 'includes/classes/uhandler.class.php';
$UHANDLER = new urlHandler();
$UHANDLER->loadConfig();

// Current handler that was `catched` by engine
$CurrentHandler = array();

$timer->registerEvent('Core files are included');

$ip		=	checkIP();
$parse	=	new parse;
$tpl	=	new tpl;
$lang	=	array();

if ( ( !file_exists(confroot.'config.php') ) || ( filesize(confroot.'config.php')<10 ) ) {
	@header("Location: ".adminDirName."/install.php");
	echo "You should run install script first";
	exit;
}

@include_once root.'includes/classes/mysql.class.php';
$mysql = new mysql;
$mysql->connect($config['dbhost'], $config['dbuser'], $config['dbpasswd'], $config['dbname']);
$timer->registerEvent('DB connection established');

foreach ($mysql->select("select * from `".prefix."_category` order by posorder asc", 1) as $row) {
	$catz[$row['alt']] = $row;
	$catmap[$row['id']] = $row['alt'];
}

$timer->registerEvent('DB category list is loaded');

//
// Make authentication
//
$timer->registerEvent('Ready to load auth plugins');
load_extras('auth');
$timer->registerEvent('Auth plugins are loaded');

// Set prefix for users DB
if (!isset($config['uprefix'])) { $config['uprefix'] = $config['prefix']; }
@define('uprefix',$config['uprefix']);

//
// Authentication process

// System protection
if (!$AUTH_CAPABILITIES[$config['auth_module']]['login']) { $config['auth_module'] = 'basic'; }
if (!$AUTH_CAPABILITIES[$config['auth_db']]['db']) { $config['auth_db'] = 'basic'; }

if ( (is_object($AUTH_METHOD[$config['auth_module']])) && (is_object($AUTH_METHOD[$config['auth_db']])) ) {
	// Auth subsystem is activated
	// * choose default or user defined auth module
	if (isset($_REQUEST['auth_module']) && $AUTH_CAPABILITIES[$_REQUEST['auth_module']]['login'] && is_object($AUTH_METHOD[$_REQUEST['auth_module']])) {
		$auth = &$AUTH_METHOD[$_REQUEST['auth_module']];
	} else {
		$auth = &$AUTH_METHOD[$config['auth_module']];
	}
	$auth_db = &$AUTH_METHOD[$config['auth_db']];

	$xrow = $auth_db->check_auth();
	$CURRENT_USER = $xrow;

	if ($xrow['name']) {
		$is_logged_cookie	= true;
		$is_logged			= true;
		$username			= $xrow['name'];
		$userROW			= $xrow;
	}
} else {
	echo "Fatal error: No auth module is found.<br />To fix problem please run <i>upgrade.php</i> script<br /><br />\n";
}

$timer->registerEvent('Auth procedure is finished');

if ($is_logged) { @define('name', $userROW['name']); }

// Load extras for action 'all'
load_extras('all');
$timer->registerEvent('ALL core-related plugins are loaded');

// Exec extras for core module
exec_acts('core');
$timer->registerEvent('ALL core-related plugins are executed');



// Define last consts
@define('tpl_site', site_root.'templates/'.$config['theme'].'/');
@define('tpl_url', home.'/templates/'.$config['theme']);

// Lang files are loaded _after_ executing core scripts. This is done for switcher plugin
$lang	=	LoadLang('common');

$langShortMonths = explode(",", $lang['short_months']);
$langMonths = explode(",", $lang['months']);

$f		=	$langShortMonths;
$f2		=	array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$f3		=	array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$r		=	explode(",", $lang['months']);

$timer->registerEvent('* CORE.PHP is complete');
