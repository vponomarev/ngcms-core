<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: news.php
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

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
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
		'personal.catpinned',
		'personal.favorite',
		'personal.setviews',
		'personal.multicat',
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
		'other.catpinned',
		'other.favorite',
		'other.setviews',
		'other.multicat',
		'other.customdate'
	));

	$id			= $_REQUEST['id'];

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}


	$isOwn = ($row['author_id'] == $userROW['id'])?1:0;
	$permGroupMode = $isOwn?'personal':'other';


	// Check permissions
	if (!$perm[$permGroupMode.'.modify'.(($row['approve'] == 1)?'.published':'')]) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}



	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]
	$SQL = array();

	$SQL['flags']	= ($perm[$permGroupMode.'.html'])?(($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0)):0;

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


	// Check if we have content
	if ((!strlen(trim($title))) || ((!strlen(trim($content))) && (!$config['news_without_content']))) {
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

	// Fetch ADDITIONAL provided categories [if allowed]
	if ($perm[$permGroupMode.'.multicat']) {
		foreach ($_POST as $k => $v) {
			if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
				$catids[$match[1]] = 1;
		}
	}

	if ($config['meta']) {
		$SQL['description'] = $_REQUEST['description'];
		$SQL['keywords']    = $_REQUEST['keywords'];
	}

	if ($perm[$permGroupMode.'.customdate']) {
		if ($_REQUEST['setdate_custom']) {
			$SQL['postdate'] = mktime(intval($_REQUEST['c_hour']), intval($_REQUEST['c_minute']), 0, intval($_REQUEST['c_month']), intval($_REQUEST['c_day']), intval($_REQUEST['c_year'])) + ($config['date_adjust'] * 60);
		} else if ($_REQUEST['setdate_current']) {
			$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
		}
	}

	$SQL['title']     = $title;
	$SQL['content']   = $content;
	$SQL['alt_name']  = $alt_name;
	$SQL['editdate']  = time();
	$SQL['catid']     = implode(",", array_keys($catids));

	// Change this parameters if user have enough access level
	$SQL['mainpage']	= ($perm[$permGroupMode.'.mainpage']	&& intval($_REQUEST['mainpage']))?1:0;
	$SQL['pinned']		= ($perm[$permGroupMode.'.pinned']		&& intval($_REQUEST['pinned']))?1:0;
	$SQL['catpinned']	= ($perm[$permGroupMode.'.catpinned']	&& intval($_REQUEST['catpinned']))?1:0;
	$SQL['favorite']	= ($perm[$permGroupMode.'.favorite']	&& intval($_REQUEST['favorite']))?1:0;

	switch (intval($_REQUEST['approve'])) {
		case -1:	$SQL['approve'] = -1;								break;
		case 0:		$SQL['approve'] = 0;								break;
		case 1:		$SQL['approve'] = (($row['approve'] == 1)||(($row['approve'] < 1) && ($perm[$permGroupMode.'.publish'])))?1:0;
					break;
		default:	$SQL['approve']	= 0;
	}


	if ($perm[$permGroupMode.'.setviews'] && $_REQUEST['setViews']) {
		$SQL['views'] = intval($_REQUEST['views']);
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
	if (($row['approve'] == 1) && sizeof($oldcatids)) {
		$mysql->query("update ".prefix."_category set posts=posts-1 where id in (".implode(",",array_keys($oldcatids)).")");
	}

	$mysql->query("delete from ".prefix."_news_map where newsID = ".db_squote($id));

	// Check if we need to update user's counters [ only if news was or will be published ]
	if (($row['approve'] != $SQL['approve']) && (($row['approve'] == 1)||($SQL['approve'] == 1))) {
		$mysql->query("update ".uprefix."_users set news=news".(($row['approve'] == 1)?'-':'+')."1 where id=".$row['author_id']);
	}

	if ($SQL['approve'] == 1) {
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

	// Notify plugins about news edit completion
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsNotify($id, $row, $SQL, $tvars); }

	// Update attach count if we need this
	$numFiles	= $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
	if ($numFiles != $row['num_files']) {
		$mysql->query("update ".prefix."_news set num_files = ".intval($numFiles)." where id = ".db_squote($id));
	}

	$numImages	= $mysql->result("select count(*) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
	if ($numImages != $row['num_images']) {
		$mysql->query("update ".prefix."_news set num_images = ".intval($numImages)." where id = ".db_squote($id));
	}

	msg(array("text" => $lang['msgo_edited']));
}


// ======================================================================================================
// Edit news form
// ======================================================================================================
function editNewsForm() {
	global $lang, $parse, $mysql, $config, $PFILTERS, $tvars, $userROW, $twig;

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
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
		'personal.catpinned',
		'personal.favorite',
		'personal.setviews',
		'personal.multicat',
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
		'other.catpinned',
		'other.favorite',
		'other.setviews',
		'other.multicat',
		'other.customdate',
	));

	// Get news id
	$id			= $_REQUEST['id'];

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id),1))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	$isOwn = ($row['author_id'] == $userROW['id'])?1:0;
	$permGroupMode = $isOwn?'personal':'other';


	// Check permissions
	if (!$perm[$permGroupMode.'.view']) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	// Load attached files/images
	$row['#files'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_files where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);
	$row['#images'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from ".prefix."_images where (linked_ds = 1) and (linked_id = ".db_squote($row['id']).')', 1);


	$cats = explode(",", $row['catid']);
	$content = $row['content'];

	$tVars = array(
		'php_self'			=>	$PHP_SELF,
		'changedate'		=>	ChangeDate($row['postdate'], 1),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0,   'selected' => count($cats)?$cats[0]:0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1, 'selected' => (count($cats)>1)?array_slice($cats,1):array(), 'disabledarea' => !$perm[$permGroupMode.'.multicat'])),
		'allcats'			=>	resolveCatNames($cats),
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
		'approve'			=> $row['approve'],
		'flags'				=> array(
			'edit_split'		=> $config['news.edit.split']?true:false,
			'meta'				=> $config['meta']?true:false,
			'mainpage'			=> $row['mainpage']?true:false,
			'favorite'			=> $row['favorite']?true:false,
			'pinned'			=> $row['pinned']?true:false,
			'catpinned'			=> $row['catpinned']?true:false,
			'can_mainpage'		=> $perm[$permGroupMode.'.mainpage']?true:false,
			'can_pinned'		=> $perm[$permGroupMode.'.pinned']?true:false,
			'can_catpinned'		=> $perm[$permGroupMode.'.catpinned']?true:false,
			'raw'				=> ($row['flags'] & 1),
			'html'				=> ($row['flags'] & 2),
			'extended_more'		=> ($config['extended_more'] || ($tvars['vars']['content.delimiter'] != ''))?true:false,
			'editable'			=> (($perm[$permGroupMode.'.modify'.(($row['approve'] == 1)?'.published':'')]) || ($perm[$permGroupMode.'.unpublish']))?true:false,
			'deleteable'		=> ($perm[$permGroupMode.'.delete'.(($row['approve'] == 1)?'.published':'')])?true:false,
			'html.lost'			=> (($row['flags'] & 2) && (!$perm[$permGroupMode.'.html']))?1:0,
			'mainpage.lost'		=> (($row['mainpage']) && (!$perm[$permGroupMode.'.mainpage']))?true:false,
			'pinned.lost'		=> (($row['pinned']) && (!$perm[$permGroupMode.'.pinned']))?true:false,
			'catpinned.lost'	=> (($row['catpinned']) && (!$perm[$permGroupMode.'.catpinned']))?true:false,
			'publish.lost'		=> (($row['approve'] == 1) && (!$perm[$permGroupMode.'.modify.published']))?true:false,
			'favorite.lost'		=> (($row['favorite']) && (!$perm[$permGroupMode.'.favorite']))?true:false,
			'multicat.lost'		=> ((count($cats)>1) && (!$perm[$permGroupMode.'.multicat']))?true:false,
			'html.disabled'		=> (!$perm[$permGroupMode.'.html'])?true:false,
			'customdate.disabled'	=> (!$perm[$permGroupMode.'.customdate'])?true:false,
			'mainpage.disabled'	=> (!$perm[$permGroupMode.'.mainpage'])?true:false,
			'pinned.disabled'	=> (!$perm[$permGroupMode.'.pinned'])?true:false,
			'catpinned.disabled'=> (!$perm[$permGroupMode.'.catpinned'])?true:false,
			'favorite.disabled'	=> (!$perm[$permGroupMode.'.favorite'])?true:false,
			'setviews.disabled'	=> (!$perm[$permGroupMode.'.setviews'])?true:false,
			'multicat.disabled'	=> (!$perm[$permGroupMode.'.multicat'])?true:false,
		)
	);

	$tVars['flags']['can_publish']		= ((($row['approve'] == 1) && ($perm[$permGroupMode.'.modify.published']))  || (($row['approve'] < 1) && $perm[$permGroupMode.'.publish']))?1:0;
	$tVars['flags']['can_unpublish']	= (($row['approve'] < 1)   || ($perm[$permGroupMode.'.unpublish']))?1:0;
	$tVars['flags']['can_draft']		= (($row['approve'] == -1) || ($perm[$permGroupMode.'.unpublish']))?1:0;

	$tVars['flags']['params.lost']		= ($tVars['flags']['publish.lost'] || $tVars['flags']['html.lost'] || $tVars['flags']['mainpage.lost'] || $tVars['flags']['pinned.lost'] || $tVars['flags']['catpinned.lost'] || $tVars['flags']['multicat.lost'])?1:0;


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
		list($comid, $author, $add_ip, $postid) = explode("-", $delid);

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
		msg(array("text" => $lang['msg.comdel.ok'], 'info' => str_replace(array('{cnt_req}', '{edit_link}'), array($countRequested, $PHP_SELF.'?mod=news&action=edit&id='.$postid), $lang['msg.comdel.ok#descr'])));
	} else {
		msg(array("text" => $lang['msg.comdel.fail'], 'info' => str_replace(array('{cnt_req}', '{cnt_deleted}', '{cnt_blocked}', '{cnt_lost}', '{edit_link}'), array($countRequested, $countDeleted, $countBlocked, $countLost, $PHP_SELF.'?mod=news&action=edit&id='.$postid), $lang['msg.comdel.fail#descr'])));
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
	msg(array("text" => $lang[$langParam], "info" => join("<br/>\n", $result)));
}


//
// Mass news delete
//
function massNewsDelete() {
	global $mysql, $lang, $PFILTERS, $userROW;

	$selected_news = $_REQUEST['selected_news'];

	if ((!is_array($selected_news))||(!count($selected_news))) {
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'personal.delete',
		'personal.delete.published',
		'other.delete',
		'other.delete.published',
	));

	$results = array();

	// Scan list of news to be deleted
	foreach ($selected_news as $id) {
		// Fetch news
		if (!is_array($nrow = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id)))) {
			// Skip ID's of non-existent news
			continue;
		}

		// Check for permissions
		$isOwn = ($nrow['author_id'] == $userROW['id'])?1:0;
		$permGroupMode = $isOwn?'personal':'other';

		if (!$perm[$permGroupMode.'.delete'.(($nrow['approve'] == 1)?'.published':'')]) {
			$results []= '#'.$nrow['id'].' ('.$nrow['title'].') - '.$lang['perm.denied'];
			continue;
		}

		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->deleteNews($nrow['id'], $nrow); }

		// Update counters only if news is published
		if ($nrow['approve'] == 1) {
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

		// Notify plugins about news deletion
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->deleteNewsNotify($nrow['id'], $nrow); }

		// Delete attached news/files if any
		$fmanager = new file_managment();
		// ** Files
		foreach ($mysql->select("select * from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($nrow['id']).")") as $frec) {
			$fmanager->file_delete(array('type' => 'file', 'id' => $frec['id']));
		}

		// ** Images
		foreach ($mysql->select("select * from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($nrow['id']).")") as $frec) {
			$fmanager->file_delete(array('type' => 'image', 'id' => $frec['id']));
		}

		$results []= '#'.$nrow['id'].' ('.$nrow['title'].') - Ok';
	}
	msg(array("text" => $lang['msgo_deleted'], "info" => join("<br/>\n", $results)));
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
	global $mysql, $lang, $twig, $tpl, $catz, $catmap, $userROW;

	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'view',
		'personal.list',
		'personal.view',
		'personal.publish',
		'personal.unpublish',
		'personal.delete',
		'personal.delete.published',
		'other.list',
		'other.view',
		'other.publish',
		'other.unpublish',
		'other.delete',
		'personal.delete.published',
	));

	// Check if we have view access
	if (!$perm['view'] || (!$perm['personal.list'] && !$perm['other.list'])) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	// Load admin page based cookies
	$admCookie = admcookie_get();

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

	// Check if user selected personal filter
	$fAuthorId = 0;
	if ($_REQUEST['aid']) {
		// Try to fetch userName
		if ($urow = $mysql->record("select id, name from ".uprefix."_users where id = ".db_squote($_REQUEST['aid']))) {
			$fAuthorId = $urow['id'];
			$fAuthorName = $urow['name'];
		}
	}


	// Records Per Page
	// - Load
	$fRPP			= isset($_REQUEST['rpp'])?intval($_REQUEST['rpp']):intval($admCookie['news']['pp']);
	// - Set default value for `Records Per Page` parameter
	if (($fRPP < 2)||($fRPP > 2000))
		$fRPP = 20;

	// - Save into cookies current value
	$admCookie['news']['pp'] = $fRPP;
	admcookie_set($admCookie);

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

	if (!$perm['other.list'])
		array_push($conditions, "author_id = ".intval($userROW['id']));

	if (!$perm['personal.list'])
		array_push($conditions, "author_id <> ".intval($userROW['id']));


	if ($fStatus)
		array_push($conditions, "approve = ".(intval($fStatus)-2));


	// Perform search
	if ($fSearchLine != '') {
		array_push($conditions, ($fSearchType?'content':'title')." like ".db_squote('%'.$fSearchLine.'%'));
	}

	$sqlQPart = "from ".prefix."_news ".(count($conditions)?"where ".implode(" AND ", $conditions):'').' '.$fSort;
	$sqlQCount = "select count(id) as cid ".$sqlQPart;
	$sqlQ = "select * ".$sqlQPart;

	// print "SQL: $sqlQ";

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
			'images_count'	=> $row['num_images'],
			'itemdate'		=> date("d.m.Y",$row['postdate']),
			'allcats'		=> resolveCatNames($cats).' &nbsp;',
			'title'			=> secure_html((strlen($row['title']) > 70)?substr($row['title'],0,70)." ...":$row['title']),
			'link'			=> newsGenerateLink($row, false, 0, true),
			'state'			=> $row['approve'],
			'flags'			=> array(
				'comments'		=> getPluginStatusInstalled('comments')?true:false,
				'status'		=> ($row['approve'] == 1)?true:false,
				'mainpage'		=> $row['mainpage']?true:false,
				'editable'		=> ($row['author_id'] == $userROW['id'])&&($perm['personal.view'])||($row['author_id'] != $userROW['id'])&&($perm['other.view']),
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
		'statuslist'	=> 	'<option value="1"'.(($fStatus==1)?' selected':'').'>'.$lang['smode_draft'].'</option>'.
							'<option value="2"'.(($fStatus==2)?' selected':'').'>'.$lang['smode_unpublished'].'</option>'.
							'<option value="3"'.(($fStatus==3)?' selected':'').'>'.$lang['smode_published'].'</option>',
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
					'/admin.php?mod=news'.
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

	//$xt = $twig->loadTemplate('skins/default/tpl/news/table_catalog.tpl');
	$xt = $twig->loadTemplate('skins/default/tpl/news/table.tpl');
	echo $xt->render($tVars);
}


// ======================================================================================================
// Add news form
// ======================================================================================================
function addNewsForm($retry = ''){
	global $lang, $mysql, $config, $userROW, $PFILTERS, $tpl, $twig;

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'add',
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
		'personal.catpinned',
		'personal.favorite',
		'personal.setviews',
		'personal.multicat',
		'personal.customdate',
	));

	// Check permissions
	if (!$perm['add']) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	$tVars = array(
		'php_self'			=> $PHP_SELF,
		'changedate'		=> ChangeDate(),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1)),
		'JEV'				=> $retry?$retry:'{}',
		'smilies'			=> ($config['use_smilies'])?InsertSmilies('', 20, 'currentInputAreaID'):'',
		'quicktags'			=> ($config['use_bbcodes'])?QuickTags('currentInputAreaID', 'news'):'',
		'flags'				=> array(
			'mainpage'			=> $perm['add.mainpage'] && $perm['personal.mainpage'],
			'favorite'			=> $perm['add.favorite'] && $perm['personal.favorite'],
			'pinned'			=> $perm['add.pinned'] && $perm['personal.pinned'],
			'catpinned'			=> $perm['add.catpinned'] && $perm['personal.catpinned'],
			'html'				=> $perm['add.html'] && $perm['personal.html'],
			'mainpage.disabled'	=> !$perm['personal.mainpage'],
			'favorite.disabled'	=> !$perm['personal.favorite'],
			'pinned.disabled'	=> !$perm['personal.pinned'],
			'catpinned.disabled'	=> !$perm['personal.catpinned'],
			'edit_split'		=> $config['news.edit.split']?true:false,
			'meta'				=> $config['meta']?true:false,
			'html.disabled'		=> !$perm['personal.html'],
			'customdate.disabled'	=> !$perm['personal.customdate'],
			'multicat.show'		=> $perm['personal.multicat'],
			'extended_more'		=> ($config['extended_more'] || ($tvars['vars']['content.delimiter'] != ''))?true:false,
			'can_publish'		=> $perm['personal.publish'],
		),
	);

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
