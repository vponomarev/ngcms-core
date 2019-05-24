<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: cron.php
// Description: Manage scheduled jobs
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('cron', 'admin', 'cron');

//
// Save changes
//
function cronCommit() {

	global $cron, $lang;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'cron'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'cron'), array('action' => 'modify'), null, array(0, 'SECURITY.PERM'));

		return false;
	}

	$cronLines = array();
	foreach ($_POST['data'] as $k => $v) {
		if (!is_array($v))
			return false;

		// Check if values are set
		foreach (array('plugin', 'handler', 'min', 'hour', 'day', 'month', 'dow') as $xk) {
			if (!isset($v[$xk]))
				return array(false, $k, $xk);
			$v[$xk] = trim($v[$xk]);
		}
		// Check content
		if ($v['plugin'] == '') {
			// EMPTY LINE, skip
			continue;
		}

		if (!$cron->checkList($v['min'], 0, 59)) return array(false, $k, 'min', $v['min']);
		if (!$cron->checkList($v['hour'], 0, 23)) return array(false, $k, 'hour');
		if (!$cron->checkList($v['day'], 0, 31)) return array(false, $k, 'day');
		if (!$cron->checkList($v['month'], 0, 12)) return array(false, $k, 'month');
		if (!$cron->checkList($v['dow'], 0, 6)) return array(false, $k, 'dow');

		$cronLines [] = array(
			'min'     => $v['min'],
			'hour'    => $v['hour'],
			'day'     => $v['day'],
			'month'   => $v['month'],
			'dow'     => $v['dow'],
			'plugin'  => $v['plugin'],
			'handler' => $v['handler'],
		);
	}

	$execResult = $cron->setConfig($cronLines);
	ngSYSLOG(array('plugin' => '#admin', 'item' => 'cron'), array('action' => 'modify', 'list' => $cronLines), null, ($execResult === true) ? array(1, 'Cron configuration is changed') : array(0, 'Execution error'));

	//print "CRON NEW DATA:<pre>".var_export($cronLines, true)."</pre>";
	return $execResult;
}

;

function cronShowForm() {

	global $cron, $twig, $lang;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'cron'), null, 'details')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'cron'), array('action' => 'details'), null, array(0, 'SECURITY.PERM'));

		return false;
	}

	$rowNum = 1;
	$entries = array();
	foreach ($cron->getConfig() as $v) {
		$tEntry = $v;
		$tEntry['id'] = $rowNum++;
		$entries[] = $tEntry;
	}
	$entries[] = array('id' => $rowNum);

	$tVars = array(
		'token'   => genUToken('admin.extra-config'),
		'entries' => $entries,
	);

	$xt = $twig->loadTemplate('skins/default/tpl/cron.tpl');
	return $xt->render($tVars);
}

// ================================
// Main content
// ================================
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'commit') {
	$res = cronCommit();
	if ($res !== true) {
		// ERROR
		msg(array("type" => "error", "text" => $lang['cron']['result_err'] . var_export($res, true)), 1, 1);
	} else {
		msg(array("text" => $lang['cron']['result_ok']));
	}

}

$main_admin = cronShowForm();
