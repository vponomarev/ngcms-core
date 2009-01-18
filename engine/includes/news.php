<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: news.php
// Description: News display sub-engine
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('news', 'site');
$situation = "news";


//
// Show one news
// Params (newsID or alt_name should be filled)
// $newsID - ID of the news
// $alt_name - alt name of the news
// $callingParams
//		'style'	  => mode for which we're called
//			* short		- short new display
//			* full		- full news display
//			* export	- export data [ for plugins or so on. No counters are updated ]
//			* export_body	- export ONLY BODY short+full [ for plugins or so on... ]
//			* export_short	- export ONLY BODY short      [ for plugins or so on... ]
//			* export_full	- export ONLY BODY full       [ for plugins or so on... ]
//      	'emulate' => array with "fake" emulated row [ used for preview or so ... ]
//		'plugin'  => if is called from plugin - ID of plugin
//		'overrideTemplateName' => alternative template for display
//		'overrideTemplatePath' => alternative path for searching of template
//		'customCategoryTemplate' => automatically override custom category templates
//
//		Returns:
//			false    - when news is not found
//			data     - when news is found && export is used
//			news row - when news is found
function news_showone($newsID, $alt_name, $callingParams = array()) {
	global $mysql, $tpl, $userROW, $catz, $catmap, $config, $template, $parse, $vars, $lang, $SYSTEM_FLAGS, $PFILTERS;
	global $year, $month, $day, $SUPRESS_TEMPLATE_SHOW;

	if (is_array($callingParams['emulate'])) {
		$row = $callingParams['emulate'];
		$callingParams['emulateMode'] = 1;
	} else {

		if ($newsID) {
			$filter = array('id='.db_squote($newsID));
		} elseif ($alt_name) {
			$filter = array('alt_name='.db_squote($alt_name));
		} else {
			return false;
		}

		if ($year) {
			array_push($filter, 'postdate >= '.db_squote(mktime(0,0,0,$month?$month:1, $day?$day:1, $year)));
			array_push($filter, 'postdate <= '.db_squote(mktime(23,59,59,$month?$month:12, $day?$day:31, $year)));
		}

		if (!is_array($row = $mysql->record("select * from ".prefix."_news where approve=1".(count($filter)?' and '.implode(" and ",$filter):'')))) {
			msg(array("type" => "info", "info" => $lang['msgi_not_found']));
			return false;
		}
	}

	// preload plugins
	load_extras('news:show');
	load_extras('news:show:one');

	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->showNewsPre($row['id'], $row, $callingParams); }

	$tvars = newsFillVariables($row, 1, $_REQUEST['page'], (substr($callingParams['style'], 0, 6) == 'export')?1:0);

	$tvars['vars']['date']		=	LangDate(timestamp, $row['postdate']);
	$tvars['vars']['views']		=	$row['views'];
	$tvars['vars']['comnum']	=	$row['com'];

	// Show category icon in case category identifier is used. Icon is showed from first category name
	$showCategory = trim(array_shift(explode("-", $_REQUEST['category'])));
	if ($showCategory && ($showCategory != "none") && trim($catz[$showCategory]['icon'])) {
		$tvars['vars']['icon']		= trim($catz[$showCategory]['icon']);
		$tvars['vars']['[icon]']	= '';
		$tvars['vars']['[/icon]']	= '';
	} else {
		$tvars['vars']['icon']		=	'';
		$tvars['regx']["'\[icon\].*?\[/icon\]'si"] = '';
	}

	// Show edit/detele news buttons
	if (is_array($userROW) && ($row['author_id'] == $userROW['id'] || $userROW['status'] == "1" || $userROW['status'] == "2")) {
		$tvars['vars']['[edit-news]'] = "<a href=\"".admin_url."/admin.php?mod=editnews&amp;action=editnews&amp;id=".$row['id']."\" target=\"_blank\">";
		$tvars['vars']['[/edit-news]'] = "</a>";
		$tvars['vars']['[del-news]'] = "<a onClick=\"confirmit('".admin_url."/admin.php?mod=editnews&amp;subaction=do_mass_delete&amp;selected_news[]=".$row['id']."', '".$lang['sure_del']."')\" target=\"_blank\" style=\"cursor: pointer;\">";
		$tvars['vars']['[/del-news]'] = "</a>";
	} else {
		$tvars['regx']["'\\[edit-news\\].*?\\[/edit-news\\]'si"] = "";
		$tvars['regx']["'\\[del-news\\].*?\\[/del-news\\]'si"] = "";
	}

	$newsid				=	$row['id'];
	$allow_comments		=	$row['allow_com'];
	$row['views']		=	$row['views']+1;

	exec_acts('news_full', '', $row, &$tvars);

	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->showNews($row['id'], $row, $tvars, $callingParams); }

	// Check if we need only to export body
	if ($callingParams['style'] == 'export_body')
		return $tvars['vars']['short-story'].' '.$tvars['vars']['full-story'];

	if ($callingParams['style'] == 'export_short')
		return $tvars['vars']['short-story'];

	if ($callingParams['style'] == 'export_full')
		return $tvars['vars']['full-story'];


	// Update visits counter if we're not in emulation mode
	if ((!$callingParams['emulate'])&&($callingParams['style'] == 'full')&&(intval($_REQUEST['page'])<2))
		$mysql->query("update ".prefix."_news set views=views+1 where id = ".db_squote($row['id']));


	// Make temlate procession - auto/manual overriding
	// -> calling style
	if (!$callingParams['style']) $callingParams['style'] = 'full';

	// -> desired template - override template if needed
	if ($callingParams['overrideTemplateName']) {
		$templateName = $callingParams['overrideTemplateName'];
	} else {
		// -> generate template name for selected style
		switch ($callingParams['style']) {
			case 'short': $templateName = 'news.short'; break;
			case 'full' : $templateName = 'news.full'; break;
			default     : $templateName = '';
		}
	}

	// Set default template path
	$templatePath = tpl_dir.$config['theme'];

	// -> desired template path - override path if needed
	if ($callingParams['overrideTemplatePath']) {
		$templatePath = $callingParams['overrideTemplatePath'];
	} else if ($callingParams['customCategoryTemplate']) {
		// -> check for custom category templates
		// Find first category
		$fcat = array_shift(explode(",", $row['catid']));
		// Check if there is a custom mapping
		if ($fcat && $catmap[$fcat] && ($ctname = $catz[$catmap[$fcat]]['tpl'])) {
			// Check if directory exists
			if (is_dir($templatePath.'/ncustom/'.$ctname))
				$templatePath = $templatePath.'/ncustom/'.$ctname;
		}
	}

	// Load & configure template
	$tpl -> template($templateName, $templatePath);
	$tpl -> vars($templateName, $tvars);

	// No comments/meta in emulation or export mode
	if ((is_array($callingParams['emulate']))||($callingParams['style'] == 'export'))
		return $tpl -> show($templateName);

	// Set meta tags for news page
	$SYSTEM_FLAGS['meta']['description'] = $row['description'];
	$SYSTEM_FLAGS['meta']['keywords']    = $row['keywords'];

	// Prepare title
	$SYSTEM_FLAGS['info']['title']['group']	= $config["category_link"]?GetCategories($row['catid'], true):LangDate(timestamp, $row['postdate']);
	$SYSTEM_FLAGS['info']['title']['item']	= secure_html($row['title']);

	// We are in short or full mode. Add data into {mainblock}
	$template['vars']['mainblock'] .= $tpl -> show($templateName);

	return $row;
}


//
// Show news list
// Params (newsID or alt_name should be filled)
// $categoryList - list of categories to show
// $callingParams
//		'style'	  => mode for which we're called
//			* short		- short new display
//			* full		- full news display
//			* export	- export data [ for plugins or so on. No counters are updated ]
//		'plugin'  => if is called from plugin - ID of plugin
//		'overrideTemplateName' => alternative template for display
//		'overrideTemplatePath' => alternative path for searching of template
//		'customCategoryTemplate' => flag automatically override custom category templates
//		'customCategoryNumber'	=> flag automatically override number of news per page
//			[!!!] USES CUSTOM TEMPLATE FOR FIRST CATEGORY FROM NEWS [!!!]
function news_showlist($categoryList = array(), $callingParams = array()){
	global $mysql, $tpl, $userROW, $catz, $catmap, $config, $vars, $parse, $template, $lang, $PFILTERS;
	global $year, $month, $day;
	global $timer;
	global $SYSTEM_FLAGS;

	// Get a list of categories to show
	// "-" means "AND", "," means "OR"
	// I.e: news-games,web,files is "Show news from: news&games (2 categories in news) or web or files"
	if (is_array($categoryList)&&count($categoryList)) {
		$carray = $categoryList;
	} else {
		$ctext  = trim(!is_array($categoryList)?$categoryList:category);
		$carray = generateCategoryArray($ctext);

		// Error - didn't find chosen categories
		if (strlen($ctext) && !count($carray)) {
			msg(array("type" => "info", "info" => $lang['msgi_cat_not_found']));
			return false;
		}
	}

	// Make temlate procession - auto/manual overriding
	// -> calling style
	if (!$callingParams['style']) $callingParams['style'] = 'short';

	// -> desired template - override template if needed
	if ($callingParams['overrideTemplateName']) {
		$templateName = $callingParams['overrideTemplateName'];
	} else {
		// -> generate template name for selected style
		switch ($callingParams['style']) {
			case 'short': $templateName = 'news.short'; break;
			case 'full' : $templateName = 'news.full'; break;
			default     : $templateName = '';
		}
	}

	// Set default template path
	$templatePath = tpl_dir.$config['theme'];

	$cstart		= abs(intval($_REQUEST['cstart'])?intval($_REQUEST['cstart']):0);
	$start_from	= abs(intval($_REQUEST['start_from'])?intval($_REQUEST['start_from']):0);

	if (!$cstart) { $cstart = 1; }

	$i			= $start_from?$start_from:0;

	$showNumber = ($config['number']>=1)?$config['number']:5;

	if (($callingParams['customCategoryNumber']) && count($carray) && ($fcat=$carray[0][0]) && $catmap[$fcat] && ($fcnt = $catz[$catmap[$fcat]]['number']))
		$showNumber = $fcnt;

	if ($config['number']<1)
		$config['number'] = 5;

	$limit_start = $cstart?($cstart-1)*$showNumber:0;
	$limit_count = $showNumber;

	$query['where'] = "";

	if ((count($carray) == 1)&&(is_array($carray[0]))&&(count($carray[0])==1)&&($catorder=$catz[$catmap[$carray[0][0]]]['orderby'])) {
		$orderBy = "pinned desc, ".$catorder;
	} else {
		if (in_array($config['default_newsorder'], array('id desc', 'id asc', 'postdate desc', 'postdate asc', 'title desc', 'title asc'))) {
			$orderBy = "pinned desc, ".$config['default_newsorder'];
		} else {
			$orderBy = "pinned desc, id desc";
		}
	}

	$query['orderby'] = " order by ".$orderBy." limit ".$limit_start.",".$limit_count;

	// ===================================================================
	// Check what display mode is requested:
	// * by category alt. name
	// * by year
	// * by month
	// * by day
	// * MAINPAGE DISPLAY

	if (count($carray)) {
		// * by category alt. name

		// Mark link type for navigations
		$which_link				=	'category_page';

		// Make title header
		$SYSTEM_FLAGS['info']['title']['group'] = GetCategories($carray[0], true);

		// Make category filter
		$cw = array();
		foreach($carray as $cg){
			$cn = array();
			foreach($cg as $cv) {
				array_push($cn, " (catid regexp '[[:<:]](".$cv.")[[:>:]]') ");
			}
			array_push($cw, " (".implode (" AND ",$cn).") ");
		}
		$query['where']			= implode(" OR ",$cw);
	}
	// * by day
	elseif (year && month && day) {
		// Make title header
		$SYSTEM_FLAGS['info']['title']['group'] = LangDate("j Q Y", mktime("0", "0", "0", month, day, year));

		// Mark link type for navigations
		$which_link			=	'date_page';
		$query['where']		= " postdate > '".mktime(0,0,0,month,day,year)."' AND postdate < '".mktime(23,59,59,month,day,year)."'";
	}
	// * by month
	elseif (year && month && !day) {
		// Make title header
		$SYSTEM_FLAGS['info']['title']['group'] = LangDate("F Y", mktime("0", "0", "0", month, 7, year));

		// Mark link type for navigations
		$which_link			=	'month_page';
		$query['where']		= " postdate > '".mktime(0,0,0,month,1,year)."' AND postdate < '".mktime(23,59,59,month,date("t",mktime(0,0,0,month,1,year)),year)."'";
	}
	// * by year
	elseif (year && !month && !day) {
		// Make title header
		$SYSTEM_FLAGS['info']['title']['group'] = LangDate("Y", mktime("0", "0", "0", 12, 7, year));

		// Mark link type for navigations
		$which_link			=	'year_page';
		$query['where']		= " postdate > '".mktime(0,0,0,1,1,year)."' AND postdate < '".mktime(23,59,59,12,31,year)."'";
	}
	// * MAIN PAGE DISPLAY
	else {
		// Make title header
		$SYSTEM_FLAGS['info']['title']['group'] = $lang['mainpage'];

		// Mark link type for navigations
		$which_link			=	'page';
		$query['where']		=	" mainpage=1";
	}

	$query['sql']		=	$query['where']." AND approve = 1";
	$query['count']		=	"SELECT count(*) as count FROM ".prefix."_news WHERE".$query['sql'];
	$query['result']	=	"SELECT * FROM ".prefix."_news WHERE".$query['sql'].$query['orderby'];

	// preload plugins
	load_extras('news:show');
	load_extras('news:show:list');

	$nCount = 0;
	$output = '';

	foreach ($mysql->select($query['result']) as $row) {
		$i++;
		$nCount++;

		// Give 'news in order' field to plugins
		$callingParams['nCount'] = $nCount;

		// Set default template path
		$templatePath = tpl_dir.$config['theme'];

		// -> desired template path - override path if needed
		if ($callingParams['overrideTemplatePath']) {
			$templatePath = $callingParams['overrideTemplatePath'];
		} else if ($callingParams['customCategoryTemplate']) {
			// -> check for custom category templates
			// Find first category
			$fcat = array_shift(explode(",", $row['catid']));
			// Check if there is a custom mapping
			if ($fcat && $catmap[$fcat] && ($ctname = $catz[$catmap[$fcat]]['tpl'])) {
				// Check if directory exists
				if (is_dir($templatePath.'/ncustom/'.$ctname))
					$templatePath = $templatePath.'/ncustom/'.$ctname;
			}
		}

		// Execute filters
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNewsPre($row['id'], $row, $callingParams); }

		$tvars = newsFillVariables($row, 0, $_REQUEST['page']);

		$tvars['vars']['alternating'] = ($i%2)?'odd':'even';

		$tvars['vars']['date'] = LangDate(timestamp, $row['postdate']);
		$tvars['vars']['views'] = $row['views'];

		// Print icon if only one parent category
		if ($row['catid'] && !stristr(",", $row['catid']) && ($catalt = $catmap[$row['catid']]) && $catz[$catalt]['icon']) {
			$tvars['vars']['icon'] = $catz[$catalt]['icon'];
			$tvars['vars']['[icon]'] = '';
			$tvars['vars']['[/icon]'] = '';
		} else {
			$tvars['vars']['icon'] = '';
			$tvars['regx']["'\\[icon\\].*?\\[/icon\\]'si"] = '';
		}

		if (is_array($userROW) && ($userROW['id'] == $row['author_id'] || ($userROW['status'] == 1 || $userROW['status'] == 2))) {
			$tvars['vars']['[edit-news]'] = "<a href=\"".admin_url."/admin.php?mod=editnews&amp;action=editnews&amp;id=".$row['id']."\" target=\"_blank\">";
			$tvars['vars']['[/edit-news]'] = "</a>";
			$tvars['vars']['[del-news]'] = "<a onClick=\"confirmit('".admin_url."/admin.php?mod=editnews&amp;subaction=do_mass_delete&amp;selected_news[]=".$row['id']."', '".$lang['sure_del']."')\" target=\"_blank\" style=\"cursor: pointer;\">";
			$tvars['vars']['[/del-news]'] = "</a>";
		} else {
			$tvars['regx']["'\[edit-news\].*?\[/edit-news\]'si"] = "";
			$tvars['regx']["'\[del-news\].*?\[/del-news\]'si"] = "";
		}
		exec_acts('news_short', '', $row, &$tvars);

		// Execute filters
		if (is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNews($row['id'], $row, $tvars, $callingParams); }
		}

		$tpl -> template($templateName, $templatePath);
		$tpl -> vars($templateName, $tvars);
		$output .= $tpl -> show($templateName);
	}
	unset($tvars);

	// Print "no news" if we didn't find any news
	if (!$nCount) {
		msg(array("type" => "info", "info" => $lang['msgi_no_news']));
		$limit_start = 2;
	}

	// Return output if we're in export mode
	if ($callingParams['style'] == 'export')
		return $output;

	// Add collected news into {mainlock}
	$template['vars']['mainblock'] .= $output;

	// Make navigation bar
	$navigations = getNavigations(tpl_dir.$config['theme']);
	$tpl -> template('pages', tpl_dir.$config['theme']);

	if (count($carray)) { $row['alt'] = category; }

	// Prev page link
	if ($limit_start && $nCount) {
		$prev = floor($limit_start / $showNumber);
		$row['page'] = $prev;
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',GetLink($which_link, $row), $navigations['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$no_prev = true;
	}

	$newsCount = $mysql->result($query['count']);
	$pages_count = ceil($newsCount / $showNumber);

	$pages = '';
	$maxNavigations 		= $config['newsNavigationsCount'];
	if ($maxNavigations < 1)
		$maxNavigations = 10;

	$sectionSize	= floor($maxNavigations / 3);
	if ($pages_count > $maxNavigations) {
		// We have more than 10 pages. Let's generate 3 parts
		// Situation #1: 1,2,3,4,[5],6 ... 128
		if ($cstart < ($sectionSize * 2)) {
			$pages .= generateNavigations($cstart, 1, $sectionSize * 2, $which_link, $row, $navigations);
			$pages .= " ... ";
			$pages .= generateNavigations($cstart, $pages_count-$sectionSize, $pages_count, $which_link, $row, $navigations);
		} elseif ($cstart > ($pages_count - $sectionSize * 2 + 1)) {
			$pages .= generateNavigations($cstart, 1, $sectionSize, $which_link, $row, $navigations);
			$pages .= " ... ";
			$pages .= generateNavigations($cstart, $pages_count-$sectionSize*2 + 1, $pages_count, $which_link, $row, $navigations);
		} else {
			$pages .= generateNavigations($cstart, 1, $sectionSize, $which_link, $row, $navigations);
			$pages .= " ... ";
			$pages .= generateNavigations($cstart, $cstart-1, $cstart+1, $which_link, $row, $navigations);
			$pages .= " ... ";
			$pages .= generateNavigations($cstart, $pages_count-$sectionSize, $pages_count, $which_link, $row, $navigations);
		}
	} else {
		// If we have less then 10 pages
		$pages .= generateNavigations($cstart, 1, $pages_count, $which_link, $row, $navigations);
	}
	$tvars['vars']['pages'] = $pages;

	// Next page link
	if (($prev + 2 <= $pages_count) && $nCount) {
		$row['page'] = $prev + 2;
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',GetLink($which_link, $row), $navigations['nextlink']));"<a href=\"".GetLink($which_link, $row)."\">$1</a>";
	} else {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		$no_next = true;
	}

	if ($nCount && ($pages_count>1)){
		$tpl -> vars('pages', $tvars);
		$template['vars']['mainblock'] .= $tpl -> show('pages');
	}
}



// ================================================================= //
// Module code                                                       //
// ================================================================= //

// Default "show news" function
function showNews() {
 global $catz, $catmap, $config, $userROW, $PFILTERS;
 // preload plugins
 load_extras('news');

 // Init array with configuration parameters
 $callingParams = array('customCategoryTemplate' => 1, 'customCategoryNumber' => 1);
 $callingCommentsParams = array();

 // Set default template path
 $templatePath = tpl_dir.$config['theme'];

 // If alt_name or ID is set - show full news
 if (altname || id) {
 	$callingParams['style'] = 'full';

	// Execute filters [ onBeforeShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('full'); }
	}


 	// Try to show news
	if (($row = news_showone(id?id:0, !id?altname:'', $callingParams)) !== false) {
		// Execute filters [ onAfterShow ]
		if (is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterNewsShow($row['id'], $row, array('style' => 'full')); }
		}
	 }
} else {
 	$callingParams['style'] = 'short';

	// Execute filters [ onBeforeShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('short'); }
	}

	news_showlist(array(), $callingParams);

	// Execute filters [ onAfterShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterShow('short'); }
	}
 }
}
