<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: extras.inc.php
// Description: NGCMS extras managment functions
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// #================================================================#
// # Class template definition                                      #
// #================================================================#

// CLASS DEFINITION: news filter
class NewsFilter {
	// ### Add news interceptor ###
	// Form generator
	function addNewsForm(&$tvars) { return 1;}

	// Adding executor [done BEFORE actual add and CAN block adding ]
	function addNews(&$tvars, &$SQL) { return 1;}

	// Adding notificator [ after successful adding ]
	function addNewsNotify(&$tvars, $SQL, $newsid) { return 1;}

	// ### Edit news interceptor ###
	// Form generator
	function editNewsForm($newsID, $SQLnews, &$tvars) { return 1; }

	// Edit executor  [done BEFORE actual edit and CAN block editing ]
	function editNews($newsID, $SQLnews, &$SQLnew, &$tvars) { return 1; }

	// Edit Notifier [ adfter successful editing ]
	function editNewsNotify($newsID, $SQLnews, &$SQLnew, &$tvars) { return 1; }

	// ### Delete news interceptor ###
	// Delete news call
	function deleteNews($newsID, $SQLnews) { return 1; }

	// Delete news notifier [ after news is deleted ]
	function deleteNewsNotify($newsID, $SQLnews) { return 1; }

	// ### Mass modify news interceptor ###
	// Mass modify news call
	function massModifyNews($idList, $setValue, $currentData) { return 1; }

	// Mass modify news call [ after news are modified ]
	function massModifyNewsNotify($idList, $setValue, $currentData) { return 1; }

	// ### SHOW news interceptor ###
	// Show news call :: preprocessor (call directly after news fetch)
	// Mode - news show mode [ array ]
	// 'style'   =>
	//   * short   - short news show
	//   * full    - full news show
	//   * export  - export news ( print / some plugins and so on )
	// 'plugin'  => if is called from plugin - ID of plugin
	// 'emulate' => flag if emulation mode is used [ for example, for preview ]
	// 'nCount'  => news if order (1,2,...) for SHORT news
	function showNewsPre($newsID, &$SQLnews, $mode = array()) { return 1; }

	// Show news call :: processor (call after all processing is finished and before show)
	function showNews($newsID, $SQLnews, &$tvars, $mode = array()) { return 1; }

	// Behaviour before/after starting showing any news template
	// $newsID	- ID of the news to show
	// $SQLnews	- SQL row of the news
	// $mode	- array with config params
	//  style	- working mode ( 'short' / 'full' / 'export' )
	//  num		- number in order (for short list)
	//  limit	- show limit in order (for short list)
	function onBeforeNewsShow($newsID, $SQLnews, $mode = array()) { return 1; }
	function onAfterNewsShow ($newsID, $SQLnews, $mode = array()) { return 1; }

	// Behaviour before/after showing news template
	// $mode	- calling mode (may be 'short' or 'full')
	function onBeforeShow($mode) { return 1; }
	function onAfterShow($mode)  { return 1; }

};

// CLASS DEFINITION: static filter
class StaticFilter {
	// ### Add static interceptor ###
	// Form generator
	function addStaticForm(&$tvars) { return 1;}

	// Adding executor
	function addStatic(&$tvars, &$SQL) { return 1;}

	// ### Edit static interceptor ###
	// Form generator
	function editStaticForm($staticID, $SQLnews, &$tvars) { return 1; }

	// Edit executor
	function editStatic($staticID, $SQLstatic, &$SQLnew, &$tvars) { return 1; }

	// ### Delete static page interceptor ###
	// Delete static call
	function deleteStatic($staticID, $SQLnews) { return 1; }

	// ### SHOW static interceptor ###
	// Show static call :: preprocessor (call directly after news fetch)
	// Mode - news show mode [ array ]
	// 'plugin'  => if is called from plugin - ID of plugin
	// 'emulateMode' =>  flag if emulation mode is used [ for example, for preview ]
	function showStaticPre($staticID, &$SQLstatic, $mode) { return 1; }

	// Show static call :: processor (call after all processing is finished and before show)
	function showStatic($staticID, $SQLstatic, &$tvars, $mode) { return 1; }
}



//
// Get/Load list of active plugins & required files
//
function getPluginsActiveList(){
	global $PLUGINS;

	if ($PLUGINS['active:loaded']) { return $PLUGINS['active']; }
	if (is_file(conf_pactive)) {
		@include conf_pactive;
		if (is_array($array)) { $PLUGINS['active'] = $array; }
	}
	$PLUGINS['active:loaded'] = 1;
	return $PLUGINS['active'];
}

//
// Report if plugin is active
//
function getPluginStatusActive($pluginID){
	$array = getPluginsActiveList();
	if ($array['active'][$pluginID]) { return true; }
	return false;
}

//
// Report if plugin is installed
//
function getPluginStatusInstalled($pluginID) {
	$array = getPluginsActiveList();
	if ($array['installed'][$pluginID]) { return true; }
	return false;
}

//
// Load plugins for specified action [ CAN BE USED FOR MANUAL PLUGIN PRELOAD ]
// * if $plugin name specified - manual PRELOAD mode is used:
//   Each plugin can call load_extras(<action>, <plugin name>) for preloading
//   plugin for action
//
function load_extras($action, $plugin = '') {
	global $PLUGINS, $timer;

	$loadedCount = 0;
	$array = getPluginsActiveList();
	// Find extras for selected action
	if (isset($array['actions'][$action]) && is_array($array['actions'][$action])) {
		// There're some modules
		foreach ($array['actions'][$action] as $key => $value) {
			// Skip plugins in manual mode
			if ($plugin && ($key != $plugin))
				continue;

			// Do only if we already loaded this file
			if (!isset($PLUGINS['loaded:files'][$value])) {
				// Try to load file. First check if it exists
				if (is_file(extras_dir.'/'.$value)) {
				        $tX = $timer->stop(4);
					include_once extras_dir.'/'.$value;
					$timer->registerEvent('func LOAD_EXTRAS ('.$action.'): preloaded file "'.$value.'" for '.($timer->stop(4) - $tX)." sec");
					$PLUGINS['loaded:files'][$value] = 1;
					$loadedCount ++;
				} else {
					$timer->registerEvent('func LOAD_EXTRAS ('.$action.'): CAN\'t preload file that doesn\'t exists: "'.$value.'"');
				}
				$PLUGINS['loaded'][$key] = 1;
			}
		}
	}
	// Return count of loaded plugins
	return $loadedCount;
}


//
// Load plugin [ Same behaviour as for load_extras ]
//
function loadPlugin($pluginName, $actionList = '*') {
	global $PLUGINS, $timer;

	$plugList = getPluginsActiveList();

	// Don't load if plugin is not activated
	if (!$plugList['active'][$pluginName])
		return false;

	// Scan all available actions and preload plugin's file if needed
	foreach ($plugList['actions'] as $aName => $pList) {
		if (isset($pList[$pluginName]) &&
			((is_array($actionList) && in_array($aName, $actionList)) ||
			(!is_array($actionList) && (($actionList == '*')||($actionList == $aName))))
		   ) {
			// Yes, we should load this file. If it's not loaded earlier
			$pluginFileName = $pList[$pluginName];

			if (!isset($PLUGINS['loaded:files'][$pluginFileName])) {
				// Try to load file. First check if it exists
				if (is_file(extras_dir.'/'.$pluginFileName)) {
				        $tX = $timer->stop(4);
					include_once extras_dir.'/'.$pluginFileName;
					$timer->registerEvent('func loadPlugin ('.$pluginName.'): preloaded file "'.$pluginFileName.'" for '.($timer->stop(4) - $tX)." sec");
					$PLUGINS['loaded:files'][$value] = 1;
					$loadedCount ++;
				} else {
					$timer->registerEvent('func loadPlugin ('.$pluginName.'): CAN\'t preload file that doesn\'t exists: "'.$pluginFileName.'"');
				}
			}
		}
	}

	$PLUGINS['loaded'][$key] = 1;
	return true;
}

//
// Load plugin's library
//
function loadPluginLibrary($plugin, $libname = '') {
	global $timer;

	$list = getPluginsActiveList();

	// Check if we know about this plugin
	if (!isset($list['active'][$plugin])) return false;

	// Check if we need to load all libs
	if (!$libname) {
		foreach ($list['libs'][$plugin] as $id => $file) {
			$tX = $timer->stop(4);
			include_once extras_dir.'/'.$list['active'][$plugin].'/'.$file;
			$timer->registerEvent('loadPluginLibrary: '.$plugin.'.'.$id.' ['.$file.'] for '.($timer->stop(4) - $tX)." sec");
		}
		return true;
	} else {
		if (isset($list['libs'][$plugin][$libname])) {
			include_once extras_dir.'/'.$list['active'][$plugin].'/'.$list['libs'][$plugin][$libname];
			return true;
		}
		return false;
	}
}


function add_act($item, $function, $arguments = 1, $priority = 0) {
	global $acts;

	if ($acts[$item][$priority]) {
		foreach($acts[$item][$priority] as $act) {
			if ($act['function'] == $function) {
				return true;
			}
		}
	}

	$acts[$item][$priority][] = array('function' => $function, 'arguments' => $arguments);
	return true;

}

function exec_acts($item, $sth = '', $arg1 = NULL, $arg2 = NULL, $arg3 = NULL, $arg4 = NULL) {
	global $acts, $timer, $SYSTEM_FLAGS;

	// Do not run action if it's disabled
	if ($SYSTEM_FLAGS['actions.disabled'][$item]) {
		$timer->registerEvent('disabled EXEC_ACTS ('.$item.')');
		return;
	}
	
	$timer->registerEvent('exec EXEC_ACTS ('.$item.')');

	// Make module preload if needed
	load_extras($item);

	// process params
	$args			= array_slice(func_get_args(), 2);
	$all_arguments	= array_merge(array($sth), $args);

	if (!isset($acts[$item]) || !$acts[$item]) {
		return $sth;
	} else {
		uksort($acts[$item], "strnatcasecmp");
	}

	foreach ($acts[$item] as $priority => $functions) {
		if (!is_null($functions)) {
			foreach($functions as $func) {

			        $tX = $timer->stop(4);

				if ($func['arguments'] == 0) {
					$sth.=call_user_func($func['function']);
				}
				if ($func['arguments'] == 1) {
					$sth.=call_user_func($func['function'],$sth);
				}
				if ($func['arguments'] == 2) {
					$sth.=call_user_func($func['function'],$sth, &$arg1);
				}
				if ($func['arguments'] == 3) {
					$sth.=call_user_func($func['function'],$sth, &$arg1, &$arg2);
				}
				if ($func['arguments'] == 4) {
					$sth.=call_user_func($func['function'],$sth, &$arg1, &$arg2, &$arg3);
				}
				if ($func['arguments'] == 4) {
					$sth.=call_user_func($func['function'],$sth, &$arg1, &$arg2, &$arg3, &$arg4);
				}
				$timer->registerEvent('func EXEC_ACTS ('.$item.'): call function "'.$func['function'].'" ['.$func['arguments'].' params] for '.($timer->stop(4) - $tX)." sec");
			}
		}
	}
	return $sth;
}


// Disable desired action from plugin
function actionDisable($action) {
	global $SYSTEM_FLAGS;
	$SYSTEM_FLAGS['actions.disabled'][$action] = 1;
	return;
}

// =========================================================
// PLUGINS: parameters managment
// =========================================================

//
// Get variable
//
function pluginGetVariable($pluginID, $var) {
	global $PLUGINS;

	if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig())
		return false;

	return $PLUGINS['config'][$pluginID][$var];
}
// OLD
function extra_get_param($module, $var) { return pluginGetVariable($module, $var); }


//
// Set variable
//
function pluginSetVariable($pluginID, $var, $value){
	global $PLUGINS;

	if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig())
		return false;

	$PLUGINS['config'][$pluginID][$var] = $value;
	return true;
}
// OLD
function extra_set_param($module, $var, $value) { return pluginSetVariable($module, $var, $value); }

//
// Save configuration parameters of plugins (should be called after pluginSetVariable)
//
function pluginsSaveConfig(){
	global $PLUGINS;

	if (!$PLUGINS['config:loaded'] && !pluginsLoadConfig())
		return false;

	//
	if (!($fconfig = @fopen(conf_pconfig, 'w')))
		return false;

	fwrite($fconfig, serialize($PLUGINS['config']));
	fclose($fconfig);
	return true;
}
// OLD
function extra_commit_changes() { pluginsSaveConfig(); }

//
// Load configuration variables for plugins
//
function pluginsLoadConfig() {
  global $PLUGINS;

	if ($PLUGINS['config:loaded']) { return 1; }
	$fconfig = @fopen(conf_pconfig,'r');
	if ($fconfig) {
		if (filesize(conf_pconfig)) {
			$content = fread($fconfig, filesize(conf_pconfig));
		} else {
			$content = serialize(array());
		}
        $PLUGINS['config'] = unserialize($content);
    	$PLUGINS['config:loaded'] = 1;
        fclose($fconfig);
        return true;
	} else {
		// File doesn't exists. Mark as `loaded`
		$PLUGINS['config'] = array();
		$PLUGINS['config:loaded'] = 1;
	}
	return false;
}

// OLD
function plugins_load_config() { return pluginsLoadConfig(); }


//
// Load 'version' file from plugin directory
//
function plugins_load_version_file($filename) {

	// config variables & function init
	$config_params = array ( 'id', 'name', 'version', 'acts', 'file', 'config', 'install', 'deinstall', 'management', 'type', 'description', 'author', 'author_uri', 'permanent', 'library', 'actions' );
	$required_params = array ('id', 'name', 'version', 'type');
	$list_params = array( 'library', 'actions' );
	$ver = array();

	foreach ($list_params as $id)
		$ver[$id] = array();

	// open file
	if (!($file = @fopen($filename,'r'))) { return false; }

	// read file
	while (!feof($file)) {
		$line = fgets($file);
		if (preg_match("/^(.+?) *\: *(.+?) *$/i",$line, $r) == 1) {
				$key = rtrim(strtolower($r[1]));
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
	$ver['acts'] = isset($ver['acts'])?str_replace(' ','',$ver['acts']):'';
	if (isset($ver['permanent']) && ($ver['permanent'] == 'yes')) { $ver['permanent'] = 1; } else { $ver['permanent'] = 0; }

	// check for filling required params
	foreach ($required_params as $v) { if (!$ver[$v]) { return false; } }

	// check for library/actions filling
	foreach (array('library', 'actions') as $key) {
		$list = $ver[$key];
		$ver[$key] = array();

		foreach ($list as $rec) {
			if (!$rec) continue;
			list ($ids, $fname) = explode(';', $rec);

			$ids = trim($ids);
			$fname = trim($fname);

			if (!$ids || !$fname) return false;
			$idlist = explode(',', $ids);
			foreach ($idlist as $entry)
				if (trim($entry))
					$ver[$key][trim($entry)] = $fname;
		}
	}
	return $ver;
}

//
// Get a list of installed plugins
//
function get_extras_list() {
	global $timer;

	$timer->registerEvent('@ get_extras_list() called');
	// open directory
	$handle = @opendir(extras_dir);
	$extras = array();
	// load list of extras
	while (false != ($dir = @readdir($handle))) {
		$edir = extras_dir.'/'.$dir;
		// Skip special dirs ',' and '..'
		if (($dir == '.')||($dir == '..')||(!is_dir($edir))) { continue; }

		// Check 'version' file
		if (!is_file($edir.'/version')) { continue; }

		// Load version file
		$ver = plugins_load_version_file($edir.'/version');
		if (!is_array($ver)) { continue; }

		// fill fully file path (within 'plugins' directory)
		$ver['dir'] = $dir;

		// Good, version file is successfully loaded, add data into array
		$extras[$ver['id']] = $ver;
		//array_push($extras, $ver);
	}
	return $extras;
}

//
// Add plugin's page
//
function register_plugin_page($pname, $mode, $func_name, $show_template = 1) {
	global $PPAGES;

	if (!isset($PPAGES[$pname]) || !is_array($PPAGES[$pname])) {
		$PPAGES[$pname] = array();
	}
	$PPAGES[$pname][$mode] = array('func' => $func_name, 'mode' => $mode);
}

//
// Add item into list of additional HTML meta tags
//
function register_htmlvar($type, $data) {
	global $EXTRA_HTML_VARS;

	// Check for duplicate

	$EXTRA_HTML_VARS[] = array('type' => $type, 'data' => $data);
}

//
// Add new stylesheet into template
//
function register_stylesheet($url) {
	global $EXTRA_CSS;
	register_htmlvar('css', $url);
}

//
// Get configuration directory for plugin (and create it if needed)
//
function get_plugcfg_dir($plugin) {

 $dir = confroot.'extras';
 if ((!is_dir($dir)) && (!mkdir($dir))) {
	print "Can't create config directory for plugins. Please, check permissions for engine/conf/ dir<br/>\n";
	return '';
 }

 $dir .= '/'.$plugin;
 if (!is_dir($dir)) {
  if (!mkdir($dir)) {
  	print "Can't create config directory for plugin '$plugin'. Please, check permissions for engine/conf/plugins/ dir<br>\n";
  	return '';
  }
 }
 return $dir;
}

//
// Get plugin cache dir
//
function get_plugcache_dir($plugin) {
 global $multiDomainName, $multimaster;

 $dir = root.'cache/';
 if ($multiDomainName && $multimaster && ($multiDomainName != $multimaster)) {
 	$dir .= 'multi/';
 	if ((!is_dir($dir)) && (!mkdir($dir))) {
 		print "Can't create multi cache dir!<br>\n";
 		return '';
 	}
 }
 if ($plugin) {
	 $dir .= $plugin.'/';
	 if ((!is_dir($dir)) && (!mkdir($dir))) {
		print "Can't create cache for plugin '$plugin'<br>\n";
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
function cacheStoreFile($fname, $data, $plugin = '', $hugeMode = 0) {

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
	if (($fn = @fopen($dir.$fname, 'w')) == FALSE) {
		return false;
	}

	// Try to make exclusive file lock. Return if failed
	if (@flock($fn, LOCK_EX) == FALSE) {
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
function cacheRetrieveFile($fname, $expire, $plugin = '') {

	// Try to get cache directory name. Return false if it's not possible
	if (!($dir = get_plugcache_dir($plugin))) {
		return false;
	}

	// Try to open file with data
	if (($fn = @fopen($dir.$fname, 'r')) == FALSE) {
		return false;
	}

	// Check if file is expired. Return if it's so.
	$stat = fstat($fn);
	if (!is_array($stat) || ($stat[9]+$expire<time())) {
		return false;
	}

	// Try to make shared file lock. Return if failed
	if (@flock($fn, LOCK_SH) == FALSE) {
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

//
// Routine that helps plugin to locate template files. It checks if required file
// exists in "global template" dir
//
// $tname		- template names (in string array)
// $plugin		- plugin name
// $localSource	- flag if function should work in "local only" mode, i.e.
//				  that all files are in own plugin dir
// $skin - skin name in plugin dir ( plugins/PLUGIN/tpl/skin/ )

function locatePluginTemplates($tname, $plugin, $localsource = 0, $skin = '') {
	global $config;

	$tpath = array();
	foreach ($tname as $fn) {
		$fnc = (substr($fn, 0, 1) == ':')?substr($fn,1):($fn.'.tpl');
		if (!$localsource && is_readable(tpl_site.'plugins/'.$plugin.'/'.$fnc)) {
			$tpath[$fn] = tpl_site.'plugins/'.$plugin.'/';
			$tpath['url:'.$fn] = tpl_url.'/plugins/'.$plugin;
		} else {
			$tpath[$fn] = extras_dir.'/'.$plugin.'/tpl/'.($skin?'skins/'.$skin.'/':'');
			$tpath['url:'.$fn] = admin_url.'/plugins/'.$plugin.'/tpl'.($skin?'/skins/'.$skin:'');
		}
	}
	return $tpath;
}



// Register system filter
function register_filter($group, $name, $instance) {
 global $PFILTERS;
 $PFILTERS[$group][$name] = $instance;
}


//
// Check if we have handler for specified action
//
function checkLinkAvailable($pluginName, $handlerName) {
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
function generateLink($pluginName, $handlerName, $params = array(), $xparams = array(), $intLink = false){
	global $UHANDLER;

	$flagCommon = false;

	// Check if we have handler for requested plugin
	if (!isset($UHANDLER->hPrimary[$pluginName][$handlerName])) {
		// No handler. Let's use "COMMON WAY"
		$params['plugin'] = $pluginName;
		$params['handler'] = $handlerName;

		$pluginName = 'core';
		$handlerName = 'plugin';
		$flagCommon = true;
	}

	// Fetch identity [ array( recNo, primaryFlagStatus ) ]
	$hId = $UHANDLER->hPrimary[$pluginName][$handlerName];
	// Fetch record
	$hRec = $UHANDLER->hList[$hId[0]];

	// Check integrity
	if (!is_array($hRec) || !is_array($hRec['rstyle']['genrMAP']))
		return false;

	// First: find block dependency
	$depMAP = array();
	foreach ($hRec['rstyle']['genrMAP'] as $rec) {
		// If dependent block & this is variable & no rec in $depMAP - save
		if ($rec[2] && $rec[0] && !isset($depMAP[$rec[2]]))
			$depMAP[$rec[2]] = $rec[1];
	}


	// Now we can generate URL
	$url = array();
	foreach ($hRec['rstyle']['genrMAP'] as $rec) {
		if (!$rec[2] || ($rec[2] && isset($params[$depMAP[$rec[2]]]))) {
			$url[] = $rec[0]?$params[$rec[1]]:$rec[1];
		}
	}

	// Add params in case of common mode
	$uparams = array();
	if ($flagCommon) {
		unset($params['plugin']);
		unset($params['handler']);
		$xparams = array_merge($params, $xparams);
	}

	foreach ($xparams as $k => $v) {
		if (($k != 'plugin') && ($k != 'handler'))
			$uparams[]= $k.'='.urlencode($v);
	}

	return join('', $url).(count($uparams)?'?'.join('&'.($intLink?'amp;':''), $uparams):'');
}


//
// Generate link to page
//
function generatePageLink($paginationParams, $page) {
	//print "generatePageLink(".var_export($paginationParams, true).";".$page.")<br/>\n";
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
	return generateLink($paginationParams['pluginName'], $paginationParams['pluginHandler'], $lparams, $lxparams);
}


//
//
//
function _MASTER_defaultRUN($pluginName, $handlerName, $params, &$skip) {
	global $PPAGES, $lang, $SYSTEM_FLAGS, $CurrentHandler;
	// Preload requested plugin
	loadPlugin($pluginName, 'ppages');

	$pcall = $PPAGES[$pluginName][$handlerName];

	if (is_array($pcall) && function_exists($pcall['func'])) {
		// Make page title
		$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_plugin'];

		// Report current handler config
		$CurrentHandler = array('pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params);
		call_user_func($pcall['func'], $params);
	} else {
		msg(array('type' => 'error', 'text' => str_replace(array('{handler}', '{plugin}'), array($handlerName, $pluginName), $lang['plugins.nohadler'])));
	}
}

function _MASTER_URL_PROCESSOR($pluginName, $handlerName, $params, &$skip) {
	global $PPAGES, $lang, $CurrentHandler;

	//print "## PLUGIN CALL: <b> (".$pluginName.")</b><br/>\n";
	//print "<pre>".var_export($params, tru)."</pre><br/>\n";

	// Check for predefined plugins call
	switch ($pluginName) {
		case 'news':
			$CurrentHandler = array('pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params);
			switch ($handlerName) {
				default:
					include_once root.'includes/news.php';
					showNews($handlerName, $params);
			}
			break;

		case 'static':
			$CurrentHandler = array('pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params, 'FFC' => $SKIP['FFC']);
			switch ($handlerName) {
				default:
					include_once root.'includes/static.php';
					$cResult = showStaticPage(array('id' => intval($params['id']), 'altname' => $params['altname'], 'FFC' => $skip['FFC']));
					if (!$cResult && $skip['FFC']) {
						$skip['fail'] = 1;
					}
			}
			break;

		case 'search':
			$CurrentHandler = array('pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params);
			switch ($handlerName) {
				default:
					include_once root.'includes/search.php';
					search_news();
			}
			break;


		case 'core':
			$CurrentHandler = array('pluginName' => $pluginName, 'handlerName' => $handlerName, 'params' => $params);
			switch ($handlerName) {
				case 'plugin':
					// Set our own params $pluginName and $handlerName and pass it to default handler
					_MASTER_defaultRUN($params['plugin'], $params['handler'], $params, $skip);
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
			_MASTER_defaultRUN($pluginName, $handlerName, $params, $skip);
	}
}
