<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru)
// Name: lostpassword.php
// Description: password recovery system
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_lostpass'];

$lang = LoadLang('lostpassword', 'site');
$type = $_REQUEST['type'];
global $AUTH_METHOD;

if (!$type && $config['users_selfregister'] == "1" && !$is_logged) {
	// Receiving parameter list during password recovery
	$auth = $AUTH_METHOD[$config['auth_module']];
	$params = $auth->get_restorepw_params();
	generate_restorepw_page($params);
} else if ($type == "send" && $config['users_selfregister'] == "1" && !$is_logged) {
	// Receiving parameter list during password recovery
	$auth = $AUTH_METHOD[$config['auth_module']];
	$params = $auth->get_restorepw_params();
	$values = array();

	foreach ($params as $param) {
		$values[$param['name']] = $_POST[$param['name']];
	}
	// Trying password recovery
	$msg = '';

	if ($auth->restorepw(&$params, $values, &$msg)) {
		// OK
		// ...
	} else {
		// Fail and reloading page
		generate_restorepw_page($params, $values, $msg);
	}
} else if ($type == "confirm") {
	// Confirmation
	$auth = $AUTH_METHOD[$config['auth_module']];
	$msg = '';

	if ($auth->confirm_restorepw(&$msg)) {
		// OK
		msg(array("text" => $msg));
	} else {
		// Fail
		msg(array("type" => "error", "text" => $msg));
	}
} else {
	msg(array("type" => "error", "text" => $lang['msge_lpforbid']));
}

// Registration page generation
function generate_restorepw_page($params, $values = array(), $msg = '') {
	global $tpl, $template, $PHP_SELF, $config;
	$tpl -> template('lostpassword.entries', tpl_site);
	$tpl -> template('lostpassword.entry-full', tpl_site);

	if ($msg) {
		msg(array("text" => $msg));
	}

	foreach($params as $param) {
		$tvars['vars'] = array(
			'name'	=> $param['name'],
			'title' => $param['title'],
			'descr' => $param['descr'],
			'error' => '',
			'input' => '',
			'text' => $param['text']
		);

		if ($param['error']) {
			$tvars['vars']['error'] = str_replace('%error%',$param['error'],$lang['param_error']);
		}
		if ($values[$param['name']]) {
			$param['value'] = $values[$param['name']];
		}

		if ($param['type'] == 'text') {
			$tvars['vars']['input'] = '<textarea name="'.$param['name'].'" title="'.$param['title'].'" '.$param['html_flags'].'>'.$param['value'].'</textarea>';
		} else if (($param['type'] == 'input')||($param['type'] == 'password')||($param['type'] == 'hidden')) {
			$tvars['vars']['input'] = '<input name="'.$param['name'].'" type="'.$param['type'].'" title="'.$param['title'].'" '.$param['html_flags'].' value="'.$param['value'].'">';
		} else if ($param['type'] == 'select') {
			$tvars['vars']['input'] = '<select name="'.$param['name'].'" title="'.$param['title'].'" '.$param['html_flags'].'>';
			foreach ($param['values'] as $oid => $oval) {
				$tvars['vars']['input'].= '<option value="'.$oid.'"'.($param['value']==$oid?' selected':'').'>'.$oval.'</option>';
			}
			$tvars['vars']['input'].='</select>';
		} else if ($param['type'] = 'manual') {
			$tvars['vars']['input'] = $param['manual'];
		}

		if ($param['text']) {
			$tpl -> vars('lostpassword.entry-full', $tvars);
			$entries .= $tpl -> show('lostpassword.entry-full');
		} else {
			$tpl -> vars('lostpassword.entries', $tvars);
			$entries .= $tpl -> show('lostpassword.entries');
		}
	}

	if ($config['use_captcha'] == "1") {
		@session_register('captcha');
		$_SESSION['captcha'] = rand(00000, 99999);
		$tvars['vars']['captcha'] = '';
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '\\1';
	}
	else {
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '';
	}

	$tvars['vars']['php_self'] = 'index.php';
	$tvars['vars']['entries']  = $entries;
	$tpl -> template('lostpassword', tpl_site);
	$tpl -> vars('lostpassword', $tvars);
	$template['vars']['mainblock'] .= $tpl -> show('lostpassword');
}

