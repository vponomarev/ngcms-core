<?php

//
// Copyright (C) 2006-2017 Next Generation CMS (http://ngcms.ru)
// Name: core.php
// Description: core
// Author: NGCMS project team
//

// Configure error display mode
@error_reporting(E_ALL ^ E_NOTICE);

// ============================================================================
// Define global directory constants
// ============================================================================

define('NGCoreDir', __DIR__ . '/');                // Location of Core directory
define('NGRootDir', dirname(__DIR__) . '/');       // Location of SiteRoot
define('NGClassDir', NGCoreDir . 'classes/');      // Location of AutoLoaded classes
define('NGVendorDir', NGRootDir . 'vendor/');      // Location of Vendor classes
$loader = require NGVendorDir . 'autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Autoloader for NEW STYLE Classes
spl_autoload_register(function ($className) {
    if (file_exists($fName = NGClassDir.$className.'.class.php')) {
        require_once $fName;
    }
});

// Magic function for immediate closure call
function NGRun($f)
{
    $f();
}

// ============================================================================
// MODULE DEPs check + basic setup
// ============================================================================
NGRun(function () {
    $depList = array(
        'sql' => array('pdo' => '', 'pdo_mysql' => ''),
        'zlib' => 'ob_gzhandler',
        'iconv' => 'iconv',
        'GD' => 'imagecreatefromjpeg',
        'mbstring' => 'mb_internal_encoding'
    );
    NGCoreFunctions::resolveDeps($depList);

    $sx = NGEngine::getInstance();
    $sx->set('events', new NGEvents());
    $sx->set('errorHandler', new NGErrorHandler());
});



// ============================================================================
// Global variables definition
// ============================================================================
global $PLUGINS, $EXTRA_HTML_VARS, $EXTRA_CSS;
global $AUTH_METHOD, $AUTH_CAPABILITIES, $PPAGES, $PFILTERS, $RPCFUNC, $TWIGFUNC;
global $RPCADMFUNC, $SUPRESS_TEMPLATE_SHOW, $SUPRESS_MAINBLOCK_SHOW, $SYSTEM_FLAGS;
global $DSlist, $PERM, $confPerm, $confPermUser, $systemAccessURL, $cron;
global $timer, $mysql, $ip, $parse, $tpl, $lang, $config;
global $TemplateCache, $siteDomainName;
global $currentHandler, $ngTrackID, $ngCookieDomain;
global $twigGlobal, $twig, $twigLoader, $twigStringLoader;

// ============================================================================
// Initialize global variables
// ============================================================================
$EXTRA_HTML_VARS = array();        // a list of added HTML vars in <head> block
$EXTRA_CSS = array();

$AUTH_METHOD = array();
$AUTH_CAPABILITIES = array();

$PPAGES = array();        // plugin's pages
$PFILTERS = array();        // filtering plugins
$RPCFUNC = array();        // RPC functions
$TWIGFUNC = array();        // TWIG defined functions
$RPCADMFUNC = array();        // RPC admin functions

$PERM = array();        // PERMISSIONS
$UGROUP = array();        // USER GROUPS

$SUPRESS_TEMPLATE_SHOW = 0;
$SUPRESS_MAINBLOCK_SHOW = 0;

$CurrentHandler = array();
$TemplateCache = array();
$lang = array();
$SYSTEM_FLAGS = array(
    'actions.disabled' => array(),
    'http.headers'     => array(
        'content-type'  => 'text/html; charset=utf-8',
        'cache-control' => 'private',
    )
);

$twigGlobal = array(
    'flags' => array(
        'isLogged' => 0,
    ),
);

// List of DataSources
$DSlist = array(
    'news'           => 1,
    'categories'     => 2,
    'comments'       => 3,
    'users'          => 4,
    'files'          => 10,
    'images'         => 11,
    '#xfields:tdata' => 51,
);

$PLUGINS = array(
    'active'        => array(),
    'active:loaded' => 0,
    'loaded'        => array(),
    'loaded:files'  => array(),
    'config'        => array(),
    'config:loaded' => 0,
);

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Define global constants "root", "site_root"
define('root', dirname(__FILE__) . '/');
define('site_root', dirname(dirname(__FILE__)) . '/');

// Define domain name for cookies
$ngCookieDomain = preg_match('#^www\.(.+)$#', $_SERVER['HTTP_HOST'], $mHost) ? $mHost[1] : $_SERVER['HTTP_HOST'];
// Remove non-standart port from domain
if (preg_match_all("#^(.+?)\:\d+$#", $ngCookieDomain, $m)) {
    $ngCookieDomain = $m[1];
}

// Manage trackID cookie - can be used for plugins that don't require authentication,
// but need to track user according to his ID
if (!isset($_COOKIE['ngTrackID'])) {
    $ngTrackID = md5(md5(uniqid(rand(), 1)));
    @setcookie('ngTrackID', $ngTrackID, time() + 86400 * 365, '/', $ngCookieDomain, 0, 1);
} else {
    $ngTrackID = $_COOKIE['ngTrackID'];
}

// Initialize last variables
$confArray = array(
    // Pre-defined init values
    'predefined' => array(
        'HTTP_REFERER' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        'PHP_SELF'     => isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '',
        'REQUEST_URI'  => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
        'config'       => array(),
        'catz'         => array(),
        'catmap'       => array(),
        'is_logged'    => false,
    )
);

// Load pre-defined variables
foreach ($confArray['predefined'] as $key => $value) {
    unset($_GET[$key], $_POST[$key], $_SESSION[$key], $_COOKIE[$key], $_ENV[$key]);
    $$key = $value;
}

// Prepare variable with access URL
$systemAccessURL = $_SERVER['REQUEST_URI'];
if (($tmp_pos = strpos($systemAccessURL, '?')) !== false) {
    $systemAccessURL = mb_substr($systemAccessURL, 0, $tmp_pos);
}

// ============================================================================
// Initialize system libraries
// ============================================================================
// ** Time measurement functions
include_once root . 'includes/classes/timer.class.php';
$timer = new microTimer;
$timer->start();

// ** Multisite engine
@include_once root . 'includes/inc/multimaster.php';

multi_multisites();
@define('confroot', root . 'conf/' . ($multiDomainName && $multimaster && ($multiDomainName != $multimaster) ? 'multi/' . $multiDomainName . '/' : ''));

// ** Load system config
@include_once confroot . 'config.php';
// [[FIX config variables]]
if (!isset($config['uprefix'])) {
    $config['uprefix'] = $config['prefix'];
}

// Set up default timezone [ default: Europe/Moscow ]
date_default_timezone_set($config['timezone'] ? $config['timezone'] : 'Europe/Moscow');

// [[MARKER]] Configuration file is loaded
$timer->registerEvent('Config file is loaded');

// Call multidomains processor
multi_multidomains();
//print "siteDomainName [".$siteDomainName."]<br/>\n";

// Initiate session - take care about right domain name for sites with/without www. prefix
//print "<pre>".var_export($_SERVER, true).var_export($_COOKIE, true)."</pre>";
@session_set_cookie_params(86400, '/', $ngCookieDomain);
@session_start();

// ** Load system libraries
@include_once root . 'includes/inc/consts.inc.php';
@include_once root . 'includes/inc/functions.inc.php';
@include_once root . 'includes/inc/extras.inc.php';

@include_once 'includes/classes/templates.class.php';
@include_once 'includes/classes/parse.class.php';

@include_once 'includes/classes/uhandler.class.php';

// [[MARKER]] All system libraries are loaded
$timer->registerEvent('Core files are included');

// ** Activate URL processing library
$UHANDLER = new urlHandler();
$UHANDLER->loadConfig();

// ** Other libraries
$parse = new parse;
$tpl = new tpl;
$ip = checkIP();

// ** Load configuration file
if ((!file_exists(confroot . 'config.php')) || (filesize(confroot . 'config.php') < 10)) {
    if (preg_match("#^(.*?)(\/index\.php|\/engine\/admin\.php)$#", $_SERVER['PHP_SELF'], $ms)) {
        @header("Location: " . $ms[1] . "/engine/install.php");
    } else {
        @header("Location: " . adminDirName . "/install.php");
    }
    echo "NGCMS: Engine is not installed yet. Please run installer from /engine/install.php";
    exit;
}

// ** Load user groups
loadGroups();

// ** Init our own exception handler
set_exception_handler('ngExceptionHandler');
set_error_handler('ngErrorHandler');
register_shutdown_function('ngShutdownHandler');

//
// *** Initialize TWIG engine
//$twigLoader = new Twig_Loader_NGCMS(root);
//$twigStringLoader = new Twig_Loader_String();

$twigLoader = new NGTwigLoader(root);
// replace https://stackoverflow.com/questions/31081910/what-to-use-instead-of-twig-loader-string
// $twigStringLoader = new Twig_Loader_String();
/**
 * Wrapper for template processing. Adds to each template variables:
 * _templateName
 * _templatePath
 */
abstract class Twig_Template_NGCMS extends Twig_Template
{
    public function render(array $context)
    {
        $context['_templateName'] = $this->getTemplateName();
        $context['_templatePath'] = dirname($this->getTemplateName()).DIRECTORY_SEPARATOR;
        return parent::render($context);
    }
}

// - Configure environment and general parameters
$twig = new Twig_Environment($twigLoader, array(
    'cache'               => root . 'cache/twig/',
    'auto_reload'         => true,
    'autoescape'          => false,
    'charset'             => 'UTF-8',
    'base_template_class' => 'Twig_Template_NGCMS',
));

$twig->addExtension(new Twig_Extension_StringLoader());

// - Global variables [by REFERENCE]
$twig->addGlobalRef('lang', $lang);
$twig->addGlobalRef('handler', $CurrentHandler);
$twig->addGlobalRef('global', $twigGlobal);
$twig->addGlobalRef('system_flags', $SYSTEM_FLAGS);

// - Global variables [by VALUE]
$twig->addGlobal('skins_url', skins_url);
$twig->addGlobal('admin_url', admin_url);
$twig->addGlobal('home', home);
$twig->addGlobal('currentURL', $systemAccessURL);

// - Define functions
$twig->addFunction('pluginIsActive', new Twig_Function_Function('getPluginStatusActive'));
$twig->addFunction('localPath', new Twig_Function_Function('twigLocalPath', array('needs_context' => true)));
$twig->addFunction('getLang', new Twig_Function_Function('twigGetLang'));
$twig->addFunction('isLang', new Twig_Function_Function('twigIsLang'));
$twig->addFunction('isHandler', new Twig_Function_Function('twigIsHandler'));
$twig->addFunction('isCategory', new Twig_Function_Function('twigIsCategory'));
$twig->addFunction('isNews', new Twig_Function_Function('twigIsNews'));
$twig->addFunction('isPerm', new Twig_Function_Function('twigIsPerm'));
$twig->addFunction('callPlugin', new Twig_Function_Function('twigCallPlugin'));
$twig->addFunction('isSet', new Twig_Function_Function('twigIsSet', array('needs_context' => true)));
$twig->addFunction('debugContext', new Twig_Function_Function('twigDebugContext', array('needs_context' => true)));
$twig->addFunction('debugValue', new Twig_Function_Function('twigDebugValue'));
$twig->addFunction('getCategoryTree', new Twig_Function_Function('twigGetCategoryTree'));
$twig->addFunction('engineMSG', new Twig_Function_Function('twigEngineMSG'));

// - Define filters
$twig->addFilter('truncateHTML', new Twig_Filter_Function('twigTruncateHTML'));

// [[MARKER]] TWIG template engine is loaded
$timer->registerEvent('Template engine is activated');

// Give domainName to URL handler engine for generating absolute links
$UHANDLER->setOptions(array('domainPrefix' => $config['home_url']));

// Check if engine is installed in subdirectory
if (preg_match('#^http\:\/\/([^\/])+(\/.+)#', $config['home_url'], $match)) {
    $UHANDLER->setOptions(array('localPrefix' => $match[2]));
}

// ** Load cache engine
@include_once root . 'includes/classes/cache.class.php';
@include_once root . 'includes/inc/DBLoad.php';

// OLD :: MySQLi driver
// $mysql = DBLoad();
// $mysql->connect($config['dbhost'], $config['dbuser'], $config['dbpasswd'], $config['dbname']);

// NEW :: PDO driver with global classes handler
NGRun(function () {
    global $config, $mysql;

    $sx = NGEngine::getInstance();
    
    switch ($config['dbtype']) {
        case 'mysqli':
            $sx->set('db', new NGMYSQLi(array('host' => $config['dbhost'], 'user' => $config['dbuser'], 'pass' => $config['dbpasswd'], 'db' => $config['dbname'], 'charset' => 'utf8')));
            break;
        case 'pdo':
            $sx->set('db', new NGPDO(array('host' => $config['dbhost'], 'user' => $config['dbuser'], 'pass' => $config['dbpasswd'], 'db' => $config['dbname'], 'charset' => 'utf8')));
            break;
        default:
            $sx->set('db', new NGMYSQLi(array('host' => $config['dbhost'], 'user' => $config['dbuser'], 'pass' => $config['dbpasswd'], 'db' => $config['dbname'], 'charset' => 'utf8')));
    }

    $sx->set('config', $config);
    $sx->set('legacyDB', new NGLegacyDB(false));
    $sx->getLegacyDB()->connect('', '', '');
    $mysql = $sx->getLegacyDB();
});


// [[MARKER]] MySQL connection is established
$timer->registerEvent('DB connection established');

// ** Load categories from DB
ngLoadCategories();

// [[MARKER]] Categories are loaded
$timer->registerEvent('DB category list is loaded');

// ** Load compatibility engine [ rewrite old links ]
if ($config['libcompat']) {
    include_once root . 'includes/inc/libcompat.php';
    compatRedirector();
}

//
// Special way to pass authentication cookie via POST params
if (!isset($_COOKIE['zz_auth']) && isset($_POST['ngAuthCookie'])) {
    $_COOKIE['zz_auth'] = $_POST['ngAuthCookie'];
}

// [[MARKER]] Ready to load auth plugins
$timer->registerEvent('Ready to load auth plugins');
loadActionHandlers('auth');

// ** Load user's permissions DB
loadPermissions();

// ============================================================================
// Initialize system libraries
// ============================================================================
// System protection
if (!$AUTH_CAPABILITIES[$config['auth_module']]['login']) {
    $config['auth_module'] = 'basic';
}
if (!$AUTH_CAPABILITIES[$config['auth_db']]['db']) {
    $config['auth_db'] = 'basic';
}

if ((is_object($AUTH_METHOD[$config['auth_module']])) && (is_object($AUTH_METHOD[$config['auth_db']]))) {
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
        $is_logged_cookie = true;
        $is_logged = true;
        $username = $xrow['name'];
        $userROW = $xrow;
        if ($config['x_ng_headers']) {
            header("X-NG-UserID: " . intval($userROW['id']));
            header("X-NG-Login: " . htmlentities($username));
        }

        // - Now every TWIG template will know if user is logged in
        $twigGlobal['flags']['isLogged'] = 1;
        $twigGlobal['user'] = $userROW;
        //$twig->addGlobalRef('user',	$userROW);
    }
} else {
    echo "Fatal error: No auth module is found.<br />To fix problem please run <i>upgrade.php</i> script<br /><br />\n";
}

// [[MARKER]] Authentification process is complete
$timer->registerEvent('Auth procedure is finished');

if ($is_logged) {
    @define('name', $userROW['name']);
}

// Init internal cron module
$cron = new cronManager();

// ** Load action handlers for action 'all'
loadActionHandlers('all');
$timer->registerEvent('ALL core-related plugins are loaded');

// ** Execute 'core' action handler
executeActionHandler('core');
$timer->registerEvent('ALL core-related plugins are executed');

// Define last consts
@define('tpl_site', site_root . 'templates/' . $config['theme'] . '/');
@define('tpl_url', home . '/templates/' . $config['theme']);

// - TWIG: Reconfigure allowed template paths - site template is also available
$twigLoader->setPaths(array(tpl_site, root));

// - TWIG: Added global variable `tpl_url`, `scriptLibrary`
$twig->addGlobal('tpl_url', tpl_url);
$twig->addGlobal('scriptLibrary', scriptLibrary);

// Lang files are loaded _after_ executing core scripts. This is done for switcher plugin
$lang = LoadLang('common');
$lang = LoadLangTheme();

$langShortMonths = explode(",", $lang['short_months']);
$langMonths = explode(",", $lang['months']);

$timer->registerEvent('* CORE.PHP is complete');
