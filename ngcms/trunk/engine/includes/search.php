<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: search.php
// Description: News search
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('search', 'site');

//
// Make search
//
include_once root.'includes/news.php';

function search_news(){
	global $catz, $catmap, $mysql, $config, $userROW, $tpl, $parse, $template, $lang, $PFILTERS, $SYSTEM_FLAGS;

	$SYSTEM_FLAGS['info']['title']['group'] = $lang['search.title'];

	// PREPARE FILTER RULES FOR NEWS SHOWER
	$filter = array();

	// AUTHOR
	if ($_REQUEST['author']) {
		array_push($filter, array('DATA', 'author', '=', $_REQUEST['author']));
	}

	// CATEGORY
	if ($_REQUEST['catid']) {
		array_push($filter, array('DATA', 'category', '=', $_REQUEST['catid']));
	}

	// POST DATE
	if ($_REQUEST['postdate'] && preg_match('#^(\d{4})(\d{2})$#', $_REQUEST['postdate'], $dv)) {
		if (($dv[1] >= 1970)&&($dv[1] <= 2100)&&($dv[2] >=1)&&($dv[2] <= 12)) {
			array_push($filter, array('OR',
				array('DATA', 'postdate', 'BETWEEN', array(mktime(0,0,0,$dv[2],1,$dv[1]), mktime(23,59,59,$dv[2],date("t",mktime(0,0,0,$dv[2],1,$dv[1])),$dv[1]))),
			//	array('DATA', 'editdate', 'BETWEEN', array(mktime(0,0,0,$dv[2],1,$dv[1]), mktime(23,59,59,$dv[2],date("t",mktime(0,0,0,$dv[2],1,$dv[1])),$dv[1]))),
			));

		}
	}


	// TEXT
	$search = array();
	$search_words = array();
	if ($_REQUEST['search']) {
		$search_words	= preg_split('#[ \,\.]+#', trim(str_replace(array('<', '>', '%', '$', '#'), '', substr($_REQUEST['search'], 0, 64))), -1, PREG_SPLIT_NO_EMPTY);

		foreach ($search_words as $s) {
			array_push($search,
					array(	'OR',
							array('DATA', 'title', 'like', '%'.mysql_real_escape_string($s).'%'),
							array('DATA', 'content', 'like', '%'.mysql_real_escape_string($s).'%')
					)
			);
		}

		if (count($search) > 1) { array_unshift($search, 'AND'); }
		if (count($search) == 1) { $search = $search[0]; }

		array_push($filter, $search);
	}

	if (count($filter) > 1) { array_unshift($filter, 'AND'); }
	if (count($filter) == 1) { $filter = $filter[0]; }

	//print "FILTER: <pre>".var_export($filter, true)."</pre>\n";

	// Configure pagination
	$paginationParams = array('pluginName' => 'search', 'xparams' => array('search' => $_REQUEST['search'], 'author' => $_REQUEST['author'], 'catid' => $_REQUEST['catid']), 'paginator' => array('page', 1, false));

	// Configure display params
	$callingParams = array('style' => 'short', 'extendedReturn' => true);
	if ($_REQUEST['page']) {
		$callingParams['page'] = intval($_REQUEST['page']);
	}

	// Call SEARCH only if search words are entered
	if (count($search_words)) {
		$found = news_showlist($filter, $paginationParams, $callingParams);
	} else {
		$found = array('count' => 0, 'data' => false);
	}

	// Now let's show SEARCH basic template
	$tpl -> template('search.table', tpl_dir.$config['theme']);
	$tvars = array();
	$tvars['vars']['author'] = secure_html($_REQUEST['author']);
	$tvars['vars']['search'] = secure_html($_REQUEST['search']);

	$tvars['vars']['padeg2'] = Padeg($found['count'], $lang['found_skl2']);
	$tvars['vars']['padeg1'] = Padeg($found['count'], $lang['found_skl1']);
	$tvars['vars']['count'] = $found['count'];
	$tvars['vars']['form_url'] = generateLink('search', '', array());

	$tvars['regx']['#\[found\](.+?)\[/found\]#is'] = (isset($_REQUEST['search']) && count($search_words) && $found['count'])?'$1':'';
	$tvars['regx']['#\[notfound\](.+?)\[/notfound\]#is'] = (isset($_REQUEST['search']) && count($search_words) && !$found['count'])?'$1':'';
	$tvars['regx']['#\[error\](.+?)\[/error\]#is'] = (isset($_REQUEST['search']) && !count($search_words))?'$1':'';

	// Make category list
	$tvars['vars']['catlist'] = makeCategoryList( array ('name' => 'catid', 'selected' => $_REQUEST['catid'], 'doempty' => 1, 'class' => 'mw_search_f'));

	// Results of search
	$tvars['vars']['entries'] = $found['data'];
	// Make month list
	$mnth_list = explode(",", $lang['months']);
	foreach ($mysql->select("SELECT month(from_unixtime(postdate)) as month, year(from_unixtime(postdate)) as year, COUNT(id) AS cnt FROM ".prefix."_news WHERE approve = '1' GROUP BY year(from_unixtime(postdate)), month(from_unixtime(postdate)) ORDER BY id DESC") as $row) {

		$pd_value = sprintf("%04u%02u",$row['year'],$row['month']);
		$pd_text  = $mnth_list[$row['month']-1].' '.$row['year'];

		$tvars['vars']['datelist'] .= "<option value=\"".$pd_value."\"".(($pd_value == $_REQUEST['postdate'])?' selected':'').">".$pd_text."</option>";
	}

	$tpl -> vars('search.table', $tvars);
	$template['vars']['mainblock'] .= $tpl -> show('search.table');
}