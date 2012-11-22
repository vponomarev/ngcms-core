<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru)
// Name: core.php
// Description: core
// Author: NGCMS project team
//



//
// Global variables definition
//
global $PLUGINS, $EXTRA_HTML_VARS, $EXTRA_CSS;
global $AUTH_METHOD, $AUTH_CAPABILITIES, $PPAGES, $PFILTERS, $RPCFUNC, $TWIGFUNC, $RPCADMFUNC, $SUPRESS_TEMPLATE_SHOW, $SUPRESS_MAINBLOCK_SHOW, $SYSTEM_FLAGS, $DSlist, $PERM, $confPerm, $confPermUser, $systemAccessURL, $cron;
global $timer, $mysql, $ip, $parse, $tpl, $lang;
global $TemplateCache;

$PLUGINS	= array('active'	=> array(),
			'active:loaded'	=> 0,
			'loaded'	=> array(),
			'loaded:files'	=> array(),
			'config'	=> array(),
			'config:loaded'	=> 0,

);
$EXTRA_HTML_VARS		= array();			// a list of added HTML vars in <head> block
$EXTRA_CSS				= array();

$AUTH_METHOD = array();
$AUTH_CAPABILITIES = array();

$PPAGES = array();			// plugin's pages
$PFILTERS = array();		// filtering plugins
$RPCFUNC = array();			// RPC functions
$TWIGFUNC = array();		// TWIG defined functions
$RPCADMFUNC = array();		// RPC admin functions

$PERM = array();			// PERMISSIONS

$SUPRESS_TEMPLATE_SHOW	= 0;
$SUPRESS_MAINBLOCK_SHOW	= 0;

$SYSTEM_FLAGS = array();	// internal system global flags
$TemplateCache = array();

// List of DataSources
$DSlist = array(
	'news' 				=> 1,
	'categories'		=> 2,
	'comments'			=> 3,
	'users'				=> 4,
	'files'				=> 10,
	'images'			=> 11,
	'#xfields:tdata'	=> 51,
);

// Configure error display mode
@error_reporting (E_ALL ^ E_NOTICE);
//@error_reporting (E_ALL);
/*
ini_set("display_errors","1");
if (version_compare(phpversion(), "5.0.0", ">")==1) {
    ini_set("error_reporting", E_ALL | E_STRICT);
} else {
    ini_set("error_reporting", E_ALL);
}
*/
// Override TimeZone warning generator for PHP >= 5.1.0
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set')) {
 date_default_timezone_set ( @date_default_timezone_get() );
}

// Disable magic_quotes_runtime if this [f.cking] feature is turned on
if (ini_get('magic_quotes_runtime') && function_exists('set_magic_quotes_runtime'))
	set_magic_quotes_runtime(false);

define('root', dirname(__FILE__).'/');
define('site_root', dirname(dirname(__FILE__)).'/');

// Initialize time measurement functions
include_once root.'includes/classes/timer.class.php';
$timer = new microTimer;
$timer -> start();

// Fix MagicQuotes [ if this feature is enabled ]
if (get_magic_quotes_gpc()) {
	@include_once root.'includes/inc/fix_magic_quotes.php';
	fix_magic_quotes();
}

// Start session
@session_start();

// Manage trackID cookie - can be used for plugins that don't require authentication,
// but need to track user according to his ID
if (!isset($_COOKIE['ngTrackID'])) {
	@setcookie('ngTrackID', md5(md5(uniqid(rand(),1))), time()+86400*365, '/', '', 0, 1);
}

//
// Global variables configuration arrays
//

// List of disabled (by plugins) actions
$SYSTEM_FLAGS['actions.disabled'] = array();

// Define default HTTP flags
$SYSTEM_FLAGS['http.headers']['content-type']	= 'text/html; charset=Windows-1251';
$SYSTEM_FLAGS['http.headers']['cache-control']	= 'private';



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

// Prepare variable with access URL
$systemAccessURL = $_SERVER['REQUEST_URI'];
if (($tmp_pos = strpos($systemAccessURL, '?')) !== FALSE) {
 $systemAccessURL = substr($systemAccessURL, 0, $tmp_pos);
}


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

// Check if we have config file
if ( ( !file_exists(confroot.'config.php') ) || ( filesize(confroot.'config.php')<10 ) ) {
	if (preg_match("#^(.*?)(\/index\.php|\/engine\/admin\.php)$#", $_SERVER['PHP_SELF'], $ms)) {
		@header("Location: ".$ms[1]."/engine/install.php");
	} else {
		@header("Location: ".adminDirName."/install.php");
	}
	echo "You should run install script first";
	exit;
}

// Preload TWIG engine
require_once root.'includes/classes/Twig/Autoloader.php';
Twig_Autoloader::register();

// Init our own exception handler
set_exception_handler('ngExceptionHandler');
set_error_handler('ngErrorHandler');
register_shutdown_function('ngShutdownHandler');

//
// *** Initialize TWIG engine
// - Reference for GLOBAL information array
global $twigGlobal;
$twigGlobal = array(
	'flags'	=> array(
		'isLogged' => 0,
	),
);

// - Main variables
global $twig, $twigLoader;

// - Configure loader parameters
$twigLoader = new Twig_Loader_NGCMS(root);
$twigStringLoader = new Twig_Loader_String();

// - Configure environment and general parameters
$twig = new Twig_Environment($twigLoader, array(
  'cache' => root.'cache/twig/',
  'auto_reload' => true,
  'autoescape' => false,
  'base_template_class' => 'Twig_Template_NGCMS',
));

$twig->addExtension(new Twig_Extension_StringLoader());

// - Global variables [by REFERENCE]
$twig->addGlobalRef('lang',		$lang);
$twig->addGlobalRef('handler',	$CurrentHandler);
$twig->addGlobalRef('global',	$twigGlobal);

// - Global variables [by VALUE]
$twig->addGlobal('skins_url',	skins_url);
$twig->addGlobal('admin_url',	admin_url);
$twig->addGlobal('currentURL',	$systemAccessURL);

// - Define functions
$twig->addFunction('pluginIsActive',	new Twig_Function_Function('getPluginStatusActive'));
$twig->addFunction('localPath', 	new Twig_Function_Function('twigLocalPath', array('needs_context' => true)));
$twig->addFunction('getLang', 		new Twig_Function_Function('twigGetLang'));
$twig->addFunction('isLang', 		new Twig_Function_Function('twigIsLang'));
$twig->addFunction('isHandler',		new Twig_Function_Function('twigIsHandler'));
$twig->addFunction('isCategory',	new Twig_Function_Function('twigIsCategory'));
$twig->addFunction('isNews',		new Twig_Function_Function('twigIsNews'));
$twig->addFunction('callPlugin', 	new Twig_Function_Function('twigCallPlugin'));
$twig->addFunction('isSet',			new Twig_Function_Function('twigIsSet', array('needs_context' => true)));
$twig->addFunction('debugContext',	new Twig_Function_Function('twigDebugContext', array('needs_context' => true)));
$twig->addFunction('debugValue',	new Twig_Function_Function('twigDebugValue'));

$twig->addFilter('truncateHTML',	new Twig_Filter_Function('twigTruncateHTML'));

$timer->registerEvent('Template engine is activated');


// Give domainName to URL handler engine for generating absolute links
$UHANDLER->setOptions(array('domainPrefix' => $config['home_url']));

// Check if engine is installed in subdirectory
if (preg_match('#^http\:\/\/([^\/])+(\/.+)#', $config['home_url'], $match))
	$UHANDLER->setOptions(array('localPrefix' => $match[2]));


@include_once root.'includes/classes/mysql.class.php';
$mysql = new mysql;
$mysql->connect($config['dbhost'], $config['dbuser'], $config['dbpasswd'], $config['dbname']);
$timer->registerEvent('DB connection established');

foreach ($mysql->select("select nc.*, ni.id as icon_id, ni.name as icon_name, ni.storage as icon_storage, ni.folder as icon_folder, ni.preview as icon_preview, ni.width as icon_width, ni.height as icon_height, ni.p_width as icon_pwidth, ni.p_height as icon_pheight from `".prefix."_category` as nc left join `".prefix."_images` ni on nc.image_id = ni.id order by nc.posorder asc", 1) as $row) {
	$catz[$row['alt']] = $row;
	$catmap[$row['id']] = $row['alt'];
}

$timer->registerEvent('DB category list is loaded');

//
// RUN compatibility mode [ rewrite old links ]
//
if ($config['libcompat']) {
	include_once root.'includes/inc/libcompat.php';
	compatRedirector();
}
// [ END compatibility mode ]

//
// Special way to pass authentication cookie via POST params
if (!isset($_COOKIE['zz_auth']) && isset($_POST['ngAuthCookie']))
	$_COOKIE['zz_auth'] = $_POST['ngAuthCookie'];


//
// Make authentication
//
$timer->registerEvent('Ready to load auth plugins');
load_extras('auth');

// Load user's permissions DB
loadPermissions();
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

	if (isset($xrow['name']) && $xrow['name']) {
		$is_logged_cookie	= true;
		$is_logged			= true;
		$username			= $xrow['name'];
		$userROW			= $xrow;

		// - Now every TWIG template will know if user is logged in
		$twigGlobal['flags']['isLogged'] = 1;
		$twigGlobal['user'] = $userROW;
		//$twig->addGlobalRef('user',	$userROW);
	}
} else {
	echo "Fatal error: No auth module is found.<br />To fix problem please run <i>upgrade.php</i> script<br /><br />\n";
}

$timer->registerEvent('Auth procedure is finished');

if ($is_logged) { @define('name', $userROW['name']); }

// Init internal cron module
$cron = new cronManager();

// Load extras for action 'all'
load_extras('all');
$timer->registerEvent('ALL core-related plugins are loaded');

// Exec extras for core module
exec_acts('core');
$timer->registerEvent('ALL core-related plugins are executed');



// Define last consts
@define('tpl_site', site_root.'templates/'.$config['theme'].'/');
@define('tpl_url', home.'/templates/'.$config['theme']);

// - TWIG: Reconfigure allowed template paths - site template is also available
$twigLoader->setPaths(array(tpl_site, root));

// - TWIG: Added global variable `tpl_url`
$twig->addGlobal('tpl_url',		tpl_url);


// Lang files are loaded _after_ executing core scripts. This is done for switcher plugin
$lang	=	LoadLang('common');

$langShortMonths = explode(",", $lang['short_months']);
$langMonths = explode(",", $lang['months']);

$f		=	$langShortMonths;
$f2		=	array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$f3		=	array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$r		=	explode(",", $lang['months']);

$timer->registerEvent('* CORE.PHP is complete');