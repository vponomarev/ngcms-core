<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: extras.php
// Description: List plugins
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// ==============================================================
//  Module functions
// ==============================================================
@include_once root . 'includes/inc/extraconf.inc.php';
@include_once root . 'includes/inc/httpget.inc.php';

// ==========================================================
// Functions
// ==========================================================

//
// Generate list of plugins
function admGeneratePluginList() {

	global $lang, $twig, $repoPluginInfo, $PHP_SELF;

	$extras = pluginsGetList();
	ksort($extras);

	$pCount = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);

	$tEntries = array();
	foreach ($extras as $id => $extra) {
		if (!isset($extra['author_uri'])) {
			$extra['author_uri'] = '';
		}
		if (!isset($extra['author'])) {
			$extra['author'] = 'Unknown';
		}

		$tEntry = array(
			'version'     => $extra['version'],
			'description' => isset($extra['description']) ? $extra['description'] : '',
			'author_url'  => ($extra['author_uri']) ? ('<a href="' . ((strpos($extra['author_uri'], '@') !== false) ? 'mailto:' : '') . $extra['author_uri'] . '">' . $extra['author'] . "</a>") : $extra['author'],
			'author'      => $extra['author'],
			'id'          => $extra['id'],
			'style'       => getPluginStatusActive($id) ? 'pluginEntryActive' : 'pluginEntryInactive',
			'readme'      => file_exists(extras_dir . '/' . $id . '/readme') && filesize(extras_dir . '/' . $id . '/readme') ? ('<a href="' . admin_url . '/includes/showinfo.php?mode=plugin&amp;item=readme&amp;plugin=' . $id . '" target="_blank" title="' . $lang['entry.readme'] . '"><img src="' . skins_url . '/images/readme.png" width=16 height=16/></a>') : '',
			'history'     => file_exists(extras_dir . '/' . $id . '/history') && filesize(extras_dir . '/' . $id . '/history') ? ('<a href="' . admin_url . '/includes/showinfo.php?mode=plugin&amp;item=history&amp;plugin=' . $id . '" target="_blank" title="' . $lang['entry.history'] . '"><img src="' . skins_url . '/images/history.png" width=16 height=16/></a>') : ''
		);

		if (isset($repoPluginInfo[$extra['id']]) && ($repoPluginInfo[$extra['id']][1] > $extra['version'])) {
			$tEntry['new'] = '<a href="http://ngcms.ru/sync/plugins.php?action=jump&amp;id=' . $extra['id'] . '.html" title="' . $repoPluginInfo[$extra['id']][1] . '"target="_blank"><img src="' . skins_url . '/images/new.gif" width=30 height=15/></a>';
		} else {
			$tEntry['new'] = '';
		}

		$tEntry['type'] = in_array($extra['type'], array('plugin', 'module', 'filter', 'auth', 'widget', 'maintanance')) ? $lang[$extra['type']] : "Undefined";

		//
		// Check for permanent modules
		//
		if (($extra['permanent']) && (!getPluginStatusActive($id))) {
			// turn on
			if (pluginSwitch($id, 'on')) {
				$notify = msg(array("text" => sprintf($lang['msgo_is_on'], $extra['name'])));
			} else {
				// generate error message
				$notify = msg(array("text" => 'ERROR: ' . sprintf($lang['msgo_is_on'], $extra['name'])));
			}
		}

		$needinstall = 0;
		$tEntry['install'] = '';
		if (getPluginStatusInstalled($extra['id'])) {
			if (isset($extra['deinstall']) && $extra['deinstall'] && is_file(extras_dir . '/' . $extra['dir'] . '/' . $extra['deinstall'])) {
				$tEntry['install'] = '<a href="' . $PHP_SELF . '?mod=extra-config&amp;plugin=' . $extra['id'] . '&amp;stype=deinstall">' . $lang['deinstall'] . '</a>';
			}
		} else {
			if (isset($extra['install']) && $extra['install'] && is_file(extras_dir . '/' . $extra['dir'] . '/' . $extra['install'])) {
				$tEntry['install'] = '<a href="' . $PHP_SELF . '?mod=extra-config&amp;plugin=' . $extra['id'] . '&amp;stype=install">' . $lang['install'] . '</a>';
				$needinstall = 1;
			}
		}

		$tEntry['url'] = (isset($extra['config']) && $extra['config'] && (!$needinstall) && is_file(extras_dir . '/' . $extra['dir'] . '/' . $extra['config'])) ? '<a href="' . $PHP_SELF . '?mod=extra-config&amp;plugin=' . $extra['id'] . '">' . $extra['name'] . '</a>' : $extra['name'];
		$tEntry['link'] = (getPluginStatusActive($id) ? '<a href="' . $PHP_SELF . '?mod=extras&amp;&amp;token=' . genUToken('admin.extras') . '&amp;disable=' . $id . '">' . $lang['switch_off'] . '</a>' : '<a href="' . $PHP_SELF . '?mod=extras&amp;&amp;token=' . genUToken('admin.extras') . '&amp;enable=' . $id . '">' . $lang['switch_on'] . '</a>');

		if ($needinstall) {
			$tEntry['link'] = '';
			$tEntry['style'] = 'pluginEntryUninstalled';
			$pCount[3]++;
		} else {
			$pCount[1 + (!getPluginStatusActive($id))]++;
		}
		$pCount[0]++;

		$tEntries [] = $tEntry;
	}

	$tVars = array(
		'entries'        => $tEntries,
		'token'          => genUToken('admin.extras'),
		'cntAll'         => $pCount[0],
		'cntActive'      => $pCount[1],
		'cntInactive'    => $pCount[2],
		'cntUninstalled' => $pCount[3]
	);
	$xt = $twig->loadTemplate(tpl_actions . 'extras/table.tpl');
	return $xt->render($tVars);
}

function repoSync() {

	global $extras, $config;
	if (($vms = cacheRetrieveFile('plugversion.dat', 86400)) === false) {
		// Prepare request to repository
		$paramList = array('_ver=' . urlencode(engineVersion), 'UUID=' . $config['UUID']);
		foreach ($extras as $id => $extra)
			$paramList [] = urlencode($extra['id']) . "=" . urlencode($extra['version']);

		$req = new http_get();
		$vms = $req->get('http://ngcms.ru/components/update/?action=info&' . join('&', $paramList), 3, 1);

		// Save into cache
		cacheStoreFile('plugversion.dat', $vms);
	}
	$rps = unserialize($vms);

	return is_array($rps) ? $rps : array();
}

// ==============================================================
//  Main module code
// ==============================================================

$lang = LoadLang('extras', 'admin');
$extras = pluginsGetList();
ksort($extras);

// ==============================================================
// Load a list of updated plugins from central repository
// ==============================================================
$repoPluginInfo = repoSync();

// ==============================================================
// Process enable request
// ==============================================================
$enable = isset($_REQUEST['enable']) ? $_REQUEST['enable'] : '';
$disable = isset($_REQUEST['disable']) ? $_REQUEST['disable'] : '';
$manage = (isset($_REQUEST['manageConfig']) && $_REQUEST['manageConfig'] && isset($_REQUEST['action']) && ($_REQUEST['action'] == 'commit')) ? true : false;

// Check for security token
if ($enable || $disable || $manage) {
	if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.extras'))) {
		$notify = msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras', 'ds_id' => $id), array('action' => 'modify'), null, array(0, 'SECURITY.TOKEN'));
		exit;
	}
}

if (isset($_REQUEST['manageConfig']) && $_REQUEST['manageConfig']) {
	plugins_load_config();

	if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'commit')) {
		print "TRY COMMIT";
	}
	$confLine = json_encode(arrayCharsetConvert(0, $PLUGINS['config']));
	$confLine = jsonFormatter($confLine);

	$tVars = array(
		'config' => $confLine,
		'token'  => genUToken('admin.extras'),
	);
	$xt = $twig->loadTemplate('skins/default/tpl/extras/manage_config.tpl');
	return $xt->render($tVars);

	exit;
}

if ($enable) {
	if (pluginSwitch($enable, 'on')) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $enable)), null, array(1, ''));
		msgSticker(array(array(sprintf($lang['msgo_is_on'], $extras[$enable]['name']), '', 1)));
	} else {
		// generate error message
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $enable)), null, array(0, 'ERROR: ' . $enable));
		$notify = msg(array("text" => 'ERROR: ' . sprintf($lang['msgo_is_on'], $extras[$id]['name'])));
	}
}

if ($disable) {
	if ($extras[$disable]['permanent']) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_off', 'list' => array('plugin' => $disable)), null, array(0, 'ERROR: PLUGIN is permanent ' . $disable));
		msgSticker(array(
			array($lang['permanent.lock'], 'title'),
			array(str_replace('{name}', $disable, $lang['permanent.lock#desc']))
		), 'error');
	} else {
		if (pluginSwitch($disable, 'off')) {
			ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_off', 'list' => array('plugin' => $disable)), null, array(1, ''));
			msgSticker(array(array(sprintf($lang['msgo_is_off'], $extras[$enable]['name']), '', 1)));
		} else {
			ngSYSLOG(array('plugin' => '#admin', 'item' => 'extras'), array('action' => 'switch_on', 'list' => array('plugin' => $disable)), null, array(0, 'ERROR: ' . $disable));
			msgSticker(array(array(sprintf('ERROR: ' . $lang['msgo_is_off'], $extras[$enable]['name']), 'error', 1)));
			//msg(array("text" => 'ERROR: '.sprintf($lang['msgo_is_off'], $extras[$id]['name'])));
		}
	}
}

$main_admin = admGeneratePluginList();