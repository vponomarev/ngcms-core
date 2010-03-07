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
	'version'		=>	engineVersion,
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
		$template['vars']['htmlvars'] = '';

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
exec_acts('index_pre');

// Deactivate block [sitelock] ... [/sitelock]
$template['vars']["[sitelock]"] = "";
$template['vars']["[/sitelock]"] = "";


//
// Core URL processing block
//

// Prepare variable with access URL
$systemAccessURL = $_SERVER['REQUEST_URI'];
if (($tmp_pos = strpos($systemAccessURL, '?')) !== FALSE) {
 $systemAccessURL = substr($systemAccessURL, 0, $tmp_pos);
}

// /////////////////////////////////////////////////////////// //
// You may modify variable $systemAccessURL here (for hacks)   //
// /////////////////////////////////////////////////////////// //


// /////////////////////////////////////////////////////////// //
$timer->registerEvent('Search route for URL "'.$systemAccessURL.'"');

// Check if engine is installed in subdirectory
if (preg_match('#^http\:\/\/([^\/])+(\/.+)#', $config['home_url'], $match))
	$UHANDLER->setOptions(array('localPrefix' => $match[2]));
$runResult = $UHANDLER->run($systemAccessURL, array('debug' => false));


// If no pages are catched
if (!$runResult) {
	error404();
}


//$link = generateLink('core', 'plugin', array('plugin' => 'rss_export'));
//print "URL ForWArd: ".var_export($link, true);
//print "Config status: ".$UHANDLER->configLoaded."</br>\n";
//print "<pre>".var_export($UHANDLER->hList, true)."</pre><br/>\n";

// Run plugins
exec_acts('index');


// ===================================================================
// Generate additional informational blocks
// ===================================================================
$timer->registerEvent('Master activity finished');

// Generate category menu
$template['vars']['categories'] = generateCategoryMenu();
$timer->registerEvent('Category menu created');

// Generate page title
$template['vars']['titles'] = join(" : ", array_values($SYSTEM_FLAGS['info']['title']));


// Generate user menu
@include_once root.'usermenu.php';

// Generate search form
$tpl -> template('search.form', tpl_site);
$tpl -> vars('search.form', array('vars' => array('form_url' =>	generateLink('search', '', array()) )));
$template['vars']['search_form'] = $tpl -> show('search.form');

// Save 'category' variable
$template['vars']['category'] = (isset($_REQUEST['category']) && ($_REQUEST['category'] != ''))?secure_html($_REQUEST['category']):'';


// ====================================================================
// PLUGIN EXEC CALL: Exec actions that should be done after main page
// parameters were generated
exec_acts('index_post');

// Make empty OLD STYLE variables
$template['vars']['metatags'] = '';
$template['vars']['extracss'] = '';

// Fill extra CSS links
foreach ($EXTRA_CSS as $css => $null)
	$EXTRA_HTML_VARS[] = array('type' => 'css', 'data' => $css);

// Generate metatags
$EXTRA_HTML_VARS[] = array('type' => 'plain', 'data' => GetMetatags());

// Fill additional HTML vars
$htmlrow = array();
$dupCheck = array();
foreach ($EXTRA_HTML_VARS as $htmlvar) {
	// Skip empty
	if (!$htmlvar['data'])
		continue;

	// Check for duplicated rows
	if (in_array($htmlvar['data'], $dupCheck))
		continue;
	$dupCheck[] = $htmlvar['data'];

	switch ($htmlvar['type']) {
		case 'css': 	$htmlrow[] = "<link href=\"".$htmlvar['data']."\" rel=\"stylesheet\" type=\"text/css\" />";
			break;
		case 'js' :	$htmlrow[] = "<script type=\"text/javascript\" src=\"".$htmlvar['data']."\"></script>";
			break;
		case 'rss' : $htmlrow[] = "<link href=\"".$htmlvar['data']."\" rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" />";
			break;
		case 'plain':$htmlrow[] = $htmlvar['data'];
			break;
	}
}
if (count($htmlrow))
	$template['vars']['htmlvars'] .= join("\n",$htmlrow);

// Add support of blocks [is-logged] .. [/isnt-logged] in main template
$template['regx']['#\[is-logged\](.+?)\[/is-logged\]#is'] = $is_logged?'$1':'';
$template['regx']['#\[isnt-logged\](.+?)\[/isnt-logged\]#is'] = $is_logged?'':'$1';


// ***** EXECUTION TIME CATCH POINT *****
// Calculate script execution time
$template['vars']['queries'] = $mysql -> qcnt();
$template['vars']['exectime'] = $timer -> stop();

// Fill debug information (if it is requested)
if ($config['debug']) {
	$timer->registerEvent('Templates generation time: '.$tpl->execTime. ' ('.$tpl->execCount.' times called)');
	$timer->registerEvent('Generate DEBUG output');
	if (is_array($userROW) && ($userROW['status'] == 1)) {
		$template['vars']['debug_queries'] = ($config['debug_queries'])?('<b><u>SQL queries:</u></b><br>'.implode("<br />\n",$mysql->query_list)."<br />"):'';
		$template['vars']['debug_profiler'] = ($config['debug_profiler'])?('<b><u>Time profiler:</u></b>'.$timer->printEvents(1)."<br />"):'';
		$template['vars']['[debug]'] = '';
		$template['vars']['[/debug]'] = '';
	} else {
		$template['regx']["#\[debug\].*?\[/debug\]#si"] = '';
	}
}


// ===================================================================
// Make page output
// ===================================================================
// 1. Determine template name & path
$mainTemplateName = isset($SYSTEM_FLAGS['template.main.name']) ? $SYSTEM_FLAGS['template.main.name'] : 'main';
$mainTemplatePath = isset($SYSTEM_FLAGS['template.main.path']) ? $SYSTEM_FLAGS['template.main.path'] : tpl_site;

// 2. Load & show template
$tpl -> template($mainTemplateName, $mainTemplatePath);
$tpl -> vars($mainTemplateName, $template);
if (!$SUPRESS_TEMPLATE_SHOW) {
	printHTTPheaders();
	echo $tpl -> show($mainTemplateName);
} else if (!$SUPRESS_MAINBLOCK_SHOW) {
	printHTTPheaders();
	echo $template['vars']['mainblock'];
}


// ===================================================================
// Make page output
// ===================================================================

// Call maintanance actions
exec_acts('maintenance');
if ($config['auto_backup'] == "1") { AutoBackup(); }