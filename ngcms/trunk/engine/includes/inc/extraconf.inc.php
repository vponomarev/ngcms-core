<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: extraconf.inc.php
// Description: Plugin configuration manager
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Switch module ON
//
function switch_on($module) {
	global $EXTRA_ACTIVE;
	
	// Load plugins list
	$extras = get_extras_list();
	if (!is_array($extras)) { return false; }
	if (!$extras[$module]) { return false; }

	// Turn plugin ON
	$activated = get_active_array();
	
	// Mark module as active
	$activated['active'][$module] = $extras[$module]['dir'];
	
	// Mark module to be activated in all listed actions
	if (isset($extras[$module]['acts']) && isset($extras[$module]['file'])) {
		foreach (explode(',',$extras[$module]['acts']) as $act) {
				$activated['actions'][$act][$module] = $extras[$module]['dir'].'/'.$extras[$module]['file'];
		}
	}

	foreach ($extras[$module]['actions'] as $act => $file) {
		$activated['actions'][$act][$module] = $extras[$module]['dir'].'/'.$file;
	}

	$activated['libs'][$module] = $extras[$module]['library'];
	// update active extra list in memory
	$EXTRA_ACTIVE = $activated;
	return save_info($activated);
}

function switch_off($module) {
	global $EXTRA_ACTIVE;

	$activated = get_active_array();
	unset($activated['active'][$module]);
	
	foreach ($activated['actions'] as $key => $value) {
		if ($activated['actions'][$key][$module]) {
				unset($activated['actions'][$key][$module]);
		}
	}

	$EXTRA_ACTIVE = $activated;
	return save_info($activated);
}


//
// Mark plugin as installed
//
function plugin_mark_installed($plugin) {
	
	// Load activated list
	$activated = get_active_array();

	// return if already installed
	if ($activated['installed'][$plugin]) {
		return 1;
	}

	$activated['installed'][$plugin] = 1;
	$EXTRA_ACTIVE = $activated;
	return save_info($activated);
}

//
// Mark plugin as deinstalled
//
function plugin_mark_deinstalled($plugin) {
	
	// Load activated list
	$activated = get_active_array();

	// return if already installed
	if (!$activated['installed'][$plugin]) {
		return 1;                             
	}

	$activated['installed'][$plugin] = '';
	$EXTRA_ACTIVE = $activated;
	return save_info($activated);
}



function save_info($array) {

	$content = '<?php $array = '. var_export($array,1) .'; ?>';
	return write_file($content);
}

function write_file($content) {

	if (is_file(conf_pactive)) {
		$file = fopen(conf_pactive, "w");
		fwrite($file, $content);
		fclose($file);

		return true;
	}
	else {
		return false;
	}
}

