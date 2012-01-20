<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: lib_admin.php
// Description: General function for site administration calls
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
			if (($ndata['approve'] == 1) && ($setValue['approve'] != 1)) {
				$mysql->query("update ".uprefix."_users set news=news-1 where id = ".intval($ndata['author_id']));
			} else if (($ndata['approve'] != 1) && ($setValue['approve'] == 1)) {
				$mysql->query("update ".uprefix."_users set news=news+1 where id = ".intval($ndata['author_id']));
			}
		}

		// DeApprove news
		if ($setValue['approve'] < 1) {
			// Count categories & counters to decrease - we have this news currently in _news_map because this news are marked as published
			foreach ($mysql->select("select categoryID, count(newsID) as cnt from ".prefix."_news_map where newsID in (".join(", ", $nList).") group by categoryID") as $crec) {
				$mysql->query("update ".prefix."_category set posts=posts-".intval($crec['cnt'])." where id = ".intval($crec['categoryID']));
			}

			// Delete news map
			$mysql->query("delete from ".prefix."_news_map where newsID in (".join(", ", $nList).")");
		} else if ($setValue['approve'] == 1) {
			// Approve news
			$clist = array();
			foreach ($nData as $nr) {
				// Skip already published news
				if ($nr['approve'] == 1) continue;

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

// Generate backup for table list. If no list is given - backup ALL tables with system prefix
function dbBackup($fname, $gzmode, $tlist = ''){
	global $mysql;

	if ($gzmode && (!function_exists('gzopen')))
		$gzmode = 0;

	if ($gzmode)	$fh = gzopen($fname, "w");
	else			$fh = fopen($fname, "w");

	if ($fh === false)
		return 0;

	// Generate a list of tables for backup
	if (!is_array($tlist)) {
		$tlist = array();

		foreach ($mysql->select("show tables like '".prefix."_%'") as $tn)
			$tlist [] = $tn[0];
	}

	// Now make a header
	$out  = "# ".str_repeat('=', 60)."\n# Backup file for `Next Generation CMS`\n# ".str_repeat('=', 60)."\n# DATE: ".gmdate("d-m-Y H:i:s", time())." GMT\n# VERSION: ".engineVersion."\n#\n";
	$out .= "# List of tables for backup: ".join(", ", $tlist)."\n#\n";

	// Write a header
	if ($gzmode)	gzwrite($fh, $out);
	else			fwrite($fh, $out);

	// Now, let's scan tables
	foreach ($tlist as $tname) {
		// Fetch create syntax for table and after - write table's content
		if (is_array($csql = $mysql->record("show create table `".$tname."`"))) {
			$out  = "\n#\n# Table `".$tname."`\n#\n";
			$out .= "DROP TABLE IF EXISTS `".$tname."`;\n";
			$out .= $csql[1].";\n";

			if ($gzmode)	gzwrite($fh, $out);
			else			fwrite($fh, $out);

			// Now let's make content of the table
			$query = mysql_query("select * from `".$tname."`", $mysql->connect);
			$rowNo = 0;
			while ($row = mysql_fetch_row($query)) {
				$out = "insert into `".$tname."` values (";
				$rowNo++;
				$colNo = 0;
				foreach ($row as $v)
					$out .= (($colNo++)?', ':'').db_squote($v);
				$out .= ");\n";

				if ($gzmode)	gzwrite($fh, $out);
				else			fwrite($fh, $out);
			}

			$out = "# Total records: $rowNo\n";

			if ($gzmode)	gzwrite($fh, $out);
			else			fwrite($fh, $out);
		} else {
			$out = "#% Error fetching information for table `$tname`\n";

			if ($gzmode)	gzwrite($fh, $out);
			else			fwrite($fh, $out);
		}
	}
	if ($gzmode)	gzclose($fh);
	else			fclose($fh);

	return 1;
}


// ======================================================================================================
// Add news
// ======================================================================================================
// $mode - calling mode
//	* 	'onsite'	- flag if we're called in `onsite` mode (adding news on site)
function addNews($mode = array()){
	global $mysql, $lang, $userROW, $parse, $PFILTERS, $config, $catz, $catmap;

	// Set default calling params
	if (!isset($mode['onsite'])) {
		$mode['onsite'] = false;
	}

	// Load required library
	@include_once root.'includes/classes/upload.class.php';


	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'add',
		'add.onsite',
		'add.approve',
		'add.mainpage',
		'add.pinned',
		'add.favorite',
		'add.html',
		'personal.view',
		'personal.modify',
		'personal.modify.published',
		'personal.publish',
		'personal.unpublish',
		'personal.delete',
		'personal.delete.published',
		'personal.html',
		'personal.mainpage',
		'personal.pinned',
		'personal.favorite',
		'personal.setviews',
		'personal.multicat',
		'personal.customdate',
	));


	// Check permissions
	if (!$perm['add'.($mode['onsite']?'.onsite':'')]) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return 0;
	}


	$title = $_REQUEST['title'];

	// Fill content
	$content	= '';

	// Check if EDITOR SPLIT feature is activated
	if ($config['news.edit.split']) {
		// Prepare delimiter
		$ed = '<!--more-->';
		if ($config['extended_more'] && ($_REQUEST['content_delimiter'] != '')) {
			// Disable `new line` + protect from XSS
			$ed = '<!--more="'.str_replace(array("\r", "\n", '"'), '', $_REQUEST['content_delimiter']).'"-->';
		}
		$content = $_REQUEST['ng_news_content_short'].(($_REQUEST['ng_news_content_full'] != '')?$ed.$_REQUEST['ng_news_content_full']:'');

	} else {
		$content = $_REQUEST['ng_news_content'];
	}

	// Rewrite `\r\n` to `\n`
	$content = str_replace("\r\n", "\n", $content);

	$alt_name = $parse->translit(trim($_REQUEST['alt_name']), 1);

	// Check title
	if ((!strlen(trim($title))) || ((!strlen(trim($content))) && (!$config['news_without_content']))) {
		msg(array("type" => "error", "text" => $lang['addnews']['msge_fields'], "info" => $lang['addnews']['msgi_fields']));
		return 0;
	}

	$SQL['title'] = $title;

	// Check for dup if alt_name is specified [ alt name can't be specified during `onsite` add ]
	if ($alt_name && (!$mode['onsite'])) {
		if ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name)." limit 1")) ) {
			msg(array("type" => "error", "text" => $lang['addnews']['msge_alt_name'], "info" => $lang['addnews']['msgi_alt_name']));
			return 0;
		}
		$SQL['alt_name'] = $alt_name;
	} else {
		// Generate uniq alt_name if no alt_name specified
		$alt_name = strtolower($parse->translit(trim($title), 1));
		// Make a conversion:
		// * '.'  to '_'
		// * '__' to '_' (several to one)
		// * Delete leading/finishing '_'
		$alt_name = preg_replace(array('/\./', '/(_{2,20})/', '/^(_+)/', '/(_+)$/'), array('_', '_'), $alt_name);

		// Make alt_name equal to '_' if it appear to be blank after conversion
		if ($alt_name == '') $alt_name = '_';

		$i = '';
		while ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
			$i++;
		}
		$SQL['alt_name'] = $alt_name.$i;
	}

	// Custom date[ only while adding via admin panel ]
	if ($_REQUEST['customdate'] && $perm['personal.customdate'] && (!$mode['onsite'])) {
		$SQL['postdate'] = mktime(intval($_REQUEST['c_hour']), intval($_REQUEST['c_minute']), 0, intval($_REQUEST['c_month']), intval($_REQUEST['c_day']), intval($_REQUEST['c_year'])) + ($config['date_adjust'] * 60);
	} else {
		$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
	}

	$SQL['editdate'] = $SQL['postdate'];

	// Fetch MASTER provided categories
	$catids = array ();
	if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
		$catids[intval($_POST['category'])] = 1;
	}

	// Fetch ADDITIONAL provided categories [if allowed]
	if ($perm['personal.multicat']) {
		foreach ($_POST as $k => $v) {
			if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
				$catids[$match[1]] = 1;
		}
	}

	// Metatags (only for adding via admin panel)
	if ($config['meta'] && (!$mode['onsite'])) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	$SQL['author']		= $userROW['name'];
	$SQL['author_id']	= $userROW['id'];
	$SQL['catid']		= implode(",", array_keys($catids));

	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	if ($perm['personal.html']) {
		$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
	} else {
		$SQL['flags']	=	0;
	}

	// This functions are not available in onsite mode
	if (!$mode['onsite']) {
		$SQL['mainpage']	= intval($_REQUEST['mainpage']) && $perm['personal.mainpage'];
		$SQL['favorite']	= intval($_REQUEST['favorite']) && $perm['personal.favorite'];
		$SQL['pinned']		= intval($_REQUEST['pinned']) && $perm['personal.pinned'];
	}

	switch (intval($_REQUEST['approve'])) {
		case -1:	$SQL['approve'] = -1;								break;
		case 0:		$SQL['approve'] = 0;								break;
		case 1:		$SQL['approve'] = $perm['personal.publish']?1:0;	break;
		default:	$SQL['approve']	= 0;
	}

	$SQL['content']		= $content;

	exec_acts('addnews');

	$pluginNoError = 1;
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			if (!($pluginNoError = $v->addNews($tvars, $SQL))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['addnews']['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$vnames = array(); $vparams = array();
	foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }

	$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");
	$id = $mysql->result("SELECT LAST_INSERT_ID() as id");

	// Update category / user posts counter [ ONLY if news is approved ]
	if ($SQL['approve'] == 1) {
		if (count($catids)) {
			$mysql->query("update ".prefix."_category set posts=posts+1 where id in (".implode(", ",array_keys($catids)).")");
			foreach (array_keys($catids) as $catid) {
				$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".db_squote($id).", ".db_squote($catid).")");
			}
		}
		$mysql->query("update ".uprefix."_users set news=news+1 where id=".$SQL['author_id']);
	}

	// Attaches are available only for admin panel
	if (!$mode['onsite']) {

		// Now let's manage attached files
		$fmanager = new file_managment();

		$flagUpdateAttachCount = false;

		// Delete files (if needed)
		foreach ($_POST as $k => $v) {
			if (preg_match('#^delfile_(\d+)$#', $k, $match)) {
				$fmanager->file_delete(array('type' => 'file', 'id' => $match[1]));
				$flagUpdateAttachCount = true;
			}
		}

		//print "<pre>".var_export($_FILES, true)."</pre>";
		// PREPARE a list for upload
		if (is_array($_FILES['userfile']['name']))
			foreach($_FILES['userfile']['name'] as $i => $v) {
				if ($v == '')
					continue;

				$flagUpdateAttachCount = true;
				//
				$up = $fmanager->file_upload(array('dsn' => true, 'linked_ds' => 1, 'linked_id' => $id, 'type' => 'file', 'http_var' => 'userfile', 'http_varnum' => $i));
				//print "OUT: <pre>".var_export($up, true)."</pre>";
				if (!is_array($up)) {
					// Error uploading file
					// ... show error message ...
				}
			}

		// Update attach count if we need this
		$numFiles = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		if ($numFiles) {
			$mysql->query("update ".prefix."_news set num_files = ".intval($numFiles)." where id = ".db_squote($id));
		}

		$numImages = $mysql->result("select count(*) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		if ($numImages) {
			$mysql->query("update ".prefix."_news set num_images = ".intval($numImages)." where id = ".db_squote($id));
		}
	}

	// Notify plugins about adding new news
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsNotify($tvars, $SQL, $id); }

	exec_acts('addnews_', $id);
	msg(array("text" => $lang['addnews']['msgo_added'], "info" => sprintf($lang['addnews']['msgi_added'], admin_url.'/admin.php?mod=news&action=edit&id='.$id, admin_url.'/admin.php?mod=news')));

	return 1;
}
