<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: usermenu.php
// Description: user's menu on the site
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('usermenu', 'site');

// If not logged in
if (!$is_logged) {
	$tvars['vars'] = array(
		'request_uri'	=>	$_SERVER['REQUEST_URI'],
		'reg_link'		=>	GetLink('registration'),
		'lost_link'		=>	GetLink('lostpassword'),
		'result'		=>	($result) ? '<div style="color : #fff; padding : 5px;">'.$lang['msge_login'].'</div>' : ''
	);
	$tvars['regx']["'\[login\](.*?)\[/login\]'si"] = '$1';
	$tvars['regx']["'\[is-logged\](.*?)\[/is-logged\]'si"] = '';
	$tvars['regx']["'\[isnt-logged\](.*?)\[/isnt-logged\]'si"] = '$1';
	$tvars['regx']["'\[login-err\](.*?)\[/login-err\]'si"] = ($SYSTEM_FLAGS['auth_fail'])?'$1':'';

} else {
	// User is logged in
	$tvars['vars'] = array(
		'profile_link'	=>	GetLink('profile'),
		'addnews_link'	=>	GetLink('addnews'),
		'name'			=>	name,
		'avatars_url'	=>	avatars_url,
		'avatar'		=>	($userROW['avatar']) ? $userROW['avatar'] : 'noavatar.gif',
		'avatar_url'		=> 	avatars_url.'/'.(($userROW['avatar'])?$userROW['avatar']:'noavatar.gif'),
		'phtumb_url'		=>	photos_url.'/'.(($userROW['photo'] != "")?'thumb/'.$userROW['photo']:'nophoto.gif'),
		'pm_new'		=>	($newpm != "0") ? '<strong>'.$newpm.'</strong>' : '0',
		'result'		=>	($result) ? '<div style="color : #fff; padding:5px;">'.$lang['msge_login'].'</div>' : '',
		'home_url'		=>	home,
	);
	$tvars['regx']["'\[login\](.*?)\[/login\]'si"] = '';
	$tvars['regx']["'\[is-logged\](.*?)\[/is-logged\]'si"] = '$1';
	$tvars['regx']["'\[isnt-logged\](.*?)\[/isnt-logged\]'si"] = '';
	$tvars['regx']["'\[login-err\](.*?)\[/login-err\]'si"] = ($SYSTEM_FLAGS['auth_fail'])?'$1':'';

	$tvars['regx']["'\[if-have-perm\](.*?)\[/if-have-perm\]'si"] = ($userROW['status'] > 3)?'':'$1';
}

exec_acts('usermenu');

$tpl -> template('usermenu', tpl_site);
$tpl -> vars('usermenu', $tvars);
$template['vars']['personal_menu'] = $tpl -> show('usermenu');
