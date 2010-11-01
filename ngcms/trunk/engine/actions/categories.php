<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: categories.php
// Description: Category management
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('categories', 'admin');

function CatTree() {
	global $mysql, $tpl, $out, $cat_tree, $lang, $config;

	foreach ($mysql->select("select * from ".prefix."_category order by posorder") as $row) {
		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'rid'		=>	$row['id'],
			'position'	=>	str_repeat('&#8212; ',$row['poslevel'])."<input type=\"text\" name=\"position[".$row['id']."]\" value=\"".$row['position']."\" maxlength=\"5\" size=\"5\" />",
			'name'		=>	$row['name'],
			'alt'		=>	$row['alt'],
			'alt_url'	=> $row['alt_url'],
			'orderlist'	=>	OrderList(''),
			'show_main'	=>	intval(substr($row['flags'],0,1)) ? ('<img src="'.skins_url.'/images/yes.png" alt="'.$lang['yesa'].'"/>') : ('<img src="'.skins_url.'/images/no.png" alt="'.$lang['noa'].'"/>'),
			'template'	=>	($row['tpl'] != '')?$row['tpl']:'--',
			'news'		=>	($row['posts']>0)?$row['posts']:'--',
			'showcat'	=>  (checkLinkAvailable('news', 'by.category')?
							generateLink('news', 'by.category', array('category' => $row['alt'], 'catid' => $row['id']), array(), false, true):
							generateLink('core', 'plugin', array('plugin' => 'news', 'handler' => 'by.category'), array('category' => $row['alt'], 'catid' => $row['id']), false, true)),
			'cutter'	=>	str_repeat('&#8212; ', $row['poslevel']),
		);
		$tvars['regx']['#\[news\](.*?)\[\/news\]#is'] = ($row['posts']>0)?'$1':'';

		$tpl -> vars('entries', $tvars);
		$cat_tree .= $tpl -> show('entries');
	}
	return $cat_tree;
}

function categoryReorder() {
	global $catz, $mysql;

	$tree[0] = array('parent' => 0, 'children' => array(), 'position' => 0, 'poslevel' => 0);
	foreach($mysql->select("select * from ".prefix."_category order by posorder", 1) as $v){
		$ncat[$v['id']] = $v;
		$tree[$v['id']] = array('children' => array(), 'parent' => $v['parent'], 'position' => $v['position']?$v['position']:999, 'poslevel' => $v['poslevel']);
	}

	// List children
	foreach($tree as $k => $v){
		$wrid = 0;
		if (!$k) { continue; }
		if (array_key_exists($v['parent'], $tree)) { $wrid = $v['parent']; }
		$vx = &$tree[$wrid];
		array_push($vx['children'], $k);
	}

	$num = 0;
	$nc = $ncat[0];
	$idx = array();
	$idxD = array();
	$ordr = array();
	$level = 0;
	array_unshift($idx, 0,0);

	do {
		for ($i=$idx[1]; $i<count($tree[$idx[0]]['children']);$i++) {
			$restart = 0;
			$cscan = $tree[$idx[0]]['children'][$i];

			$ordr[implode("-",$idxD).(count($idxD)?'-':'').sprintf("%04u-%04u", $tree[$cscan]['position'], $cscan)]= array($cscan, $level);
			$idx[1]++;
			if (count($tree[$cscan]['children'])) {
				$level++;
				array_unshift($idx, $cscan,0);
				array_push($idxD,sprintf("%04u",$tree[$cscan]['position']),sprintf("%04u",$cscan));
				$restart = 1;
				break;
			}
		}
		if ($restart) { continue; }
		array_shift($idx);  array_shift($idx);
		array_pop($idxD); array_pop($idxD);
		$level--;
	} while (count($idx));

	ksort($ordr);

	$num = 1;
	foreach($ordr as $k => $v){
		list($catID, $level) = $v;
		if (($ncat[$catID]['posorder'] != $num)||($ncat[$catID]['poslevel'] != $level)) {
			$mysql->query("update ".prefix."_category set posorder = $num, poslevel = $level where id = ".db_squote($catID));
		}
		$num++;
	}
}

function listSubdirs($dir) {
	$list = array();
	if ($h = @opendir($dir)) {
		while (($fn = readdir($h)) !== false) {
			if (($fn != '.') && ($fn != '..') && is_dir($dir.'/'.$fn))
				array_push($list, $fn);
		}
		closedir($h);
	};

	return $list;
}

function category_add() {
	global $mysql, $lang, $mod, $tpl, $parse, $config, $AFILTERS;

	$SQL			= array();
	$SQL['name']	= secure_html(trim($_REQUEST['name']));
	$SQL['alt']		= trim($_REQUEST['alt']);
	$SQL['parent']	= intval($_REQUEST['parent']);
	$SQL['icon']	= $_REQUEST['icon'];
	$SQL['alt_url']	= $_REQUEST['alt_url'];
	$SQL['orderby']	= $_REQUEST['orderby'];
	$SQL['tpl']		= $_REQUEST['tpl'];
	$SQL['number']	= intval($_REQUEST['number']);

	$SQL['flags']	= intval($_REQUEST['cat_show'])?'1':'0';
	$SQL['flags']  .= (string) (abs(intval($_REQUEST['show_link'])<=2)?abs(intval($_REQUEST['show_link'])):'0');

	$cat_name		= secure_html($_REQUEST['cat_name']);
	$alt_cat_name	= trim($_REQUEST['alt_cat_name']);
	$description	= secure_html(trim($_REQUEST['description']));
	$keywords		= secure_html(trim($_REQUEST['keywords']));
	$category		= intval($_REQUEST['category']);

	if (!$SQL['name']) {
		msg(array("type" => "error", "text" => $lang['msge_name'], "info" => $lang['msgi_name']));
		return;
	}

	$SQL['alt']		= strtolower($parse->translit($SQL['alt']?$SQL['alt']:$SQL['name'], 1));

	if (is_array($mysql->record("select * from ".prefix."_category where lower(alt) = ".db_squote(strtolower($SQL['alt']))))) {
			msg(array("type" => "error", "text" => $lang['msge_exists'], "info" => $lang['msgi_exists']));
			return;
	}

	if ($config['meta']) {
		$SQL['description']	= secure_html(trim($_REQUEST['description']));
		$SQL['keywords']	= secure_html(trim($_REQUEST['keywords']));
	}

	$pluginNoError = 1;
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) {
			if (!($pluginNoError = $v->addCategory($tvars, $SQL))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$SQLout = array();
	foreach ($SQL as $k => $v)
		$SQLout[$k] = db_squote($v);

	$mysql->query("insert into ".prefix."_category (".join(", ", array_keys($SQLout)).") values (".join(", ", array_values($SQLout)).")");
	msg(array("text" => $lang['msgo_added']));
}

function category_sort(){
	global $mysql, $lang;

	if (!is_array($_POST['position'])) { return; }
	foreach ($_POST["position"] as $catid => $position) {
		if(strlen($position)) {
			$position = intval($position);
			$catid = intval($catid);
			$mysql->query("update ".prefix."_category set position = '".$position."' WHERE id = '".$catid."'");
		}
	}
	msg(array("text" => $lang['msgo_sorted']));
}

function category_remove(){
	global $mysql, $lang;

	$catid = intval($_REQUEST['catid']);
	if (!$catid) {
		msg(array("type" => "error", "text" => $lang['msge_select']));
		return;
	}

	if (is_array($mysql->record("select * from ".prefix."_news where catid regexp '[[:<:]](".$catid.")[[:>:]]' limit 1"))) {
		msg(array("type" => "error", "text" => $lang['msge_havenews']));
		return;
	}

	$mysql->query("delete from ".prefix."_category where id='".$catid."'");
	msg(array("text" => $lang['msgo_deleted']));
}


//
// EDIT CATEGORY
//

// Form
function category_edit(){
	global $mysql, $lang, $mod, $tpl, $config, $AFILTERS;

	$catid = intval($_REQUEST['catid']);
	if (!is_array($row=$mysql->record("select * from ".prefix."_category where id = ".db_squote($catid)))) {
		msg(array("type" => "error", "text" => $lang['msge_id'], "info" => sprintf($lang['msgi_id'], $PHP_SELF.'?mod=categories')));
		return;
	}

	$tpl_list = '<option value="">* '.$lang['cat_tpldefault']." *</option>\n";
	foreach (listSubdirs(tpl_site.'ncustom/') as $k) {
		$tpl_list .= '<option value="'.secure_html($k).'"'.(($row['tpl'] == $k)?' selected="selected"':'').'>'.secure_html($k)."</option>\n";
	}

	$showLink = '';
	foreach (array('always', 'ifnews', 'never') as $k => $v) {
		$showLink .= '<option value="'.$k.'"'.(($k == intval(substr($row['flags'], 1, 1)))?' selected="selected"':'').'>'.$lang['link.'.$v].'</option>';
	}

	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'parent'		=> makeCategoryList(array('name' => 'parent', 'selected' => $row['parent'], 'skip' => $row['id'], 'doempty' => 1)),
		'catid'			=>	$row['id'],
		'name'			=>	secure_html($row['name']),
		'alt'			=>	secure_html($row['alt']),
		'alt_url'		=>	secure_html($row['alt_url']),
		'orderlist'		=>	OrderList($row['orderby'], true),
		'description'		=>	secure_html($row['description']),
		'keywords'		=>	secure_html($row['keywords']),
		'icon'			=>	secure_html($row['icon']),
		'check'			=>	(substr($row['flags'],0, 1))?'checked="checked"':'',
		'tpl_value'		=>	secure_html($row['tpl']),
		'number'		=>	$row['number'],
		'show.link'		=>  $showLink,
		'tpl_list'		=>	$tpl_list
	);

	if ($config['meta']) {
		$tvars['vars']['[meta]']	=	'';
		$tvars['vars']['[/meta]']	=	'';
	} else{
		$tvars['regx']['/\[meta\].*?\[\/meta\]/si'] = '';
	}

	$tvars['vars']['extend'] = '';
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) { $v->editCategoryForm($catid, $row, &$tvars); }

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	echo $tpl -> show('edit');
}

// Action
function category_doedit(){
	global $mysql, $lang, $config, $catz, $catmap, $AFILTERS;

	$SQL			= array();
	$SQL['name']	= secure_html($_REQUEST['name']);
	$SQL['alt']		= trim($_REQUEST['alt']);
	$SQL['parent']	= intval($_REQUEST['parent']);
	$SQL['icon']	= $_REQUEST['icon'];
	$SQL['alt_url']	= $_REQUEST['alt_url'];
	$SQL['orderby']	= $_REQUEST['orderby'];
	$SQL['tpl']		= $_REQUEST['tpl'];
	$SQL['number']	= intval($_REQUEST['number']);

	$SQL['flags']	= intval($_REQUEST['cat_show'])?'1':'0';
	$SQL['flags']  .= (string) (abs(intval($_REQUEST['show_link'])<=2)?abs(intval($_REQUEST['show_link'])):'0');

	$catid			= intval($_REQUEST['catid']);

	if (!$SQL['name'] || !$catid || (!is_array($SQLold = $catz[$catmap[$catid]]))) {
		msg(array("type" => "error", "text" => $lang['msge_name'], "info" => $lang['msgi_name']));
		return;
	}

	if (!$catid || (!is_array($SQLold = $catz[$catmap[$catid]]))) {
		msg(array("type" => "error", "text" => $lang['msge_id'], "info" => $lang['msgi_id']));
		return;
	}


	if ($config['meta']) {
		$SQL['description']	= secure_html(trim($_REQUEST['description']));
		$SQL['keywords']	= secure_html(trim($_REQUEST['keywords']));
	}

	$pluginNoError = 1;
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) {
			if (!($pluginNoError = $v->editCategory($catid, $SQLold, &$SQL, $tvars))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$SQLout = array();
	foreach ($SQL as $var => $val)
		$SQLout []= '`'.$var.'` = '.db_squote($val);

	$mysql->query("update ".prefix."_category set ".join(", ", $SQLout)." where id=".db_squote($catid));
	msg(array("text" => $lang['msgo_saved']));
}

function category_list(){
	global $mysql, $tpl, $mod, $PHP_SELF, $config, $lang, $AFILTERS;

	$tpl -> template('entries', tpl_actions.$mod);
	$tpl -> template('table', tpl_actions.$mod);

	$tpl_list = '<option value="">* '.$lang['cat_tpldefault']." *</option>\n";
	foreach (listSubdirs(tpl_site.'ncustom/') as $k) {
		$tpl_list .= '<option value="'.secure_html($k).'"'.(($row['tpl'] == $k)?' selected="selected"':'').'>'.secure_html($k)."</option>\n";
	}

	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'parent'	=>	makeCategoryList(array('name' => 'parent', 'doempty' => 1, 'resync' => ($_REQUEST['action']?1:0))),
		'cat_tree'	=>	CatTree(),
		'orderlist'	=>	OrderList(''),
		'tpl_list'	=> $tpl_list
	);

	if ($config['meta']) {
		$tvars['vars']['[meta]']	=	'';
		$tvars['vars']['[/meta]']	=	'';
	} else{
		$tvars['regx']["'\\[meta\\].*?\\[/meta\\]'si"] = "";
	}
	$tvars['vars']['extend'] = '';

	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) { $v->addCategoryForm($tvars); }

	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}


// ==========================================================================
// Select action
// ==========================================================================

if ($action == "edit") {
	category_edit();
} else {
	$dosort = 1;
	switch ($action) {
		case "add"   : category_add(); break;
		case "sort"  : category_sort(); break;
		case "remove": category_remove(); break;
		case "doedit": category_doedit(); break;
		default: $dosort = 0;
	}
	if ($dosort) { categoryReorder(); }
	category_list();
}