<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: extras.inc.php
// Description: NGCMS extras managment functions
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// #================================================================#
// # Class template definition                                      #
// #================================================================#

// CLASS DEFINITION: news filter
class NewsFilter
{
    // ### Add news interceptor ###
    // Form generator
    public function addNewsForm(&$tvars)
    {
        return 1;
    }

    // Adding executor [done BEFORE actual add and CAN block adding ]
    public function addNews(&$tvars, &$SQL)
    {
        return 1;
    }

    // Adding notificator [ after successful adding ]
    public function addNewsNotify(&$tvars, $SQL, $newsid)
    {
        return 1;
    }

    // ### Edit news interceptor ###
    // Form generator
    public function editNewsForm($newsID, $SQLnews, &$tvars)
    {
        return 1;
    }

    // Edit executor  [done BEFORE actual edit and CAN block editing ]
    public function editNews($newsID, $SQLnews, &$SQLnew, &$tvars)
    {
        return 1;
    }

    // Edit Notifier [ adfter successful editing ]
    public function editNewsNotify($newsID, $SQLnews, &$SQLnew, &$tvars)
    {
        return 1;
    }

    // List news form generator [ in admin panel ]
    public function listNewsForm($newsID, $SQLnews, &$tvars)
    {
        return 1;
    }

    // ### Delete news interceptor ###
    // Delete news call
    public function deleteNews($newsID, $SQLnews)
    {
        return 1;
    }

    // Delete news notifier [ after news is deleted ]
    public function deleteNewsNotify($newsID, $SQLnews)
    {
        return 1;
    }

    // ### Mass modify news interceptor ###
    // Mass modify news call
    public function massModifyNews($idList, $setValue, $currentData)
    {
        return 1;
    }

    // Mass modify news call [ after news are modified ]
    public function massModifyNewsNotify($idList, $setValue, $currentData)
    {
        return 1;
    }

    // ### SHOW news interceptor ###
    // Show news call :: preprocessor (call directly after news fetch) for each record
    // Mode - news show mode [ array ]
    // 'style'   =>
    //   * short   - short news show
    //   * full    - full news show
    //   * export  - export news ( print / some plugins and so on )
    // 'plugin'  => if is called from plugin - ID of plugin
    // 'emulate' => flag if emulation mode is used [ for example, for preview ]
    // 'nCount'  => news if order (1,2,...) for SHORT news
    public function showNewsPre($newsID, &$SQLnews, $mode = [])
    {
        return 1;
    }

    // Show news call :: processor (call after all processing is finished and before show) for each record
    public function showNews($newsID, $SQLnews, &$tvars, $mode = [])
    {
        return 1;
    }

    // Behaviour before/after starting showing any news template
    // $newsID	- ID of the news to show
    // $SQLnews	- SQL row of the news
    // $mode	- array with config params
    //  style	- working mode ( 'short' / 'full' / 'export' )
    //  num		- number in order (for short list)
    //  limit	- show limit in order (for short list)
    public function onBeforeNewsShow($newsID, $SQLnews, $mode = [])
    {
        return 1;
    }

    public function onAfterNewsShow($newsID, $SQLnews, $mode = [])
    {
        return 1;
    }

    // Called BEFORE showing list of news, but after fetching SQL query
    // $mode - callingParams, interesing values:
    //		'style'	  => mode for which we're called
    //			* short		- short new display
    //			* full		- full news display
    //			* export	- export data [ for plugins or so on. No counters are updated ]
    //		'query'   => results of SELECT news query
    //			* count		- number of fetched news
    //			* ids		- array with IDs of fetched news
    //			* results	- output from SELECT query
    public function onBeforeShowlist($mode)
    {
        return 1;
    }

    // Behaviour before/after showing news template
    // $mode	- calling mode (may be 'short' or 'full')
    public function onBeforeShow($mode)
    {
        return 1;
    }

    public function onAfterShow($mode)
    {
        return 1;
    }
}

// CLASS DEFINITION: static filter
class StaticFilter
{
    // ### Add static interceptor ###
    // Form generator
    public function addStaticForm(&$tvars)
    {
        return 1;
    }

    // Adding executor
    public function addStatic(&$tvars, &$SQL)
    {
        return 1;
    }

    // ### Edit static interceptor ###
    // Form generator
    public function editStaticForm($staticID, $SQLnews, &$tvars)
    {
        return 1;
    }

    // Edit executor
    public function editStatic($staticID, $SQLstatic, &$SQLnew, &$tvars)
    {
        return 1;
    }

    // ### Delete static page interceptor ###
    // Delete static call
    public function deleteStatic($staticID, $SQLstatic)
    {
        return 1;
    }

    // ### SHOW static interceptor ###
    // Show static call :: preprocessor (call directly after news fetch)
    // Mode - news show mode [ array ]
    // 'plugin'  => if is called from plugin - ID of plugin
    // 'emulateMode' =>  flag if emulation mode is used [ for example, for preview ]
    public function showStaticPre($staticID, &$SQLstatic, $mode)
    {
        return 1;
    }

    // Show static call :: processor (call after all processing is finished and before show)
    public function showStatic($staticID, $SQLstatic, &$tvars, $mode)
    {
        return 1;
    }
}

// CLASS DEFINITION: core actions filter
class CoreFilter
{
    // Register new user: FORM handler
    public function registerUserForm(&$tvars)
    {
        return 1;
    }

    // Register new user: BEFORE actual registration
    public function registerUser($params, &$msg)
    {
        return 1;
    }

    // Register new user: Notifier [ after successful adding ]
    public function registerUserNotify($userID, $userRec)
    {
        return 1;
    }

    // Show usermenu
    public function showUserMenu(&$tVars)
    {
        return 1;
    }
}

// CLASS DEFINITION: registration plugin
class CoreAuthPlugin
{
    //
    public function login($auto_scan = 1, $username = '', $password = '')
    {
        return 'ERR:METHOD.NOT.IMPLEMENTED';
    }

    public function save_auth($dbrow)
    {
        return false;
    }

    public function check_auth()
    {
        return false;
    }

    public function drop_auth()
    {
        return false;
    }

    public function get_reg_params()
    {
        return false;
    }

    public function register(&$params, $values, &$msg)
    {
        return 0;
    }

    public function get_restorepw_params()
    {
        return false;
    }

    public function restorepw(&$params, $values, &$msg)
    {
        return false;
    }

    public function confirm_restorepw(&$msg, $reqid = null, $reqsecret = null)
    {
        return false;
    }

    // AJAX call - online check registration parameters for correct valuescheck if login is available
    // Input:
    // $params - array of 'fieldName' => 'fieldValue' for checking
    // Returns:
    // $result - array of 'fieldName' => status
    // List of statuses:
    // 0	- Method not implemented [ this field is not checked/can't be checked/... ] OR NOT SET
    // 1	- Occupied
    // 2	- Incorrect length
    // 3	- Incorrect format
    // 100	- Available for registration
    public function onlineCheckRegistration($params)
    {
        return [];
    }
}

// ==================================================================
// Categories edit interceptors
// ==================================================================

class FilterAdminCategories
{
    // ### Add category interceptor ###
    // Form generator
    public function addCategoryForm(&$tvars)
    {
        return 1;
    }

    // Adding executor [done BEFORE actual add and CAN block adding ]
    public function addCategory(&$tvars, &$SQL)
    {
        return 1;
    }

    // Adding notificator [ after successful adding ]
    public function addCategoryNotify(&$tvars, $SQL, $newsid)
    {
        return 1;
    }

    // ### Edit category interceptor ###
    // Form generator
    public function editCategoryForm($categoryID, $SQL, &$tvars)
    {
        return 1;
    }

    // Edit executor  [done BEFORE actual edit and CAN block editing ]
    public function editCategory($categoryID, $SQL, &$SQLnew, &$tvars)
    {
        return 1;
    }

    // Edit Notifier [ adfter successful editing ]
    public function editCategoryNotify($categoryID, $SQL, &$SQLnew, &$tvars)
    {
        return 1;
    }
}

// Register admin filter
function register_admin_filter($group, $name, $instance)
{
    global $AFILTERS;
    $AFILTERS[$group][$name] = $instance;
}

//
// Get/Load list of active plugins & required files
//
function getPluginsActiveList()
{
    global $PLUGINS;

    if ($PLUGINS['active:loaded']) {
        return $PLUGINS['active'];
    }
    if (is_file(conf_pactive)) {
        @include conf_pactive;
        if (is_array($array)) {
            $PLUGINS['active'] = $array;
        }
    }
    $PLUGINS['active:loaded'] = 1;

    return $PLUGINS['active'];
}

//
// Report if plugin is active
//
function getPluginStatusActive($pluginID)
{
    $array = getPluginsActiveList();
    if (isset($array['active'][$pluginID]) && $array['active'][$pluginID]) {
        return true;
    }

    return false;
}

//
// Report if plugin is installed
//
function getPluginStatusInstalled($pluginID)
{
    $array = getPluginsActiveList();
    if (isset($array['installed'][$pluginID]) && $array['installed'][$pluginID]) {
        return true;
    }

    return false;
}

//
// Load plugins for specified action [ CAN BE USED FOR MANUAL PLUGIN PRELOAD ]
// * if $plugin name specified - manual PRELOAD mode is used:
//   Each plugin can call load_extras(<action>, <plugin name>) for preloading
//   plugin for action
//
function load_extras($action, $plugin = '')
{
    global $PLUGINS, $timer;

    $loadedCount = 0;
    $array = getPluginsActiveList();
    // Find extras for selected action
    if (isset($array['actions'][$action]) && is_array($array['actions'][$action])) {
        // There're some modules
        foreach ($array['actions'][$action] as $key => $value) {
            // Skip plugins in manual mode
            if ($plugin && ($key != $plugin)) {
                continue;
            }

            // Do only if this file is was not loaded earlier
            if (!isset($PLUGINS['loaded:files'][$value])) {
                // Try to load file. First check if it exists
                if (is_file(extras_dir.'/'.$value)) {
                    $tX = $timer->stop(4);
                    include_once extras_dir.'/'.$value;
                    $timer->registerEvent('loadActionHandlers('.$action.'): preloaded file "'.$value.'" for '.round($timer->stop(4) - $tX, 4).' sec');
                    $PLUGINS['loaded:files'][$value] = 1;
                    $loadedCount++;
                } else {
                    $timer->registerEvent('loadActionHandlers('.$action.'): CAN\'t preload file that doesn\'t exists: "'.$value.'"');
                }
                $PLUGINS['loaded'][$key] = 1;
            }
        }
    }

    // Return count of loaded plugins
    return $loadedCount;
}

// * New Style function name for `load_extras`
function loadActionHandlers($action, $plugin = '')
{
    return load_extras($action, $plugin);
}

//
// Load plugin [ Same behaviour as for load_extras ]
//
function loadPlugin($pluginName, $actionList = '*')
{
    global $PLUGINS, $timer;

    $plugList = getPluginsActiveList();
    $loadCount = 0;

    // Don't load if plugin is not activated
    if (!$plugList['active'][$pluginName]) {
        return false;
    }

    // Scan all available actions and preload plugin's file if needed
    foreach ($plugList['actions'] as $aName => $pList) {
        if (isset($pList[$pluginName]) &&
            ((is_array($actionList) && in_array($aName, $actionList)) ||
                (!is_array($actionList) && (($actionList == '*') || ($actionList == $aName))))
        ) {
            // Yes, we should load this file. If it's not loaded earlier
            $pluginFileName = $pList[$pluginName];

            if (!isset($PLUGINS['loaded:files'][$pluginFileName])) {
                // Try to load file. First check if it exists
                if (is_file(extras_dir.'/'.$pluginFileName)) {
                    $tX = $timer->stop(4);
                    include_once extras_dir.'/'.$pluginFileName;
                    $timer->registerEvent('func loadPlugin ('.$pluginName.'): preloaded file "'.$pluginFileName.'" for '.($timer->stop(4) - $tX).' sec');
                    $PLUGINS['loaded:files'][$pluginFileName] = 1;
                    $loadCount++;
                } else {
                    $timer->registerEvent('func loadPlugin ('.$pluginName.'): CAN\'t preload file that doesn\'t exists: "'.$pluginFileName.'"');
                }
            }
        }
    }

    $PLUGINS['loaded'][$pluginName] = 1;

    return $loadCount;
}

//
// Load plugin's library
//
function loadPluginLibrary($plugin, $libname = '')
{
    global $timer;

    $list = getPluginsActiveList();

    // Check if we know about this plugin
    if (!isset($list['active'][$plugin])) {
        return false;
    }

    // Check if we need to load all libs
    if (!$libname) {
        foreach ($list['libs'][$plugin] as $id => $file) {
            $tX = $timer->stop(4);
            include_once extras_dir.'/'.$list['active'][$plugin].'/'.$file;
            $timer->registerEvent('loadPluginLibrary: '.$plugin.'.'.$id.' ['.$file.'] for '.round($timer->stop(4) - $tX, 4).' sec');
        }

        return true;
    } else {
        if (isset($list['libs'][$plugin][$libname])) {
            $tX = $timer->stop(4);
            include_once extras_dir.'/'.$list['active'][$plugin].'/'.$list['libs'][$plugin][$libname];
            $timer->registerEvent('loadPluginLibrary: '.$plugin.' ['.$libname.'] for '.round($timer->stop(4) - $tX, 4).' sec');

            return true;
        }

        return false;
    }
}

function add_act($item, $function, $arguments = 0, $priority = 5)
{
    global $acts;

    // Check if function is already loaded for this action
    if (isset($acts['action']) && is_array($acts['action'])) {
        foreach ($acts['action'] as $k => $v) {
            if (is_array($v) && (array_search($function, $v) !== false)) {
                return true;
            }
        }
    }

    // Register new item
    $acts[$item][$priority][] = $function;

    return true;
}

// * New Style function name for `add_act`
function registerActionHandler($action, $function, $priority = 5)
{
    return add_act($action, $function, 0, $priority);
}

// * New Style function name for 'exec_acts'
function executeActionHandler($action)
{
    global $acts, $timer, $SYSTEM_FLAGS;

    $output = '';

    // Do not run action if it's disabled
    if (isset($SYSTEM_FLAGS['actions.disabled'][$action]) && $SYSTEM_FLAGS['actions.disabled'][$action]) {
        $timer->registerEvent('disabled EXEC_ACTS ('.$action.')');

        return;
    }

    $timer->registerEvent('executeActionHandler ('.$action.')');

    // Preload plugins (if needed)
    loadActionHandlers($action);

    // Finish if there're no plugins for this action
    if (!isset($acts[$action]) || !$acts[$action] || !is_array($acts[$action])) {
        return true;
    }
    foreach ($acts[$action] as $priority => $functions) {
        if (!is_array($functions)) {
            continue;
        }

        foreach ($functions as $func) {
            $tX = $timer->stop(4);
            $output .= call_user_func($func);
            $timer->registerEvent('executeActionHandler ('.$action.'): call function "'.$func.'" for '.round($timer->stop(4) - $tX, 4).' sec');
        }
    }

    return $output;
}

function exec_acts($item, $sth = '', $arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null)
{
    global $acts, $timer, $SYSTEM_FLAGS;

    return executeActionHandler($item);

    // Do not run action if it's disabled
    if (isset($SYSTEM_FLAGS['actions.disabled'][$item]) && $SYSTEM_FLAGS['actions.disabled'][$item]) {
        $timer->registerEvent('disabled EXEC_ACTS ('.$item.')');

        return;
    }

    $timer->registerEvent('executeActionHandler ('.$item.')');

    // Make module preload if needed
    load_extras($item);

    // process params
    $args = array_slice(func_get_args(), 2);
    $all_arguments = array_merge([$sth], $args);

    if (!isset($acts[$item]) || !$acts[$item]) {
        return $sth;
    } else {
        uksort($acts[$item], 'strnatcasecmp');
    }

    foreach ($acts[$item] as $priority => $functions) {
        if (!is_null($functions)) {
            foreach ($functions as $func) {
                $tX = $timer->stop(4);

                if ($func['arguments'] == 0) {
                    $sth .= call_user_func($func['function']);
                }
                if ($func['arguments'] == 1) {
                    $sth .= call_user_func($func['function'], $sth);
                }
                if ($func['arguments'] == 2) {
                    $sth .= call_user_func($func['function'], $sth, $arg1);
                }
                if ($func['arguments'] == 3) {
                    $sth .= call_user_func($func['function'], $sth, $arg1, $arg2);
                }
                if ($func['arguments'] == 4) {
                    $sth .= call_user_func($func['function'], $sth, $arg1, $arg2, $arg3);
                }
                if ($func['arguments'] == 5) {
                    $sth .= call_user_func($func['function'], $sth, $arg1, $arg2, $arg3, $arg4);
                }
                $timer->registerEvent('executeActionHandler ('.$item.'): call function "'.$func['function'].'" ['.$func['arguments'].' params] for '.($timer->stop(4) - $tX).' sec');
            }
        }
    }

    return $sth;
}

// Disable desired action from plugin
function actionDisable($action)
{
    global $SYSTEM_FLAGS;
    $SYSTEM_FLAGS['actions.disabled'][$action] = 1;
}

// =========================================================
// PLUGINS: parameters managment
// =========================================================

//
// Get variable
//
function pluginGetVariable($pluginID, $var)
{
    global $PLUGINS;

    if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig()) {
        return false;
    }

    if (!isset($PLUGINS['config'][$pluginID])) {
        return null;
    }
    if (!isset($PLUGINS['config'][$pluginID][$var])) {
        return null;
    }

    return $PLUGINS['config'][$pluginID][$var];
}

// OLD
function extra_get_param($module, $var)
{
    return pluginGetVariable($module, $var);
}

//
// Set variable
//
function pluginSetVariable($pluginID, $var, $value)
{
    global $PLUGINS;

    if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig()) {
        return false;
    }

    $PLUGINS['config'][$pluginID][$var] = $value;

    return true;
}

// OLD
function extra_set_param($module, $var, $value)
{
    return pluginSetVariable($module, $var, $value);
}

//
// Save configuration parameters of plugins (should be called after pluginSetVariable)
//
function pluginsSaveConfig($suppressNotify = false)
{
    global $PLUGINS, $lang;

    if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig()) {
        if (!$suppressNotify) {
            msg(['type' => 'error', 'text' => str_replace('{name}', conf_pconfig, $lang['error.config.read']), 'info' => $lang['error.config.read#desc']]);
        }

        return false;
    }

    //
    if (!($fconfig = @fopen(conf_pconfig, 'w'))) {
        if (!$suppressNotify) {
            msg(['type' => 'error', 'text' => str_replace('{name}', conf_pconfig, $lang['error.config.write']), 'info' => $lang['error.config.write#desc']]);
        }

        return false;
    }

    fwrite($fconfig, serialize($PLUGINS['config']));
    fclose($fconfig);

    return true;
}

// OLD
function extra_commit_changes()
{
    pluginsSaveConfig();
}

//
// Load configuration variables for plugins
//
function pluginsLoadConfig()
{
    global $PLUGINS;

    if ($PLUGINS['config:loaded']) {
        return 1;
    }
    $fconfig = @fopen(conf_pconfig, 'r');
    if ($fconfig) {
        if (filesize(conf_pconfig)) {
            $content = fread($fconfig, filesize(conf_pconfig));
        } else {
            $content = serialize([]);
        }
        $PLUGINS['config'] = unserialize($content);
        $PLUGINS['config:loaded'] = 1;
        fclose($fconfig);

        return true;
    } else {
        // File doesn't exists. Mark as `loaded`
        $PLUGINS['config'] = [];
        $PLUGINS['config:loaded'] = 1;
    }

    return false;
}

//
// Load 'version' file from plugin directory
//
function plugins_load_version_file($filename)
{

    // config variables & function init
    $config_params = ['id', 'name', 'version', 'acts', 'file', 'config', 'install', 'deinstall', 'management', 'type', 'description', 'author', 'author_uri', 'permanent', 'library', 'actions', 'minenginebuild'];
    $required_params = ['id', 'name', 'version', 'type'];
    $list_params = ['library', 'actions'];
    $ver = [];

    foreach ($list_params as $id) {
        $ver[$id] = [];
    }

    // open file
    if (!($file = @fopen($filename, 'r'))) {
        return false;
    }

    // read file
    while (!feof($file)) {
        $line = fgets($file);
        if (preg_match("/^(.+?) *\: *(.+?) *$/i", $line, $r) == 1) {
            $key = rtrim(mb_strtolower($r[1]));
            $value = rtrim($r[2]);
            if (in_array($key, $config_params)) {
                if (in_array($key, $list_params)) {
                    $ver[$key][] = $value;
                } else {
                    $ver[$key] = $value;
                }
            }
        }
    }

    // Make some cleanup
    $ver['acts'] = isset($ver['acts']) ? str_replace(' ', '', $ver['acts']) : '';
    if (isset($ver['permanent']) && ($ver['permanent'] == 'yes')) {
        $ver['permanent'] = 1;
    } else {
        $ver['permanent'] = 0;
    }

    // check for filling required params
    foreach ($required_params as $v) {
        if (!$ver[$v]) {
            return false;
        }
    }

    // check for library/actions filling
    foreach (['library', 'actions'] as $key) {
        $list = $ver[$key];
        $ver[$key] = [];

        foreach ($list as $rec) {
            if (!$rec) {
                continue;
            }
            list($ids, $fname) = explode(';', $rec);

            $ids = trim($ids);
            $fname = trim($fname);

            if (!$ids || !$fname) {
                return false;
            }
            $idlist = explode(',', $ids);
            foreach ($idlist as $entry) {
                if (trim($entry)) {
                    $ver[$key][trim($entry)] = $fname;
                }
            }
        }
    }

    return $ver;
}

//
// Get a list of installed plugins
//
function pluginsGetList()
{
    global $timer;

    $timer->registerEvent('@ pluginsGetList() called');
    // open directory
    $handle = @opendir(extras_dir);
    $extras = [];
    // load list of extras
    while (false != ($dir = @readdir($handle))) {
        $edir = extras_dir.'/'.$dir;
        // Skip special dirs ',' and '..'
        if (($dir == '.') || ($dir == '..') || (!is_dir($edir))) {
            continue;
        }

        // Check 'version' file
        if (!is_file($edir.'/version')) {
            continue;
        }

        // Load version file
        $ver = plugins_load_version_file($edir.'/version');
        if (!is_array($ver)) {
            continue;
        }

        // fill fully file path (within 'plugins' directory)
        $ver['dir'] = $dir;

        // Check if version is compatible
        if (!isset($ver['minenginebuild']) || ($ver['minenginebuild'] > engineVersionBuild)) {
            $ver['isCompatible'] = false;
        } else {
            $ver['isCompatible'] = true;
        }

        // Good, version file is successfully loaded, add data into array
        $extras[$ver['id']] = $ver;
        //array_push($extras, $ver);
    }

    return $extras;
}

//
// Add plugin's page
//
function register_plugin_page($pname, $mode, $func_name, $show_template = 1)
{
    global $PPAGES;

    if (!isset($PPAGES[$pname]) || !is_array($PPAGES[$pname])) {
        $PPAGES[$pname] = [];
    }
    $PPAGES[$pname][$mode] = ['func' => $func_name, 'mode' => $mode];
}

//
// Add item into list of additional HTML meta tags
//
function register_htmlvar($type, $data)
{
    global $EXTRA_HTML_VARS;

    // Check for duplicate

    $EXTRA_HTML_VARS[] = ['type' => $type, 'data' => $data];
}

//
// Add new stylesheet into template
//
function register_stylesheet($url)
{
    global $EXTRA_CSS;
    register_htmlvar('css', $url);
}

//
// Get configuration directory for plugin (and create it if needed)
//
function get_plugcfg_dir($plugin)
{
    $dir = confroot.'extras';
    if ((!is_dir($dir)) && (!mkdir($dir))) {
        echo "Can't create config directory for plugins. Please, check permissions for engine/conf/ dir<br/>\n";

        return '';
    }

    $dir .= '/'.$plugin;
    if (!is_dir($dir)) {
        if (!mkdir($dir)) {
            echo "Can't create config directory for plugin '$plugin'. Please, check permissions for engine/conf/plugins/ dir<br>\n";

            return '';
        }
    }

    return $dir;
}

//
// Get plugin cache dir
//
function get_plugcache_dir($plugin)
{
    global $multiDomainName, $multimaster;

    $dir = root.'cache/';
    if ($multiDomainName && $multimaster && ($multiDomainName != $multimaster)) {
        $dir .= 'multi/';
        if ((!is_dir($dir)) && (!mkdir($dir))) {
            echo "Can't create multi cache dir!<br>\n";

            return '';
        }
    }
    if ($plugin) {
        $dir .= $plugin.'/';
        if ((!is_dir($dir)) && (!mkdir($dir))) {
            echo "Can't create cache for plugin '$plugin'<br>\n";

            return '';
        }
    }

    return $dir;
}

//
// Save file into cache
// Params:
// $fname - file name to store
// $data - what should be written into cache
// $plugin - [optional] plugin name that stores data
// $hugeMode - flag if plugin wants to store huge data
//  [ data will be stored in binary tree 32 x 32 = 1024 different dirs ]
//
function cacheStoreFile($fname, $data, $plugin = '', $hugeMode = 0)
{

    // Try to get cache directory name. Return false if it's not possible
    if (!($dir = get_plugcache_dir($plugin))) {
        return false;
    }

    // In case of huge mode - try to access the tree
    //if ($hugeMode) {
    //	$fhash = md5($fname);
    //	//$dp1   =
    //	//
    //	//if (!
    //}

    // Try to create file
    if (($fn = @fopen($dir.$fname, 'w')) == false) {
        return false;
    }

    // Try to make exclusive file lock. Return if failed
    if (@flock($fn, LOCK_EX) == false) {
        fclose($fn);

        return false;
    }

    // Write into file
    if (@fwrite($fn, $data) == -1) {
        // Failed.
        flock($fn, LOCK_UN);
        fclose($fn);

        return false;
    }

    flock($fn, LOCK_UN);
    fclose($fn);

    return true;
}

// Load file from cache
// Params:
// $fname  - file name to store
// $expire - expiration period for data. Nothing will be returned if data expired
// $plugin - [optional] plugin name that stores data
function cacheRetrieveFile($fname, $expire, $plugin = '')
{

    // Try to get cache directory name. Return false if it's not possible
    if (!($dir = get_plugcache_dir($plugin))) {
        return false;
    }

    // Try to open file with data
    if (($fn = @fopen($dir.$fname, 'r')) == false) {
        return false;
    }

    // Check if file is expired. Return if it's so.
    $stat = fstat($fn);
    if (!is_array($stat) || ($stat[9] + $expire < time())) {
        return false;
    }

    // Try to make shared file lock. Return if failed
    if (@flock($fn, LOCK_SH) == false) {
        fclose($fn);

        return false;
    }

    // Return if file is empty
    if ($stat[7] < 1) {
        fclose($fn);

        return false;
    }

    // Read data from file
    $data = fread($fn, $stat[7]);

    // Unlock and close file
    flock($fn, LOCK_UN);
    fclose($fn);

    // Return data
    return $data;
}

function create_access_htaccess()
{
    $htaccess_array = [
        ['dir' => 'cache', 'data' => "# Lock access\nOrder Deny,Allow\nDeny from all"],
        ['dir' => 'backups', 'data' => "# Lock access\n\t<FilesMatch .*>\n\tDeny from all\n</FilesMatch>"],
        ['dir' => 'conf', 'data' => "<files *>\n\tOrder Deny,Allow\n\tDeny from All\n</files>"],
    ];

    //print '<pre>'.var_export($htaccess_array, true).'</pre>';

    if (is_array($htaccess_array)) {
        foreach ($htaccess_array as $result) {
            $htaccessFile = root.$result['dir'].'/.htaccess';

            // Try to create file
            if (file_exists($htaccessFile)) {
                continue;
            }

            if (($fn = @fopen($htaccessFile, 'w')) == false) {
                continue;
            }

            // Try to make exclusive file lock. Return if failed
            if (@flock($fn, LOCK_EX) == false) {
                fclose($fn);
                continue;
            }
            // Write into file
            if (@fwrite($fn, $result['data']) == -1) {
                // Failed.
                flock($fn, LOCK_UN);
                fclose($fn);
                continue;
            }
            flock($fn, LOCK_UN);
            fclose($fn);
        }
    }
}

//
// Routine that helps plugin to locate template files. It checks if required file
// exists in "global template" dir
//
// $tname		- template names (in string array or single name)
// $plugin		- plugin name
// $localSource		- flag if function should work in "local only" mode, i.e.
//				  that all files are in own plugin dir
// $skin		- skin name in plugin dir ( plugins/PLUGIN/tpl/skin/ )
// $block		- name of subdir within current template/block

function locatePluginTemplates($tname, $plugin, $localsource = 0, $skin = '', $block = '')
{
    global $config;

    // Check if $tname is correct
    if (!is_array($tname)) {
        if ($tname == '') {
            return [];
        }
        $tname = [$tname];
    }

    // Text SKIN+BLOCK
    $tsb = ((($skin != '') || ($block != '')) ? '/' : '').
        ($skin ? 'skins/'.$skin : '').
        ((($skin != '') && ($block != '')) ? '/' : '').
        ($block ? $block : '');

    $tpath = [];
    foreach ($tname as $fn) {
        $fnc = (mb_substr($fn, 0, 1) == ':') ? mb_substr($fn, 1) : ($fn.'.tpl');
        if (!$localsource && is_readable(tpl_site.'plugins/'.$plugin.$tsb.'/'.$fnc)) {
            $tpath[$fn] = tpl_site.'plugins/'.$plugin.$tsb.'/';
            $tpath['url:'.$fn] = tpl_url.'/plugins/'.$plugin.$tsb;
        } elseif (!$localsource && is_readable(tpl_site.'plugins/'.$plugin.($block ? ('/'.$block) : '').'/'.$fnc)) {
            $tpath[$fn] = tpl_site.'plugins/'.$plugin.($block ? ('/'.$block) : '').'/';
            $tpath['url:'.$fn] = tpl_url.'/plugins/'.$plugin.($block ? ('/'.$block) : '');
        } elseif (is_readable(extras_dir.'/'.$plugin.'/tpl'.$tsb.'/'.$fnc)) {
            $tpath[$fn] = extras_dir.'/'.$plugin.'/tpl'.$tsb.'/';
            $tpath['url:'.$fn] = admin_url.'/plugins/'.$plugin.'/tpl'.$tsb;
        }
    }

    return $tpath;
}

// Register system filter
function register_filter($group, $name, $instance)
{
    global $PFILTERS;
    $PFILTERS[$group][$name] = $instance;
}

// NEW style for `register_filter`
function pluginRegisterFilter($group, $name, $instance)
{
    return register_filter($group, $name, $instance);
}

// Register RPC function
function rpcRegisterFunction($name, $instance, $permanent = false)
{
    global $RPCFUNC;
    $RPCFUNC[$name] = $instance;
}

// Register TWIG function call
function twigRegisterFunction($pluginName, $funcName, $instance)
{
    global $TWIGFUNC;
    $TWIGFUNC[$pluginName.'.'.$funcName] = $instance;
}

//
// Check if we have handler for specified action
//
function checkLinkAvailable($pluginName, $handlerName)
{
    global $UHANDLER;

    return isset($UHANDLER->hPrimary[$pluginName][$handlerName]);
}

//
// Generate link
// Params:
// $pluginName	- ID of plugin
// $handlerName	- Handler name
// $params	- Params to pass to processor
// $xparams	- External params to pass as "?param1=value1&...&paramX=valueX"
// $intLink	- Flag if links should be treated as `internal` (i.e. all '&' should be displayed as '&amp;'
// $absoluteLink - Flag if absolute link (including http:// ... ) should be generated
function generateLink($pluginName, $handlerName, $params = [], $xparams = [], $intLink = false, $absoluteLink = false)
{
    global $UHANDLER;

    return $UHANDLER->generateLink($pluginName, $handlerName, $params, $xparams, $intLink, $absoluteLink);
}

//
// Generate plugin link [ generate personal link if available. if not - generate common link ]
// Params:
// $pluginName	- ID of plugin
// $handlerName	- Handler name
// $params	- Params to pass to processor
// $xparams	- External params to pass as "?param1=value1&...&paramX=valueX"
// $intLink	- Flag if links should be treated as `internal` (i.e. all '&' should be displayed as '&amp;'
// $absoluteLink - Flag if absolute link (including http:// ... ) should be generated
function generatePluginLink($pluginName, $handlerName, $params = [], $xparams = [], $intLink = false, $absoluteLink = false)
{
    return checkLinkAvailable($pluginName, $handlerName) ?
        generateLink($pluginName, $handlerName, $params, $xparams, $intLink, $absoluteLink) :
        generateLink('core', 'plugin', ['plugin' => $pluginName, 'handler' => $handlerName], array_merge($params, $xparams), $intLink, $absoluteLink);
}

//
// Generate link to page
//
function generatePageLink($paginationParams, $page, $intlink = false)
{

    //print "generatePageLink(".var_export($paginationParams, true).", ".$intlink.";".$page.")<br/>\n";
    // Generate link
    $lparams = $paginationParams['params'];
    $lxparams = $paginationParams['xparams'];

    if ($paginationParams['paginator'][2] || ($page > 1)) {
        if ($paginationParams['paginator'][1]) {
            $lxparams[$paginationParams['paginator'][0]] = $page;
        } else {
            $lparams[$paginationParams['paginator'][0]] = $page;
        }
    }

    //return generateLink($paginationParams['pluginName'], $paginationParams['pluginHandler'], $lparams, $lxparams);
    return generatePluginLink($paginationParams['pluginName'], $paginationParams['pluginHandler'], $lparams, $lxparams, $intlink);
}

//
//
//
function _MASTER_defaultRUN($pluginName, $handlerName, $params, &$skip, $handlerParams)
{
    global $PPAGES, $lang, $SYSTEM_FLAGS, $CurrentHandler;
    // Preload requested plugin
    loadPlugin($pluginName, 'ppages');

    // Make chain-load for all plugins, that want to activate during this plugin activation
    loadActionHandlers('action.ppages.'.$pluginName);
    loadActionHandlers('plugin.'.$pluginName);

    $pcall = $PPAGES[$pluginName][$handlerName];

    if (is_array($pcall) && function_exists($pcall['func'])) {
        // Make page title
        $SYSTEM_FLAGS['info']['title']['group'] = $lang['loc_plugin'];

        // Report current handler config
        $CurrentHandler = [
            'pluginName'    => $pluginName,
            'handlerName'   => $handlerName,
            'params'        => $params,
            'handlerParams' => $handlerParams,
        ];
        $req = call_user_func($pcall['func'], $params);
        if (!is_null($req) && $skip['FFC'] && !$req) {
            $skip['fail'] = 1;
        }
    } else {
        msg(['type' => 'error', 'text' => str_replace(['{handler}', '{plugin}'], [secure_html($handlerName), secure_html($pluginName)], $lang['plugins.nohadler'])]);

        return false;
    }

    return true;
}

function _MASTER_URL_PROCESSOR($pluginName, $handlerName, $params, $skip, $handlerParams)
{
    global $PPAGES, $lang, $CurrentHandler, $timer;

    //print "## PLUGIN CALL: <b> (".$pluginName.", ".$handlerName.")</b><br/>\n";
    //print "<pre>".var_export($params, true)."</pre><br/>\n";
    $timer->registerEvent('URL Processor for ['.$pluginName.']['.$handlerName.']');

    // Check for predefined plugins call
    switch ($pluginName) {
        case 'news':
            $CurrentHandler = ['pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params, 'handlerParams' => $handlerParams];
            switch ($handlerName) {
                default:
                    include_once root.'includes/news.php';
                    showNews($handlerName, $params);
            }
            break;

        case 'static':
            $CurrentHandler = ['pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params, 'FFC' => $skip['FFC'], 'handlerParams' => $handlerParams];
            switch ($handlerName) {
                default:
                    include_once root.'includes/static.php';
                    $cResult = showStaticPage(['id' => intval($params['id']), 'altname' => $params['altname'], 'FFC' => $skip['FFC'], 'print' => (($handlerName == 'print') ? true : false)]);
                    if (!$cResult && $skip['FFC']) {
                        $skip['fail'] = 1;
                    }
            }
            break;

        case 'search':
            $CurrentHandler = ['pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params, 'handlerParams' => $handlerParams];
            switch ($handlerName) {
                default:
                    include_once root.'includes/search.php';
                    search_news();
            }
            break;

        case 'core':
            $CurrentHandler = ['pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params, 'handlerParams' => $handlerParams];
            switch ($handlerName) {
                case 'plugin':
                    // Set our own params $pluginName and $handlerName and pass it to default handler
                    _MASTER_defaultRUN($params['plugin'], isset($params['handler']) ? $params['handler'] : null, $params, $skip, $handlerParams);
                    break;

                case 'registration':
                    include_once root.'cmodules.php';
                    coreRegisterUser();
                    break;

                case 'activation':
                    include_once root.'cmodules.php';
                    coreActivateUser();
                    break;

                case 'lostpassword':
                    include_once root.'cmodules.php';
                    coreRestorePassword();
                    break;

                case 'login':
                    include_once root.'cmodules.php';
                    coreLogin();
                    break;

                case 'logout':
                    include_once root.'cmodules.php';
                    coreLogout();
                    break;

                default:
            }
            break;
        default:
            _MASTER_defaultRUN($pluginName, $handlerName, $params, $skip, $handlerParams);
    }

    // Return according to SKIP value
    if (isset($skip['fail']) && $skip['fail']) {
        return ['fail' => $skip['fail']];
    }
}

// ======================================================================== //
// CRON scheduler management                                                //
// ======================================================================== //
class cronManager
{
    // Configuration parameters
    public $config;

    // Constructor
    public function __construct()
    {

        // Try to load configuration
        $configFileName = root.'conf/cron.php';

        // Check if config file exists
        if (!is_file($configFileName)) {
            $this->config = [];

            return;
        }

        // Load config file
        $this->config = @include root.'conf/cron.php';
        if (!is_array($this->config)) {
            $this->config = [];
        }
    }

    // Save updated config

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;

        return $this->saveConfig();
    }

    public function saveConfig()
    {
        $configFileName = root.'conf/cron.php';

        // Prepare resulting config content
        $fcData = "<?php\n".'return '.var_export($this->config, true).';';

        // Try to save config
        $fcHandler = @fopen($configFileName, 'w');
        if ($fcHandler) {
            fwrite($fcHandler, $fcData);
            fclose($fcHandler);

            return true;
        }

        return false;
    }

    public function registerTask($plugin, $handler, $min = '*', $hour = '*', $day = '*', $month = '*', $dow = '*')
    {

        // Проверяем параметры
        if ((!$this->checkList($min, 0, 59)) || (!$this->checkList($hour, 0, 23)) ||
            (!$this->checkList($day, 0, 31)) || (!$this->checkList($month, 0, 12)) ||
            (!$this->checkList($dow, 0, 6)) || (!$plugin)
        ) {
            // Неверные значения параметров
            return 0;
        }

        $this->config[] = [
            'min'     => $min,
            'hour'    => $hour,
            'day'     => $day,
            'month'   => $month,
            'dow'     => $dow,
            'plugin'  => $plugin,
            'handler' => $handler,
        ];

        return $this->saveConfig();
    }

    // Register new CRON task

    public function checkList($value, $min, $max)
    {
        if ($value == '*') {
            return true;
        }

        foreach (explode(',', $value) as $v) {
            if (!preg_match("#^(\d+)$#", $v, $m)) {
                return false;
            }

            if (($m[1] < $min) || ($m[1] > $max)) {
                return false;
            }
        }

        return true;
    }

    // Deregister CRON task(s)

    public function unregisterTask($plugin, $handler = '', $min = '', $hour = '', $day = '', $month = '', $DOW = '')
    {
        $ok = 0;
        foreach ($this->config as $k => $v) {
            if (((!$min) && ($v['plugin'] == $plugin) && ((!$handler) || ($v['handler'] == $handler))) ||
                (($v['min'] == $min) && ($v['hour'] == $hour) && ($v['day'] == $day) && ($v['month'] == $month) &&
                    ($v['dow'] == $DOW) && ($v['plugin'] == $plugin) && ($v['handler'] == $handler))
            ) {
                array_splice($this->config, $k, 1);
                $ok = 1;
            }
        }
        if ($ok) {
            return $this->saveConfig();
        }

        return 0;
    }

    // Cron core - run tasks
    public function run($isSysCron = false)
    {
        global $timer;

        // Check if there're any CRON tasks, return if no tasks
        if (count($this->config) == 0) {
            return 0;
        }

        // Prepare for creating internal execution flag
        $cacheDir = get_plugcache_dir('core');

        $timeout = 120;  // 120 секунд (2 минуты) на попытку
        $period = 300;  // 5 минут между запусками

        //$timeout = 5;
        //$period = 10;

        if (!is_dir($cacheDir) && !mkdir($cacheDir)) {
            echo "Can't create temp directory for plugin 'core'<br />\n";

            return;
        }

        // Determine time of last successfull run
        $lastRunTime = 0;
        $nowTime = time();
        $fn_progress = 0;

        if (!($dir = opendir($cacheDir))) {
            return -1;
        }
        while (false !== ($file = readdir($dir))) {
            if ((false !== ($fsize = filesize($cacheDir.'/'.$file))) && (preg_match('#^cron_(\d+)$#', $file, $m))) {
                if ($fsize && (intval($m[1]) > $lastRunTime)) {
                    $lastRunTime = intval($m[1]);
                } elseif (intval($m[1]) > $fn_progress) {
                    $fn_progress = intval($m[1]);
                }
            }
        }
        closedir($dir);

        // Stop if there're still running processes or $period is not finished yet
        if (!(($lastRunTime + $period < $nowTime) && ($fn_progress + $timeout < $nowTime))) {
            return 0;
        }

        // Create temporally file for flag
        if (false === ($temp = tempnam($cacheDir, 'tmp_cron_'))) {
            // Не смогли создать файл (???)
            return -1;
        }

        // Create flag
        $myFlagTime = $nowTime;
        $myFlagFile = 'cron_'.$myFlagTime;

        // Try to rename
        if (!rename($temp, $cacheDir.'/'.$myFlagFile)) {
            // Unsuccessfull, delete temp file, terminate (someone was before us)
            unlink($temp);

            return 0;
        }

        // Check if someone else already created his flag
        $fn_max = 0;
        if (!($dir = opendir($cacheDir))) {
            return -1;
        }
        while (false !== ($file = readdir($dir))) {
            if (preg_match("#^cron_(\d+)$#", $file, $m) && intval($m[1]) > $fn_max) {
                $fn_max = $m[1];
            }
        }
        closedir($dir);

        if ($fn_max > $myFlagFile) {
            // Someone was faster, terminate
            unlink($cacheDir.'/'.$myFlagFile);

            return 0;
        }

        //===========================================================================================
        // Fine, we created our own flag! Now we can run jobs, but within $timeout period!
        //===========================================================================================

        // Prepare a list of tasks that we should run after previous call
        $runList = [];

        //print ">> Last run: ".date("Y-m-d H:i:s", $lastRunTime)." ($lastRunTime)<br />\n";
        //print ">> Now: ".date("Y-m-d H:i:s", $nowTime)." ($nowTime)<br />\n";
        foreach ($this->config as $cronLine) {
            // Expand lines that uses lists
            $execList = [];
            foreach (explode(',', $cronLine['month']) as $xm) {
                foreach (explode(',', $cronLine['day']) as $xd) {
                    foreach (explode(',', $cronLine['hour']) as $xh) {
                        foreach (explode(',', $cronLine['min']) as $xmin) {
                            $execList[] = ['month' => $xm, 'day' => $xd, 'hour' => $xh, 'min' => $xmin];
                        }
                    }
                }
            }

            // Scan expanded lines and check if we should run plugin now
            //print "Exec lines: <pre>".var_export($execList, true)."</pre>";
            $flagRun = false;
            foreach ($execList as $rec) {
                // Use 'last execution time' as basis
                $at = localtime($lastRunTime, 1);

                // Zero seconds
                $at['tm_sec'] = 0;

                // Process line
                if ($rec['min'] !== '*') {
                    if ($rec['min'] < $at['tm_min']) {
                        $at['tm_hour']++;
                    }
                    $at['tm_min'] = $rec['min'];
                }
                if ($rec['hour'] !== '*') {
                    if ($rec['hour'] < $at['tm_hour']) {
                        $at['tm_mday']++;
                    }
                    $at['tm_hour'] = $rec['hour'];
                }
                if ($rec['day'] !== '*') {
                    if ($rec['day'] < $at['tm_mday']) {
                        $at['tm_mon']++;
                    }
                    $at['tm_mday'] = $rec['day'];
                }
                if ($rec['month'] !== '*') {
                    if ($rec['month'] < ($at['tm_mon'] + 1)) {
                        $at['tm_year']++;
                    }
                    $at['tm_mon'] = $rec['month'] - 1;
                }

                $newtime = mktime($at['tm_hour'], $at['tm_min'], 0, $at['tm_mon'] + 1, $at['tm_mday'], $at['tm_year'] + 1900);

                if ($newtime <= $nowTime) {
                    $flagRun = true;
                    break;
                }
            }

            if ($flagRun) {
                // Mark plugin as 'need to run'
                $runList[$cronLine['plugin'].'_'.$cronLine['handler']] = [$cronLine['plugin'], $cronLine['handler']];
            }
        }

        // Check if now we have anything to run
        if (count($runList)) {

            // Call handlers
            $trace = '';
            foreach ($runList as $num => $run) {
                $funcName = '';
                // Preload plugin and get function name
                if ($run[0] == 'core') {
                    // SYSTEM plugins
                    $funcName = 'core_cron';
                } else {
                    // COMMON plugins - load plugin for handler "CRON"
                    loadPlugin($run[0], 'cron');
                    $funcName = 'plugin_'.$run[0].'_cron';
                }
                // Try to call function and to pass parameter (handler)
                if (function_exists($funcName)) {
                    $t0 = $timer->stop(4);
                    call_user_func($funcName, $isSysCron, $run[1]);
                    $t1 = $timer->stop(4);

                    ngSYSLOG(['plugin' => 'core', 'item' => 'cronExecute'], ['action' => $run[0], 'list' => $run], null, [1, 'Execute cron job for '.sprintf('%7.4f', $t1 - $t0).' sec']);
                } else {
                    ngSYSLOG(['plugin' => 'core', 'item' => 'cronExecute'], ['action' => $run[0], 'list' => $run], null, [0, 'Function does not exists!']);
                }
                $trace .= 'Execute cron job ['.$run[0].'] action ['.$run[1]."]\n";
            }
        }

        // ====================================
        // All tasks are finished
        // ====================================

        // Mark flag as 'complete'
        if (false !== ($f = fopen($cacheDir.'/'.$myFlagFile, 'w'))) {
            fwrite($f, $trace);
            fwrite($f, 'OK');
            fclose($f);
        } else {
            return -1;
        }

        // ====================================
        // CleanUP old flags
        // ====================================

        if (!($dir = opendir($cacheDir))) {
            return -1;
        }
        while (false !== ($file = readdir($dir))) {
            if (preg_match("#^cron_(\d+)$#", $file, $m) && (intval($m[1]) < $myFlagTime)) {
                unlink($cacheDir.'/'.$file);
            }
        }
        closedir($dir);

        // !! DONE !! //
        return 1;
    }
}
