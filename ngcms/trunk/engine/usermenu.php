<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: usermenu.php
// Description: user's menu on the site
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

global $lang;
global $userROW;
$lang = LoadLang('usermenu', 'site');

// Prepare global params for TWIG
$tVars = array();
$tVars['flags']['isLogged'] = $is_logged;

// Prepare REGEX conversion table
$conversionConfigRegex = array(
		"#\[login\](.*?)\[/login\]#si"					=> '{% if (not flags.isLogged) %}$1{% endif %}',
		"#\[isnt-logged\](.*?)\[/isnt-logged\]#si"		=> '{% if (not flags.isLogged) %}$1{% endif %}',
		"#\[is-logged\](.*?)\[/is-logged\]#si"			=> '{% if (flags.isLogged) %}$1{% endif %}',
		"#\[login-err\](.*?)\[/login-err\]#si"			=> '{% if (flags.loginError) %}$1{% endif %}',
		"#\[if-have-perm\](.*?)\[/if-have-perm\]#si"	=> "{% if (global.flags.isLogged and (global.user['status'] <= 3)) %}$1{% endif %}",
//		"#\{l_([0-9a-zA-Z\-\_\.\#]+)}#"					=> "{{ lang['$1'] }}",
);

// Prepare conversion table
$conversionConfig = array(
		'{profile_link}'		=> '{{ profile_link }}',
		'{addnews_link}'		=> '{{ addnews_link }}',
		'{logout_link}'			=> '{{ logout_link }}',
		'{phtumb_url}'			=> '{{ phtumb_url }}',
		'{name}'				=> '{{ name }}',
		'{result}'				=> '{{ result }}',
		'{home_url}'			=> '{{ home_url }}',
		'{redirect}'			=> '{{ redirect }}',
		'{reg_link}'			=> '{{ reg_link }}',
		'{lost_link}'			=> '{{ lost_link }}',
		'{form_action}'			=> '{{ form_action }}',
);


// If not logged in
if (!$is_logged) {

	$tVars['flags']['loginError']	= ($SYSTEM_FLAGS['auth_fail'])?'$1':'';
	$tVars['redirect']				= isset($SYSTEM_FLAGS['module.usermenu']['redirect'])?$SYSTEM_FLAGS['module.usermenu']['redirect']:$_SERVER['REQUEST_URI'];
	$tVars['reg_link']				= generateLink('core', 'registration');
	$tVars['lost_link']				= generateLink('core', 'lostpassword');
	$tVars['form_action']			= generateLink('core', 'login');
	$tVars['result']				= ($result) ? '<div style="color : #fff; padding : 5px;">'.$lang['msge_login'].'</div>' : '';
} else {
	// User is logged in
	$tVars['profile_link']				= generateLink('uprofile', 'edit');
	$tVars['addnews_link']				= $config['admin_url'].'/admin.php?mod=news&amp;action=add';
	$tVars['logout_link']				= generateLink('core', 'logout');
	$tVars['name']						= $userROW['name'];
	$tVars['phtumb_url']				= photos_url.'/'.(($userROW['photo'] != "")?'thumb/'.$userROW['photo']:'nophoto.gif');
	$tVars['result']					= ($result) ? '<div style="color : #fff; padding:5px;">'.$lang['msge_login'].'</div>' : '';
	$tVars['home_url']					= home;

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
	$tVars['avatar_url'] = $userAvatar;
}

exec_acts('usermenu');

$twigLoader->setConversion('usermenu.tpl', $conversionConfig, $conversionConfigRegex);
$xt = $twig->loadTemplate('usermenu.tpl');
$template['vars']['personal_menu'] = $xt->render($tVars);

// Add special variables `personal_menu:logged` and `personal_menu:not.logged`
$template['vars']['personal_menu:logged'] = $is_logged ? $template['vars']['personal_menu'] : '';
$template['vars']['personal_menu:not.logged'] = $is_logged ? '' : $template['vars']['personal_menu'];