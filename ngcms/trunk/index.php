<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: index.php
// Description: core index file
// Author: NGCMS project team
//


@include_once 'engine/core.php';
gzip();


// Define default TITLE
$SYSTEM_FLAGS['info']['title'] = array ();
$SYSTEM_FLAGS['info']['title']['header'] = home_title;

$template['vars'] = array(
	'what'			=>	engineName,
	'version'		=>	version,
	'home'			=>	home,
	'titles'		=>	home_title,
	'home_title'	=>	home_title
);

// ===================================================================
// Check if site access is locked [ for everyone except admins ]
// ===================================================================
if ($config['lock'] && (!is_array($userROW) || ($userROW['status'] != 1))) {
	$tvars['vars']['lock_reason'] = $config['lock_reason'];

	// If template 'sitelock.tpl' exists - show only this template
	// ELSE: show template 'lock.tpl' within template 'main.tpl'
	if (file_exists(tpl_site.'sitelock.tpl')) {
		$tpl->template('sitelock', tpl_site);
		$tpl->vars('sitelock', $tvars);
		echo $tpl->show('sitelock');
	} else {
		$tpl -> template('lock', tpl_site);
		$tpl -> vars('lock', $tvars);
		$template['regx']["'\[sitelock\].*?\[/sitelock\]'si"] = $tpl -> show('lock');
		$template['regx']["'\[debug\].*?\[/debug\]'si"] = '';
		$template['vars']['metatags'] = '';
		$template['vars']['extracss'] = '';

		$tpl -> template('main', tpl_site);
		$tpl -> vars('main', $template);
		echo $tpl->show('main');
	}

	// STOP SCRIPT EXECUTION
	exit;
}


// ===================================================================
// Start generating page
// ===================================================================

// External call: before executing activity
exec_acts("index");

// Deactivate block [sitelock] ... [/sitelock]
$template['vars']["[sitelock]"] = "";
$template['vars']["[/sitelock]"] = "";


// MASTER SWITCH - select desired action
switch ($action) {
	case 'static':			include root.'includes/static.php'; break;
	case 'plugin':			include root.'includes/plugin.php'; break;

	case 'search':			include root.'includes/search.php'; break;
	case 'addnews':			include root.'actions/addnews.php'; break;
	case 'profile':			include root.'profile.php'; break;
	case 'registration':	include root.'registration.php'; break;
	case 'lostpassword':	include root.'lostpassword.php'; break;
	case 'activation':		include root.'activation.php'; break;
	case 'users':			include root.'includes/users.php'; break;

	default:
		include_once root.'includes/news.php';
		showNews();

}

// ===================================================================
// Generate additional informational blocks
// ===================================================================

// Generate category menu
$template['vars']['categories'] = generateCategoryMenu();

// Generate page title
$template['vars']['titles'] = join(" : ", array_values($SYSTEM_FLAGS['info']['title']));

// Generate user menu
@include_once root.'usermenu.php';

// Generate search form
$tpl -> template('search.form', tpl_site);
$tpl -> vars('search.form', array('vars' => array()));
$template['vars']['search_form'] = $tpl -> show('search.form');

// Generate metatags
$template['vars']['metatags'] = GetMetatags();

// Save 'category' variable
$template['vars']['category'] = $_REQUEST['category']?secure_html($_REQUEST['category']):'';


// ====================================================================
// PLUGIN EXEC CALL: Exec actions that should be done after main page
// parameters were generated
exec_acts('index_post');


// Fill extra CSS links
$template['vars']['extracss'] = '';
foreach ($EXTRA_CSS as $css => $null)
	$template['vars']['extracss'] .= "<link href=\"".$css."\" rel=\"stylesheet\" type=\"text/css\" />\n";


// ***** EXECUTION TIME CATCH POINT *****
// Calculate script execution time
$template['vars']['queries'] = $mysql -> qcnt();
$template['vars']['exectime'] = $timer -> stop();

// Fill debug information (if it is requested)
if ($config['debug']) {
	if (is_array($userROW) && ($userROW['status'] == 1)) {
		$template['vars']['debug_queries'] = ($config['debug_queries'])?('<b><u>SQL queries:</u></b><br>'.implode("<br />\n",$mysql->query_list)."<br />"):'';
		$template['vars']['debug_profiler'] = ($config['debug_profiler'])?('<b><u>Time profiler:</u></b>'.$timer->printEvents(1)."<br />"):'';
		$template['vars']['[debug]'] = '';
		$template['vars']['[/debug]'] = '';
	} else {
		$template['regx']["'\\[debug\\].*?\\[/debug\\]'si"] = '';
	}
}


// ===================================================================
// Make page output
// ===================================================================

$tpl -> template('main', tpl_site);
$tpl -> vars('main', $template);
if (!$SUPRESS_TEMPLATE_SHOW) {
	echo $tpl -> show('main');
} else if (!$SUPRESS_MAINBLOCK_SHOW) {
	echo $template['vars']['mainblock'];
}


// ===================================================================
// Make page output
// ===================================================================

// Call maintanance actions
exec_acts('maintenance');
if ($config['auto_backup'] == "1") { AutoBackup(); }
