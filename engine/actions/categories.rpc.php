<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: categories.rpc.php
// Description: Externally available library for CATEGORIES manipulation
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Load library
@include_once root.'includes/classes/upload.class.php';

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: show list of categories
// ///////////////////////////////////////////////////////////////////////////
// retMode: 0 - print returned data
//          1 - return data from function
//			2 - return only cat_tree from function
function admCategoryList($retMode = 0) {
	global $mysql, $tpl, $PHP_SELF, $config, $lang, $AFILTERS;


	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'view')) {
		switch ($retMode) {
			case 1:
				return msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 2);
			case 2:
				return false;
			default:
				msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
				return;
		}
	}

	// Determine user's permissions
	$permModify		= checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify');
	$permDetails	= checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'details');

	// Prepare list of rows
	$tpl -> template('entries', tpl_actions.'categories');

	// Fetch list of categories
	$cList = $mysql->select("select * from ".prefix."_category order by posorder");
	$cLen  = count($cList);

	// Go through this list
	foreach ( $cList as $num => $row) {
		// Prepare data for template
		$tvars['vars'] = array(
			'token'		=>	genUToken('admin.categories'),
			'php_self'	=>	$PHP_SELF,
			'rid'		=>	$row['id'],
			'name'		=>	$row['name'],
			'alt'		=>	$row['alt'],
			'alt_url'	=>	$row['alt_url'],
			'show_main'	=>	intval(substr($row['flags'],0,1)) ? ('<img src="'.skins_url.'/images/yes.png" alt="'.$lang['yesa'].'" title="'.$lang['yesa'].'"/>') : ('<img src="'.skins_url.'/images/no.png" alt="'.$lang['noa'].'"/>'),
			'template'	=>	($row['tpl'] != '')?$row['tpl']:'--',
			'news'		=>	($row['posts']>0)?$row['posts']:'--',
			'showcat'	=>  (checkLinkAvailable('news', 'by.category')?
							generateLink('news', 'by.category', array('category' => $row['alt'], 'catid' => $row['id']), array(), false, true):
							generateLink('core', 'plugin', array('plugin' => 'news', 'handler' => 'by.category'), array('category' => $row['alt'], 'catid' => $row['id']), false, true)),
		);

		$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is']	= $permModify?'$1':'';
		$tvars['regx']['#\[perm\.details\](.*?)\[\/perm\.details\]#is']	= $permDetails?'$1':'';

		// Prepare position
		$tvars['vars']['cutter'] = '';
		if ($row['poslevel'] > 0) {
			$tvars['vars']['cutter'] = str_repeat('<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/line.gif" />', ($row['poslevel']));
		} else {
			$tvars['vars']['cutter'] = '';
		}
		$tvars['vars']['cutter'] = $tvars['vars']['cutter'] .
			'<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/join'.((($num == ($cLen-1) || ($cList[$num]['poslevel'] > $cList[$num+1]['poslevel'])))?'bottom':'').'.gif" />';
		$tvars['regx']['#\[news\](.*?)\[\/news\]#is'] = ($row['posts']>0)?'$1':'';

		$tpl -> vars('entries', $tvars);
		$cat_tree .= $tpl -> show('entries');
	}


	// Prepare main template
	$tvars['vars']['cat_tree'] = $cat_tree;

	// Check for permissions for adding new category
	$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is'] = $permModify?'$1':'';

	$tpl -> template('table', tpl_actions.'categories');
	$tpl -> vars('table', $tvars);

	switch ($retMode) {
		case 1:
			return $tpl->show('table');
		case 2:
			return $cat_tree;
		default:
			echo $tpl -> show('table');
	}
}



//
// Reorder categories
// Params:
// 	* mode - 'up' / 'down' -- move category up or down
//  * id                   -- id of category to move
function admCategoryReorder($params = array()) {
	global $catz, $mysql;

	$tree[0] = array('parent' => 0, 'children' => array(), 'poslevel' => 0);
	foreach($mysql->select("select * from ".prefix."_category order by posorder", 1) as $v){
		$ncat[$v['id']] = $v;
		$tree[$v['id']] = array('children' => array(), 'parent' => $v['parent'], 'poslevel' => $v['poslevel']);
	}
	// List children
	foreach($tree as $k => $v){
		$wrid = 0;
		if (!$k) { continue; }
		if (array_key_exists($v['parent'], $tree)) { $wrid = $v['parent']; } else {	$tree[$k]['parent'] = 0; $wrid = 0; }
		$vx = &$tree[$wrid];
		array_push($vx['children'], $k);
	}

	// Check if we need to move category (and this category exists
	if (is_array($params) && isset($params['mode']) && isset($params['id']) && isset($ncat[$params['id']])) {
		// 1. Find parent category
		$xpc = $tree[$params['id']]['parent'];

		// 2. Find position
		$xps = array_search($params['id'], $tree[$xpc]['children']);
		$xpl = count($tree[$xpc]['children']);

		// 3. Move if requested and possible
		if (($xps !== FALSE) && ($params['mode'] == 'up') && ($xps > 0)) {
			$xt = $tree[$xpc]['children'][$xps-1];
			$tree[$xpc]['children'][$xps-1] = $tree[$xpc]['children'][$xps];
			$tree[$xpc]['children'][$xps] = $xt;
		}

		if (($xps !== FALSE) && ($params['mode'] == 'down') && ($xps < ($xpl-1))) {
			$xt = $tree[$xpc]['children'][$xps+1];
			$tree[$xpc]['children'][$xps+1] = $tree[$xpc]['children'][$xps];
			$tree[$xpc]['children'][$xps] = $xt;
		}
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

			$ordr[]= array($cscan, $level);
			//print "new order: [] = (".$cscan.", ".$level.")<br/>\n";
			$idx[1]++;
			if (count($tree[$cscan]['children'])) {
				$level++;
				array_unshift($idx, $cscan, 0);
				array_push($idxD,sprintf("%04u",$cscan));
				$restart = 1;
				break;
			}
		}
		if ($restart) { continue; }
		array_shift($idx); array_shift($idx);
		array_pop($idxD);
		$level--;
	} while (count($idx));

	ksort($ordr);

	$num = 1;
	foreach($ordr as $k => $v){
		list($catID, $level) = $v;
		if (($ncat[$catID]['posorder'] != $num)||($ncat[$catID]['poslevel'] != $level)||($ncat[$catID]['parent'] != $tree[$catID]['parent'])) {
			$mysql->query("update ".prefix."_category set posorder = ".db_squote($num).", poslevel = ".db_squote($level).", parent = ".db_squote($tree[$catID]['parent'])." where id = ".db_squote($catID));
		}
		$num++;
	}
}
function admCategoriesRPCmodify($params) {
	global $userROW, $mysql, $catmap, $catz;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify')) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] != 1)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	// Scan incoming params
	if (!is_array($params) || !isset($params['mode']) || !isset($params['id']) || !isset($params['token'])) {
		return array('status' => 0, 'errorCode' => 4, 'errorText' => 'Wrong params type');
	}

	// Check for security token
	if ($params['token'] != genUToken('admin.categories')) {
		return array('status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code');
	}

	// Check if category exists
	if (!isset($catmap[$params['id']])) {
		return array('status' => 0, 'errorCode' => 10, 'errorText' => 'Category does not exist');
	}
	$row = $catz[$catmap[$params['id']]];

	switch ($params['mode']) {
		// Delete category
		case 'del':
			// Check if category have children
			$refCCount = $mysql->record("select count(*) as cnt from ".prefix."_category where parent = ".intval($params['id']));
			if ($refCCount['cnt'] > 0) {
				return array('status' => 0, 'errorCode' => 11, 'errorText' => 'Category have children, please delete news from this category first');
			}

			// Check for news in category
			$refNCount = $mysql->record("select count(*) as cnt from ".prefix."_news_map where categoryID = ".intval($params['id']));
			if ($refNCount['cnt'] > 0) {
				return array('status' => 0, 'errorCode' => 12, 'errorText' => 'Category have news, please delete news from this category first');
			}

			// Fine, now we can delete category!
			// * Delete
			$mysql->query("delete from ".prefix."_category where id = ".intval($params['id']));

			// Delete attached files (if any)
			if ($row['image_id']) {
				$fmanager = new file_managment();
				$fmanager->file_delete(array('type' => 'image', 'id' => $row['image_id']));
			}

			// * Reorder
			admCategoryReorder();
			// * Rewrite page content
			$data = admCategoryList(2);
			if ($data === false) {
				$data = '[permission denied]';
			}

			return (array('status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'content' => arrayCharsetConvert(0,$data)));

		// Move category UP/DOWN
		case 'up':
		case 'down':
			admCategoryReorder(array('mode' => $params['mode'], 'id' => intval($params['id'])));

			// * Rewrite page content
			$data = admCategoryList(2);

			return (array('status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'content' => arrayCharsetConvert(0,$data)));

	}

	return array('status' => 0, 'errorCode' => 999, 'errorText' => 'Params: '.var_export($params, true));
}


if (function_exists('rpcRegisterAdminFunction')) {
	rpcRegisterAdminFunction('admin.categories.modify', 'admCategoriesRPCmodify');
}
