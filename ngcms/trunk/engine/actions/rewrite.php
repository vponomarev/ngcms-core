<?php


//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
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
$format = $_REQUEST['format'];
$subaction = $_REQUEST['subaction'];

// ================================================================
// Handlers for new REWRITE format
// ================================================================


//
// Generate list of supported commands [ config ]
//
$jconfig = array();
foreach ($ULIB->CMD as $plugin => $crow) {
	foreach ($crow as $cmd => $param) {
		$jconfig[$plugin][$cmd] = array('vars' => array(), 'descr' => iconv('Windows-1251', 'UTF-8', $ULIB->extractLangRec($param['descr'])));
		foreach($param['vars'] as $vname => $vdata) {
			$jconfig[$plugin][$cmd]['vars'][$vname] = iconv('Windows-1251', 'UTF-8', $ULIB->extractLangRec($vdata['descr']));
		}
	}
}

//
// Generate list of active rules [ data ]
//
$recno = 0;
$jdata = array();
foreach ($UH->hList as $hId) {
	$jrow = array(	'id' => $recno,
			'pluginName' => $hId['pluginName'],
			'handlerName' => $hId['handlerName'],
			'regex'	=> $hId['rstyle']['rcmd'],
			'flagPrimary' => $hId['flagPrimary'],
			'flagFailContinue' => $hId['flagFailContinue'],
			'flagDisabled' => $hId['flagDisabled'],
		);

	// Fetch associated command
	if ($cmd = $ULIB->fetchCommand($hId['pluginName'], $hId['handlerName'])) {
		$jrow['description']	= iconv('Windows-1251', 'UTF-8', $ULIB->extractLangRec($cmd['descr']));
	}
	$jdata[] = $jrow;
	$recno++;
}

$tpl -> template('entry', tpl_actions.'/rewrite');
$tpl->vars('entry', array());

$tvars['vars']['json.config']	= json_encode($jconfig);
$tvars['vars']['json.data']		= json_encode($jdata);
$tvars['vars']['json.template']	= json_encode($tpl->show('entry'));
$tvars['vars']['token']			= genUToken('admin.rewrite');

$tpl -> template('rewrite', tpl_actions);
$tpl -> vars('rewrite', $tvars);
echo $tpl -> show('rewrite');


//$UH->populateHandler($ULIB, array('pluginName' => 'news', 'handlerName' => 'by.day', 'regex' => '/{year}-{month}-{day}[-page{page}].html'));
