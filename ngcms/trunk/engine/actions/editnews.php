<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: editnews.php
// Description: News managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root.'includes/classes/upload.class.php';


$lang = LoadLang('editnews', 'admin');
$situation = "news";

$SQL = array();

//
// Выполнение редактирования новости
//
function editNews() {
	global $lang, $parse, $mysql, $config, $PFILTERS, $userROW, $catz, $catmap;
	global $c_day, $c_month, $c_year, $c_hour, $c_minute, $description, $keywords;

	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	$SQL['flags'] = 0;
	switch ($userROW['status']) {
		case 1:		// admin can do anything
			$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 2:		// Editor. Check if we have permissions
			if (!$config['htmlsecure_2'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 3:		// Journalists. Check if we have permissions
			if (!$config['htmlsecure_3'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 4:		// Commentors. Check if we have permissions
			if (!$config['htmlsecure_4'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;
	}

	$id			= $_REQUEST['id'];
	$title		= $_REQUEST['title'];
	$content	= $_REQUEST['content'];
	$alt_name	= $_REQUEST['alt_name'];

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id=".db_squote($id).(($userROW['status'] > 2)?" and author_id = ".db_squote($userROW['id']):'')))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return;
	}

	$alt_name = $parse->translit(trim(trim($alt_name)?$alt_name:$title), 1);

	// Check if we try to use duplicate alt_name
	if (is_array($mysql->record("select * from ".prefix."_news where alt_name=".db_squote($alt_name)." and id <> ".db_squote($row['id'])." limit 1"))) {
		msg(array("type" => "error", "text" => $lang['msge_dupaltname']));
		return;
	}

	// Generate SQL old cats list
	$oldcatids = array();
	foreach (explode(",", $row['catid']) as $cat)
		if (preg_match('#^(\d+)$#', trim($cat), $cmatch))
			$oldcatids[$cmatch[1]] = 1;

	// Fetch MASTER provided categories
	$catids = array ();
	if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
		$catids[intval($_POST['category'])] = 1;
	}

	// Fetch ADDITIONAL provided categories
	foreach ($_POST as $k => $v) {
		if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
			$catids[$match[1]] = 1;
	}

	if ($config['meta']) {
		$SQL['description'] = $_REQUEST['description'];
		$SQL['keywords']    = $_REQUEST['keywords'];
	}


	$content = str_replace("\r\n", "\n", $_REQUEST['content']);

	$SQL['postdate']  = $_REQUEST['customdate']?mktime($c_hour, $c_minute, 0, $c_month, $c_day, $c_year) + ($config['date_adjust'] * 60):$row['postdate'];
	$SQL['title']     = $title;
	$SQL['content']   = $content;
	$SQL['alt_name']  = $alt_name;
	$SQL['editdate']  = time();
	$SQL['catid']     = implode(",", array_keys($catids));

	// Change this parameters if user have enough access level
	if ($userROW['status'] < 3) {
		$SQL['mainpage']  = intval($_REQUEST['mainpage']);
		$SQL['approve']   = intval($_REQUEST['approve']);
		$SQL['favorite']  = intval($_REQUEST['favorite']);
		$SQL['pinned']    = intval($_REQUEST['pinned']);

		// Use flag 'allow comments' only in case when plugin 'comments' is installed
		if (getPluginStatusInstalled('comments'))
			$SQL['allow_com'] = intval($_REQUEST['allow_com']);

		if ($_REQUEST['setViews'])
			$SQL['views'] = intval($_REQUEST['views']);
	} else {
		foreach (array('mainpage', 'approve', 'favorite', 'pinned') as $v) {
			$SQL[$v] = $row[$v];
		}

		if (getPluginStatusInstalled('comments'))
			$SQL['allow_com'] = $row['allow_com'];
	}

	exec_acts('editnews', $id);

	$pluginNoError = 1;
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
		if (!($pluginNoError = $v->editNews($id, $row, $SQL, $tvars))) {
			msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
			break;
		}
	}

	if (!$pluginNoError) {
		return;
	}

	$SQLparams = array();
	foreach ($SQL as $k => $v) { $SQLparams[] = $k.' = '.db_squote($v); }

	$mysql->query("update ".prefix."_news set ".implode(", ",$SQLparams)." where id = ".db_squote($id));

	// Update category posts counters
	if ($row['approve'] && sizeof($oldcatids)) {
		$mysql->query("update ".prefix."_category set posts=posts-1 where id in (".implode(",",array_keys($oldcatids)).")");
	}

	$mysql->query("delete from ".prefix."_news_map where newsID = ".db_squote($id));

	// Check if we need to update user's counters
	if ($row['approve'] != $SQL['approve']) {
		$mysql->query("update ".uprefix."_users set news=news".($row['approve']?'-':'+')."1 where id=".$row['author_id']);
	}

	if ($SQL['approve']) {
		if (sizeof($catids)) {
			$mysql->query("update ".prefix."_category set posts=posts+1 where id in (".implode(",",array_keys($catids)).")");
			foreach (array_keys($catids) as $catid) {
				$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".db_squote($id).", ".db_squote($catid).")");
			}
		}
	}
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsNotify($id, $row, $SQL, $tvars); }

	msg(array("text" => $lang['msgo_edited']));

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
	if ($flagUpdateAttachCount) {
		$attachCount = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		$mysql->query("update ".prefix."_news set attach_count = ".intval($attachCount)." where id = ".db_squote($id));
	}

}


//
// Форма редактирования новости
//
function editNewsForm() {
	global $lang, $parse, $mysql, $config, $tpl, $mod, $PFILTERS, $tvars, $userROW;
	global $title, $contentshort, $contentfull, $alt_name, $id, $c_day, $c_month, $c_year, $c_hour, $c_minute;

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id).(($userROW['status'] > 2)?" and author_id = ".db_squote($userROW['id']):'')))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	$cats = explode(",", $row['catid']);
	$content = $row['content'];

	$tvars = array();
	$tvars['vars'] = array(
		'php_self'			=>	$PHP_SELF,
		'changedate'		=>	ChangeDate($row['postdate']),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0,   'selected' => count($cats)?$cats[0]:0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1, 'selected' => (count($cats)>1)?array_slice($cats,1):array())),
		'allcats'			=>	@GetAllCategories($cats),
		'id'				=>	$row['id'],
		'title'				=>	secure_html($row['title']),
		'content'			=>  secure_html($row['content']),
		'alt_name'			=>	$row['alt_name'],
		'avatar'			=>	$row['avatar'],
		'description'		=>	secure_html($row['description']),
		'keywords'			=>	secure_html($row['keywords']),
		'views'				=>	$row['views'],
		'author'			=>  $row['author'],
		'createdate'		=>  strftime('%d.%m.%Y %H:%M', $row['postdate']),
		'editdate'			=>  ($row['editdate'] > $row['postdate'])?strftime('%d.%m.%Y %H:%M', $row['editdate']):'-',
	);

	if ($config['use_smilies']) {
		$tvars['vars']['smilies'] = InsertSmilies('content', 20);
	} else {
		$tvars['vars']['smilies'] = '';
	}

	if ($config['use_bbcodes']) {
		$tvars['vars']['quicktags'] = QuickTags('', 'news');
	} else {
		$tvars['vars']['quicktags'] = '';
	}

	if ($userROW['status'] < 3) {
		$tvars['vars']['[options]'] = '';
		$tvars['vars']['[/options]'] = '';
	} else {
		$tvars['regx']["'\[options\].*?\[/options\]'si"] = '';
	}

	$tvars['vars']['ifmp']		=	($row['mainpage'])  ? 'checked="checked"' : '';
	$tvars['vars']['ifch']		=	($row['allow_com']) ? 'checked="checked"' : '';
	$tvars['vars']['iffav']		=	($row['favorite'])  ? 'checked="checked"' : '';
	$tvars['vars']['ifapp']		=	($row['approve'])   ? 'checked="checked"' : '';
	$tvars['vars']['ifpin']		=	($row['pinned'])    ? 'checked="checked"' : '';
	$tvars['vars']['ifraw']		=	($row['flags'] & 1) ? 'checked="checked"' : '';
	$tvars['vars']['ifhtml']	=	($row['flags'] & 2) ? 'checked="checked"' : '';

	// Disable flag for comments if plugin 'comments' is not installed
	$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}

	$tvars['vars']['disable_flag_raw']	= $flock?'disabled':'';
	$tvars['vars']['disable_flag_html']	= $flock?'disabled':'';
	$tvars['vars']['flags_lost']		= $flock?'[<font color=red>'.$lang['flags_lost'].'</font>]':'';

	//
	if ($config['meta']) {
		$tvars['vars']['[meta]'] = '';
		$tvars['vars']['[/meta]'] = '';
	} else{
		$tvars['regx']["'\[meta\].*?\[/meta\]'si"] = '';
	}

	// Check for attached files
	$attaches_entries = '';
	if ($row['attach_count']) {
		// Yeah! We have some attached files
		$tpl->template('attach.file', tpl_actions.$mod);

		$num = 0;
		foreach ($mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_files where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')') as $arow) {
			$num++;
			$avars = array('vars' => array(
				'id'	=> $arow['id'],
				'num'	=> $num,
				'date'	=> $arow['date'],
				'orig_name'	=> $arow['orig_name'],
			));

			// Check if file exists
			$fname = ($arow['storage']?$config['attach_dir']:$config['files_dir']).$arow['folder'].'/'.$arow['name'];
			if (file_exists($fname) && ($fsize = @filesize($fname))) {
				$avars['vars']['filesize'] = Formatsize($fsize);
				$avars['vars']['url'] = (($arow['storage'])?($config['attach_url']):($config['files_url'])).'/'.$arow['folder'].'/'.$arow['name'];
			} else {
				$avars['vars']['filesize'] = '<font color="red">n/a</font>';
			}
			$tpl->vars('attach.file', $avars);
			$attach_entries .= $tpl->show('attach.file');
		}
	}

	$tvars['vars']['attach_entries'] = $attach_entries;
	$tvars['vars']['attach_count'] = '('.($num?$num:$lang['noa']).')';

	exec_acts('editnews_entry', $row['xfields'], '');
	exec_acts('editnews_form');

	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsForm($id, $row, $tvars); }

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	echo $tpl -> show('edit');
}

//
// Mass comment delete
//
function massCommentDelete(){
	global $mysql, $lang, $userROW;

	$delcomid = $_REQUEST['delcomid'];

	if (!$delcomid || !count($delcomid)){
		msg(array("type" => "error", "text" => $lang['msge_selectcom'], "info" => $lang['msgi_selectcom']));
		return;
	}

	$countRequested = count($delcomid);
	$countDeleted = 0;
	$countBlocked = 0;
	$countLost    = 0;
	foreach ($delcomid as $delid) {
		list($comid, $author, $add_ip, $postid) = split("-", $delid);

		// Let's delete using only comment id ( $comid )
		if (!is_array($crow = $mysql->record("select (select status from ng_users u where u.id=c.author_id) as castatus, (select author_id from ng_news n where n.id=c.post) as naid, c.* from ".prefix."_comments c where c.id = ".db_squote($comid)))) {
			$countLost++;
			continue;
		}

		// Check permissions. Journalists (status=3) can delete comments only from their own news
		// and only from commenters (status=4)
		if ($userROW['status'] == 3) {
			if ($crow['naid'] != $userROW['id']) {
				// Attempt to delete comments from news created by another person
				$countBlocked++;
				continue;
			}
		}

		// User can't delete comments of another user with the same (or higher) access level
		if (($userROW['status'] > 1) && ($userROW['status'] >= $crow['castatus']) && ($userROW['id'] != $crow['author_id']) && ($crow['castatus'] > 0)) {
			$countBlocked++;
			continue;
		}

		$countDeleted++;
		$mysql->query("update ".prefix."_news set com=com-1 where id=".db_squote($crow['post']));
		if ($crow['author_id']) {
			$mysql->query("update ".uprefix."_users set com=com-1 where id=".db_squote($crow['author_id']));
		}

		$mysql->query("delete from ".prefix."_comments where id=".db_squote($comid));
	}
	//
	if ($countRequested == $countDeleted) {
		msg(array("text" => $lang['msg.comdel.ok'], 'info' => str_replace(array('{cnt_req}', '{edit_link}'), array($countRequested, $PHP_SELF.'?mod=editnews&action=editnews&id='.$postid), $lang['msg.comdel.ok#descr'])));
	} else {
		msg(array("text" => $lang['msg.comdel.fail'], 'info' => str_replace(array('{cnt_req}', '{cnt_deleted}', '{cnt_blocked}', '{cnt_lost}', '{edit_link}'), array($countRequested, $countDeleted, $countBlocked, $countLost, $PHP_SELF.'?mod=editnews&action=editnews&id='.$postid), $lang['msg.comdel.fail#descr'])));
	}
}


//
// Mass news flags modifier
// $setValue  - what to change in table (array with field => value)
// $langParam - name of variable in lang file to show on success
// $auto      - flag: automatic mode is used, nothing should be printed
//
function massNewsModify($setValue, $langParam, $auto = false) {
	global $mysql, $lang, $PFILTERS, $catmap;

	$selected_news = $_REQUEST['selected_news'];

	if ((!is_array($selected_news))||(!count($selected_news))) {
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}

	// Convert $setValue into SQL string
	$sqllSET = array();
	foreach ($setValue as $k => $v)
		$sqllSET[] = $k." = ".db_squote($v);

	$sqlSET = join(", ", $sqllSET);

	$SNQ = array();
	foreach ($selected_news as $id) {
		$SNQ [] = db_squote($id);
	}

	// Make updated list with REALLY EXISTED NEWS and save values of changed fields for all news
	$nList = array();
	$nData = array();

	foreach ($mysql->select("select id, catid, author_id, ".join(", ", array_keys($setValue))." from ".prefix."_news where id in (".join(", ", $SNQ).")") as $nrow) {
		$nList [] = $nrow['id'];
		$nData [$nrow['id']] = $nrow;
	}

	// If we do not have real news - exit
	if (!count($nList)) {
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}


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

	msg(array("text" => $lang[$langParam]));
}


//
// Mass news delete
//
function massNewsDelete() {
	global $mysql, $lang, $PFILTERS;

	$selected_news = $_REQUEST['selected_news'];

	if (!$selected_news){
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}

	// Scan list of news to be deleted
	foreach ($selected_news as $id) {
		// Fetch news
		if (!is_array($nrow = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id)))) {
			// Skip ID's of non-existent news
			continue;
		}

		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->deleteNews($nrow['id'], $nrow); }

		// Update counters only if news is published
		if ($nrow['approve']) {
			if ($nrow['catid']) {
				$oldcatsql = array();
				foreach(explode(",",$nrow['catid']) as $key) {
					$oldcatsql[] = "id = ".db_squote($key);
				}
				$mysql->query("update ".prefix."_category set posts=posts-1 where ".implode(" or ",$oldcatsql));
			}

			// Update user's posts counter
			if ($nrow['author_id']) {
				$mysql->query("update ".uprefix."_users set news=news-1 where id=".$nrow['author_id']);
			}
		}

		// Delete comments (with updating user's comment counter) [ if plugin comments is installed ]
		if (getPluginStatusInstalled('comments')) {
			foreach ($mysql->select("select * from ".prefix."_comments where post=".$nrow['id']) as $crow) {
				if ($nrow['author_id']) {
					$mysql->query("update ".uprefix."_users set com=com-1 where id=".$crow['author_id']);
				}
			}
			$mysql->query("delete from ".prefix."_comments WHERE post=".db_squote($nrow['id']));
		}

		$mysql->query("delete from ".prefix."_news where id=".db_squote($nrow['id']));
		$mysql->query("delete from ".prefix."_news_map where newsID = ".db_squote($nrow['id']));

		// Delete attached files if any
		if ($nrow['attach_count']) {
			foreach ($mysql->select("select * from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($nrow['id']).")") as $frec) {
				$fmanager = new file_managment();
				$fmanager->file_delete(array('type' => 'file', 'id' => $frec['id']));
			}
		}


		// Notify plugins about news deletion
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->deleteNewsNotify($nrow['id'], $nrow); }

	}
	msg(array("text" => $lang['msgo_deleted']));
}

function makeSortList($selected) {
	global $lang;

	return	'<option value="id_desc"'.($selected == "id_desc"?' selected':'').">".$lang['sort_postid_desc']."</option>".
			'<option value="id"'.($selected == "id"?' selected':'').">".$lang['sort_postid']."</option>".
			'<option value="postdate_desc"'.($selected == "postdate_desc"?' selected':'').">".$lang['sort_postdate_desc']."</option>".
			'<option value="postdate"'.($selected == "postdate"?' selected':'').">".$lang['sort_postdate']."</option>";
}


// #=======================================#
// # Action selection                      #
// #=======================================#

$action		=	$_REQUEST['action'];
$subaction	=	$_REQUEST['subaction'];


if ($action == "editnews") {
	if ($subaction == "doeditnews") { editNews(); }
	editNewsForm();
} elseif ($action == "do_mass_com_delete") {
	massCommentDelete();
} else {
	switch($subaction) {
		case 'do_mass_approve'      : massNewsModify( array('approve'   => 1),   'msgo_approved',   'approve');    break;
		case 'do_mass_mainpage'     : massNewsModify( array('mainpage'  => 1),  'msgo_mainpaged',  'mainpage');   break;
		case 'do_mass_unmainpage'   : massNewsModify( array('mainpage'  => 0),  'msgo_unmainpage', 'unmainpage'); break;
		case 'do_mass_forbidden'    : massNewsModify( array('approve'   => 0),   'msgo_forbidden',  'forbidden');  break;
		case 'do_mass_com_forbidden': massNewsModify( array('allow_com' => 0), 'msgo_cforbidden', 'cforbidden'); break;
		case 'do_mass_com_approve'  : massNewsModify( array('allow_com' => 1), 'msgo_capproved',  'capprove');   break;
		case 'do_mass_delete'       : massNewsDelete(); break;
	}

	$postdate		= intval($_REQUEST['postdate']);
	$authorid		= intval($_REQUEST['authorid']);
	$category		= intval($_REQUEST['category']);

	$news_per_page	= intval($_REQUEST['news_per_page']);
	$start_from		= intval($_REQUEST['start_from']);
	$status_mode	= intval($_REQUEST['status_mode']);

	$sortBy = '';
	switch($_REQUEST['sort']){
		case 'id':				$sortBy = 'id';	break;
		case 'id_desc':			$sortBy = 'id desc';	break;
		case 'postdate':		$sortBy = 'postdate';	break;
		case 'postdate_desc':	$sortBy = 'postdate desc';	break;
	}

	if ($sortBy) {
		$sortBy = " order by ".$sortBy;
	} else {
		$sortBy = "order by id desc";
	}

	if ($userROW['status'] >= 3)	{ $authorid = $userROW['id']; }
	if (($news_per_page < 2)||($news_per_page > 2000)) $news_per_page = 20;

	$pageNo		= intval($_REQUEST['page'])?$_REQUEST['page']:0;
	if ($pageNo < 1)	$pageNo = 1;
	if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;

	$i				=	$start_from;
	$postyear		=	substr($postdate, 0, 4);
	$postmonth		=	substr($postdate, 4, 2);
	$entries_showed	=	'0';

	$conditions = array();
	if ($category)
		array_push($conditions, "catid regexp '[[:<:]](".intval($category).")[[:>:]]'");

	if ($postdate) {
		array_push($conditions, "postdate > '".mktime(0, 0, 0, $postmonth, 1, $postyear)."'");
		array_push($conditions, "postdate < '".mktime(23,59,59,$postmonth,date("t",mktime(0, 0, 0, $postmonth, 1, $postyear)), $postyear)."'");
	}

	if ($authorid)
		array_push($conditions, "author_id = ".db_squote($authorid));

	if ($status_mode)
		array_push($conditions, "approve = ".(($status_mode == 1)?'0':'1'));

	$sql_endr = "from ".prefix."_news ".(count($conditions)?"where ".implode(" AND ", $conditions):'').' '.$sortBy;
	$sql_count = "select count(id) as cid ".$sql_endr;
	$sql = "select * ".$sql_endr;


	$cnt = $mysql->record($sql_count);
	$all_count_news = $cnt['cid'];
	$countPages = ceil($all_count_news / $news_per_page);

	$result = $sql." LIMIT $start_from,$news_per_page";
	$tpl -> template('entries', tpl_actions.$mod);

	foreach ($mysql->select($result) as $row) {
		$i++;
		$allow_com	=	$row['allow_com'];
		$cats		=	explode(",", $row['catid']);

		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'home'		=>	home,
			'newsid'	=>	$row['id'],
			'userid'	=>	$row['author_id'],
			'username'	=>	$row['author'],
			'comments'	=> ($row['com'])?$row['com']:''
		);
		$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

		$showtitle = (strlen($row['title']) > 70)?substr($row['title'],0,70)." ...":$row['title'];
		$tvars['vars']['title'] = secure_html($showtitle);

		$tvars['vars']['status']	=	($row['approve'] == "1") ? '<img src="'.skins_url.'/images/yes.png" alt="'.$lang['approved'].'" />' : '<img src="'.skins_url.'/images/no.png" alt="'.$lang['unapproved'].'" />';
		$tvars['vars']['itemdate']	=	date("d.m.Y",$row['postdate']);
		$tvars['vars']['allcats']	=	@GetAllCategories($cats).' &nbsp;';

		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
		$entries_showed ++;

	}
	$tvars = array();

	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'news_per_page'	=>	$news_per_page,
		'entries'		=>	$entries,
		'sortlist'		=>	makeSortList($_REQUEST['sort']),
		'statuslist'	=> 	'<option value="1"'.(($status_mode==1)?' selected':'').'>'.$lang['smode_unpublished'].'</option><option value="2"'.(($status_mode==2)?' selected':'').'>'.$lang['smode_published'].'</option>'
	);

	foreach ($mysql->select("SELECT DISTINCT FROM_UNIXTIME(postdate,'%b %Y') as monthes, COUNT(id) AS cnt FROM ".prefix."_news GROUP BY monthes ORDER BY postdate DESC") as $row){
		$ifselected = '';
		$post_date['ru']	=	str_replace($f3, $r, $row['monthes']);
		$post_date['en']	=	str_replace($f3, $f2, $row['monthes']);
		$arch_url			=	explode (" ", $post_date['en']);
		$post_date['en']	=	$arch_url[1].$arch_url[0];

		if ($post_date['en'] == $postdate) {
			$ifselected = "selected";
		}

		$tvars['vars']['selectdate'] .= "<option value=\"$post_date[en]\" $ifselected>$post_date[ru]</option>";
	}

	$tvars['vars']['category_select'] = makeCategoryList(array('doall' => 1, 'selected' => $category));

	if ($userROW['status'] < 3) {
		foreach($mysql->select("select id, status, news, name from ".uprefix."_users where news>0 ".($authorid?"or id=".db_squote($authorid):'')." order by name") as $row){
			$tvars['vars']['authorlist'] .= "<option value=\"".$row['id']."\"".($row['id']==$authorid?' selected':'').">".$row['name']." [".$row['news']."]</option>\n";
		}
	}

	if ($entries_showed == "0") {
		$tvars['vars']['[no-news]']		=	'';
		$tvars['vars']['[/no-news]']	=	'';
		$tvars['vars']['entries']		=	'';
		$tvars['vars']['pagesss']		=	'';
	}
	else {
		$tvars['regx']["'\\[no-news\\].*?\\[/no-news\\]'si"] = '';
		$tvars['vars']['pagesss'] = generateAdminPagelist( array('current' => $pageNo, 'count' => $countPages, 'url' => admin_url.'/admin.php?mod=editnews&action=list'.($_REQUEST['news_per_page']?'&news_per_page='.$news_per_page:'').($_REQUEST['author']?'&author='.$_REQUEST['author']:'').($_REQUEST['category']?'&category='.$_REQUEST['category']:'').($_REQUEST['sort']?'&sort='.$_REQUEST['sort']:'').($postdate?'&postdate='.$postdate:'').($authorid?'&authorid='.$authorid:'').($status_mode?'&status_mode='.$status_mode:'').'&page=%page%'));
	}

	if($userROW['status'] <= 2) {
		$tvars['vars']['[actions]'] = '';
		$tvars['vars']['[/actions]'] = '';
	}
	else {
		$tvars['regx']["'\\[actions\\].*?\\[/actions\\]'si"] = '';
	}

	$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

	exec_acts('editnews_list');

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}