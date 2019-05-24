<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: news.php
// Description: News managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root . 'includes/classes/upload.class.php';

LoadLang('editnews', 'admin');
LoadLang('editnews', 'admin', 'editnews');
LoadLang('addnews', 'admin', 'addnews');

// ======================================================================================================
// Edit news form
// ======================================================================================================
function editNewsForm() {

	global $lang, $parse, $mysql, $config, $PFILTERS, $tvars, $userROW, $twig, $PHP_SELF;

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
		'personal.nocat',
		'personal.customdate',
		'personal.altname',
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
		'other.nocat',
		'other.customdate',
		'other.altname',
	));

	// Get news id
	$id = intval($_REQUEST['id']);

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from " . prefix . "_news where id = " . db_squote($id), 1))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));

		return;
	}

	$isOwn = ($row['author_id'] == $userROW['id']) ? 1 : 0;
	$permGroupMode = $isOwn ? 'personal' : 'other';

	// Check permissions
	if (!$perm[$permGroupMode . '.view']) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));

		return;
	}

	// Load attached files/images
	$row['#files'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from " . prefix . "_files where (linked_ds = 1) and (linked_id = " . db_squote($row['id']) . ')', 1);
	$row['#images'] = $mysql->select("select *, date_format(from_unixtime(date), '%d.%m.%Y') as date from " . prefix . "_images where (linked_ds = 1) and (linked_id = " . db_squote($row['id']) . ')', 1);

	$cats = (strlen($row['catid']) > 0) ? explode(",", $row['catid']) : array();
	$content = $row['content'];
	$tVars = array(
		'php_self'    => $PHP_SELF,
		'cdate'       => date('d.m.Y H:i', $row['postdate']),
		'changedate'  => ChangeDate($row['postdate'], 1),
		'mastercat'   => makeCategoryList(array('doempty' => ($perm[$permGroupMode . '.nocat'] || !count($cats)) ? 1 : 0, 'greyempty' => !$perm['personal.nocat'], 'nameval' => 0, 'selected' => count($cats) ? $cats[0] : 0)),
		'extcat'      => makeCategoryList(array('nameval' => 0, 'checkarea' => 1, 'selected' => (count($cats) > 1) ? array_slice($cats, 1) : array(), 'disabledarea' => !$perm[$permGroupMode . '.multicat'])),
		'allcats'     => resolveCatNames($cats),
		'id'          => $row['id'],
		'title'       => secure_html($row['title']),
		'content'     => array(),
		'alt_name'    => $row['alt_name'],
		'description' => secure_html($row['description']),
		'keywords'    => secure_html($row['keywords']),
		'views'       => $row['views'],
		'author'      => $row['author'],
		'authorid'    => $row['author_id'],
		'createdate'  => strftime('%d.%m.%Y %H:%M', $row['postdate']),
		'editdate'    => ($row['editdate'] > $row['postdate']) ? strftime('%d.%m.%Y %H:%M', $row['editdate']) : '-',
		'author_page' => checkLinkAvailable('uprofile', 'show') ?
			generateLink('uprofile', 'show', array('name' => $row['author'], 'id' => $row['author_id'])) :
			generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['author'], 'id' => $row['author_id'])),
		'smilies'     => $config['use_smilies'] ? InsertSmilies('', 20, 'currentInputAreaID') : '',
		'quicktags'   => $config['use_bbcodes'] ? QuickTags('currentInputAreaID', 'news') : '',
		'approve'     => $row['approve'],
		'token'       => genUToken('admin.news.edit'),
		'flags'       => array(
			'edit_split'          => $config['news.edit.split'] ? true : false,
			'meta'                => $config['meta'] ? true : false,
			'mainpage'            => $row['mainpage'] ? true : false,
			'favorite'            => $row['favorite'] ? true : false,
			'pinned'              => $row['pinned'] ? true : false,
			'catpinned'           => $row['catpinned'] ? true : false,
			'can_mainpage'        => $perm[$permGroupMode . '.mainpage'] ? true : false,
			'can_pinned'          => $perm[$permGroupMode . '.pinned'] ? true : false,
			'can_catpinned'       => $perm[$permGroupMode . '.catpinned'] ? true : false,
			'raw'                 => ($row['flags'] & 1),
			'html'                => ($row['flags'] & 2),
			'extended_more'       => ($config['extended_more'] || ($tvars['vars']['content.delimiter'] != '')) ? true : false,
			'editable'            => (($perm[$permGroupMode . '.modify' . (($row['approve'] == 1) ? '.published' : '')]) || ($perm[$permGroupMode . '.unpublish'])) ? true : false,
			'deleteable'          => ($perm[$permGroupMode . '.delete' . (($row['approve'] == 1) ? '.published' : '')]) ? true : false,
			'html.lost'           => (($row['flags'] & 2) && (!$perm[$permGroupMode . '.html'])) ? 1 : 0,
			'mainpage.lost'       => (($row['mainpage']) && (!$perm[$permGroupMode . '.mainpage'])) ? true : false,
			'pinned.lost'         => (($row['pinned']) && (!$perm[$permGroupMode . '.pinned'])) ? true : false,
			'catpinned.lost'      => (($row['catpinned']) && (!$perm[$permGroupMode . '.catpinned'])) ? true : false,
			'publish.lost'        => (($row['approve'] == 1) && (!$perm[$permGroupMode . '.modify.published'])) ? true : false,
			'favorite.lost'       => (($row['favorite']) && (!$perm[$permGroupMode . '.favorite'])) ? true : false,
			'multicat.lost'       => ((count($cats) > 1) && (!$perm[$permGroupMode . '.multicat'])) ? true : false,
			'html.disabled'       => (!$perm[$permGroupMode . '.html']) ? true : false,
			'customdate.disabled' => (!$perm[$permGroupMode . '.customdate']) ? true : false,
			'mainpage.disabled'   => (!$perm[$permGroupMode . '.mainpage']) ? true : false,
			'pinned.disabled'     => (!$perm[$permGroupMode . '.pinned']) ? true : false,
			'catpinned.disabled'  => (!$perm[$permGroupMode . '.catpinned']) ? true : false,
			'favorite.disabled'   => (!$perm[$permGroupMode . '.favorite']) ? true : false,
			'setviews.disabled'   => (!$perm[$permGroupMode . '.setviews']) ? true : false,
			'multicat.disabled'   => (!$perm[$permGroupMode . '.multicat']) ? true : false,
			'altname.disabled'    => (!$perm[$permGroupMode . '.altname']) ? true : false,
			'mondatory_cat'       => (!$perm[$permGroupMode . '.nocat']) ? true : false,
		)
	);

	// Generate link for published news
	if ($row['approve'] == 1) {
		$tVars['link'] = newsGenerateLink($row, false, 0, true);
	}

	$tVars['flags']['can_publish'] = ((($row['approve'] == 1) && ($perm[$permGroupMode . '.modify.published'])) || (($row['approve'] < 1) && $perm[$permGroupMode . '.publish'])) ? 1 : 0;
	$tVars['flags']['can_unpublish'] = (($row['approve'] < 1) || ($perm[$permGroupMode . '.unpublish'])) ? 1 : 0;
	$tVars['flags']['can_draft'] = (($row['approve'] == -1) || ($perm[$permGroupMode . '.unpublish'])) ? 1 : 0;

	$tVars['flags']['params.lost'] = ($tVars['flags']['publish.lost'] || $tVars['flags']['html.lost'] || $tVars['flags']['mainpage.lost'] || $tVars['flags']['pinned.lost'] || $tVars['flags']['catpinned.lost'] || $tVars['flags']['multicat.lost']) ? 1 : 0;

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
				'id'        => $arow['id'],
				'num'       => $attachNumber,
				'date'      => $arow['date'],
				'orig_name' => $arow['orig_name'],
			);

			// Check if file exists
			$fname = ($arow['storage'] ? $config['attach_dir'] : $config['files_dir']) . $arow['folder'] . '/' . $arow['name'];
			if (file_exists($fname) && ($fsize = @filesize($fname))) {
				$attachEntry['filesize'] = Formatsize($fsize);
				$attachEntry['url'] = (($arow['storage']) ? ($config['attach_url']) : ($config['files_url'])) . '/' . $arow['folder'] . '/' . $arow['name'];
			} else {
				$attachEntry['filesize'] = '<font color="red">n/a</font>';
			}
			$attachEntries [] = $attachEntry;
		}
	}
	$tVars['attachEntries'] = $attachEntries;
	$tVars['attachCount'] = $attachNumber;

	if (getIsSet($row['xfields']))
		exec_acts('editnews_entry', $row['xfields'], '');
	exec_acts('editnews_form');

	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			$v->editNewsForm($id, $row, $tVars);
		}

	$xt = $twig->loadTemplate('skins/default/tpl/news/edit.tpl');
	return $xt->render($tVars);
}

//
// Mass comment delete
//
function massCommentDelete() {

	global $mysql, $lang, $userROW;

	$delcomid = $_REQUEST['delcomid'];

	// Check for security token
	if ($permCheck && (!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.news.edit'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));

		return;
	}

	if (!$delcomid || !count($delcomid)) {
		msg(array("type" => "error", "text" => $lang['msge_selectcom'], "info" => $lang['msgi_selectcom']));

		return;
	}

	$countRequested = count($delcomid);
	$countDeleted = 0;
	$countBlocked = 0;
	$countLost = 0;
	foreach ($delcomid as $delid) {
		list($comid, $author, $add_ip, $postid) = explode("-", $delid);

		// Let's delete using only comment id ( $comid )
		if (!is_array($crow = $mysql->record("select (select status from " . uprefix . "_users u where u.id=c.author_id) as castatus, (select author_id from " . prefix . "_news n where n.id=c.post) as naid, c.* from " . prefix . "_comments c where c.id = " . db_squote($comid)))) {
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
		$mysql->query("update " . prefix . "_news set com=com-1 where id=" . db_squote($crow['post']));
		if ($crow['author_id']) {
			$mysql->query("update " . uprefix . "_users set com=com-1 where id=" . db_squote($crow['author_id']));
		}

		$mysql->query("delete from " . prefix . "_comments where id=" . db_squote($comid));
	}
	//
	if ($countRequested == $countDeleted) {
		msg(array("text" => $lang['msg.comdel.ok'], 'info' => str_replace(array('{cnt_req}', '{edit_link}'), array($countRequested, $PHP_SELF . '?mod=news&action=edit&id=' . $postid), $lang['msg.comdel.ok#descr'])));
	} else {
		msg(array("text" => $lang['msg.comdel.fail'], 'info' => str_replace(array('{cnt_req}', '{cnt_deleted}', '{cnt_blocked}', '{cnt_lost}', '{edit_link}'), array($countRequested, $countDeleted, $countBlocked, $countLost, $PHP_SELF . '?mod=news&action=edit&id=' . $postid), $lang['msg.comdel.fail#descr'])));
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

	$selected_news = getIsSet($_REQUEST['selected_news']);

	if ((!is_array($selected_news)) || (!count($selected_news))) {
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

	massDeleteNews(getIsSet($_REQUEST['selected_news']));
}

function makeSortList($selected) {

	global $lang;

	return '<option value="id_desc"' . ($selected == "id_desc" ? ' selected' : '') . ">" . $lang['sort_postid_desc'] . "</option>" .
		'<option value="id"' . ($selected == "id" ? ' selected' : '') . ">" . $lang['sort_postid'] . "</option>" .
		'<option value="postdate_desc"' . ($selected == "postdate_desc" ? ' selected' : '') . ">" . $lang['sort_postdate_desc'] . "</option>" .
		'<option value="postdate"' . ($selected == "postdate" ? ' selected' : '') . ">" . $lang['sort_postdate'] . "</option>" .
		'<option value="title_desc"' . ($selected == "title_desc" ? ' selected' : '') . ">" . $lang['sort_title_desc'] . "</option>" .
		'<option value="title"' . ($selected == "title" ? ' selected' : '') . ">" . $lang['sort_title'] . "</option>";
}

// ======================================================================================================
// List news
// ======================================================================================================
function listNewsForm() {

	global $mysql, $lang, $twig, $catz, $catmap, $userROW, $PHP_SELF, $config;

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
	$fSearchLine = getIsSet($_REQUEST['sl']);
	$fSearchType = intval(getIsSet($_REQUEST['st']));

	// Author filter (by name)
	$fAuthorName = getIsSet($_REQUEST['an']);

	// Date range
	$fDateStart = '';
	$fDateStartText = '';
	if (preg_match('#^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$#', getIsSet($_REQUEST['dr1']), $match)) {
		$fDateStartText = getIsSet($_REQUEST['dr1']);
		$fDateStart = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
	}

	$fDateStop = '';
	$fDateStopText = '';
	if (preg_match('#^(\d{1,2})\.(\d{1,2})\.(\d{2,4})$#', getIsSet($_REQUEST['dr2']), $match)) {
		$fDateStopText = getIsSet($_REQUEST['dr2']);
		$fDateStop = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
	}

	// Category
	$fCategoryId = null;
	if (getIsSet($_REQUEST['category']) != '')
		$fCategoryId = intval(getIsSet($_REQUEST['category']));

	// Status
	$fStatus = intval(getIsSet($_REQUEST['status']));

	// Sort mode
	$fSort = '';
	switch (getIsSet($_REQUEST['sort'])) {
		case 'id':
			$fSort = 'id';
			break;
		case 'id_desc':
			$fSort = 'id desc';
			break;
		case 'postdate':
			$fSort = 'postdate';
			break;
		case 'postdate_desc':
			$fSort = 'postdate desc';
			break;
		case 'title':
			$fSort = 'title';
			break;
		case 'title_desc':
			$fSort = 'title desc';
			break;
	}
	$fSort = ' order by ' . ($fSort ? $fSort : 'id desc');

	// Check if user selected personal filter
	$fAuthorId = 0;
	if (getIsSet($_REQUEST['aid'])) {
		// Try to fetch userName
		if ($urow = $mysql->record("select id, name from " . uprefix . "_users where id = " . db_squote(getIsSet($_REQUEST['aid'])))) {
			$fAuthorId = $urow['id'];
			$fAuthorName = $urow['name'];
		}
	}

	// Records Per Page
	// - Load
	$fRPP = isset($_REQUEST['rpp']) ? intval($_REQUEST['rpp']) : intval($admCookie['news']['pp']);
	// - Set default value for `Records Per Page` parameter
	if (($fRPP < 2) || ($fRPP > 2000))
		$fRPP = 20;

	// - Save into cookies current value
	$admCookie['news']['pp'] = $fRPP;
	admcookie_set($admCookie);

	// Determine requested page number
	$pageNo = getIsSet($_REQUEST['page']) ? intval($_REQUEST['page']) : 0;
	if ($pageNo < 1)
		$pageNo = 1;

	if (empty($start_from))
		$start_from = $pageNo - 1;

	$i = $start_from;

	$conditions = array();
	if (!is_null($fCategoryId)) {
		array_push($conditions, "catid " . ($fCategoryId ? ("regexp '[[:<:]](" . intval($fCategoryId) . ")[[:>:]]'") : (' = ""')));

	}

	if ($fDateStart) {
		array_push($conditions, "postdate >= " . intval($fDateStart));
	}

	if ($fDateStop) {
		array_push($conditions, "postdate <= " . intval($fDateStop));
	}

	if ($fAuthorId) {
		array_push($conditions, "author_id = " . $fAuthorId);
	} else if ($fAuthorName) {
		array_push($conditions, "author = " . db_squote($fAuthorName));
	}

	if (!$perm['other.list'])
		array_push($conditions, "author_id = " . intval($userROW['id']));

	if (!$perm['personal.list'])
		array_push($conditions, "author_id <> " . intval($userROW['id']));

	if ($fStatus)
		array_push($conditions, "approve = " . (intval($fStatus) - 2));

	// Perform search
	if ($fSearchLine != '') {
		array_push($conditions, ($fSearchType ? 'content' : 'title') . " like " . db_squote('%' . $fSearchLine . '%'));
	}

	$sqlQPart = "from " . prefix . "_news " . (count($conditions) ? "where " . implode(" AND ", $conditions) : '') . ' ' . $fSort;
	$sqlQCount = "select count(id) as cid " . $sqlQPart;
	$sqlQ = "select * " . $sqlQPart;

	// print "SQL: $sqlQ";

	$cnt = $mysql->record($sqlQCount);
	$countNews = $cnt['cid'];
	$countPages = ceil($countNews / $fRPP);

	// If Count of pages is less that pageNo we want to show - show last page
	if (($pageNo > $countPages) && ($pageNo > 1))
		$pageNo = $countPages;

	$newsEntries = array();

	$sqlResult = $sqlQ . " LIMIT " . (($pageNo - 1) * $fRPP) . "," . $fRPP;
	foreach ($mysql->select($sqlResult) as $row) {
		$cats = explode(",", $row['catid']);

		$newsEntry = array(
			'php_self'     => $PHP_SELF,
			'home'         => home,
			'newsid'       => $row['id'],
			'userid'       => $row['author_id'],
			'username'     => $row['author'],
			'comments'     => isset($row['com']) ? $row['com'] : '',
			'views'        => $row['views'],
			'attach_count' => $row['num_files'],
			'images_count' => $row['num_images'],
			'itemdate'     => date("d.m.Y", $row['postdate']),
			'allcats'      => resolveCatNames($cats) . ' &nbsp;',
			'title'        => secure_html((strlen($row['title']) > 70) ? substr($row['title'], 0, 70) . " ..." : $row['title']),
			'link'         => newsGenerateLink($row, false, 0, true),
			'state'        => $row['approve'],
			'flags'        => array(
				'comments' => getPluginStatusInstalled('comments') ? true : false,
				'status'   => ($row['approve'] == 1) ? true : false,
				'mainpage' => $row['mainpage'] ? true : false,
				'editable' => ($row['author_id'] == $userROW['id']) && ($perm['personal.view']) || ($row['author_id'] != $userROW['id']) && ($perm['other.view']),
				'isActive' => ($row['approve'] == 1) ? true : false,
			)
		);

		if (getIsSet($PFILTERS['news']) && is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) {
				$v->listNewsForm($id, $row, $tVars);
			}
		$newsEntries [] = $newsEntry;
	}
	$tVars = array(
		'php_self'   => $PHP_SELF,
		'rpp'        => $fRPP,
		'entries'    => $newsEntries,
		'sortlist'   => makeSortList($_REQUEST['sort']),
		'statuslist' => '<option value="1"' . (($fStatus == 1) ? ' selected' : '') . '>' . $lang['smode_draft'] . '</option>' .
			'<option value="2"' . (($fStatus == 2) ? ' selected' : '') . '>' . $lang['smode_unpublished'] . '</option>' .
			'<option value="3"' . (($fStatus == 3) ? ' selected' : '') . '>' . $lang['smode_published'] . '</option>',
		'flags'      => array(
			'comments'     => getPluginStatusInstalled('comments') ? true : false,
			'allow_modify' => ($userROW['status'] <= 2) ? true : false,
		),
	);

	$tVars['category_select'] = makeCategoryList(array('doall' => 1, 'dowithout' => 1, 'selected' => $fCategoryId, 'style' => 'width: 200px;'));

	$maxNavigations = !(empty($config['newsNavigationsAdminCount']) || $config['newsNavigationsAdminCount'] < 1) ? $config['newsNavigationsAdminCount'] : 20;

	if (count($newsEntries) > 0) {
		$tVars['pagesss'] = generateAdminPagelist(
			array(
				'maxNavigations' => $maxNavigations,
				'current'        => $pageNo,
				'count'          => $countPages,
				'url'            => admin_url .
					'/admin.php?mod=news' .
					($fRPP ? '&rpp=' . $fRPP : '') .
					($fAuthorName != '' ? '&an=' . $fAuthorName : '') .
					($fSearchLine != '' ? '&sl=' . $fSearchLine : '') .
					($fSearchType != '' ? '&st=' . $fSearchType : '') .
					($fDateStartText != '' ? '&dr1=' . $fDateStartText : '') .
					($fDateStopText != '' ? '&dr2=' . $fDateStopText : '') .
					($fCategoryId != '' ? '&category=' . $fCategoryId : '') .
					($fStatus != '' ? '&status=' . $fStatus : '') .
					'&page=%page%'
			));
	}

	$tVars['an'] = secure_html($fAuthorName);
	$tVars['sl'] = secure_html($fSearchLine);
	$tVars['selected'] = $fSearchType;
	$tVars['dr1'] = $fDateStartText;
	$tVars['dr2'] = $fDateStopText;
	$tVars['token'] = genUToken('admin.news.edit');
	$tVars['localPrefix'] = localPrefix;

	// Prepare category menu
	{
		$cList = $mysql->select("select * from " . prefix . "_category order by posorder");
		$cLen = count($cList);

		$tcRecs = array();
		// Go through this list
		foreach ($cList as $num => $row) {
			// Prepare data for template
			$tcRec = array(
				'id'      => $row['id'],
				'name'    => $row['name'],
				'posts'   => $row['posts'],
				'alt'     => $row['alt'],
				'alt_url' => $row['alt_url'],
				'flags'   => array(
					'selected' => (isset($_REQUEST['category']) && ($row['id'] == $_REQUEST['category'])) ? true : false,
				),
			);

			// Prepare position
			$tcRec['cutter'] = '';
			if ($row['poslevel'] > 0) {
				$tcRec['cutter'] = str_repeat('<img alt="-" height="18" width="18" src="' . skins_url . '/images/catmenu/line.gif" />', ($row['poslevel']));
			} else {
				$tcRec['cutter'] = '';
			}
			$tcRec['cutter'] = $tcRec['cutter'] .
				'<img alt="-" height="18" width="18" src="' . skins_url . '/images/catmenu/join' . ((($num == ($cLen - 1) || ($cList[$num]['poslevel'] > $cList[$num + 1]['poslevel']))) ? 'bottom' : '') . '.gif" />';

			$tcRecs [] = $tcRec;
		}
	}

	$tVars['catmenu'] = $tcRecs;
	$tVars['cat_active'] = ((isset($_REQUEST['category']) && (isset($catmap[intval($_REQUEST['category'])])))) ? intval($_REQUEST['category']) : 0;

	//$xt = $twig->loadTemplate('skins/default/tpl/news/table_catalog.tpl');
	$xt = $twig->loadTemplate('skins/default/tpl/news/table.tpl');
	return $xt->render($tVars);
}

// ======================================================================================================
// Add news form
// ======================================================================================================
function addNewsForm($retry = '') {

	global $lang, $mysql, $config, $userROW, $PFILTERS, $twig, $PHP_SELF;

	// Load permissions
	$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array(
		'add',
		'add.approve',
		'add.mainpage',
		'add.pinned',
		'add.catpinned',
		'add.favorite',
		'add.html',
		'add.raw',
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
		'personal.nocat',
		'personal.customdate',
		'personal.altname',
	));

	// Check permissions
	if (!$perm['add']) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));

		return;
	}

	$tVars = array(
		'php_self'   => $PHP_SELF,
		'changedate' => ChangeDate(),
		'mastercat'  => makeCategoryList(array('doempty' => 1, 'greyempty' => !$perm['personal.nocat'], 'nameval' => 0)),
		'extcat'     => makeCategoryList(array('nameval' => 0, 'checkarea' => 1)),
		'JEV'        => $retry ? $retry : '{}',
		'smilies'    => ($config['use_smilies']) ? InsertSmilies('', 20, 'currentInputAreaID') : '',
		'quicktags'  => ($config['use_bbcodes']) ? QuickTags('currentInputAreaID', 'news') : '',
		'token'      => genUToken('admin.news.add'),
		'flags'      => array(
			'mainpage'            => $perm['add.mainpage'] && $perm['personal.mainpage'],
			'favorite'            => $perm['add.favorite'] && $perm['personal.favorite'],
			'pinned'              => $perm['add.pinned'] && $perm['personal.pinned'],
			'catpinned'           => $perm['add.catpinned'] && $perm['personal.catpinned'],
			'html'                => $perm['add.html'] && $perm['personal.html'],
			'raw'                 => $perm['add.raw'] && $perm['personal.html'],
			'mainpage.disabled'   => !$perm['personal.mainpage'],
			'favorite.disabled'   => !$perm['personal.favorite'],
			'pinned.disabled'     => !$perm['personal.pinned'],
			'catpinned.disabled'  => !$perm['personal.catpinned'],
			'edit_split'          => $config['news.edit.split'] ? true : false,
			'meta'                => $config['meta'] ? true : false,
			'html.disabled'       => !$perm['personal.html'],
			'customdate.disabled' => !$perm['personal.customdate'],
			'multicat.show'       => $perm['personal.multicat'],
			'extended_more'       => ($config['extended_more'] || (getIsSet($tvars['vars']['content.delimiter']) != '')) ? true : false,
			'can_publish'         => $perm['personal.publish'],
			'altname.disabled'    => (!$perm['personal.altname']) ? true : false,
			'mondatory_cat'       => (!$perm['personal.nocat']) ? true : false,
		),
	);

	// Run interceptors
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			$v->addNewsForm($tVars);
		}

	$xt = $twig->loadTemplate('skins/default/tpl/news/add.tpl');
	return $xt->render($tVars);
}

// #==============================================================================#
// # Action selection                                                             #
// #==============================================================================#

$action = getIsSet($_REQUEST['action']);
$subaction = getIsSet($_REQUEST['subaction']);

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
		//$main_admin = addNewsForm($replay ? json_encode(arrayCharsetConvert(0, $_POST)) : null);
		$main_admin = addNewsForm($replay ? json_encode($_POST) : null);
		break;
	}

	if ($action == "edit") {
		if ($subaction == "submit") {
			$main_admin = editNews();
		}
		if ($subaction == "mass_com_delete") {
			$main_admin = massCommentDelete();
		}
		$main_admin = editNewsForm();
		break;
	}

	if ($action == "manage") {
		switch ($subaction) {
			case 'mass_currdate'    :
				$curdate = time() + ($config['date_adjust'] * 60);
				$main_admin = massNewsModify(array('postdate' => $curdate), 'msgo_currdate', 'capprove');
				break;
			case 'mass_approve'      :
				$main_admin = massNewsModify(array('approve' => 1), 'msgo_approved', 'approve');
				break;
			case 'mass_mainpage'     :
				$main_admin = massNewsModify(array('mainpage' => 1), 'msgo_mainpaged', 'mainpage');
				break;
			case 'mass_unmainpage'   :
				$main_admin = massNewsModify(array('mainpage' => 0), 'msgo_unmainpage', 'unmainpage');
				break;
			case 'mass_forbidden'    :
				$main_admin = massNewsModify(array('approve' => 0), 'msgo_forbidden', 'forbidden');
				break;
			case 'mass_com_forbidden':
				$main_admin = massNewsModify(array('allow_com' => 0), 'msgo_cforbidden', 'cforbidden');
				break;
			case 'mass_com_approve'  :
				$main_admin = massNewsModify(array('allow_com' => 1), 'msgo_capproved', 'capprove');
				break;
			case 'mass_delete'       :
				$main_admin = massNewsDelete();
				break;
		}
	}
	$main_admin = listNewsForm();

} while (false);
