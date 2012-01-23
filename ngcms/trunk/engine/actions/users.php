<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: users.php
// Description: manage users
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('users', 'admin');


//
// Form: Edit user
function userEditForm(){
	global $mysql, $lang, $tpl, $mod, $PFILTERS;

	$id = intval($_REQUEST['id']);

	// Determine user's permissions
	$perm			= checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, array('modify', 'details'));
	$permModify		= $perm['modify'];
	$permDetails	= $perm['details'];

	// Check for permissions
	if (!$perm['modify'] && !$perm['details']) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(0, 'SECURITY.PERM'));
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	if (!($row = $mysql->record("select * from ".uprefix."_users where id=".db_squote($id)))) {
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(0, 'NOT.FOUND'));
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

//	if (is_array($PFILTERS['p_uprofile']))
//		foreach ($PFILTERS['p_uprofile'] as $k => $v) { $v->showProfilePre($row['id'], $row, $tvars); }

    for ($i = 4; $i >= 1; $i--) {
    	$status .= ' <option value="'.$i.'"'.(($row['status'] == $i)?' selected':'').'>'.$i.' ('.$lang['st_'.$i].')</option>';
    }
	//	Обрабатываем необходимые переменные для шаблона
	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'name'			=>	secure_html($row['name']),
		'regdate'		=>	LangDate("l, j Q Y - H:i", $row['reg']),
		'com'			=>	$row['com'],
		'news'			=>	$row['news'],
		'status'		=>	$status,
		'mail'			=>	secure_html($row['mail']),
		'site'			=>	secure_html($row['site']),
		'icq'			=>	secure_html($row['icq']),
		'where_from'	=>	secure_html($row['where_from']),
		'info'			=>	secure_html($row['info']),
		'id'			=>	$id,
		'last'			=>	(empty($row['last'])) ? $lang['no_last'] : LangDate('l, j Q Y - H:i', $row['last']),
		'ip'			=>	$row['ip'],
		'token'			=> genUToken('admin.users'),
	);
	$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is'] = $perm['modify']?'$1':'';

//	if (is_array($PFILTERS['p_uprofile']))
//		foreach ($PFILTERS['p_uprofile'] as $k => $v) { $v->showProfile($row['id'], $row, $tvars); }


	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(1));
	echo $tpl -> show('edit');
}


//
// Edit user's profile
function userEdit(){
	global $mysql, $lang, $tpl, $mod;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(0, 'SECURITY.PERM'));
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(0, 'SECURITY.TOKEN'));
		return;
	}

	$id = intval($_REQUEST['id']);

	// Check if user exists
	if (!($row = $mysql->record("select * from ".uprefix."_users where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm'), null, array(0, 'NOT.FOUND'));
		return;
	}

	$pass = ($_REQUEST['password']) ? EncodePassword($_REQUEST['password']) : '';

	// Prepare a list of changed params
	$cList = array();
	foreach (array('level', 'site', 'icq', 'where_from', 'info', 'mail') as $k) {
		if ($row[$k] != $_REQUEST[$k]) {
			$cList[$k] = array($row[$k], $_REQUEST[$k]);
		}
	}
	if ($pass) {
		$cList['pass'] = array('****', '****');
	}

	ngSYSLOG(array('plugin' => '#admin', 'item' => 'users', 'ds_id' => $id), array('action' => 'editForm', 'list' => $cList), null, array(1));

	$mysql->query("update ".uprefix."_users set `status`=".db_squote($_REQUEST['status']).", `site`=".db_squote($_REQUEST['site']).", `icq`=".db_squote($_REQUEST['icq']).", `where_from`=".db_squote($_REQUEST['where_from']).", `info`=".db_squote($_REQUEST['info']).", `mail`=".db_squote($_REQUEST['mail']).($pass?", `pass`=".db_squote($pass):'')." where id=".db_squote($row['id']));
	msg(array("text" => $lang['msgo_edituser']));
}


//
// Add new user
function userAdd(){
	global $mysql, $lang, $tpl, $mod;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$regusername	= trim($_REQUEST['regusername']);
	$regemail		= trim($_REQUEST['regemail']);
	$regpassword	= $_REQUEST['regpassword'];
	$reglevel		= $_REQUEST['reglevel'];

	if ((!$regusername) || (!strlen(trim($regpassword))) || (!$regemail)) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return;
	}
	if ($mysql->record("select * from ".uprefix."_users where lower(name) = ".db_squote(strtolower($regusername))." or lower(mail)=".db_squote(strtolower($regemail)))) {
		msg(array("type" => "error", "text" => $lang['msge_userexists'], "info" => $lang['msgi_userexists']));
		return;
	}

	$add_time		=	time() + ($config['date_adjust']*60);
	$regpassword	=	EncodePassword($regpassword);

	$mysql->query("insert into ".uprefix."_users (name, pass, mail, status, reg) values (".db_squote($regusername).", ".db_squote($regpassword).", ".db_squote($regemail).", ".db_squote($reglevel).", ".db_squote($add_time).")");
	msg(array("text" => $lang['msgo_adduser']));
}


//
// Bulk action: activate selected users
function userMassActivate(){
	global $mysql, $lang;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$selected_users = $_REQUEST['selected_users'];
	if (!$selected_users) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}
	foreach ($selected_users as $id) {
		$mysql->query("update ".uprefix."_users set activation='' where id=".db_squote($id));
	}
	msg(array("text" => $lang['msgo_activate']));
}

//
// Bulk action: LOCK selected users
function userMassLock(){
	global $mysql, $lang;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$selected_users = $_REQUEST['selected_users'];
	if (!$selected_users) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}

	// Lock all users (excluding admins) and log them out!
	foreach ($selected_users as $id) {
		$mysql->query("update ".uprefix."_users set activation=".db_squote(MakeRandomPassword()).", authcookie='' where (id=".db_squote($id).") and (status <> 1)");
	}
	msg(array("text" => $lang['msgo_lock']));
}


//
// Bulk action: set status to selected users
function userMassSetStatus(){
	global $mysql, $lang, $userROW;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$selected_users = $_REQUEST['selected_users'];
	if (!$selected_users) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}


	// Determine status to set to
	// NOTE: we CAN'T set status `ADMIN` and we can't change STATUS for ADMINS
	$status = intval($_REQUEST['newstatus']);
	if (($status <= 1) or ($status > 4)) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}

	// Lock all users (excluding admins)
	foreach ($selected_users as $id) {
		$mysql->query("update ".uprefix."_users set status=".db_squote($status)." where (id=".db_squote($id).") and (status <> 1)");
	}
	msg(array("text" => $lang['msgo_status']));
}


//
// Bulk action: delete selected users
function userMassDelete(){
	global $mysql, $lang, $userROW, $config;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$selected_users = $_REQUEST['selected_users'];
	if (!$selected_users || !is_array($selected_users)) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}

	foreach ($selected_users as $id) {
		// Don't let us to delete ourselves
		if ($id == $userROW['id']) { continue; }

		// Fetch user's record
		if (is_array($urow = $mysql->record("select * from ".prefix."_users where id = ".db_squote($id)))) {
			// Do not delete admins
			if ($urow['status'] == 1) { continue; }

			// Check if user has his own photo or avatar
			if (($urow['avatar'] != '') && (file_exists($config['avatars_dir'].$urow['photo'])))
				@unlink($config['avatars_dir'].$urow['avatar']);

			if (($urow['photo'] != '') && (file_exists($config['photos_dir'].$urow['photo'])))
				@unlink($config['photos_dir'].$urow['photo']);

			$mysql->query("delete from ".uprefix."_users where id=".db_squote($id));
		}
	}
	msg(array("text" => $lang['msgo_deluser']));
}


//
// Bulk action: delete inactive (never logged in) users [but user should be registered for more than 1 day ago or who have 1+ news]
function userMassDeleteInactive(){
	global $mysql, $lang, $userROW;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.users'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	$today = time();

	$mysql->query("DELETE FROM ".uprefix."_users WHERE ((last IS NULL) OR (last='')) AND ((reg + 86400) < $today) AND (news < 1)");
	msg(array("text" => $lang['msgo_delunact']));
}


//
// Show list of users
function userList(){
	global $mysql, $lang, $tpl, $mod, $userROW;
	global $news_per_page, $start_from;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'view')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		return;
	}

	// Load admin page based cookies
	$admCookie = admcookie_get();

	// Determine user's permissions
	$permModify		= checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'modify');
	$permDetails	= checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'details');


	$available_orders = array('name' => 'name', 'reg' => 'regdate', 'last' => 'last_login', 'status' => 'status');
	$rsort = $_REQUEST['sort']?$_REQUEST['sort']:'reg';


	$name = ($_REQUEST['name'] != '')?("'%".mysql_real_escape_string($_REQUEST['name'])."%'"):'';

	$per_page	= isset($_REQUEST['per_page'])?intval($_REQUEST['per_page']):intval($admCookie['users']['pp']);
	if (($per_page < 1)||($per_page > 500))
		$per_page = 30;

	// - Save into cookies current value
	$admCookie['users']['pp'] = $per_page;
	admcookie_set($admCookie);


	$pageNo = intval($_REQUEST['page']);
	if (!$pageNo)
		$pageNo = 1;

	$limit = "limit ".(($pageNo-1)*$per_page).", ".$per_page;
	$where = ($name?'where name like '.$name:'');
	$order = "order by ".(($available_orders[$rsort])?$rsort:'reg').($_REQUEST['how']?' desc':'');

	$tpl -> template('entries', tpl_actions.$mod);
	$sql = "select * from ".uprefix."_users ".$where.' '.$order.' '.$limit;

	foreach ($mysql->select($sql) as $row) {
		$ls = 'st_'.$row['status'];
		$status = $lang[$ls]?$lang[$ls]:'unknown';

		$tvars['vars'] = array(
			'php_self'		=>	$PHP_SELF,
			'name'			=>	$row['name'],
			'news'			=>	$row['news'],
			'comments'		=>	$row['com'],
			'status'		=>	$status,
			'id'			=>	$row['id'],
			'regdate'		=>	LangDate('j Q Y - H:i', $row['reg']),
			'last_login'	=>	(empty($row['last'])) ? $lang['no_last'] : LangDate('j Q Y - H:i', $row['last'])
		);

		$tvars['vars']['active'] = (!$row['activation'] || $row['activation'] == "") ? '<img src="'.skins_url.'/images/yes.png" alt="'.$lang['active'].'" />' : '<img src="'.skins_url.'/images/no.png" alt="'.$lang['unactive'].'" />';

		$tvars['regx']['#\[mass\](.+?)\[\/mass\]#is'] = ($row['status'] == 1)?'':'$1';
		$tvars['regx']['#\[link_news\](.+?)\[\/link_news\]#is'] = ($row['news'] > 0)?'$1':'';

		$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is'] = ($permModify)?'$1':'';
		$tvars['regx']['#\[perm\.details\](.*?)\[\/perm\.details\]#is'] = ($permModify|$permDetails)?'$1':'';

		// Disable flag for comments if plugin 'comments' is not installed
		$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	foreach($available_orders as $k => $v){
		$sort_options .= "<option value='$k'".(($rsort == $k)?' selected':'').">".$lang[$v]."</option>";
	}

	$how_options = "<option value=''".($_REQUEST['how']?'':' selected').">".$lang['asc']."</option><option value='desc'".($_REQUEST['how']?' selected':'').">".$lang['desc']."</option>";

	$userCount	=	$mysql->result("SELECT count(*) FROM ".uprefix."_users ".$where);
	$pageCount	=	ceil($userCount / $per_page);


	$pagesss = generateAdminPagelist( array(
			'current' => $pageNo,
			'count' => $pageCount,
			'url' => admin_url.'/admin.php?mod=users&action=list'.
				($_REQUEST['name']?'&name='.htmlspecialchars($_REQUEST['name']):'').
				($_REQUEST['how']?'&how='.htmlspecialchars($_REQUEST['how']):'').
				($_REQUEST['per_page']?'&per_page='.intval($_REQUEST['per_page']):'').
				'&sort='.$rsort.
				'&page=%page%'
		));

	if ($pageCount > 1) {

		for ($i = 1; $i <= $pageCount; $i++) {
			if ($i == $pageNo) {
				$npp_nav .= ' <b>[ </b>'.$i.' <b>]</b> ';
			} else {
				$npp_nav .= "<a href=\"$PHP_SELF?mod=users&amp;action=list&amp;name=".
					htmlspecialchars($_REQUEST['name']).
					"&amp;sort=".$rsort.
					"&amp;how=".($_REQUEST['how']?'desc':'').
					"&amp;page=".($i).($_REQUEST['per_page']?'&amp;per_page='.$per_page:'').
					"\">".$i."</a> ";
			}
		}
	}

	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'sort_options'	=>	$sort_options,
		'how_options'	=> $how_options,
		'npp_nav'		=>	$npp_nav,
		'entries'		=>	$entries,
		'per_page'		=> $per_page,
		'name'			=> htmlspecialchars($_REQUEST['name']),
		'pagesss'		=> $pagesss,
		'how_value'		=> htmlspecialchars($_REQUEST['how']),
		'sort_value'	=> htmlspecialchars($_REQUEST['sort']),
		'page_value'	=> htmlspecialchars($_REQUEST['page']),
		'per_page_value'	=> htmlspecialchars($_REQUEST['per_page']),
		'token'			=> genUToken('admin.users'),
	);

	$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is'] = ($permModify)?'$1':'';
	$tvars['regx']['#\[perm\.details\](.*?)\[\/perm\.details\]#is'] = ($permModify|$permDetails)?'$1':'';

	// Disable flag for comments if plugin 'comments' is not installed
	$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}


// ==============================================
// Actions
// ==============================================

if ($action == 'editForm') {
	userEditForm();
} else {
	switch ($action) {
		case 'edit'				: userEdit();				break;
		case 'add'				: userAdd();				break;
		case 'massActivate'		: userMassActivate();		break;
		case 'massLock'			: userMassLock();			break;
		case 'massSetStatus'	: userMassSetStatus();		break;
		case 'massDel'			: userMassDelete();			break;
		case 'massDelInactive'	: userMassDeleteInactive();	break;
	}
	userList();
}