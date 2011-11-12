<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: usermenu.php
// Description: user's menu on the site
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('usermenu', 'site');

// Prepare global params for TWIG
$tVars = array();
$tVars['flags']['isLogged'] = $is_logged;

// If not logged in
if (!$is_logged) {

	$tVars['flags']['loginError']	= ($SYSTEM_FLAGS['auth_fail'])?'$1':'';
	$tVars['redirect']				= isset($SYSTEM_FLAGS['module.usermenu']['redirect'])?$SYSTEM_FLAGS['module.usermenu']['redirect']:$_SERVER['REQUEST_URI'];
	$tVars['reg_link']				= generateLink('core', 'registration');
	$tVars['lost_link']				= generateLink('core', 'lostpassword');
	$tVars['form_action']			= generateLink('core', 'login');
	$tVars['result']				= ($result) ? '<div style="color : #fff; padding : 5px;">'.$lang['msge_login'].'</div>' : '';

	// Prepare conversion table
	$conversionConfigRegex = array(
			"#\[login\](.*?)\[/login\]#si"					=> '{% if (not flags.isLogged) %}$1{% endif %}',
			"#\[isnt-logged\](.*?)\[/isnt-logged\]#si"		=> '{% if (not flags.isLogged) %}$1{% endif %}',
			"#\[is-logged\](.*?)\[/is-logged\]#si"			=> '{% if (flags.isLogged) %}$1{% endif %}',
			"#\[login-err\](.*?)\[/login-err\]#si"			=> '{% if (flags.loginError) %}$1{% endif %}',
	);

	$conversionConfig = array(
			'{redirect}'			=> '{{ redirect }}',
			'{reg_link'				=> '{{ reg_link }}',
			'{lost_link}'			=> '{{ lost_link }}',
			'{form_action}'			=> '{{ form_action }}',
			'{result}'				=> '{{ result }}',
	);
} else {
	// User is logged in
	$tvars['vars'] = array(
		'profile_link'	=>	generateLink('uprofile', 'edit'),
		'addnews_link'	=>	$config['admin_url'].'/admin.php?mod=news&action=add',
		'logout_link'	=>  generateLink('core', 'logout'),
		'name'			=>	$userROW['name'],
		'phtumb_url'		=>	photos_url.'/'.(($userROW['photo'] != "")?'thumb/'.$userROW['photo']:'nophoto.gif'),
		'pm_new'		=>	'0',
		'result'		=>	($result) ? '<div style="color : #fff; padding:5px;">'.$lang['msge_login'].'</div>' : '',
		'home_url'		=>	home,
	);

	// Generate avatar link
	$userAvatar = '';

	if ($config['use_avatars']) {
		if ($userROW['avatar']) {
			$userAvatar = avatars_url."/".$userROW['avatar'];
		} else {
			// If gravatar integration is active, show avatar from GRAVATAR.COM
			if ($config['avatars_gravatar']) {
				$userAvatar = 'http://www.gravatar.com/avatar/'.md5(strtolower($userROW['mail'])).'.jpg?s='.$config['avatar_wh'].'&d='.urlencode(avatars_url."/noavatar.gif");
			} else {
				$userAvatar = avatars_url."/noavatar.gif";
			}
		}
	}
	$tvars['vars']['avatar_url'] = $userAvatar;

	$tvars['regx']["'\[login\](.*?)\[/login\]'si"] = '';
	$tvars['regx']["'\[is-logged\](.*?)\[/is-logged\]'si"] = '$1';
	$tvars['regx']["'\[isnt-logged\](.*?)\[/isnt-logged\]'si"] = '';
	$tvars['regx']["'\[login-err\](.*?)\[/login-err\]'si"] = (isset($SYSTEM_FLAGS['auth_fail']) && $SYSTEM_FLAGS['auth_fail'])?'$1':'';

	$tvars['regx']["'\[if-have-perm\](.*?)\[/if-have-perm\]'si"] = ($userROW['status'] > 3)?'':'$1';
}

exec_acts('usermenu');

$tpl -> template('usermenu', tpl_site);
$tpl -> vars('usermenu', $tvars);
$template['vars']['personal_menu'] = $tpl -> show('usermenu');

// Add special variables `personal_menu:logged` and `personal_menu:not.logged`
$template['vars']['personal_menu:logged'] = $is_logged ? $template['vars']['personal_menu'] : '';
$template['vars']['personal_menu:not.logged'] = $is_logged ? '' : $template['vars']['personal_menu'];