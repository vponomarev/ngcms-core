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
function search_news(){
	global $catz, $catmap, $mysql, $config, $userROW, $tpl, $parse, $template, $lang, $PFILTERS, $SYSTEM_FLAGS;

	// Configure plugin calling information
	$plugCallMode = array('style' => 'short');

	// Make page title
	$SYSTEM_FLAGS['info']['title']['group']	= $lang['search'];

	// Prepare search parameters
	$limit = array();

	// Check for searched words
	$search_words	= preg_split('#[ \,\.]+#', trim(str_replace(array('<', '>', '%', '$', '#'), '', substr($_REQUEST['search'], 0, 64))));

	if (!is_array($search_words)) {
		msg(array("type" => "error", "text" => $lang['msge_story'], "info" => $lang['msgi_story']));
		return;
	}

	$search = array();
	$sw_replace = array();
	foreach ($search_words as $s) {
		array_push($search, "((title Like '%".mysql_real_escape_string($s)."%') or (content Like '%".mysql_real_escape_string($s)."%'))");
		array_push($sw_replace,"<span class='search_hlight'>".$s."</span>");
	}
	array_push($limit, join(" AND ", $search));

	// Check for author_id
	if ($author_id = intval($_REQUEST['author_id'])) {
		// Try to fetch name of author
		if ($alc = $mysql->record("select * from ".uprefix."_users where id = ".db_squote($author_id))) {
			$author_name = $alc['name'];
			array_push($limit, 'author_id = '.$author_id);
		} else {
			$author_id = 0;
		}
	}

	// If no author_id is specified - check if 'author' is specified
	if (!$author_id && $_REQUEST['author']) {
		// Try to fetch author
		if ($alc = $mysql->record("select * from ".uprefix."_users where name = ".db_squote($_REQUEST['author']))) {
			$author_id = $alc['id'];
			$author_name = $alc['name'];
			array_push($limit, 'author_id = '.$author_id);
		}
	}

	// Check for post month
	if (($postdate = $_REQUEST['postdate']) && preg_match('#^(\d{4})(\d{2})#', $_REQUEST['postdate'], $res)) {
		$year = $res['1'];
		$month = $res['2'];

		if (($year > 1980) && ($year < 2100) && ($month > 0) && ($month < 13)) {
			$d1 = "unix_timestamp('".$year."-".$month."-1')";
			$d2 = "unix_timestamp('".(($month==12)?($year+1).'-1-1':$year.'-'.($month+1).'-1')."')-1";
			array_push( $limit, "((postdate >= $d1) and (postdate <= $d2))");
		}
	}

	// Check for category
	if ($catid = intval($_REQUEST['catid']))
		array_push($limit, "(catid regexp '[[:<:]](".$catid.")[[:>:]]')");

	$limit_count = (intval($config['number'])<1 || intval($config['number']) > 200)?5:intval($config['number']);
	if (!($page = intval($_REQUEST['page'])))
		$page = 1;

	$limit_start = ($page-1)*$limit_count;

	// Add limit - search only within approved news
	array_push($limit, "(approve = 1)");

	$query['sql']	= "select count(*) as cnt from ".prefix."_news where ".join(" AND ", $limit);
	$query['list']	= "select * from ".prefix."_news where ".join(" AND ", $limit)." limit ".$limit_start.",".$limit_count;

	$tpl -> template('search.entries', tpl_dir.$config['theme']);

	load_extras('news');
	load_extras('news:show');
	load_extras('news:search');

	$entries = '';
	foreach ($mysql->select($query['list']) as $row) {

		// Execute filters
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNewsPre($row['id'], $row, $plugCallMode); }

		$tvars = newsFillVariables($row, 0, $page);
		$tvars['vars']['date'] = LangDate(timestamp, $row['postdate']);
		$tvars['vars']['views'] = $row['views'];

		if ($config['blocks_for_reg']) {
			if (is_array($userROW)) {
				$tvars['vars']['[is-logged]']	=	'';
				$tvars['vars']['[/is-logged]']	=	'';
			} else {
				$tvars['regx']["'\\[hide\\].*?\\[/hide\\]'si"] = "<div class=\"not_logged\">".$lang['not_logged']."</div>";
			}
		} else {
			$tvars['regx']["'\\[hide\\].*?\\[/hide\\]'si"] = '$1';
		}

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
		exec_acts('news_search', '', $row, &$tvars);

		// Temporally turn off words replacement
		// // Make search words replacement
		// $tvars['vars']['title']		  = str_replace($search_words, $sw_replace, $tvars['vars']['title']);
		// $tvars['vars']['short-story'] = str_replace($search_words, $sw_replace, $tvars['vars']['short-story']);

		// Execute filters
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->showNews($row['id'], $row, $tvars, $plugCallMode); }


		// Generate page
		$tpl -> vars('search.entries', $tvars);
		$entries .= $tpl -> show('search.entries');
	}
	unset($tvars);


	// Make counters
	$newsCount		= $mysql->result($query['sql']);
	$pages_count = ceil($newsCount / $config['number']);

	// Make category list
	$tvars['vars']['catlist'] = makeCategoryList( array ('name' => 'catid', 'selected' => $_REQUEST['catid'], 'doempty' => 1, 'class' => 'mw_search_f'));


	// Make month list
	$mnth_list = explode(",", $lang['months']);
	foreach ($mysql->select("SELECT month(from_unixtime(postdate)) as month, year(from_unixtime(postdate)) as year, COUNT(id) AS cnt FROM ".prefix."_news WHERE approve = '1' GROUP BY year(from_unixtime(postdate)), month(from_unixtime(postdate)) ORDER BY id DESC") as $row) {

	        $pd_value = sprintf("%04u%02u",$row['year'],$row['month']);
	        $pd_text  = $mnth_list[$row['month']-1].' '.$row['year'];

		$tvars['vars']['datelist'] .= "<option value=\"".$pd_value."\"".(($pd_value == $_REQUEST['postdate'])?' selected':'').">".$pd_text."</option>";
	}

	$tpl -> template('search.table', tpl_dir.$config['theme']);
	$tvars['vars']['count_all']	=	$newsCount;
	$tvars['vars']['padeg2']	=	Padeg($count, $lang['found_skl2']);
	$tvars['vars']['padeg1']	=	Padeg($count, $lang['found_skl1']);
	$tvars['vars']['author']	=	$author_name;
	$tvars['vars']['entries']	=	$entries;
	$tvars['vars']['search']	= str_replace('<','&lt;',join(" ", $search_words));



	// Make navigation bar
	$navigations = getNavigations(tpl_dir.$config['theme']);
	$tpl -> template('pages', tpl_dir.$config['theme']);

	if (count($carray)) { $row['alt'] = category; }


	exec_acts('search_entry');
	$tpl -> vars('search.table', $tvars);
	$template['vars']['mainblock'] .= $tpl -> show('search.table');


	// Pages params for link
	$pLink = array ('action' => 'search', 'catid' => $catid, 'author_id' => $author_id, 'postdate' => $postdate, 'search' => $_REQUEST['search']);
	$pURL  = home."?";
	foreach ($pLink as $k => $v)
		$pURL .= $k.'='.urlencode($v).'&';
	$pURL = substr($pURL, 0, -1);

	// Prev page link
	if ($limit_start && $newsCount) {
		$prev = floor($limit_start / $config['number']);
		$row['page'] = $prev;
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$pURL.'&page='.$prev, $navigations['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$no_prev = true;
	}


	// ===[ TO PUT INTO CONFIG ]===
	$pages = '';
	$maxNavigations 		= 10;


	$sectionSize	= floor($maxNavigations / 3);
	if ($pages_count > $maxNavigations) {
		// We have more than 10 pages. Let's generate 3 parts
		// Situation #1: 1,2,3,4,[5],6 ... 128
		if ($page < ($sectionSize * 2)) {
			$pages .= generateSearchNavigations($page, 1, $sectionSize * 2, $pURL, $navigations);
			$pages .= " ... ";
			$pages .= generateSearchNavigations($page, $pages_count-$sectionSize, $pages_count, $pURL, $navigations);
		} elseif ($page > ($pages_count - $sectionSize * 2 + 1)) {
			$pages .= generateSearchNavigations($page, 1, $sectionSize, $pURL, $navigations);
			$pages .= " ... ";
			$pages .= generateSearchNavigations($page, $pages_count-$sectionSize*2 + 1, $pages_count, $pURL, $navigations);
		} else {
			$pages .= generateSearchNavigations($page, 1, $sectionSize, $pURL, $navigations);
			$pages .= " ... ";
			$pages .= generateSearchNavigations($page, $page-1, $page+1, $pURL, $navigations);
			$pages .= " ... ";
			$pages .= generateSearchNavigations($page, $pages_count-$sectionSize, $pages_count, $pURL, $navigations);
		}
	} else {
		// If we have less then 10 pages
		$pages .= generateSearchNavigations($page, 1, $pages_count, $pURL, $navigations);
	}

	// Next page link
	if (($prev + 2 <= $pages_count) && $newsCount) {
		$next	= $prev + 2;
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$pURL.'&page='.$next, $navigations['nextlink']));
	} else {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		$no_next = true;
	}

	if ($newsCount && ($pages_count>1)){
		$tvars['vars']['pages'] = $pages;
		$tpl -> vars('pages', $tvars);
		$template['vars']['mainblock'] .= $tpl -> show('pages');
	}
}


function generateSearchNavigations($current, $start, $stop, $url, $navigations){
	$result = '';
	for ($j=$start; $j<=$stop; $j++) {
		if ($j == $current) {
			$result .= str_replace('%page%',$j,$navigations['current_page']);
		} else {
			$row['page'] = $j;
			$result .= str_replace('%page%',$j,str_replace('%link%',$url."&page=".$j, $navigations['link_page']));
		}
	}
	return $result;
}

