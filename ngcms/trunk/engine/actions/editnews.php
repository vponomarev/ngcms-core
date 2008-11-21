<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: editnews.php
// Description: News managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('editnews', 'admin');
$situation = "news";

$SQL = array();

//
// Выполнение редактирования новости
//
function editNews() {
	global $lang, $parse, $mysql, $config, $PFILTERS, $userROW;
	global $title, $contentshort, $contentfull, $alt_name, $id, $c_day, $c_month, $c_year, $c_hour, $c_minute, $description, $keywords;

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


	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}
	$oldcat = $row['catid'];

	if ((!strlen(trim($title))) || (!strlen(trim($contentshort)))) {
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
	$oldcatsql = array();
	foreach(explode(",",$oldcat) as $key) { if ($key) $oldcatsql[] = "id = ".db_squote($key); }

	$categories = explode(",", secure_html($_POST['categories']));
	$categories = array_diff($categories,array(''));
	$catids = array();
	$catsql = array();

	foreach($categories as $key=>$keyword){
		$keyword = trim($keyword);
		$keywordid = $mysql->result("select id from ".prefix."_category where name = ".db_squote($keyword).(is_numeric($keyword)?(" or id=".db_squote($keyword)):''));
		$catids[] = $keywordid;
		$catsql[] = "id = ".db_squote($keywordid);
	}
	$cats = implode(",", $catids);

	if ($config['meta'] == "1") {
		$SQL['description'] = $description;
		$SQL['keywords']    = $keywords;
	}


	$content = $contentshort.(trim($contentfull)?'<!--more-->'.$contentfull:'');
	$content = str_replace("\r\n", "\n", $content);

	//$SQL['postdate']  = $_REQUEST['customdate']?strtotime("$c_day "."$c_month "."$c_year "."$c_hour:$c_minute") + ($config['date_adjust'] * 60):$row['postdate'];
	$SQL['postdate']  = $_REQUEST['customdate']?mktime($c_hour, $c_minute, 0, $c_month, $c_day, $c_year) + ($config['date_adjust'] * 60):$row['postdate'];
	$SQL['title']     = $title;
	$SQL['content']   = $content;
	$SQL['alt_name']  = $alt_name;
	$SQL['editdate']  = time();
	$SQL['catid']     = $cats;
	
	// Change this parameters if user have enough access level
	if ($userROW['status'] < 3) {
		$SQL['mainpage']  = intval($_REQUEST['mainpage']);
		$SQL['allow_com'] = intval($_REQUEST['allow_com']);
		$SQL['approve']   = intval($_REQUEST['approve']);
		$SQL['favorite']  = intval($_REQUEST['favorite']);
		$SQL['pinned']    = intval($_REQUEST['pinned']);
		if ($_REQUEST['setViews'])
			$SQL['views'] = intval($_REQUEST['views']);
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
	if (sizeof($oldcatsql)) {
		$mysql->query("update ".prefix."_category set posts=posts-1 where ".implode(" or ",$oldcatsql));
	}
	if (sizeof($catsql)) {
		$mysql->query("update ".prefix."_category set posts=posts+1 where ".implode(" or ",$catsql));
	}

	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->editNewsNotify($id, $row, $SQL, $tvars); }

	msg(array("text" => $lang['msgo_edited']));
}


//
// Форма редактирования новости
//
function editNewsForm() {
	global $lang, $parse, $mysql, $config, $tpl, $mod, $PFILTERS, $tvars, $userROW;
	global $title, $contentshort, $contentfull, $alt_name, $id, $c_day, $c_month, $c_year, $c_hour, $c_minute;

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_news where id = ".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	$cats = explode(",", $row['catid']);
	$story = explode("<!--more-->", $row['content']);

	// Load comments
	$tpl -> template('comments', tpl_actions.$mod);

	foreach ($mysql->select("select * from ".prefix."_comments where post='".$row['id']."' order by id") as $crow) {

		$text	= $crow['text'];

		if ($config['blocks_for_reg'])		{ $text = $parse -> userblocks($text); }
		if ($config['use_htmlformatter'])	{ $text = $parse -> htmlformatter($text); }
		if ($config['use_bbcodes'])			{ $text = $parse -> bbcodes($text); }
		if ($config['use_smilies'])			{ $text = $parse -> smilies($text); }

		$tvars['vars'] = array(
			'php_self'		=>	$PHP_SELF,
			'com_author'		=>	$crow['author'],
			'com_post'		=>	$crow['post'],
			'com_url'		=>	($crow['url']) ? $crow['url'] : $PHP_SELF.'?mod=users&action=edituser&id='.$crow['author_id'],
			'com_id'		=>	$crow['id'],
			'com_ip'		=>	$crow['ip'],
			'com_time'		=>	LangDate($config['timestamp_comment'], $crow['postdate']),
			'com_part'		=>	$text
		);

		if ($crow['reg']) {
			$tvars['vars']['[userlink]'] = '';
			$tvars['vars']['[/userlink]'] = '';
		} else {
			$tvars['regx']["'\\[userlink\\].*?\\[/userlink\\]'si"] = $crow['author'];
		}

		$tpl -> vars('comments', $tvars);
		$comments .= $tpl -> show('comments');
	}

	$tvars = array();

	$tvars['vars'] = array(
		'php_self'			=>	$PHP_SELF,
		'changedate'		=>	ChangeDate($row['postdate']),
		'catlist'			=>	makeCategoryList(array('skip' => $cats, 'nameval' => 1)),
		'allcats'			=>	@GetAllCategories($cats),
		'comments'			=>	$parse->smilies($comments),
		'id'				=>	$row['id'],
		'title'				=>	secure_html($row['title']),
		'short'				=>	secure_html($story[0]),
		'full'				=>	secure_html($story[1]),
		'alt_name'			=>	$row['alt_name'],
		'avatar'			=>	$row['avatar'],
		'description'		=>	secure_html($row['description']),
		'keywords'			=>	secure_html($row['keywords']),
		'views'				=>	$row['views']
	);

	if ($config['use_smilies']) {
		$tvars['vars']['smilies_short'] = InsertSmilies('contentshort', 20, "short");
		$tvars['vars']['smilies_full'] = InsertSmilies('contentfull', 20, "full");
	} else {
		$tvars['vars']['smilies_short'] = '';
		$tvars['vars']['smilies_full'] = '';
	}

	if ($config['use_bbcodes']) {
		$tvars['vars']['quicktags_short'] = QuickTags("short");
		$tvars['vars']['quicktags_full']  = QuickTags("full");
	} else {
		$tvars['vars']['quicktags_short'] = '';
		$tvars['vars']['quicktags_full']  = '';
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

	exec_acts('editnews_entry', $row['xfields'], '');

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
	global $mysql, $lang;

	$delcomid = $_REQUEST['delcomid'];

	if (!$delcomid){
		msg(array("type" => "error", "text" => $lang['msge_selectcom'], "info" => $lang['msgi_selectcom']));
		return;
	}

	foreach ($delcomid as $delid) {
		list($comid, $author, $add_ip, $postid) = split("-", $delid);

		// Let's delete using only comment id ( $comid )
		if (!is_array($crow = $mysql->record("select * from ".prefix."_comments where id = ".db_squote($comid)))) {
			continue;
		}

		$mysql->query("update ".prefix."_news set com=com-1 where id=".db_squote($crow['post']));
		if ($crow['author_id']) {
			$mysql->query("update ".uprefix."_users set com=com-1 where id=".db_squote($crow['author_id']));
		}

		$mysql->query("delete from ".prefix."_comments where id=".db_squote($comid));
	}
	msg(array("text" => $lang['msgo_comdeleted'], "info" => sprintf($lang['msgi_comdeleted'], $PHP_SELF.'?mod=editnews&action=editnews&id='.$postid)));
}


//
// Mass news flags modifier
// $setValue  - what to change in table (array with field => value)
// $langParam - name of variable in lang file to show on success
// $tag       - tag param to send to plugins
//
function massNewsModify($setValue, $langParam, $tag ='') {
	global $mysql, $lang, $PFILTERS;

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

	foreach ($mysql->select("select id, ".join(", ", array_keys($setValue))." from ".prefix."_news where id in (".join(", ", $SNQ).")") as $nrow) {
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

	$mysql->query("UPDATE ".prefix."_news SET $sqlSET WHERE id in (".join(", ", $SNQ).")");

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
		// Delete comments (with updating user's comment counter)
		foreach ($mysql->select("select * from ".prefix."_comments where post=".$nrow['id']) as $crow) {
			if ($nrow['author_id']) {
				$mysql->query("update ".uprefix."_users set com=com-1 where id=".$crow['author_id']);
			}
		}
		$mysql->query("delete from ".prefix."_comments WHERE post=".db_squote($nrow['id']));
		$mysql->query("delete from ".prefix."_news where id=".db_squote($nrow['id']));

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


		$showtitle = (strlen($row['title']) > 70)?substr($row['title'],0,70)." ...":$row['title'];
		$tvars['vars']['title'] = secure_html($showtitle);

		$tvars['vars']['status']	=	($row['approve'] == "1") ? '<img src="'.skins_url.'/images/bullet_green.gif" alt="'.$lang['approved'].'" />' : '<img src="'.skins_url.'/images/bullet_white.gif" alt="'.$lang['unapproved'].'" />';
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

	exec_acts('editnews_list');

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}
