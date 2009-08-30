<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
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


function check_uri($uri) {
	if (ereg("@",$uri)) {
		$uri="mailto:".$uri;
	}
	return $uri;
}



// ==============================================================
//  Main module code
// ==============================================================

$lang	=	LoadLang('extras', 'admin');
$extras	=	get_extras_list();
ksort($extras);

// ==============================================================
// Load a list of updated plugins from central repository
// ==============================================================
$repoPluginInfo = repoSync();

// ==============================================================
// Process enable request
// ==============================================================
$enable  = $_REQUEST['enable'];
$disable = $_REQUEST['disable'];

if ($enable) {
	if (pluginSwitch($enable, 'on')) {
		msg(array("text" => sprintf($lang['msgo_is_on'], $extras[$enable]['name'])));
	} else {
		// generate error message
		msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_on'], $extras[$id]['name'])));
	}
}

if ($disable) {
	if ($extras[$disable]['permanent']) {
		msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
	} else {
		if (pluginSwitch($disable, 'off')) {
			msg(array("text" => sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
		} else {
			msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
		}
	}
}

$tpl -> template('entries', tpl_actions.$mod);

foreach($extras as $id => $extra) {
	$tvars['vars'] = array(
		'version'		=>	$extra['version'],
		'description'	=>	$extra['description'],
		'author_url'	=>	($extra['author_uri'])?'<a href="'.check_uri($extra['author_uri']).'">'.$extra['author']."</a>":$extra['author'],
		'author'		=>	$extra['author'],
		'id'			=>	$extra['id'],
		'style'			=>	getPluginStatusActive($id)?'contRow1':'contRow2',
		'readme'		=>	file_exists(extras_dir.'/'.$id.'/readme')&&filesize(extras_dir.'/'.$id.'/readme')?('<a href="'.admin_url.'/includes/showinfo.php?mode=plugin&item=readme&plugin='.$id.'" target="_blank" title="Documentation"><img src="'.skins_url.'/images/readme.png" width=16 height=16/></a>'):'',
		'history'		=>	file_exists(extras_dir.'/'.$id.'/history')&&filesize(extras_dir.'/'.$id.'/history')?('<a href="'.admin_url.'/includes/showinfo.php?mode=plugin&item=history&plugin='.$id.'" target="_blank" title="Documentation"><img src="'.skins_url.'/images/history.png" width=16 height=16/></a>'):''
	);

	if (isset($repoPluginInfo[$extra['id']]) && ($repoPluginInfo[$extra['id']][1] != $extra['version'])) {
		$tvars['vars']['new']		= '<a href="http://ngcms.ru/sync/plugins.php?action=jump&id='.$extra['id'].'.html" title="'.$repoPluginInfo[$extra['id']][1].'"target="_blank"><img src="'.skins_url.'/images/new.gif" width=30 height=15/></a>';	
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
		if ($extra['deinstall'] && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['deinstall'])) {
			$tvars['vars']['install'] = '<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'&amp;stype=deinstall">'.$lang['deinstall'].'</a>';
		}
	} else {
		if ($extra['install'] && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['install'])) {
			$tvars['vars']['install'] = '<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'&amp;stype=install">'.$lang['install'].'</a>';
			$needinstall = 1;
		}
	}

	$tvars['vars']['url'] = ($extra['config'] && (!$needinstall) && is_file(extras_dir.'/'.$extra['dir'].'/'.$extra['config']))?'<a href="'.$PHP_SELF.'?mod=extra-config&amp;plugin='.$extra['id'].'">'.$extra['name'].'</a>' : $extra['name'];
	$tvars['vars']['link'] = (getPluginStatusActive($id) ? '<a href="'.$PHP_SELF.'?mod=extras&amp;disable='.$id.'">'.$lang['switch_off'].'</a>' : '<a href="'.$PHP_SELF.'?mod=extras&amp;enable='.$id.'">'.$lang['switch_on'].'</a>');

	if ($needinstall) { $tvars['vars']['link'] = ''; $tvars['vars']['style'] = 'contRow3'; }

	$tpl -> vars('entries', $tvars);
	$entries .= $tpl -> show('entries');
}

$tpl -> template('table', tpl_actions.$mod);
$tvars['vars']['entries'] = $entries;

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
