<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: editnews.php
// Description: News managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root.'includes/classes/upload.class.php';


LoadLang('editnews', 'admin');
LoadLang('editnews', 'admin', 'editnews');
LoadLang('addnews', 'admin', 'addnews');

// ======================================================================================================
// Edit news
// ======================================================================================================
function editNews() {
	global $lang, $parse, $mysql, $config, $PFILTERS, $userROW, $catz, $catmap;


	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]
	$SQL = array();

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

	// Fill content
	$content	= '';

	// Check if EDITOR SPLIT feature is activated
	if ($config['news.edit.split']) {
		// Prepare delimiter
		$ed = '<!--more-->';
		if ($_REQUEST['content_delimiter'] != '') {
			// Disable `new line` + protect from XSS
			$ed = '<!--more="'.str_replace(array("\r", "\n", '"'), '', $_REQUEST['content_delimiter']).'"-->';
		}
		$content = $_REQUEST['ng_news_content_short'].(($_REQUEST['ng_news_content_full'] != '')?$ed.$_REQUEST['ng_news_content_full']:'');

	} else {
		$content = $_REQUEST['ng_news_content'];
	}

	// Rewrite `\r\n` to `\n`
	$content = str_replace("\r\n", "\n", $content);


	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id=".db_squote($id).(($userROW['status'] > 2)?" and author_id = ".db_squote($userROW['id']):'')))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return;
	}

	// Manage alt name
	$alt_name	= $_REQUEST['alt_name'];
	// Check if alt name should be generated again
	if (trim($alt_name) == '') {
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
		$alt_name = $alt_name.$i;
	}


	// Check if alt name was changed
	if ($alt_name != $row['alt_name']) {
		// Check for allowed chars in alt name
		if (!$parse->nameCheck($alt_name)) {
			msg(array("type" => "error", "text" => $lang['err.altname.wrong']));
			return;
		}
	}

	// Check if we try to use duplicate alt_name
	if (is_array($mysql->record("select * from ".prefix."_news where alt_name=".db_squote($alt_name)." and id <> ".db_squote($row['id'])." limit 1"))) {
		msg(array("type" => "error", "text" => $lang['err.altname.dup']));
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

	if ($_REQUEST['setdate_custom']) {
		$SQL['postdate'] = mktime(intval($_REQUEST['c_hour']), intval($_REQUEST['c_minute']), 0, intval($_REQUEST['c_month']), intval($_REQUEST['c_day']), intval($_REQUEST['c_year'])) + ($config['date_adjust'] * 60);
	} else if ($_REQUEST['setdate_current']) {
		$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
	}

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

		if ($_REQUEST['setViews'])
			$SQL['views'] = intval($_REQUEST['views']);
	} else {
		foreach (array('mainpage', 'approve', 'favorite', 'pinned') as $v) {
			$SQL[$v] = $row[$v];
		}
	}

	// Load list of attached images/files
	$row['#files']	= $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_files where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);
	$row['#images']	= $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_images where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);

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
		$numFiles = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		$numImages = $mysql->result("select count(*) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");

		$mysql->query("update ".prefix."_news set num_files = ".intval($numFiles)." where id = ".db_squote($id));
		$mysql->query("update ".prefix."_news set num_images = ".intval($numImages)." where id = ".db_squote($id));

	}

	// Notify plugins about news edit completion
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsNotify($id, $row, $SQL, $tvars); }

	msg(array("text" => $lang['msgo_edited']));
}


// ======================================================================================================
// Edit news form
// ======================================================================================================
function editNewsForm() {
	global $lang, $parse, $mysql, $config, $PFILTERS, $tvars, $userROW, $twig;

	// Get news id
	$id			= $_REQUEST['id'];

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id).(($userROW['status'] > 2)?" and author_id = ".db_squote($userROW['id']):''),1))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	// Join attached images / files to record
	//if ($row['num_files']) {
		$row['#files'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_files where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);
	//}

	//if ($row['num_images']) {
		$row['#images'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_images where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);
	//}

	$cats = explode(",", $row['catid']);
	$content = $row['content'];

	$tVars = array(
		'php_self'			=>	$PHP_SELF,
		'changedate'		=>	ChangeDate($row['postdate'], 1),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0,   'selected' => count($cats)?$cats[0]:0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1, 'selected' => (count($cats)>1)?array_slice($cats,1):array())),
		'allcats'			=>	@GetAllCategories($cats),
		'id'				=>	$row['id'],
		'title'				=>	secure_html($row['title']),
		'content'			=>  array(),
		'alt_name'			=>	$row['alt_name'],
		'avatar'			=>	$row['avatar'],
		'description'		=>	secure_html($row['description']),
		'keywords'			=>	secure_html($row['keywords']),
		'views'				=>	$row['views'],
		'author'			=>  $row['author'],
		'authorid'			=>  $row['author_id'],
		'createdate'		=>  strftime('%d.%m.%Y %H:%M', $row['postdate']),
		'editdate'			=>  ($row['editdate'] > $row['postdate'])?strftime('%d.%m.%Y %H:%M', $row['editdate']):'-',
		'author_page'		=>  checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('name' => $row['author'], 'id' => $row['author_id'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['author'], 'id' => $row['author_id'])),
		'smilies'			=> $config['use_smilies']?InsertSmilies('', 20, 'currentInputAreaID'):'',
		'quicktags'			=> $config['use_bbcodes']?QuickTags('currentInputAreaID', 'news'):'',
		'flags'				=> array(
			'edit_split'		=> $config['news.edit.split']?true:false,
			'meta'				=> $config['meta']?true:false,
			'options'			=> ($userROW['status'] < 3)?true:false,
			'mainpage'			=> $row['mainpage']?true:false,
			'favorite'			=> $row['favorite']?true:false,
			'approve'			=> $row['approve']?true:false,
			'pinned'			=> $row['pinned']?true:false,
			'raw'				=> ($row['flags'] & 1),
			'html'				=> ($row['flags'] & 2),
			'extended_more'		=> ($config['extended_more'] || ($tvars['vars']['content.delimiter'] != ''))?true:false,
		)
	);


	// Generate data for content input fields
	if ($config['news.edit.split']) {
		$tVars['content']['delimiter'] = '';
		if (preg_match('#^(.*?)<!--more-->(.*?)$#si', $row['content'], $match)) {
			$tVars['content']['short'] = secure_html($match[1]);
			$tVars['content']['full'] = secure_html($match[2]);
		} else if (preg_match('#^(.*?)<!--more=\"(.*?)\"-->(.*?)$#si', $row['content'], $match)) {
			$tVars['content']['short'] = secure_html($match[1]);
			$tVars['content']['full'] = secure_html($match[3]);
			$tVars['content']['delimiter'] = secure_html($match[2]);
		} else {
			$tVars['content']['short'] = secure_html($row['content']);
			$tVars['content']['full'] = '';
		}
	} else {
		$tVars['content']['short'] = secure_html($row['content']);
	}


	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}

	$tVars['falgs']['raw.disabled']		= $flock?true:false;
	$tVars['flags']['html.disabled']	= $flock?true:false;

	// Check for attached files
	$attachEntries = array();
	$attachNumber = 0;

	if ($row['num_files']) {

		$num = 0;
		foreach ($row['#files'] as $arow) {
			// Skip files, that are attached by plugins
			if ($arow['plugin'] != '') continue;

			$attachNumber++;
			$attachEntry = array(
				'id'	=> $arow['id'],
				'num'	=> $attachNumber,
				'date'	=> $arow['date'],
				'orig_name'	=> $arow['orig_name'],
			);

			// Check if file exists
			$fname = ($arow['storage']?$config['attach_dir']:$config['files_dir']).$arow['folder'].'/'.$arow['name'];
			if (file_exists($fname) && ($fsize = @filesize($fname))) {
				$attachEntry['filesize'] = Formatsize($fsize);
				$attachEntry['url'] = (($arow['storage'])?($config['attach_url']):($config['files_url'])).'/'.$arow['folder'].'/'.$arow['name'];
			} else {
				$attachEntry['filesize'] = '<font color="red">n/a</font>';
			}
			$attachEntries []= $attachEntry;
		}
	}
	$tVars['attachEntries'] = $attachEntries;
	$tVars['attachCount'] = $attachNumber;

	exec_acts('editnews_entry', $row['xfields'], '');
	exec_acts('editnews_form');

	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsForm($id, $row, $tVars); }

	$xt = $twig->loadTemplate('skins/default/tpl/news/edit.tpl');
	echo $xt->render($tVars);
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
		if (!is_array($crow = $mysql->record("select (select status from ".uprefix."_users u where u.id=c.author_id) as castatus, (select author_id from ".prefix."_news n where n.id=c.post) as naid, c.* from ".prefix."_comments c where c.id = ".db_squote($comid)))) {
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

	$result = massModifyNews(array('id' => $selected_news), $setValue, true);
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
		if ($nrow['num_files']) {
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
			'<option value="postdate"'.($selected == "postdate"?' selected':'').">".$lang['sort_postdate']."</option>".
			'<option value="title_desc"'.($selected == "title_desc"?' selected':'').">".$lang['sort_title_desc']."</option>".
			'<option value="title"'.($selected == "title"?' selected':'').">".$lang['sort_title']."</option>";
}


// ======================================================================================================
// List news
// ======================================================================================================
function listNewsForm() {
	global $mysql, $lang, $twig, $tpl, $catz, $catmap;

	// Search filters
	$fSearchLine		= $_REQUEST['sl'];
	$fSearchType		= intval($_REQUEST['st']);

	// Author filter (by name)
	$fAuthorName		= $_REQUEST['an'];

	// Date range
	$fDateStart			= '';
	$fDateStartText		= '';
	if (preg_match('#^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$#', $_REQUEST['dr1'], $match)) {
		$fDateStartText = $_REQUEST['dr1'];
		$fDateStart		= mktime(0, 0, 0, $match[2], $match[1], $match[3]);
	}

	$fDateStop			= '';
	$fDateStopText		= '';
	if (preg_match('#^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$#', $_REQUEST['dr2'], $match)) {
		$fDateStopText	= $_REQUEST['dr2'];
		$fDateStop		= mktime(0, 0, 0, $match[2], $match[1], $match[3]);
	}

	// Category
	$fCategoryId	= intval($_REQUEST['category']);

	// Status
	$fStatus		= intval($_REQUEST['status']);

	// Records Per Page
	$fRPP			= intval($_REQUEST['rpp']);

	// Sort mode
	$fSort = '';
	switch($_REQUEST['sort']){
		case 'id':				$fSort = 'id';				break;
		case 'id_desc':			$fSort = 'id desc';			break;
		case 'postdate':		$fSort = 'postdate';		break;
		case 'postdate_desc':	$fSort = 'postdate desc';	break;
		case 'title':			$fSort = 'title';			break;
		case 'title_desc':		$fSort = 'title desc';		break;
	}
	$fSort = ' order by '.($fSort?$fSort:'id desc');


	// Users with status >= 3 can see only their own news
	$fAuthorId = 0;
	if ($userROW['status'] >= 3)	{ $fAuthorId = intval($userROW['id']); }
	else {
		// But another's can set AuthorID manually for filtering (callback from users list)
		if ($_REQUEST['aid']) {
			// Try to fetch userName
			if ($urow = $mysql->record("select id, name from ".uprefix."_users where id = ".db_squote($_REQUEST['aid']))) {
				$fAuthorId = $urow['id'];
				$fAuthorName = $urow['name'];
			}
		}
	}

	// Set default value for `Records Per Page` parameter
	if (($fRPP < 2)||($fRPP > 2000))
		$fRPP = 20;

	// Determine requested page number
	$pageNo		= intval($_REQUEST['page'])?$_REQUEST['page']:0;
	if ($pageNo < 1)	$pageNo = 1;

	if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;

	$i				=	$start_from;
	$entries_showed	=	'0';

	$conditions = array();
	if ($fCategoryId)
		array_push($conditions, "catid regexp '[[:<:]](".intval($fCategoryId).")[[:>:]]'");

	if ($fDateStart) {
		array_push($conditions, "postdate >= ".intval($fDateStart));
	}

	if ($fDateStop) {
		array_push($conditions, "postdate <= ".intval($fDateStop));
	}

	if ($fAuthorId) {
		array_push($conditions, "author_id = ".$fAuthorId);
	} else if ($fAuthorName) {
		array_push($conditions, "author = ".db_squote($fAuthorName));
	}

	if ($fStatus)
		array_push($conditions, "approve = ".(($fStatus == 1)?'0':'1'));


	// Perform search
	if ($fSearchLine != '') {
		array_push($conditions, ($fSearchType?'content':'title')." like ".db_squote('%'.$fSearchLine.'%'));
	}

	$sqlQPart = "from ".prefix."_news ".(count($conditions)?"where ".implode(" AND ", $conditions):'').' '.$fSort;
	$sqlQCount = "select count(id) as cid ".$sqlQPart;
	$sqlQ = "select * ".$sqlQPart;


	$cnt = $mysql->record($sqlQCount);
	$countNews = $cnt['cid'];
	$countPages = ceil($countNews / $fRPP);

	// If Count of pages is less that pageNo we want to show - show last page
	if (($pageNo > $countPages)&&($pageNo > 1))
		$pageNo = $countPages;

	$newsEntries = array();

	$sqlResult = $sqlQ." LIMIT ".(($pageNo - 1)* $fRPP).",".$fRPP;
	foreach ($mysql->select($sqlResult) as $row) {
		$cats		=	explode(",", $row['catid']);

		$newsEntry = array(
			'php_self'		=> $PHP_SELF,
			'home'			=> home,
			'newsid'		=> $row['id'],
			'userid'		=> $row['author_id'],
			'username'		=> $row['author'],
			'comments'		=> isset($row['com'])?$row['com']:'',
			'attach_count'	=> $row['num_files'],
			'itemdate'		=> date("d.m.Y",$row['postdate']),
			'allcats'		=> @GetAllCategories($cats).' &nbsp;',
			'title'			=> secure_html((strlen($row['title']) > 70)?substr($row['title'],0,70)." ...":$row['title']),
			'link'			=> newsGenerateLink($row, false, 0, true),

			'flags'			=> array(
				'comments'		=> getPluginStatusInstalled('comments')?true:false,
				'status'		=> $row['approve']?true:false,
				'mainpage'		=> $row['mainpage']?true:false,
			)
		);

		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) {
				$v->listNewsForm($id, $row, $tVars);
			}

		$entries_showed ++;
		$newsEntries []= $newsEntry;
	}
	$tVars = array(
		'php_self'		=>	$PHP_SELF,
		'rpp'			=>	$fRPP,
		'entries'		=>	$newsEntries,
		'sortlist'		=>	makeSortList($_REQUEST['sort']),
		'statuslist'	=> 	'<option value="1"'.(($fStatus==1)?' selected':'').'>'.$lang['smode_unpublished'].'</option><option value="2"'.(($fStatus==2)?' selected':'').'>'.$lang['smode_published'].'</option>',
		'flags'			=> array(
			'comments'		=> getPluginStatusInstalled('comments')?true:false,
			'allow_modify'	=> ($userROW['status'] <= 2)?true:false,
		),
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

		$tVars['selectdate'] .= "<option value=\"$post_date[en]\" $ifselected>$post_date[ru]</option>";
	}

	$tVars['category_select'] = makeCategoryList(array('doall' => 1, 'selected' => $category, 'style' => 'width: 200px;'));

	if ($entries_showed) {
		$tVars['pagesss'] = generateAdminPagelist(
		array(
			'maxNavigations' => 30,
			'current' => $pageNo,
			'count' => $countPages,
			'url' => admin_url.
					'/admin.php?mod=editnews&action=list'.
					($fRPP?'&rpp='.$fRPP:'').
					($fAuthorName != ''?'&an='.$fAuthorName:'').
					($fSearchLine != ''?'&sl='.$fSearchLine:'').
					($fSearchType != ''?'&st='.$fSearchType:'').
					($fDateStartText != ''?'&dr1='.$fDateStartText:'').
					($fDateStopText != ''?'&dr2='.$fDateStopText:'').
					($fCategoryId != ''?'&category='.$fCategoryId:'').
					($fStatus != ''?'&status='.$fStatus:'').
					'&page=%page%'
				));
	}

	$tVars['an'] = secure_html($fAuthorName);
	$tVars['sl'] = secure_html($fSearchLine);
	$tVars['st.selected0'] = !$fSearchType?' selected="selected"':'';
	$tVars['st.selected1'] =  $fSearchType?' selected="selected"':'';
	$tVars['dr1'] = $fDateStartText;
	$tVars['dr2'] = $fDateStopText;
	$tVars['localPrefix'] = localPrefix;

	// Prepare category menu
	{
		$cList = $mysql->select("select * from ".prefix."_category order by posorder");
		$cLen  = count($cList);

		$tcRecs = array();
		// Go through this list
		foreach ( $cList as $num => $row) {
			// Prepare data for template
			$tcRec = array(
				'id'		=>	$row['id'],
				'name'		=>	$row['name'],
				'posts'		=>	$row['posts'],
				'alt'		=>	$row['alt'],
				'alt_url'	=>	$row['alt_url'],
				'flags'		=>	array(
					'selected'	=> (isset($_REQUEST['category']) && ($row['id'] == $_REQUEST['category']))?true:false,
				),
			);

			// Prepare position
			$tcRec['cutter'] = '';
			if ($row['poslevel'] > 0) {
				$tcRec['cutter'] = str_repeat('<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/line.gif" />', ($row['poslevel']));
			} else {
				$tcRec['cutter'] = '';
			}
			$tcRec['cutter'] = $tcRec['cutter'] .
				'<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/join'.((($num == ($cLen-1) || ($cList[$num]['poslevel'] > $cList[$num+1]['poslevel'])))?'bottom':'').'.gif" />';

			$tcRecs []= $tcRec;
		}
	}

	$tVars['catmenu'] = $tcRecs;
	$tVars['cat_active'] = ((isset($_REQUEST['category']) && (isset($catmap[intval($_REQUEST['category'])]))))?intval($_REQUEST['category']):0;

	$xt = $twig->loadTemplate('skins/default/tpl/news/table.tpl');
	echo $xt->render($tVars);
}



// ======================================================================================================
// Add news
// ======================================================================================================
function addNews(){
	global $mysql, $lang, $userROW, $parse, $PFILTERS, $config, $catz, $catmap;

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
	if ( (!strlen(trim($title))) || (!strlen(trim($content))) ) {
		msg(array("type" => "error", "text" => $lang['addnews']['msge_fields'], "info" => $lang['addnews']['msgi_fields']));
		return 0;
	}

	$SQL['title'] = $title;

	// Check for dup if alt_name is specified
	if ($alt_name) {
		if ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name)." limit 1")) ) {
			msg(array("type" => "error", "text" => $lang['addnews']['msge_alt_name'], "info" => $lang['addnews']['msgi_alt_name']));
			return;
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

	if ($_REQUEST['customdate']) {
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

	// Fetch ADDITIONAL provided categories
	foreach ($_POST as $k => $v) {
		if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
			$catids[$match[1]] = 1;
	}

	if ($config['meta']) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	$SQL['author']		= $userROW['name'];
	$SQL['author_id']	= $userROW['id'];
	$SQL['catid']		= implode(",", array_keys($catids));

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

	// This actions are allowed only for admins & Edtiors
	if (($userROW['status'] == 1)||($userROW['status'] == 2)) {
		$SQL['mainpage']	= intval($_REQUEST['mainpage']);
		$SQL['approve']		= intval($_REQUEST['approve']);
		$SQL['favorite']	= intval($_REQUEST['favorite']);
		$SQL['pinned']		= intval($_REQUEST['pinned']);
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
	if ($SQL['approve']) {
		if (count($catids)) {
			$mysql->query("update ".prefix."_category set posts=posts+1 where id in (".implode(", ",array_keys($catids)).")");
			foreach (array_keys($catids) as $catid) {
				$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".db_squote($id).", ".db_squote($catid).")");
			}
		}
		$mysql->query("update ".uprefix."_users set news=news+1 where id=".$SQL['author_id']);
	}


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
		$numFiles = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		$numImages = $mysql->result("select count(*) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");

		$mysql->query("update ".prefix."_news set num_files = ".intval($numFiles)." where id = ".db_squote($id));
		$mysql->query("update ".prefix."_news set num_images = ".intval($numImages)." where id = ".db_squote($id));
	}

	// Notify plugins about adding new news
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsNotify($tvars, $SQL, $id); }

	exec_acts('addnews_', $id);
	msg(array("text" => $lang['addnews']['msgo_added'], "info" => sprintf($lang['addnews']['msgi_added'], admin_url.'/admin.php?mod=news&action=edit&id='.$id, admin_url.'/admin.php?mod=news')));

	return 1;
}


// ======================================================================================================
// Add news form
// ======================================================================================================
function addNewsForm($retry = ''){
	global $lang, $mysql, $config, $userROW, $PFILTERS, $tpl, $twig;

	$tVars = array(
		'php_self'			=> $PHP_SELF,
		'changedate'		=> ChangeDate(),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1)),
		'JEV'				=> $retry,
		'smilies'			=> ($config['use_smilies'])?InsertSmilies('', 20, 'currentInputAreaID'):'',
		'tags'				=> ($config['use_bbcodes'])?QuickTags('currentInputAreaID', 'news'):'',
		'flags'				=> array(
			'edit_split'		=> $config['news.edit.split']?true:false,
			'options'			=> ($userROW['status'] < 3)?true:false,
			'meta'				=> $config['meta']?true:false,
			'extended_more'		=> ($config['extended_more'] || ($tvars['vars']['content.delimiter'] != ''))?true:false,
		),
	);

	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}

	$tVars['falgs']['raw.disabled']		= $flock?true:false;
	$tVars['flags']['html.disabled']	= $flock?true:false;

	// Configure flags
	$tVars['flags']['mainpage']  = (($userROW['status'] == 1)||($userROW['status'] == 2))?true:false;
	$tVars['flags']['approve']   = (($userROW['status'] == 1)||($userROW['status'] == 2))?true:false;
	$tVars['flags']['favorite']  = (($userROW['status'] == 1)||($userROW['status'] == 2))?true:false;
	$tVars['flags']['pinned']    = (($userROW['status'] == 1)||($userROW['status'] == 2))?true:false;

	// Generate data for content input fields
	if ($config['news.edit.split']) {
		$tvars['regx']['#\[edit\.split\](.+?)\[\/edit\.split\]#is']		= '$1';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\/edit\.nosplit\]#is']	= '';
	} else {
		$tvars['regx']['#\[edit\.split\](.+?)\[\/edit\.split\]#is']		= '';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\/edit\.nosplit\]#is']	= '$1';
	}

	// Disable flag for comments if plugin 'comments' is not installed
	$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

	// Run interceptors
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsForm($tVars); }

	$xt = $twig->loadTemplate('skins/default/tpl/news/add.tpl');
	echo $xt->render($tVars);
}




// #==============================================================================#
// # Action selection                                                             #
// #==============================================================================#

$action		=	$_REQUEST['action'];
$subaction	=	$_REQUEST['subaction'];

// Main execution block
do {
	// Manage "ADD" mode
	if ($action == "add") {
		$replay = false;
		if ($subaction == "submit") {
			if (!addNews()) {
				$replay = true;
			}
		}
		addNewsForm($replay?json_encode(arrayCharsetConvert(0, $_POST)):null);
		break;
	}

	if ($action == "edit") {
		if ($subaction == "submit") {
			editNews();
		}
		if ($subaction == "mass_com_delete") {
			massCommentDelete();
		}
		editNewsForm();
		break;
	}

	if ($action == "manage") {
		switch($subaction) {
			case 'mass_currdate'	:	$curdate = time() + ($config['date_adjust'] * 60);
										massNewsModify( array('postdate' => $curdate),	'msgo_currdate',	'capprove');	break;
			case 'mass_approve'      :	massNewsModify( array('approve'   => 1),	'msgo_approved',	'approve');			break;
			case 'mass_mainpage'     :	massNewsModify( array('mainpage'  => 1),	'msgo_mainpaged',	'mainpage');		break;
			case 'mass_unmainpage'   :	massNewsModify( array('mainpage'  => 0),	'msgo_unmainpage',	'unmainpage');		break;
			case 'mass_forbidden'    :	massNewsModify( array('approve'   => 0),	'msgo_forbidden',	'forbidden');		break;
			case 'mass_com_forbidden':	massNewsModify( array('allow_com' => 0),	'msgo_cforbidden',	'cforbidden');		break;
			case 'mass_com_approve'  :	massNewsModify( array('allow_com' => 1),	'msgo_capproved',	'capprove');		break;
			case 'mass_delete'       :	massNewsDelete();	break;
		}
	}
	listNewsForm();

} while (false);

/*
if ($action == "editnews") {
	if ($subaction == "doeditnews") { editNews(); }
	editNewsForm();
} elseif ($action == "do_mass_com_delete") {
	massCommentDelete();
} else {
	switch($subaction) {
		case 'do_mass_currdate'		:	$curdate = time() + ($config['date_adjust'] * 60);
										massNewsModify( array('postdate' => $curdate),	'msgo_currdate',	'capprove');   break;
		case 'do_mass_approve'      :	massNewsModify( array('approve'   => 1),	'msgo_approved',	'approve');    break;
		case 'do_mass_mainpage'     :	massNewsModify( array('mainpage'  => 1),	'msgo_mainpaged',	'mainpage');   break;
		case 'do_mass_unmainpage'   :	massNewsModify( array('mainpage'  => 0),	'msgo_unmainpage',	'unmainpage'); break;
		case 'do_mass_forbidden'    :	massNewsModify( array('approve'   => 0),	'msgo_forbidden',	'forbidden');  break;
		case 'do_mass_com_forbidden':	massNewsModify( array('allow_com' => 0),	'msgo_cforbidden',	'cforbidden'); break;
		case 'do_mass_com_approve'  :	massNewsModify( array('allow_com' => 1),	'msgo_capproved',	'capprove');   break;
		case 'do_mass_delete'       :	massNewsDelete(); break;
	}
	listNewsForm();
}
*/
