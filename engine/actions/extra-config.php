<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: extra-config.php
// Description: Plugin managment
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root.'includes/inc/extraconf.inc.php';
@include_once root.'includes/inc/extrainst.inc.php';


// ==============================================================
//  Main module code
// ==============================================================

// Load lang files
$lang	=	LoadLang('extra-config', 'admin');


// Load plugin list
$extras	=	pluginsGetList();


// Load passed variables:
// ID of called plugin
$plugin = $_REQUEST['plugin'];

// Type of script to call  ( install / deinstall / config )
$stype = isset($_REQUEST['stype'])?$_REQUEST['stype']:'';

if (!is_array($extras[$plugin])) {
		print "There is no such plugin<br>\n";
} else {
	//
	// Call 'install'/'deinstall' script if it's requested. Else - call config script.
	if (($stype != 'install')&&($stype != 'deinstall')) { $stype = 'config'; }

	// Fetch the name of corresponding configuration file
	$cfg_file = extras_dir.'/'.$extras[$plugin]['dir'].'/'.$extras[$plugin][$stype];

	// Check if such type of script is configured in plugin & exists
	if (is_array($extras[$plugin]) && ($extras[$plugin][$stype]) && is_file($cfg_file)) {
		//
		// Include required script file
		@include extras_dir.'/'.$extras[$plugin]['dir'].'/'.$extras[$plugin][$stype];

		//
		// Run install function if it exists in file
		if (($stype == 'install') && function_exists('plugin_'.$plugin.'_install')) {
			call_user_func('plugin_'.$plugin.'_install', ($_REQUEST['action'] == 'commit')?'apply':'confirm');
		}

	} else {
		$tpl -> template('nomodule', tpl_actions.'extra-config');
		$tvars['vars'] = array('action' => $lang[$stype.'_text'], 'action_text' => $lang['nomod_'.$stype], 'plugin' => $plugin, 'php_self' => $PHP_SELF);
		$tpl -> vars('nomodule', $tvars);
		echo $tpl -> show('nomodule');
	}
}