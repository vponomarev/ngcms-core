<?php

//
// Copyright (C) 2008 Next Generation CMS (http://ngcms.ru/)
// Name: showinfo.php
// Description: Show different informational blocks
// Author: Vitaly Ponomarev
//

include_once "../core.php";

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

header("Content-Type: text/html; charset=utf-8");

if ($_REQUEST['mode'] == 'plugin') {
	$extras = get_extras_list();
	$plugin = str_replace(array('/', '\\', '..'), '', $_REQUEST['plugin']);
	if (!is_array($extras[$plugin]))
		return;

	if ($_REQUEST['item'] == 'readme') {
		if (file_exists(root . 'plugins/' . $plugin . '/readme')) {
			print "<pre>";
			print file_get_contents(root . 'plugins/' . $plugin . '/readme');
			print "</pre>";
		}
	}
	if ($_REQUEST['item'] == 'history') {
		if (file_exists(root . 'plugins/' . $plugin . '/history')) {
			print "<pre>";
			print file_get_contents(root . 'plugins/' . $plugin . '/history');
			print "</pre>";
		}
	}
}
