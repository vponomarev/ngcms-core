<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: extras.inc.php
// Description: 2z extras managment functions
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
// Get list of active modules
//
function get_active_array() {
	global $EXTRA_ACTIVE, $EXTRA_ACTIVE_loaded;

	if ($EXTRA_ACTIVE_loaded) { return $EXTRA_ACTIVE; }
	if (is_file(conf_pactive)) {
		@include conf_pactive;
		if (is_array($array)) { $EXTRA_ACTIVE = $array; }
	}
	$EXTRA_ACTIVE_loaded = 1;
	return $EXTRA_ACTIVE;
}

function status($module) {
	$array = get_active_array();
	if ($array['active'][$module]) { return true; }
	return false;
}

// Check if plugin is installed.
function plugin_is_installed($name) {
	$array = get_active_array();
	if ($array['installed'][$name]) { return true; }
	return false;
}


//
// Load plugins for specified action [ CAN BE USED FOR MANUAL PLUGIN PRELOAD ]
// * if $plugin name specified - manual PRELOAD mode is used:
//   Each plugin can call load_extras(<action>, <plugin name>) for preloading
//   plugin for action
//
function load_extras($action, $plugin = '') {
	global $EXTRA_ACTIVATED, $EXTRA_FILES_LOADED, $timer;

	$loadedCount = 0;
	$array = get_active_array();
	// Find extras for selected action
	if (isset($array['actions'][$action]) && is_array($array['actions'][$action])) {
		// There're some modules
		foreach ($array['actions'][$action] as $key => $value) {
			// Skip plugins in manual mode
			if ($plugin && ($key != $plugin))
				continue;

			// Do only if we already loaded this file
			if (!isset($EXTRA_FILES_LOADED[$value])) {
				// Try to load file. First check if it exists
				if (is_file(extras_dir.'/'.$value)) {
				        $tX = $timer->stop(4);
					include_once extras_dir.'/'.$value;
					$timer->registerEvent('func LOAD_EXTRAS ('.$action.'): preloaded file "'.$value.'" for '.($timer->stop(4) - $tX)." sec");
					$EXTRA_FILES_LOADED[$value] = 1;
					$loadedCount ++;
				} else {
					$timer->registerEvent('func LOAD_EXTRAS ('.$action.'): CAN\'t preload file that doesn\'t exists: "'.$value.'"');
				}
				$EXTRA_ACTIVATED[$key] = 1;
			}
		}
	}
	// Return count of loaded plugins
	return $loadedCount;
}

//
// Load plugin's library
//
function loadPluginLibrary($plugin, $libname = '') {
	global $EXTRA_ACTIVATED, $timer;

	$list = get_active_array();

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
	global $acts, $timer;

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

//
// Extras managment
//

// get parameter value
function extra_get_param($module, $var) {
  global $EXTRA_CONFIG, $EXTRA_CONFIG_loaded;

  if (!$EXTRA_CONFIG_loaded) { plugins_load_config(); }
  return $EXTRA_CONFIG[$module][$var];
}

// set parameter value
function extra_set_param($module, $var, $value) {
  global $EXTRA_CONFIG, $EXTRA_CONFIG_loaded;
  //print "extra_set_param('$module', '$var', '$value')<br> [load: $EXTRA_CONFIG_loaded]\n";

  if (!$EXTRA_CONFIG_loaded) { plugins_load_config(); }
  $EXTRA_CONFIG[$module][$var] = $value;
  return 1;
}

// commit changes
function extra_commit_changes() {
  global $EXTRA_CONFIG, $EXTRA_CONFIG_loaded;

  if (!$EXTRA_CONFIG_loaded) { plugins_load_config(); }

  $config = @fopen(conf_pconfig,'w');
  if ($config) {
          fwrite($config, serialize($EXTRA_CONFIG));
          fclose($config);
          return 1;
  } else {
          return 0;
  }
}

// load common config file
function plugins_load_config() {
  global $EXTRA_CONFIG, $EXTRA_CONFIG_loaded;

	if ($EXTRA_CONFIG_loaded) { return 1; }
  $config = @fopen(conf_pconfig,'r');
  if ($config && filesize(conf_pconfig)) {
          $content = fread($config, filesize(conf_pconfig));
          $EXTRA_CONFIG = unserialize($content);
          $EXTRA_CONFIG_loaded = 1;
          fclose($config);
          return 1;
  } else {
          return 0;
  }
}

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

