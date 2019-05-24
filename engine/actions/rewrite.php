<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: rewrite.php
// Description: Managing rewrite rules
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Check for permissions
if (!checkPermission(array('plugin' => '#admin', 'item' => 'rewrite'), null, 'details')) {
	msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
	ngSYSLOG(array('plugin' => '#admin', 'item' => 'rewrite'), array('action' => 'details'), null, array(0, 'SECURITY.PERM'));

	return false;
}

@include_once 'includes/classes/uhandler.class.php';
$ULIB = new urlLibrary();
$ULIB->loadConfig();

$UH = new urlHandler();
$UH->loadConfig();

$lang = LoadLang('rewrite', 'admin');

// ================================================================
// Handlers for new REWRITE format
// ================================================================

//
// Generate list of supported commands [ config ]
//
$jconfig = array();
foreach ($ULIB->CMD as $plugin => $crow) {
	foreach ($crow as $cmd => $param) {
		$jconfig[$plugin][$cmd] = array('vars' => array(), 'descr' => $ULIB->extractLangRec($param['descr']));
		foreach ($param['vars'] as $vname => $vdata) {
			$jconfig[$plugin][$cmd]['vars'][$vname] = $ULIB->extractLangRec($vdata['descr']);
		}
	}
}

//
// Generate list of active rules [ data ]
//
$recno = 0;
$jdata = array();
foreach ($UH->hList as $hId) {
	$jrow = array(
		'id'               => $recno,
		'pluginName'       => $hId['pluginName'],
		'handlerName'      => $hId['handlerName'],
		'regex'            => $hId['rstyle']['rcmd'],
		'flagPrimary'      => $hId['flagPrimary'],
		'flagFailContinue' => $hId['flagFailContinue'],
		'flagDisabled'     => $hId['flagDisabled'],
		'setVars'          => $hId['rstyle']['setVars'],
	);

	// Fetch associated command
	if ($cmd = $ULIB->fetchCommand($hId['pluginName'], $hId['handlerName'])) {
		$jrow['description'] = $ULIB->extractLangRec($cmd['descr']);
	}
	$jdata[] = $jrow;
	$recno++;
}

$xe = $twig->loadTemplate('skins/default/tpl/rewrite/entry.tpl');

$tVars = array(
	'json'  => array(
		'config'   => json_encode($jconfig),
		'data'     => json_encode($jdata),
		'template' => json_encode($xe->render(array())),
	),
	'token' => genUToken('admin.rewrite'),
);

$xt = $twig->loadTemplate('skins/default/tpl/rewrite.tpl');
$main_admin = $xt->render($tVars);


//$UH->populateHandler($ULIB, array('pluginName' => 'news', 'handlerName' => 'by.day', 'regex' => '/{year}-{month}-{day}[-page{page}].html'));
