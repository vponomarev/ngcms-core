<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
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
include_once root . 'includes/news.php';

function search_news() {

	global $catz, $catmap, $mysql, $config, $userROW, $tpl, $parse, $template, $lang, $PFILTERS, $SYSTEM_FLAGS, $TemplateCache;

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
		if (($dv[1] >= 1970) && ($dv[1] <= 2100) && ($dv[2] >= 1) && ($dv[2] <= 12)) {
			array_push($filter, array(
				'OR',
				array('DATA', 'postdate', 'BETWEEN', array(mktime(0, 0, 0, $dv[2], 1, $dv[1]), mktime(23, 59, 59, $dv[2], date("t", mktime(0, 0, 0, $dv[2], 1, $dv[1])), $dv[1]))),
			));

		}
	}

	// TEXT
	$search = array();
	$search_words = array();
	if ($_REQUEST['search']) {
		$search_words = preg_split('#[ \,\.]+#', trim(str_replace(array('<', '>', '%', '$', '#'), '', mb_substr($_REQUEST['search'], 0, 64))), -1, PREG_SPLIT_NO_EMPTY);

		foreach ($search_words as $s) {
			array_push($search,
				array(
					'OR',
					array('DATA', 'title', 'like', '%' . $mysql->db_quote($s) . '%'),
					array('DATA', 'content', 'like', '%' . $mysql->db_quote($s) . '%')
				)
			);
		}

		if (count($search) > 1) {
			array_unshift($search, 'AND');
		}
		if (count($search) == 1) {
			$search = $search[0];
		}

		array_push($filter, $search);
	}

	if (count($filter) > 1) {
		array_unshift($filter, 'AND');
	}
	if (count($filter) == 1) {
		$filter = $filter[0];
	}

	//print "FILTER: <pre>".var_export($filter, true)."</pre>\n";
	load_extras('news');
	load_extras('news:search');

	// Configure pagination
	$paginationParams = array('pluginName' => 'search', 'xparams' => array('search' => $_REQUEST['search'], 'author' => $_REQUEST['author'], 'catid' => $_REQUEST['catid'], 'postdate' => $_REQUEST['postdate']), 'paginator' => array('page', 1, false));

	// Configure display params
	$callingParams = array('style' => 'short', 'searchFlag' => true, 'extendedReturn' => true, 'customCategoryTemplate' => true);
	if ($_REQUEST['page']) {
		$callingParams['page'] = intval($_REQUEST['page']);
	}

	// Preload template configuration variables
	templateLoadVariables();
	// Check if template requires extracting embedded images
	$tplVars = $TemplateCache['site']['#variables'];
	if (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['extractEmbeddedItems']) && $tplVars['configuration']['extractEmbeddedItems']) {
		$callingParams['extractEmbeddedItems'] = true;
	}

	// Call SEARCH only if search words are entered
	if (count($search_words)) {
		$found = news_showlist($filter, $paginationParams, $callingParams);
	} else {
		$found = array('count' => 0, 'data' => false);
	}

	// Now let's show SEARCH basic template
	$tpl->template('search.table', tpl_dir . $config['theme']);
	$tvars = array();
	$tvars['vars']['author'] = secure_html($_REQUEST['author']);
	$tvars['vars']['search'] = secure_html($_REQUEST['search']);

	$tvars['vars']['count'] = $found['count'];
	$tvars['vars']['form_url'] = generateLink('search', '', array());

	$tvars['regx']['#\[found\](.+?)\[/found\]#is'] = (isset($_REQUEST['search']) && count($search_words) && $found['count']) ? '$1' : '';
	$tvars['regx']['#\[notfound\](.+?)\[/notfound\]#is'] = (isset($_REQUEST['search']) && count($search_words) && !$found['count']) ? '$1' : '';
	$tvars['regx']['#\[error\](.+?)\[/error\]#is'] = (isset($_REQUEST['search']) && !count($search_words)) ? '$1' : '';

	// Make category list
	$tvars['vars']['catlist'] = makeCategoryList(array('name' => 'catid', 'selected' => $_REQUEST['catid'], 'doempty' => 1));

	// Results of search
	$tvars['vars']['entries'] = $found['data'];
	// Make month list
	$mnth_list = explode(",", $lang['months']);
	foreach ($mysql->select("SELECT month(from_unixtime(postdate)) as month, year(from_unixtime(postdate)) as year, COUNT(id) AS cnt FROM " . prefix . "_news WHERE approve = '1' GROUP BY year, month ORDER BY year DESC, month DESC") as $row) {

		$pd_value = sprintf("%04u%02u", $row['year'], $row['month']);
		$pd_text = $mnth_list[$row['month'] - 1] . ' ' . $row['year'];

		$tvars['vars']['datelist'] .= "<option value=\"" . $pd_value . "\"" . (($pd_value == $_REQUEST['postdate']) ? ' selected' : '') . ">" . $pd_text . "</option>";
	}

	$tpl->vars('search.table', $tvars);
	$template['vars']['mainblock'] .= $tpl->show('search.table');
}