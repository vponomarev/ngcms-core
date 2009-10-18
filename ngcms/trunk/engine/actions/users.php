<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: pm.php
// Description: manage users
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('users', 'admin');

function users_edit(){
	global $mysql, $lang, $tpl, $mod;

	$id = $_REQUEST['id'];

	if (!($row = $mysql->record("select * from ".uprefix."_users where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

    for ($i = 4; $i >= 1; $i--) {
    	$status .= ' <option value="'.$i.'"'.(($row['status'] == $i)?' selected':'').'>'.$i.' ('.$lang['st_'.$i].')</option>';
    }
	//	Обрабатываем необходимые переменные для шаблона
	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'name'			=>	$row['name'],
		'regdate'		=>	LangDate("l, j Q Y - H:i", $row['reg']),
		'com'			=>	$row['com'],
		'news'			=>	$row['news'],
		'status'		=>	$status,
		'mail'			=>	$row['mail'],
		'site'			=>	$row['site'],
		'icq'			=>	$row['icq'],
		'where_from'	=>	secure_html($row['where_from']),
		'info'			=>	secure_html($row['info']),
		'id'			=>	$id,
		'pass'			=>	$row['pass'],
		'last'			=>	(empty($row['last'])) ? $lang['no_last'] : LangDate('l, j Q Y - H:i', $row['last']),
		'ip'			=>	$row['ip']
	);

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	echo $tpl -> show('edit');
}

function users_doedit(){
	global $mysql, $lang, $tpl, $mod;

	$id = $_REQUEST['id'];

	// Check if user exists
	if (!($row = $mysql->record("select * from ".uprefix."_users where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	$pass = ($_REQUEST['editpassword']) ? EncodePassword($_REQUEST['editpassword']) : '';

	$mysql->query("update ".uprefix."_users set `status`=".db_squote($_REQUEST['editlevel']).", `site`=".db_squote($_REQUEST['editsite']).", `icq`=".db_squote($_REQUEST['editicq']).", `where_from`=".db_squote($_REQUEST['editfrom']).", `info`=".db_squote($_REQUEST['editabout']).", `mail`=".db_squote($_REQUEST['editmail']).($pass?", `pass`=".db_squote($pass):'')." where id=".db_squote($row['id']));
	msg(array("text" => $lang['msgo_edituser']));
}

function users_adduser(){
	global $mysql, $lang, $tpl, $mod;

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

function users_mass_activate(){
	global $mysql, $lang;

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

function users_mass_delete(){
	global $mysql, $lang, $userROW, $config;

	$selected_users = $_REQUEST['selected_users'];
	if (!$selected_users) {
		msg(array("type" => "error", "text" => $lang['msge_select'], "info" => $lang['msgi_select']));
		return;
	}
	foreach ($selected_users as $id) {
		// Don't let us to delete ourselves
		if ($id == $userROW['id']) { continue; }

		// Check if user has his own photo or avatar
		if (is_array($urow = $mysql->record("select * from ".prefix."_users where id = ".db_squote($id)))) {
			if (($urow['avatar'] != '') && (file_exists($config['avatars_dir'].$urow['photo'])))
				@unlink($config['avatars_dir'].$urow['avatar']);

			if (($urow['photo'] != '') && (file_exists($config['photos_dir'].$urow['photo'])))
				@unlink($config['photos_dir'].$urow['photo']);

			$mysql->query("delete from ".uprefix."_users where id=".db_squote($id));
		}
	}
	msg(array("text" => $lang['msgo_deluser']));
}

function users_mass_delunact(){
	global $mysql, $lang, $userROW;

	$mysql->query("DELETE FROM ".uprefix."_users WHERE last IS NULL OR last=''");
	msg(array("text" => $lang['msgo_delunact']));
}

function users_list(){
	global $mysql, $lang, $tpl, $mod, $userROW;
	global $news_per_page, $start_from;

	$available_orders = array('name' => 'name', 'reg' => 'regdate', 'last' => 'last_login', 'status' => 'status');
	$rsort = $_REQUEST['sort']?$_REQUEST['sort']:'reg';


	$name = ($_REQUEST['name'] != '')?("'%".mysql_real_escape_string($_REQUEST['name'])."%'"):'';

	$per_page	= intval($_REQUEST['per_page']);
	if (($per_page < 1)||($per_page > 500))
		$per_page = 30;

	$page = intval($_REQUEST['page']);
	if (!$page)
		$page = 1;

	$limit = "limit ".(($page-1)*$per_page).", ".$per_page;
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
			'status'		=>	$status,
			'id'			=>	$row['id'],
			'regdate'		=>	LangDate('j Q Y - H:i', $row['reg']),
			'last_login'	=>	(empty($row['last'])) ? $lang['no_last'] : LangDate('j Q Y - H:i', $row['last'])
		);

		$tvars['vars']['active'] = (!$row['activation'] || $row['activation'] == "") ? '<img src="'.skins_url.'/images/bullet_green.gif" alt="'.$lang['active'].'" />' : '<img src="'.skins_url.'/images/bullet_white.gif" alt="'.$lang['unactive'].'" />';

		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	foreach($available_orders as $k => $v){
		$sort_options .= "<option value='$k'".(($rsort == $k)?' selected':'').">".$lang[$v]."</option>";
	}

	$how_options = "<option value=''".($_REQUEST['how']?'':' selected').">".$lang['asc']."</option><option value='desc'".($_REQUEST['how']?' selected':'').">".$lang['desc']."</option>";

	$userCount	=	$mysql->result("SELECT count(*) FROM ".uprefix."_users ".$where);
	$pageCount	=	ceil($userCount / $per_page);

	if ($pageCount > 1) {

		for ($i = 1; $i <= $pageCount; $i++) {
			if ($i == $page) {
				$npp_nav .= ' <b>[ </b>'.$i.' <b>]</b> ';
			} else {
				$npp_nav .= "<a href=\"$PHP_SELF?mod=users&amp;action=list&amp;name=".htmlspecialchars($_REQUEST['name'])."&amp;sort=".$rsort."&amp;how=".($_REQUEST['how']?'desc':'')."&amp;page=".($i).($_REQUEST['per_page']?'&amp;per_page='.$per_page:'')."\">".$i."</a> ";
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
	);

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}


// ==============================================
// Actions
// ==============================================

if ($action == 'edituser') {
	users_edit();
} else {
	switch ($action) {
		case 'doedituser'  : users_doedit();  break;
		case 'adduser'     : users_adduser(); break;
		case 'massactivate': users_mass_activate(); break;
		case 'massdelete'  : users_mass_delete(); break;
		case 'massdelunact': users_mass_delunact(); break;
	}
	users_list();
}


