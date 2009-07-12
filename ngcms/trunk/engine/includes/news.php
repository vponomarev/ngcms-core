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
	global $timer;
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

	// Calculate exec time
        $tX1 = $timer->stop(4);

	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->showNewsPre($row['id'], $row, $callingParams); }

        $tX2 = $timer->stop(4);

	$tvars = newsFillVariables($row, 1, $_REQUEST['page'], (substr($callingParams['style'], 0, 6) == 'export')?1:0);

        $tX3 = $timer->stop(4);
	$timer->registerEvent('call showNewsPre() for [ '.($tX2 - $tX1).' ] sec');
	$timer->registerEvent('call newsFillVariables() for [ '.($tX3 - $tX2).' ] sec');

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

	// Calculate exec time
        $tX1 = $timer->stop(4);
	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			$timer->registerEvent('exec showNews // '.$k);
			$v->showNews($row['id'], $row, $tvars, $callingParams);
		}

        $tX2 = $timer->stop(4);
	$timer->registerEvent('call showNews() for [ '.($tX2 - $tX1).' ] sec');

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
// [PROCESS FILTER]
function newsProcessFilter($conditions) {
	//print "CALL newsProcessFilter(".var_export($conditions, true).")<br/>\n";

	if (!is_array($conditions))
		return '';

	switch (strtoupper($conditions[0])) {
		case 'AND' :
		case 'OR'  :
			$list = array();
			for($i = 1; $i <= count($conditions); $i++) {
				$rec = newsProcessFilter($conditions[$i]);
				//print ".result: ".var_export($rec, true)."<br/>\n";
				if ($rec != '')
					$list []= '('.$rec.')';
			}
			return join(' '.strtoupper($conditions[0]).' ', $list);
		case 'DATA':
			if ($conditions[1] == 'category') {
				switch ($conditions[2]) {
					case '=':
						return "`catid` regexp '[[:<:]](".intval($conditions[3]).")[[:>:]]'";
					default:
						return '';
				}
			} else {
				switch (strtoupper($conditions[2])) {
					case '=':
					case '>=':
					case '<=':
					case '>':
					case '<':
					case 'LIKE':
						return '`'.$conditions[1].'` '.$conditions[2].' '.db_squote($conditions[3]);
					case 'IN':
						if (is_array($conditions[3])) {
							$xt = array();
							foreach ($conditions[3] as $r)
								$xt[]= db_squote($r);

							return '`'.$conditions[1].'` IN ('.join(',', $xt).') ';
						}
						return '';
					case 'BETWEEN':
						if (is_array($conditions[3])) {
							return '`'.$conditions[1].'` BETWEEN '.db_squote($conditions[3][0]).' AND '.db_squote($conditions[3][1]);
						}
						return '';
				}
			}
			//
			break;
		case 'SQL' :
			return '('.$conditions[1].')';
		default: return '';
	}
}


// Params (newsID or alt_name should be filled)
// $filterConditions - conditions for filtering
// $paginationParams - config params for page display
//		'pluginName'	- name of plugin for call
//		'pluginHandler'	- handler for call
//		'params'		- standart param list for generateLink() call
//		'xparams'		- standart param list for generateLink() call
//		'paginator'		- set up for pagination
//			[0]		- variable name
//			[1]		- variable location 0 - params / 1 - xparams
//			[2]		- zero show flag 0 - don't show if zero / 1 - show anytime
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
//		'overrideSQLquery' => array - sets if PLUGIN wants to run it's own query
//		'page'		=> page number to show
//		'extendedReturn' => flag if we need to return an extended array:
//			'count' - count of found news
//			'data'  - data to be showed
//
function news_showlist($filterConditions = array(), $paginationParams = array(), $callingParams = array()){
	global $mysql, $tpl, $userROW, $catz, $catmap, $config, $vars, $parse, $template, $lang, $PFILTERS;
	global $year, $month, $day;
	global $timer;
	global $SYSTEM_FLAGS;


	$categoryList = array();

	// Generate SQL filter for 'WHERE' using filterConditions parameter
	$query['filter'] = newsProcessFilter(array('AND', array('DATA', 'approve', '=', '1'), $filterConditions));
	//print "<pre>".var_export($filterConditions, true)."</pre>";
	//print "<pre>".$query['filter']."</pre>";

	$query['where'] = $sql_filter;

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

	$cstart = $start_from = intval($callingParams['page']);

	if (!$cstart) { $cstart = 1; }

	$i			= $start_from?$start_from:0;

	$showNumber = ($config['number']>=1)?$config['number']:5;

	if (($callingParams['customCategoryNumber']) && count($carray) && ($fcat=$carray[0][0]) && $catmap[$fcat] && ($fcnt = $catz[$catmap[$fcat]]['number']))
		$showNumber = $fcnt;

	if ($config['number']<1)
		$config['number'] = 5;

	$limit_start = $cstart?($cstart-1)*$showNumber:0;
	$limit_count = $showNumber;

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
	$query['count']		=	"SELECT count(*) as count FROM ".prefix."_news WHERE ".$query['filter'];
	$query['result']	=	"SELECT * FROM ".prefix."_news WHERE ".$query['filter'].$query['orderby'];

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

	// Return output if we're in export mode
	if ($callingParams['style'] == 'export')
		return $output;

	// Print "no news" if we didn't find any news [ DON'T PRINT IN EXTENDED MODE ]
	if (!$nCount) {
		if (!$callingParams['extendedReturn']) {
			msg(array("type" => "info", "info" => $lang['msgi_no_news']));
		}
		$limit_start = 2;
	}

	// Make navigation bar
	$navigations = getNavigations(tpl_dir.$config['theme']);
	$tpl -> template('pages', tpl_dir.$config['theme']);

	if (count($carray)) { $row['alt'] = category; }

	// Prev page link
	if ($limit_start && $nCount) {
		$prev = floor($limit_start / $showNumber);
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',generatePageLink($paginationParams, $prev), $navigations['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$no_prev = true;
	}

	// List of pages
	$newsCount = $mysql->result($query['count']);
	$pages_count = ceil($newsCount / $showNumber);

	$maxNavigations 		= $config['newsNavigationsCount'];
	if ($maxNavigations < 1)
		$maxNavigations = 10;

	$tvars['vars']['pages'] = generatePagination($cstart, 1, $pages_count, $maxNavigations, $paginationParams, $navigations);

	// Next page link
	if (($prev + 2 <= $pages_count) && $nCount) {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',generatePageLink($paginationParams, $prev+2), $navigations['nextlink']));
	} else {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		$no_next = true;
	}

	if ($nCount && ($pages_count>1)){
		$tpl -> vars('pages', $tvars);
		$output .= $tpl -> show('pages');
	}

	// Add collected news into {mainlock}
	return ($callingParams['extendedReturn'])?array('count' => $newsCount, 'data' => $output):$output;
}



// ================================================================= //
// Module code                                                       //
// ================================================================= //

// Default "show news" function
function showNews($handlerName, $params) {
 global $catz, $catmap, $template, $config, $userROW, $PFILTERS, $lang, $SYSTEM_FLAGS;
 // preload plugins
 load_extras('news');

 // Init array with configuration parameters
 $callingParams = array('customCategoryTemplate' => 1, 'customCategoryNumber' => 1);
 $callingCommentsParams = array();

 // Set default template path
 $templatePath = tpl_dir.$config['theme'];

 // Check for FULL NEWS mode
 if ($handlerName == 'news') {
 	$callingParams['style'] = 'full';

	// Execute filters [ onBeforeShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('full'); }
	}

	// Determine passed params
	$vars = array();
	if (isset($params['id'])) {
		$vars['id'] = $params['id'];
	} else if (isset($params['altname'])) {
		$vars['altname'] = $params['altname'];
	} else if (isset($_REQUEST['id'])) {
		$vars['id'] = intval($_REQUEST['id']);
	} else {
		$vars['altname'] = $_REQUEST['altname'];
	}

 	// Try to show news
	if (($row = news_showone($vars['id'], $vars['altname'], $callingParams)) !== false) {
		// Execute filters [ onAfterShow ]
		if (is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterNewsShow($row['id'], $row, array('style' => 'full')); }
		}
	 }
} else {
 	$callingParams['style'] = 'short';
 	$callingParams['page']  = intval($params['page'])?intval($params['page']):intval($_REQUEST['page']);

	// Execute filters [ onBeforeShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('short'); }
	}

	switch ($handlerName) {
		case 'main':
			$SYSTEM_FLAGS['info']['title']['group'] = $lang['mainpage'];
		    $paginationParams = checkLinkAvailable('news', 'main')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'main', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'main'), 'xparams' => array(), 'paginator' => array('page', 1, false));

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'mainpage', '=', '1'), $paginationParams, $callingParams);
			break;

		case 'by.category':
			$category = '';
			if (isset($params['catid'])) {
				$category = $params['catid'];
			} else if (isset($params['category']) && isset($catz[$params['category']])) {
				$category = $catz[$params['category']]['id'];
			} else if (isset($_REQUEST['catid'])) {
				$category = $params['catid'];
			} else if (isset($_REQUEST['category']) && isset($catz[$_REQUEST['category']])) {
				$category = $catz[$_REQUEST['category']]['id'];
			}

			if (!$category) {
				msg(array("type" => "info", "info" => $lang['msgi_cat_not_found']));
				return false;
			}
			$SYSTEM_FLAGS['info']['title']['group'] = $catz[$catmap[$category]]['name'];

			// Set meta tags for category page
			if ($catz[$catmap[$category]]['description'])
				$SYSTEM_FLAGS['meta']['description'] = $catz[$catmap[$category]]['description'];
			if ($catz[$catmap[$category]]['keywords'])
				$SYSTEM_FLAGS['meta']['keywords']    = $catz[$catmap[$category]]['keywords'];

		    $paginationParams = checkLinkAvailable('news', 'by.category')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.category', 'params' => array('category' => $catmap[$category]), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.category'), 'xparams' => array('category' => $catmap[$category]), 'paginator' => array('page', 1, false));

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'category', '=', $category), $paginationParams, $callingParams);
			break;

		case 'by.day':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);
			$day	= intval(isset($params['day'])?$params['day']:$_REQUEST['day']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12)||($day < 1)||($day > 31))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("j Q Y", mktime("0", "0", "0", $month, $day, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.day')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.day', 'params' => array('day' => $day, 'month' => $month, 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.day'), 'xparams' => array('day' => $day, 'month' => $month, 'year' => $year), 'paginator' => array('page', 1, false));

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,$day,$year), mktime(23,59,59,$month,$day,$year))), $paginationParams, $callingParams);
			break;

		case 'by.month':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("F Y", mktime(0,0,0, $month, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.month')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.month', 'params' => array('month' => $month, 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.month'), 'xparams' => array('month' => $month, 'year' => $year), 'paginator' => array('page', 1, false));

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,1,$year), mktime(23,59,59,$month,date("t",mktime(0,0,0,$month,1,$year)),$year))), $paginationParams, $callingParams);
			break;

		case 'by.year':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);

			if (($year < 1970)||($year > 2100))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("Y", mktime(0,0,0, 1, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.year')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.year', 'params' => array('year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.year'), 'xparams' => array('year' => $year), 'paginator' => array('page', 1, false));

			$template['vars']['mainblock'] .= news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,1,1,$year), mktime(23,59,59,12,31,$year))), $paginationParams, $callingParams);
			break;
	}

	// Execute filters [ onAfterShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterShow('short'); }
	}
 }
}