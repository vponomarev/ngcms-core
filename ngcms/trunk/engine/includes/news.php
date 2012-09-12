<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
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
//		'setCurrentCategory' => update Current Category in system flags
//		'validateCategoryID' => if specified, check if content represents correct category ID(s) for this news
//		'validateCategoryAlt' => if specified, check if content represents correct category altname(s) for this news
//
//		Returns:
//			false    - when news is not found
//			data     - when news is found && export is used
//			news row - when news is found
function news_showone($newsID, $alt_name, $callingParams = array()) {
	global $mysql, $tpl, $userROW, $catz, $catmap, $config, $template, $parse, $vars, $lang, $SYSTEM_FLAGS, $PFILTERS;
	global $timer;
	global $year, $month, $day, $SUPRESS_TEMPLATE_SHOW;

	// Calculate exec time
	$tX0 = $timer->stop(4);

	if (isset($callingParams['emulate']) && is_array($callingParams['emulate'])) {
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

		// Load news from DB
		if (!is_array($row = $mysql->record("select * from ".prefix."_news where approve=1".(count($filter)?' and '.implode(" and ",$filter):'')))) {
			if (!$params['FFC']) {
				error404();
			}
			return false;
		}

		// Check if correct categories were specified [ only for SINGLE category display
		if ((isset($callingParams['validateCategoryID']) || isset($callingParams['validateCategoryAlt']) || 1) && $config['news_multicat_url']) {
			$nci = intval(array_shift(explode(",", $row['catid'])));
			$nca = ($nci)?$catmap[$nci]:'none';

			if ((isset($callingParams['validateCategoryID']) && ($callingParams['validateCategoryID'] != $nci)) || (isset($callingParams['validateCategoryAlt']) && ($callingParams['validateCategoryAlt'] != $nca))) {
				$redirectURL = newsGenerateLink($row, false, 0, true);
				coreRedirectAndTerminate($redirectURL);
			}
		}


		// Fetch attached images/files (if any)
		if ($row['num_files']) {
			$row['#files'] = $mysql->select("select * from ".prefix."_files where linked_ds = 1 and linked_id = ".db_squote($row['id']));
		} else {
			$row['#files'] = array();
		}

		if ($row['num_images']) {
			$row['#images'] = $mysql->select("select * from ".prefix."_images where linked_ds = 1 and linked_id = ".db_squote($row['id']));
		} else {
			$row['#images'] = array();
		}


		// Save some significant news flags for plugin processing
		$SYSTEM_FLAGS['news']['db.id'] = $row['id'];
		$SYSTEM_FLAGS['news']['db.categories'] = array();
		foreach (explode(',', $row['catid']) as $cid) {
			if (isset($catmap[$cid]))
				array_push($SYSTEM_FLAGS['news']['db.categories'], intval($cid));
		}
	}

	if ($callingParams['setCurrentCategory']) {
		// Fetch category ID from news
		$cid = intval(array_shift(explode(',', $row['catid'])));
		if ($cid && isset($catmap[$cid])) {
			// Save current category identifier
			$SYSTEM_FLAGS['news']['currentCategory.alt']	= $catz[$catmap[$cid]]['alt'];
			$SYSTEM_FLAGS['news']['currentCategory.id']		= $catz[$catmap[$cid]]['id'];
			$SYSTEM_FLAGS['news']['currentCategory.name']	= $catz[$catmap[$cid]]['name'];
		}
	}


	// preload plugins
	loadActionHandlers('news:show');
	loadActionHandlers('news:show:one');
	loadActionHandlers('news_full');

	// Calculate exec time
	$tX1 = $timer->stop(4);

	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			$v->showNewsPre($row['id'], $row, $callingParams);
			$timer->registerEvent('[FILTER] News->showNewsPre: call plugin ['.$k.']');
		}

    $tX2 = $timer->stop(4);
	$tvars = newsFillVariables($row, 1, isset($_REQUEST['page'])?$_REQUEST['page']:0, (substr($callingParams['style'], 0, 6) == 'export')?1:0);
	$tX3 = $timer->stop(4);
	$timer->registerEvent('call newsFillVariables() for [ '.($tX3 - $tX2).' ] sec');

	$tvars['vars']['date']		=	LangDate(timestamp, $row['postdate']);
	$tvars['vars']['views']		=	$row['views'];
	$tvars['vars']['comnum']	=	$row['com'];


	// Prepare list of linked files and images
	$callingParams['linkedFiles'] = array();
	$tvars['vars']['_files'] = array();
	foreach ($row['#files'] as $k => $v) {
		if ($v['linked_id'] == $row['id']) {
			$callingParams['linkedFiles']['ids']  []= $v['id'];
			$callingParams['linkedFiles']['data'] []= $v;
			$tvars['vars']['_files'] []= array(
				'plugin'		=> $v['plugin'],
				'pidentity'		=> $v['pidentity'],
				'url'			=> ($v['storage']?$config['attach_url']:$config['files_url']).'/'.$v['folder'].'/'.$v['name'],
				'name'			=> $v['name'],
				'origName'		=> secure_html($v['orig_name']),
				'description'	=> secure_html($v['description']),
			);
		}
	}

	$callingParams['linkedImages'] = array();
	$tvars['vars']['_images'] = array();
	foreach ($row['#images'] as $k => $v) {
		if ($v['linked_id'] == $row['id']) {
			$callingParams['linkedImages']['ids']  []= $k;
			$callingParams['linkedImages']['data'] []= $v;
			$tvars['vars']['_images'] []= array(
				'plugin'		=> $v['plugin'],
				'pidentity'		=> $v['pidentity'],
				'url'			=> ($v['storage']?$config['attach_url']:$config['images_url']).'/'.$v['folder'].'/'.$v['name'],
				'purl'			=> $v['preview']?(($v['storage']?$config['attach_url']:$config['images_url']).'/'.$v['folder'].'/thumb/'.$v['name']):null,
				'width'			=> $v['width'],
				'height'		=> $v['height'],
				'pwidth'		=> $v['p_width'],
				'pheight'		=> $v['p_height'],
				'name'			=> $v['name'],
				'origName'		=> secure_html($v['orig_name']),
				'description'	=> secure_html($v['description']),
				'flags'		=> array(
					'hasPreview'	=> $v['preview'],
				),
			);
		}
	}


	// Show icon of `MAIN` category for current news
	$masterCatID = intval(array_shift(explode(",", $row['catid'])));
	if (!isset($catmap[$masterCatID]))
		$masterCatID = 0;

	if ($masterCatID && isset($catmap[$masterCatID]) && trim($catz[$catmap[$masterCatID]]['icon'])) {
		$tvars['vars']['icon']		= trim($catz[$catmap[$masterCatID]]['icon']);
		$tvars['vars']['[icon]']	= '';
		$tvars['vars']['[/icon]']	= '';
	} else {
		$tvars['vars']['icon']		=	'';
		$tvars['regx']["'\[icon\].*?\[/icon\]'si"] = '';
	}

	// Show edit/detele news buttons
	if (is_array($userROW) && ($row['author_id'] == $userROW['id'] || $userROW['status'] == "1" || $userROW['status'] == "2")) {
		$tvars['vars']['[edit-news]'] = "<a href=\"".admin_url."/admin.php?mod=news&amp;action=edit&amp;id=".$row['id']."\" target=\"_blank\">";
		$tvars['vars']['[/edit-news]'] = "</a>";
		$tvars['vars']['[del-news]'] = "<a onclick=\"confirmit('".admin_url."/admin.php?mod=news&amp;subaction=do_mass_delete&amp;token=".genUToken('admin.news.edit')."&amp;selected_news[]=".$row['id']."', '".$lang['sure_del']."')\" target=\"_blank\" style=\"cursor: pointer;\">";
		$tvars['vars']['[/del-news]'] = "</a>";
	} else {
		$tvars['regx']["'\\[edit-news\\].*?\\[/edit-news\\]'si"] = "";
		$tvars['regx']["'\\[del-news\\].*?\\[/del-news\\]'si"] = "";
	}

	$newsid				=	$row['id'];
	$allow_comments		=	$row['allow_com'];
	$row['views']		=	$row['views']+1;

	// Calculate exec time
	$tX1 = $timer->stop(4);

	// Execute filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			$timer->registerEvent('[FILTER] News->showNews: call plugin ['.$k.']');
			$v->showNews($row['id'], $row, $tvars, $callingParams);
		}

	$tX2 = $timer->stop(4);
	$timer->registerEvent('Show single news: full exec time [ '.($tX2 - $tX0).' ] sec');

	// Check if we need only to export body
	if ($callingParams['style'] == 'export_body')
		return $tvars['vars']['short-story'].' '.$tvars['vars']['full-story'];

	if ($callingParams['style'] == 'export_short')
		return $tvars['vars']['short-story'];

	if ($callingParams['style'] == 'export_full')
		return $tvars['vars']['full-story'];


	// Update visits counter if we're not in emulation mode
	if ((!$callingParams['emulate'])&&($callingParams['style'] == 'full')&&(intval($_REQUEST['page'])<2)) {
		$cmode = intval($config['news_view_counters']);
		if ($cmode > 1) {
			// Delayed update of counters
			$mysql->query("insert into ".prefix."_news_view (id, cnt) values (".db_squote($row['id']).", 1) on duplicate key update cnt = cnt + 1");
		} else if ($cmode > 0) {
			$mysql->query("update ".prefix."_news set views=views+1 where id = ".db_squote($row['id']));
		}
	}


	// Make temlate procession - auto/manual overriding
	// -> calling style
	if (!$callingParams['style']) $callingParams['style'] = 'full';

	// -> desired template - override template if needed
	if ($callingParams['overrideTemplateName']) {
		$templateName = $callingParams['overrideTemplateName'];
	} else {
		// -> generate template name for selected style
		switch ($callingParams['style']) {
			case 'short' : $templateName = 'news.short'; break;
			case 'full'  : $templateName = 'news.full'; break;
			case 'print' : $templateName = 'news.print'; break;
			default      : $templateName = '';
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
			if (is_dir($templatePath.'/ncustom/'.$ctname)) {
				$templatePath = $templatePath.'/ncustom/'.$ctname;
				if (file_exists($templatePath.'/ncustom/'.$ctname.'/main.tpl')) {
					$SYSTEM_FLAGS['template.main.path'] = $templatePath.'/ncustom/'.$ctname;
				}
			}
		}
	}

	// Load & configure template
	$tpl -> template($templateName, $templatePath);
	$tpl -> vars($templateName, $tvars);

	// No comments/meta in emulation or export mode
	if ((is_array($callingParams['emulate']))||($callingParams['style'] == 'export'))
		return $tpl -> show($templateName);

	// Set meta tags for news page
	$SYSTEM_FLAGS['meta']['description'] = ($row['description'] != '')?$row['description']:$catz[$catmap[$masterCatID]]['description'];
	$SYSTEM_FLAGS['meta']['keywords']    = ($row['keywords'] != '')?$row['keywords']:$catz[$catmap[$masterCatID]]['keywords'];

	// Prepare title
	//$SYSTEM_FLAGS['info']['title']['group']	= $config["category_link"]?GetCategories($row['catid'], true):LangDate(timestamp, $row['postdate']);
	$SYSTEM_FLAGS['info']['title']['group']	= GetCategories($row['catid'], true);
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
			for($i = 1; $i < count($conditions); $i++) {
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
//			[!!!] USES CUSTOM TEMPLATE FOR FIRST CATEGORY FROM NEWS [!!!]
//		'regenShortNews' =>
//			'mode' 		=> If we should generate `on the fly` short news from long one
//				* ''		- Leave short news as is [ default ]
//				* 'auto'	- Generate ShortNews from long only if ShortNews is empty
//				* 'force'	- Generate ShortNews from long in any case
//			'len'  			=> Length in chars of part of LongNews that will be used for regeneration (in case if regeneration is active)
//			'finisher'		=> chars that will be added into the end to indicate that this is truncated line ( default = '...' )
//		'showNumber'	=> set number of news to show per page
//		'newsOrder'		=> set news order
//		'overrideSQLquery' => array - sets if PLUGIN wants to run it's own query
//		'page'		=> page number to show
//		'extendedReturn' => flag if we need to return an extended array:
//			'count' - count of found news
//			'data'  - data to be showed
//		'searchFlag'	=> flag if we want to use non-mondatory template 'news.search.tpl' [!!only for style = 'short' !!]
//		'pin'	-	Way of sorting for PINNED news
//			0	-	`pinned` (for MainPage)
//			1	-	`catpinned`	(for Categories page)
//			2	-	without taking PIN into account
//
function news_showlist($filterConditions = array(), $paginationParams = array(), $callingParams = array()){
	global $mysql, $tpl, $userROW, $catz, $catmap, $config, $vars, $parse, $template, $lang, $PFILTERS;
	global $year, $month, $day;
	global $timer;
	global $SYSTEM_FLAGS, $TemplateCache;


	$categoryList = array();

	// Generate SQL filter for 'WHERE' using filterConditions parameter
	$query['filter'] = newsProcessFilter(array('AND', array('DATA', 'approve', '=', '1'), $filterConditions));
	//print "<pre>".var_export($filterConditions, true)."</pre>";
	//print "<pre>".$query['filter']."</pre>";

	// Make temlate procession - auto/manual overriding
	// -> calling style
	if (!$callingParams['style']) $callingParams['style'] = 'short';

	// -> desired template - override template if needed
	if (isset($callingParams['overrideTemplateName']) && $callingParams['overrideTemplateName']) {
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

	if ($cstart < 1) { $cstart = 1; }

	$i			= $start_from?$start_from:0;

	$showNumber = ($config['number']>=1)?$config['number']:5;
	if (isset($callingParams['showNumber']) && (intval($callingParams['showNumber'])>0))
			$showNumber = intval($callingParams['showNumber']);

	$limit_start = $cstart?($cstart-1)*$showNumber:0;
	$limit_count = $showNumber;

	$orderBy = isset($callingParams['newsOrder'])?$callingParams['newsOrder']:'id desc';
	if (!in_array($orderBy, array('id desc', 'id asc', 'postdate desc', 'postdate asc', 'title desc', 'title asc')))
		$orderBy = 'id desc';

	switch ($callingParams['pin']) {
		case 1:		$orderBy = 'catpinned desc, '.$orderBy;	break;
		case 2:		break;
		default:	$orderBy = 'pinned desc, '.$orderBy;	break;
	}

	//$orderBy = 'pinned desc, '.$orderBy;
	$query['orderby'] = " order by ".$orderBy." limit ".$limit_start.",".$limit_count;

	// ===================================================================
	$query['count']		=	"SELECT count(*) as count FROM ".prefix."_news WHERE ".$query['filter'];
	$query['result']	=	"SELECT * FROM ".prefix."_news WHERE ".$query['filter'].$query['orderby'];

	// preload plugins
	loadActionHandlers('news:show');
	loadActionHandlers('news:show:list');
	loadActionHandlers('news_short');


	$nCount = 0;
	$output = '';

	// Call `SELECT` query
	$selectResult = $mysql->select($query['result'], 1);

	// List of pages
	$newsCount = $mysql->result($query['count']);
	$pages_count = ceil($newsCount / $showNumber);


	// Prepare TOTAL data for plugins
	// = count		- count of fetched news
	// = totalCount	- total count of news
	// = pagesCount	- total count of pages that will be displayed
	// = result		- result of the query (array)
	// = ids		- array with IDs of fetched news

	$callingParams['query'] = array(
		'count'			=> count($selectResult),
		'result'		=> $selectResult,
		'totalCount'	=> $newsCount,
		'pagesCount'	=> $pages_count,
	);

	// Reference for LINKED images and files
	$callingParams['linkedImages'] = array(
		'ids'	=> array(),
		'data'	=> array(),
	);

	$callingParams['linkedFiles'] = array(
		'ids'	=> array(),
		'data'	=> array(),
	);

	// List of news that have linked images
	$nilink = array();
	$nflink = array();

	foreach ($selectResult as $row) {
		$callingParams['query']['ids'][] = $row['id'];
		if ($row['num_images'])
			$nilink []= $row['id'];
		if ($row['num_files'])
			$nflink []= $row['id'];

	}


	// Load linked images
	$linkedImages = array();
	if (count($nilink)) {
		foreach ($mysql->select("select * from ".prefix."_images where (linked_ds = 1) and (linked_id in (".join(", ", $nilink)."))", 1) as $nirow) {
			$linkedImages['ids'] []= $nirow['id'];
			$linkedImages['data'][$nirow['id']] = $nirow;
		}
	}

	// Load linked files
	$linkedFiles = array();
	if (count($nflink)) {
		foreach ($mysql->select("select * from ".prefix."_files where (linked_ds = 1) and (linked_id in (".join(", ", $nflink)."))", 1) as $nirow) {
			$linkedFiles['ids'] []= $nirow['id'];
			$linkedFiles['data'][$nirow['id']] = $nirow;
		}
	}

	// Execute filters
	if (is_array($PFILTERS['news'])) {
		// Special handler for linked images/files
		$callingParams['linkedImages']	= $linkedImages;
		$callingParams['linkedFiles']	= $linkedFiles;

		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShowlist($callingParams); }
	}


	// Main processing cycle
	foreach ($selectResult as $row) {
		$i++;
		$nCount++;

		// Give 'news in order' field to plugins
		$callingParams['nCount'] = $nCount;

		// Execute filters
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNewsPre($row['id'], $row, $callingParams); }

		$tvars = newsFillVariables($row, 0, isset($_REQUEST['page'])?intval($_REQUEST['page']):0, 0, isset($callingParams['regenShortNews'])?$callingParams['regenShortNews']:array());

		$tvars['vars']['alternating'] = ($i%2)?'odd':'even';

		$tvars['vars']['date'] = LangDate(timestamp, $row['postdate']);
		$tvars['vars']['views'] = $row['views'];

		// Prepare list of linked files and images
		$callingParams['linkedFiles'] = array();
		$tvars['vars']['_files'] = array();
		foreach ($linkedFiles['data'] as $k => $v) {
			if ($v['linked_id'] == $row['id']) {
				$callingParams['linkedFiles']['ids']  []= $v['id'];
				$callingParams['linkedFiles']['data'] []= $v;
				$tvars['vars']['_files'] []= array(
					'plugin'		=> $v['plugin'],
					'pidentity'		=> $v['pidentity'],
					'url'			=> ($v['storage']?$config['attach_url']:$config['files_url']).'/'.$v['folder'].'/'.$v['name'],
					'name'			=> $v['name'],
					'origName'		=> secure_html($v['orig_name']),
					'description'	=> secure_html($v['description']),
				);
			}
		}

		$callingParams['linkedFiles'] = array();
		$tvars['vars']['_images'] = array();
		foreach ($linkedImages['data'] as $k => $v) {
			if ($v['linked_id'] == $row['id']) {
				$callingParams['linkedImages']['ids']  []= $v['id'];
				$callingParams['linkedImages']['data'] []= $v;
				$tvars['vars']['_images'] []= array(
					'plugin'		=> $v['plugin'],
					'pidentity'		=> $v['pidentity'],
					'url'			=> ($v['storage']?$config['attach_url']:$config['images_url']).'/'.$v['folder'].'/'.$v['name'],
					'purl'			=> $v['preview']?(($v['storage']?$config['attach_url']:$config['images_url']).'/'.$v['folder'].'/thumb/'.$v['name']):null,
					'width'			=> $v['width'],
					'height'		=> $v['height'],
					'pwidth'		=> $v['p_width'],
					'pheight'		=> $v['p_height'],
					'name'			=> $v['name'],
					'origName'		=> secure_html($v['orig_name']),
					'description'	=> secure_html($v['description']),
					'flags'		=> array(
						'hasPreview'	=> $v['preview'],
					),
				);
			}
		}

		//print "LinkedFiles (".$row['id']."): <pre>".var_export($tvars['vars']['#files'], true)."</pre>";
		//print "LinkedImages (".$row['id']."): <pre>".var_export($tvars['vars']['#images'], true)."</pre>";
		// Print icon if only one parent category
		if (isset($row['catid']) && $row['catid'] && !stristr(",", $row['catid']) && isset($catmap[$row['catid']]) && ($catalt = $catmap[$row['catid']]) && isset($catz[$catalt]['icon']) && $catz[$catalt]['icon']) {
			$tvars['vars']['icon'] = $catz[$catalt]['icon'];
			$tvars['vars']['[icon]'] = '';
			$tvars['vars']['[/icon]'] = '';
		} else {
			$tvars['vars']['icon'] = '';
			$tvars['regx']["'\\[icon\\].*?\\[/icon\\]'si"] = '';
		}

		if (is_array($userROW) && ($userROW['id'] == $row['author_id'] || ($userROW['status'] == 1 || $userROW['status'] == 2))) {
			$tvars['vars']['[edit-news]'] = "<a href=\"".admin_url."/admin.php?mod=news&amp;action=edit&amp;id=".$row['id']."\" target=\"_blank\">";
			$tvars['vars']['[/edit-news]'] = "</a>";
			$tvars['vars']['[del-news]'] = "<a onclick=\"confirmit('".admin_url."/admin.php?mod=news&amp;action=manage&amp;subaction=mass_delete&amp;token=".genUToken('admin.news.edit')."&amp;selected_news[]=".$row['id']."', '".$lang['sure_del']."')\" target=\"_blank\" style=\"cursor: pointer;\">";
			$tvars['vars']['[/del-news]'] = "</a>";
		} else {
			$tvars['regx']["'\[edit-news\].*?\[/edit-news\]'si"] = "";
			$tvars['regx']["'\[del-news\].*?\[/del-news\]'si"] = "";
		}
		//exec_acts('news_short', '', $row, &$tvars);

		// Execute filters
		if (is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNews($row['id'], $row, $tvars, $callingParams); }
		}

		// Set default template path
		$templatePath = tpl_dir.$config['theme'];

		// -> desired template path - override path if needed
		if (isset($callingParams['overrideTemplatePath']) && $callingParams['overrideTemplatePath']) {
			$templatePath = $callingParams['overrideTemplatePath'];
		} else if (isset($callingParams['customCategoryTemplate']) && $callingParams['customCategoryTemplate']) {
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

		// Hack for 'automatic search mode'
		$currentTemplateName = $templateName;
		// switch to `search` template if no templateName was overrided AND style is search AND searchFlag is set AND search template file exists
		if (isset($callingParams['searchFlag']) && ($callingParams['searchFlag']) && (!isset($callingParams['overrideTemplatePath'])) && ($callingParams['style'] == 'short') && (@file_exists($templatePath.'/news.search.tpl'))) {
			$currentTemplateName = 'news.search';
		}

		$tpl -> template($currentTemplateName, $templatePath);
		$tpl -> vars($currentTemplateName, $tvars);
		$output .= $tpl -> show($currentTemplateName);
	}
	unset($tvars);

	// Return output if we're in export mode
	if ($callingParams['style'] == 'export')
		return $output;

	// Print "no news" if we didn't find any news [ DON'T PRINT IN EXTENDED MODE ]
	if (!$nCount) {
		if (!isset($callingParams['extendedReturn']) || !$callingParams['extendedReturn']) {
			msg(array("type" => "info", "info" => $lang['msgi_no_news']));
		}
		$limit_start = 2;
	}

	// Make navigation bar
	templateLoadVariables(true);
	$navigations = $TemplateCache['site']['#variables']['navigation'];
	$tpl -> template('pages', tpl_dir.$config['theme']);

	// Prev page link
	if ($limit_start && $nCount) {
		$prev = floor($limit_start / $showNumber);
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',generatePageLink($paginationParams, $prev), $navigations['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$prev = 0;
		$no_prev = true;
	}

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
	return (isset($callingParams['extendedReturn']) && $callingParams['extendedReturn'])?array('count' => $newsCount, 'data' => $output):$output;
}



// ================================================================= //
// Module code                                                       //
// ================================================================= //

// Default "show news" function
function showNews($handlerName, $params) {
 global $catz, $catmap, $template, $config, $userROW, $PFILTERS, $lang, $SYSTEM_FLAGS, $SUPRESS_TEMPLATE_SHOW, $tpl, $parse, $currentCategory;
 // preload plugins
 load_extras('news');

 // Init array with configuration parameters
 $callingParams = array('customCategoryTemplate' => 1, 'customCategoryNumber' => 1, 'setCurrentCategory' => 1);
 $callingCommentsParams = array();

 // Set default template path
 $templatePath = tpl_dir.$config['theme'];

 // Check for FULL NEWS mode
 if (($handlerName == 'news')||($handlerName == 'print')) {
 	$flagPrint = ($handlerName == 'print')?true:false;
	if ($flagPrint)
		$SUPRESS_TEMPLATE_SHOW = true;

	$callingParams['style'] = $flagPrint?'print':'full';

	// Execute filters [ onBeforeShow ] ** ONLY IN 'news' mode. In print mode we don't use it
	if (!$flagPrint && is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onBeforeShow('full'); }
	}

	// Determine passed params
	$vars = array('id' => 0, 'altname' => '');
	if (isset($params['id'])) {
		$vars['id'] = $params['id'];
	} else if (isset($params['zid'])) {
		$vars['id'] = $params['zid'];
	} else if (isset($params['altname'])) {
		$vars['altname'] = $params['altname'];
	} else if (isset($_REQUEST['id'])) {
		$vars['id'] = intval($_REQUEST['id']);
	} else if (isset($_REQUEST['zid'])) {
		$vars['id'] = intval($_REQUEST['zid']);
	} else {
		$vars['altname'] = $_REQUEST['altname'];
	}

 	if (isset($params['category'])) {
 		$callingParams['validateCategoryAlt'] = $params['category'];
 	}
 	if (isset($params['catid'])) {
 		$callingParams['validateCategoryID'] = $params['catid'];
 	}


 	// Try to show news
	if (($row = news_showone($vars['id'], $vars['altname'], $callingParams)) !== false) {
		// Execute filters [ onAfterShow ] ** ONLY IN 'news' mode. In print mode we don't use it
		if (!$flagPrint && is_array($PFILTERS['news'])) {
			foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterNewsShow($row['id'], $row, array('style' => 'full')); }
		}
	 }
} else {
 	$callingParams['style'] = 'short';
 	$callingParams['page']  = (isset($params['page']) && intval($params['page']))?intval($params['page']):(isset($_REQUEST['page'])?intval($_REQUEST['page']):0);

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

			if ($config['default_newsorder'] != '')
				$callingParams['newsOrder'] = $config['default_newsorder'];

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

			// We can't show unexisted categories
			if (!$category || !isset($catmap[$category])) {
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			$currentCategory = $catz[$catmap[$category]];

			// Save current category identifier
			$SYSTEM_FLAGS['news']['currentCategory.alt']	= $currentCategory['alt'];
			$SYSTEM_FLAGS['news']['currentCategory.id']		= $currentCategory['id'];
			$SYSTEM_FLAGS['news']['currentCategory.name']	= $currentCategory['name'];

			// Set title
			$SYSTEM_FLAGS['info']['title']['group'] = $currentCategory['name'];

			// Set meta tags for category page
			if ($currentCategory['description'])
				$SYSTEM_FLAGS['meta']['description'] = $currentCategory['description'];
			if ($currentCategory['keywords'])
				$SYSTEM_FLAGS['meta']['keywords']    = $currentCategory['keywords'];

			// Set personal `order by` for category
			if ($currentCategory['number'])
					$callingParams['showNumber'] = $currentCategory['number'];

			// Set number of `news per page` if this parameter is filled in category
			if ($currentCategory['orderby'])
				$callingParams['newsOrder'] = $currentCategory['orderby'];

			$paginationParams = checkLinkAvailable('news', 'by.category')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.category', 'params' => array('category' => $catmap[$category]), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.category'), 'xparams' => array('category' => $catmap[$category]), 'paginator' => array('page', 1, false));

			// Sort news for `category` mode
			$callingParams['pin'] = 1;

			// Generate news content
			$newsContent = news_showlist(array('DATA', 'category', '=', $category), $paginationParams, $callingParams);

			// Check if template 'news.table.tpl' exists [first check custom category template (if set), after that - common template for the whole site
			$ntTemplatePath = '';
			$ntTemplateFound = false;
			if ($currentCategory['tpl'] && file_exists(tpl_dir.$config['theme'].'/ncustom/'.$currentCategory['tpl'].'/news.table.tpl')) {
				$ntTemplateFound	= true;
				$ntTemplatePath		= tpl_dir.$config['theme'].'/ncustom/'.$currentCategory['tpl'];
			} else if (file_exists(tpl_dir.$config['theme'].'/news.table.tpl')) {
				$ntTemplateFound	= true;
				$ntTemplatePath		= tpl_dir.$config['theme'];
			}

			if ($ntTemplateFound) {
				$tpl->template('news.table', $ntTemplatePath);
				$tnvars = array('vars' => array(
					'category.id'	=> $currentCategory['id'],
					'category.alt'	=> secure_html($currentCategory['alt']),
					'category.name'	=> secure_html($currentCategory['name']),
					'category.info'	=> ($config['use_bbcodes'])?$parse -> bbcodes($currentCategory['info']):$currentCategory['info'],
					'entries'		=> $newsContent,
				));

				if ($currentCategory['image_id'] && $currentCategory['icon_id']) {
					$tnvars['regx']['#\[icon\](.*?)\[\/icon\]#is'] = '$1';
					$tnvars['vars']['icon.url']				= $config['attach_url'].'/'.$currentCategory['icon_folder'].'/'.$currentCategory['icon_name'];;
					$tnvars['vars']['icon.width']			= $row['icon_width'];
					$tnvars['vars']['icon.height']			= $row['icon_height'];
					if ($currentCategory['icon_preview']) {
						$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '$1';
						$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '';
						$tnvars['vars']['icon.preview.url']		= $config['attach_url'].'/'.$currentCategory['icon_folder'].'/thumb/'.$currentCategory['icon_name'];;
						$tnvars['vars']['icon.preview.width']	= $currentCategory['icon_pwidth'];
						$tnvars['vars']['icon.preview.height']	= $currentCategory['icon_pheight'];
					} else {
						$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '';
						$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '$1';
					}
				} else {
					$tnvars['regx']['#\[icon\](.*?)\[\/icon\]#is'] = '';
					$tnvars['regx']['#\[icon\.preview\](.*?)\[\/icon.preview\]#is'] = '';
					$tnvars['regx']['#\[icon\.nopreview\](.*?)\[\/icon.nopreview\]#is'] = '';
				}

				$tpl->vars('news.table', $tnvars);
				$template['vars']['mainblock'] .= $tpl->show('news.table');
			} else {
				$template['vars']['mainblock'] .= $newsContent;
			}

			break;

		case 'by.day':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);
			$day	= intval(isset($params['day'])?$params['day']:$_REQUEST['day']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12)||($day < 1)||($day > 31))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("j Q Y", mktime("0", "0", "0", $month, $day, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.day')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.day', 'params' => array('day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.day'), 'xparams' => array('day' => sprintf('%02u', $day), 'month' => sprintf('%02u', $month), 'year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,$day,$year), mktime(23,59,59,$month,$day,$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;

		case 'by.month':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);
			$month	= intval(isset($params['month'])?$params['month']:$_REQUEST['month']);

			if (($year < 1970)||($year > 2100)||($month < 1)||($month > 12))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("F Y", mktime(0,0,0, $month, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.month')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.month', 'params' => array('month' => sprintf('%02u', $month), 'year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.month'), 'xparams' => array('month' => sprintf('%02u', $month), 'year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$month,1,$year), mktime(23,59,59,$month,date("t",mktime(0,0,0,$month,1,$year)),$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;

		case 'by.year':
			$year	= intval(isset($params['year'])?$params['year']:$_REQUEST['year']);

			if (($year < 1970)||($year > 2100))
				return false;

			$SYSTEM_FLAGS['info']['title']['group'] = LangDate("Y", mktime(0,0,0, 1, 1, $year));
		    $paginationParams = checkLinkAvailable('news', 'by.year')?
		    			array('pluginName' => 'news', 'pluginHandler' => 'by.year', 'params' => array('year' => $year), 'xparams' => array(), 'paginator' => array('page', 0, false)):
		    			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'news', 'handler' => 'by.year'), 'xparams' => array('year' => $year), 'paginator' => array('page', 1, false));

			// Use extended return mode
			$callingParams['extendedReturn'] = true;
			$output = news_showlist(array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,1,1,$year), mktime(23,59,59,12,31,$year))), $paginationParams, $callingParams);

			// Check if there're output data
			if ($output['count'] > 0) {
				$template['vars']['mainblock'] .= $output['data'];
			} else {
				// No data, stop execution
				if (!$params['FFC']) {
					error404();
				}
				return false;
			}
			break;
	}

	// Execute filters [ onAfterShow ]
	if (is_array($PFILTERS['news'])) {
		foreach ($PFILTERS['news'] as $k => $v) { $v->onAfterShow('short'); }
	}
 }
}