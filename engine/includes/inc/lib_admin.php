<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: lib_admin.php
// Description: Libraries for admin panel
// Author: Vitaly Ponomarev
//

//
// Mass news flags modifier
// $list		- array with news identities [only 1 field should be filled]
//		'id'	- list of IDs
//		'data'	- list of records (result of SELECT query from DB)
// $setValue	- what to change in table (array with field => value)
// $permCheck	- flag if permissions should be checked (0 - don't check, 1 - check if current user have required rights)
//
// Return value: number of successfully updated news
//
function massModifyNews($list, $setValue, $permCheck = true) {
	global $mysql, $lang, $PFILTERS, $catmap, $userROW;

	// Check if we have anything to update
	if (!is_array($list))
		return -1;

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'personal.modify',
		'personal.modify.published',
		'personal.publish',
		'personal.unpublish',
		'personal.delete',
		'personal.delete.published',
		'personal.mainpage',
		'personal.pinned',
		'personal.customdate',
		'other.view',
		'other.modify',
		'other.modify.published',
		'other.publish',
		'other.unpublish',
		'other.delete',
		'other.delete.published',
		'other.html',
		'other.mainpage',
		'other.pinned',
		'other.customdate',
	));


	$nList = array();
	$nData = array();

	$results = array();

	if (isset($list['data'])) {
		$recList = $list['data'];
	} else if (isset($list['id'])) {
		$SNQ = array();
		foreach ($list['id'] as $id)
			$SNQ [] = db_squote($id);

		$recList = $mysql->select("select * from ".prefix."_news where id in (".join(", ", $SNQ).")");
	} else {
		return array();
	}

	// Scan RECORDS and prepare output
	foreach ($recList as $rec) {
		// SKIP records if user has not enougt permissions
		if ($permCheck) {
			$isOwn = ($rec['author_id'] == $userROW['id'])?1:0;
			$permGroupMode = $isOwn?'personal':'other';

			// Manage `PUBLISHED` field
			$ic = 0;
			if (isset($setValue['approve'])) {
				if ((($rec['approve'] == 1)&&($setValue['approve'] != 1) && (!$perm[$permGroupMode.'.unpublish']))||
					(($rec['approve'] < 1)&&($setValue['approve'] == 1) && (!$perm[$permGroupMode.'.publish']))) {
					$results []= '#'.$rec['id'].' ('.$rec['title'].') - '.$lang['perm.denied'];
					continue;
				}
				$ic++;
			}

			// Manage `MAINPAGE` flag
			if (isset($setValue['mainpage'])) {
				if (!$perm[$permGroupMode.'.mainpage']) {
					$results []= '#'.$rec['id'].' ('.$rec['title'].') - '.$lang['perm.denied'];
					continue;
				}
				$ic++;
			}

			// Check if we have other options except MAINPAGE/APPROVE
			if (count($setValue) > $ic) {
				if (!$perm[$permGroupMode.'.modify'.(($rec['approve'] == 1)?'.published':'')]) {
					$results []= '#'.$rec['id'].' ('.$rec['title'].') - '.$lang['perm.denied'];
					continue;
				}
			}

//			if (($rec['status'] > 1) && ($rec['author_id'] != $userROW['id']))
//				continue;
		}
		$results []= '#'.$rec['id'].' ('.$rec['title'].') - Ok';

		$nList[]= $rec['id'];
		$nData[$rec['id']] = $rec;
	}

	if (!count($nList))
		return $results;


	// Convert $setValue into SQL string
	$sqllSET = array();
	foreach ($setValue as $k => $v)
		$sqllSET[] = $k." = ".db_squote($v);

	$sqlSET = join(", ", $sqllSET);


	// Call plugin filters
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->massModifyNews($nList, $setValue, $nData); }

	$mysql->query("UPDATE ".prefix."_news SET $sqlSET WHERE id in (".join(", ", $nList).")");

	// Some activity if we change APPROVE flag for news
	if (isset($setValue['approve'])) {
		// Update user's news counters
		foreach ($nData as $nid => $ndata) {
			if ($ndata['approve'] != $setValue['approve'])
				$mysql->query("update ".uprefix."_users set news=news".($ndata['approve']?'-':'+')."1 where id = ".intval($ndata['author_id']));
		}

		// DeApprove news
		if (!$setValue['approve']) {
			// Count categories & counters to decrease
			foreach ($mysql->select("select categoryID, count(newsID) as cnt from ".prefix."_news_map where newsID in (".join(", ", $nList).") group by categoryID") as $crec) {
				$mysql->query("update ".prefix."_category set posts=posts-".intval($crec['cnt'])." where id = ".intval($crec['categoryID']));
			}

			// Delete news map
			$mysql->query("delete from ".prefix."_news_map where newsID in (".join(", ", $nList).")");
		} else {
			// Approve news
			$clist = array();
			foreach ($nData as $nr) {
				if ($nr['approve']) continue;
				// Calculate list
				foreach (explode(",", $nr['catid']) as $cid) {
					if (!isset($catmap[$cid])) continue;
					$clist[$cid]++;
					$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".intval($nr['id']).", ".intval($cid).")");
				}
			}
			foreach ($clist as $cid => $cv) {
				$mysql->query("update ".prefix."_category set posts=posts+".intval($cv)." where id = ".intval($cid));
			}
		}
	}

	// Call plugin filters [ NOTIFY ABOUT MODIFICATION ]
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->massModifyNewsNotify($nList, $setValue, $nData); }

	//return count($nList);
	return $results;
}