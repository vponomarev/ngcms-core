<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: preview.php
// Description: News preview
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('preview', 'admin');

// Preload news display engine
include root.'includes/news.php';

function showPreview() {
	global $userROW, $EXTRA_CSS, $EXTRA_HTML_VARS, $PFILTERS, $tpl, $parse, $mysql, $config, $catmap;

	$SQL = array( 'id' => -1 );
	// Эмулируем работу всех штатных средств отвечающих за отображение новости.
	// Заполняем соответствующие поля
	if ($_REQUEST['customdate']) {
		$SQL['postdate'] = mktime(intval($_REQUEST['c_hour']), intval($_REQUEST['c_minute']), 0, intval($_REQUEST['c_month']), intval($_REQUEST['c_day']), intval($_REQUEST['c_year'])) + ($config['date_adjust'] * 60);
	} else {
		$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
	}
	$SQL['title'] = $_REQUEST['title'];
	$SQL['alt_name'] = $parse->translit(trim($_REQUEST['alt_name']?$_REQUEST['alt_name']:$_REQUEST['title']));

	// Fetch MASTER provided categories
	$catids = array ();
	if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
		$catids[intval($_POST['category'])] = 1;
	}

	// Fetch ADDITIONAL provided categories
	foreach ($_POST as $k => $v) {
		if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($_POST['category'])]))
			$catids[$match[1]] = 1;
	}


	$SQL['author']		= $userROW['name'];
	$SQL['author_id']	= $userROW['id'];
	$SQL['catid']		= implode(",", array_keys($catids));
	$SQL['allow_com']	= $_REQUEST['allow_com'];

	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	$SQL['flags'] = 0;
	switch ($userROW['status']) {
		case 1:		// admin can do anything
			$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 2:		// Editor. Check if we have permissions
			if (!$config['htmlsecure_2'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 3:		// Journalists. Check if we have permissions
			if (!$config['htmlsecure_3'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 4:		// Commentors. Check if we have permissions
			if (!$config['htmlsecure_4'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;
	}

	// This actions are allowed only for admins & Edtiors
	if (($userROW['status'] == 1)||($userROW['status'] == 2)) {
		$SQL['mainpage']	= $_REQUEST['mainpage'];
		$SQL['approve']		= $_REQUEST['approve'];
		$SQL['favorite']	= $_REQUEST['favorite'];
		$SQL['pinned']		= $_REQUEST['pinned'];
	}

	// Fill content
	$content	= '';

	// Check if EDITOR SPLIT feature is activated
	if ($config['news.edit.split']) {
		// Prepare delimiter
		$ed = '<!--more-->';
		if ($_REQUEST['content_delimiter'] != '') {
			// Disable `new line` + protect from XSS
			$ed = '<!--more="'.str_replace(array("\r", "\n", '"'), '', $_REQUEST['content_delimiter']).'"-->';
		}
		$content = $_REQUEST['content_short'].(($_REQUEST['content_full'] != '')?$ed.$_REQUEST['content_full']:'');

	} else {
		$content = $_REQUEST['content'];
	}

	// Rewrite `\r\n` to `\n`
	$content = str_replace("\r\n", "\n", $content);

	$SQL['content']		= $content;

	// Process plugin variables to make proper SQL filling
	$tvx = array();
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNews(-1, $SQL, $SQL, $tvx); }

	$tvx = array();
	$tvx['vars']['short'] = news_showone(-1, '', array('emulate' => $SQL, 'style' => 'short'));
	$tvx['vars']['full']  = news_showone(-1, '', array('emulate' => $SQL, 'style' => 'full'));

	// Fill extra CSS links
	foreach ($EXTRA_CSS as $css => $null)
		$EXTRA_HTML_VARS[] = array('type' => 'css', 'data' => $css);

	// Generate metatags
	$EXTRA_HTML_VARS[] = array('type' => 'plain', 'data' => GetMetatags());

	$txv['vars']['htmlvars'] = '';
	// Fill additional HTML vars
	$htmlrow = array();
	$dupCheck = array();
	foreach ($EXTRA_HTML_VARS as $htmlvar) {
		if (in_array($htmlvar['data'], $dupCheck))
			continue;
		$dupCheck[] = $htmlvar['data'];
		switch ($htmlvar['type']) {
			case 'css'	: 	$htmlrow[] = "<link href=\"".$htmlvar['data']."\" rel=\"stylesheet\" type=\"text/css\" />";
							break;
			case 'js'	:	$htmlrow[] = "<script type=\"text/javascript\" src=\"".$htmlvar['data']."\"></script>";
							break;
			case 'rss'	:	$htmlrow[] = "<link href=\"".$htmlvar['data']."\" rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" />";
							break;
			case 'plain':	$htmlrow[] = $htmlvar['data'];
				break;
		}
	}

	if (count($htmlrow))
		$tvx['vars']['htmlvars'] = join("\n",$htmlrow);

	$tvx['vars']['extracss'] = '';

	$tpl -> template('preview', tpl_actions);
	$tpl -> vars('preview', $tvx);
	echo $tpl -> show('preview');
}

showPreview();