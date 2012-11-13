<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: extras.php
// Description: List plugins
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


// ==============================================================
//  Module functions
// ==============================================================
@include_once root.'includes/inc/extraconf.inc.php';
@include_once root.'includes/inc/httpget.inc.php';


// ==============================================================
//  Main module code
// ==============================================================

$lang	=	LoadLang('extras', 'admin');
$extras	=	pluginsGetList();
ksort($extras);

// ==============================================================
// Load a list of updated plugins from central repository
// ==============================================================
$repoPluginInfo = repoSync();

// ==============================================================
// Process enable request
// ==============================================================
$enable  = isset($_REQUEST['enable'])?$_REQUEST['enable']:'';
$disable = isset($_REQUEST['disable'])?$_REQUEST['disable']:'';


if (isset($_REQUEST['debugVars']) && $_REQUEST['debugVars']) {
	plugins_load_config();
	print "<pre>".var_export($PLUGINS['config'], true)."</pre>";
	exit;
}

// Check for security token
if ($enable || $disable) {
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.extras'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras', 'ds_id' => $id), array('action' => 'modify'), null, array(0, 'SECURITY.TOKEN'));
		exit;
	}
}

if ($enable) {
	if (pluginSwitch($enable, 'on')) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $enable)), null, array(1, ''));
		msg(array("text" => sprintf($lang['msgo_is_on'], $extras[$enable]['name'])));
	} else {
		// generate error message
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $enable)), null, array(0, 'ERROR: '.$enable));
		msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_on'], $extras[$id]['name'])));
	}
}

if ($disable) {
	if ($extras[$disable]['permanent']) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_off', 'list' => array('plugin' => $disable)), null, array(0, 'ERROR: PLUGIN is permanent '.$disable));
		msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
	} else {
		if (pluginSwitch($disable, 'off')) {
			ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_off', 'list' => array('plugin' => $disable)), null, array(1, ''));
			msg(array("text" => sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
		} else {
			ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $disable)), null, array(0, 'ERROR: '.$disable));
			msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
		}
	}
}

$entries = '';
$tpl -> template('entries', tpl_actions.$mod);

$pCount = array (0 => 0, 1 => 0, 2 => 0, 3 => 0);

foreach($extras as $id => $extra) {
	if (!isset($extra['author_uri'])) { $extra['author_uri'] = ''; }
	if (!isset($extra['author'])) { $extra['author'] = 'Unknown'; }

	$tvars['vars'] = array(
		'version'		=>	$extra['version'],
		'description'	=>	isset($extra['description'])?$extra['description']:'',
		'author_url'	=>	($extra['author_uri'])?('<a href="'.((strpos($extra['author_uri'], '@') !==FALSE)?'mailto:':'').$extra['author_uri'].'">'.$extra['author']."</a>"):$extra['author'],
		'author'		=>	$extra['author'],
		'id'			=>	$extra['id'],
		'style'			=>	getPluginStatusActive($id)?'pluginEntryActive':'pluginEntryInactive',
		'readme'		=>	file_exists(extras_dir.'/'.$id.'/readme')&&filesize(extras_dir.'/'.$id.'/readme')?('<a href="'.admin_url.'/includes/showinfo.php?mode=plugin&amp;item=readme&amp;plugin='.$id.'" target="_blank" title="'.$lang['entry.readme'].'"><img src="'.skins_url.'/images/readme.png" width=16 height=16/></a>'):'',
		'history'		=>	file_exists(extras_dir.'/'.$id.'/history')&&filesize(extras_dir.'/'.$id.'/history')?('<a href="'.admin_url.'/includes/showinfo.php?mode=plugin&amp;item=history&amp;plugin='.$id.'" target="_blank" title="'.$lang['entry.history'].'"><img src="'.skins_url.'/images/history.png" width=16 height=16/></a>'):''
	);

	if (isset($repoPluginInfo[$extra['id']]) && ($repoPluginInfo[$extra['id']][1] > $extra['version'])) {
		$tvars['vars']['new']		= '<a href="http://ngcms.ru/sync/plugins.php?action=jump&amp;id='.$extra['id'].'.html" title="'.$repoPluginInfo[$extra['id']][1].'"target="_blank"><img src="'.skins_url.'/images/new.gif" width=30 height=15/></a>';
	} else {
		$tvars['vars']['new'] = '';
	}

	$tvars['vars']['type'] = in_array($extra['type'], array('plugin', 'module', 'filter', 'auth', 'widget', 'maintanance'))?$lang[$extra['type']]:"Undefined";

	//
	// Check for permanent modules
	//
	if (($extra['permanent'])&&(!getPluginStatusActive($id))) {
		// turn on
		if (pluginSwitch($id, 'on')) {
			msg(array("text" => sprintf($lang['msgo_is_on'], $extra['name'])));
		} else {
			// generate error message
			msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_on'], $extra['name'])));
		}
	}

	$needinstall = 0;
	$tvars['vars']['install'] = '';
	if (getPluginStatusInstalled($extra['id'])) {
		if (isset($extra['deinstall']) && $extra['deinstall'] && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['deinstall'])) {
			$tvars['vars']['install'] = '<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'&amp;stype=deinstall">'.$lang['deinstall'].'</a>';
		}
	} else {
		if (isset($extra['install']) && $extra['install'] && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['install'])) {
			$tvars['vars']['install'] = '<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'&amp;stype=install">'.$lang['install'].'</a>';
			$needinstall = 1;
		}
	}

	$tvars['vars']['url'] = (isset($extra['config']) && $extra['config'] && (!$needinstall) && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['config']))?'<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'">'.$extra['name'].'</a>' : $extra['name'];
	$tvars['vars']['link'] = (getPluginStatusActive($id) ? '<a href="'.$PHP_SELF.'?mod=extras&amp;&amp;token='.genUToken('admin.extras').'&amp;disable='.$id.'">'.$lang['switch_off'].'</a>' : '<a href="'.$PHP_SELF.'?mod=extras&amp;&amp;token='.genUToken('admin.extras').'&amp;enable='.$id.'">'.$lang['switch_on'].'</a>');

	if ($needinstall) {
		$tvars['vars']['link'] = '';
		$tvars['vars']['style'] = 'pluginEntryUninstalled';
		$pCount[3]++;
	} else {
		$pCount[1 + (!getPluginStatusActive($id))]++;
	}
	$pCount[0]++;

	$tpl -> vars('entries', $tvars);
	$entries .= $tpl -> show('entries');
}

$tpl -> template('table', tpl_actions.$mod);
$tvars = array ('vars' => array(
	'entries'			=> $entries,
	'cntAll'			=> $pCount[0],
	'cntActive'			=> $pCount[1],
	'cntInactive'		=> $pCount[2],
	'cntUninstalled'	=> $pCount[3]
));

$tpl -> vars('table', $tvars);
echo $tpl -> show('table');




// ==========================================================
// Functions
// ==========================================================
function repoSync(){
	global $extras, $config;
	if (($vms = cacheRetrieveFile('plugversion.dat', 3600)) === false) {
		// Prepare request to repository
		$paramList = array('_ver='.urlencode(engineVersion), 'UUID='.$config['UUID']);
		foreach ($extras as $id => $extra)
			$paramList []= urlencode($extra['id'])."=".urlencode($extra['version']);

		$req = new http_get();
		$vms = $req->get('http://ngcms.ru/sync/plugins.php?action=info&'.join('&', $paramList), 3, 1);

		// Save into cache
		cacheStoreFile('plugversion.dat', $vms);
	}
	$rps = unserialize($vms);
	return is_array($rps)?$rps:array();
}