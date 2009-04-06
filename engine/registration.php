<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: registration.php
// Description: registration system
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('registration', 'site');
global $AUTH_METHOD;

$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_registration'];

if (!$_REQUEST['type'] && $config['users_selfregister'] && !is_array($userROW)) {
	// Receiving parameter list during registration
	$auth = $AUTH_METHOD[$config['auth_module']];
	$params = $auth->get_reg_params();
	generate_reg_page($params);
} else if ($_REQUEST['type'] == "doregister" && $config['users_selfregister'] && !is_array($userROW)) {
	// Receiving parameter list during registration
	$auth = $AUTH_METHOD[$config['auth_module']];
	$params = $auth->get_reg_params();
	$values = array();

	foreach ($params as $param) {
		$values[$param['name']] = $_POST[$param['name']];
	}

	$msg = '';

	// Check captcha
	if ($config['use_captcha'] == '1') {
		$captcha = $_REQUEST['vcode'];

		if (!$captcha || ($_SESSION['captcha'] != $captcha)) {
			// Fail
			$msg = $lang['msge_vcode'];
		}
	}

	// Trying register
	if (!$msg && $auth->register(&$params, $values, &$msg)) {
		// OK
		// ...
	} else {
		// Fail
		generate_reg_page($params, $values, $msg);
	}
} else { msg(array("type" => "error", "text" => $lang['msge_regforbid'])); }

// Registration page generation
function generate_reg_page($params, $values = array(), $msg = '') {
	global $tpl, $template, $PHP_SELF, $config;

	$tpl -> template('registration.entries', tpl_site);

	if ($msg) { msg(array("text" => $msg)); }

	foreach($params as $param) {
		$tvars['vars'] = array(
			'name'	=> $param['name'],
			'title' => $param['title'],
			'descr' => $param['descr'],
			'error' => '',
			'input' => ''
		);

		if ($param['error']) {
			$tvars['vars']['error'] = str_replace('%error%',$param['error'],$lang['param_error']);
		}
		if ($values[$param['name']]) {
			$param['value'] = $values[$param['name']];
		}

		if ($param['type'] == 'text') {
			$tvars['vars']['input'] = '<textarea name="'.$param['name'].'" title="'.$param['title'].'" '.$param['html_flags'].'>'.$param['value'].'</textarea>';
		} else if ($param['type'] == 'input') {
			$tvars['vars']['input'] = '<input name="'.$param['name'].'" type="text" title="'.$param['title'].'" '.$param['html_flags'].' value="'.$param['value'].'"/>';
		} else if (($param['type'] == 'password')||($param['type'] == 'hidden')) {
			$tvars['vars']['input'] = '<input name="'.$param['name'].'" type="'.$param['type'].'" title="'.$param['title'].'" '.$param['html_flags'].' value="'.$param['value'].'"/>';
		} else if ($param['type'] == 'select') {
			$tvars['vars']['input'] = '<select name="'.$param['name'].'" title="'.$param['title'].'" '.$param['html_flags'].'>';
			foreach ($param['values'] as $oid => $oval) {
				$tvars['vars']['input'].= '<option value="'.$oid.'"'.($param['value']==$oid?' selected':'').'>'.$oval.'</option>';
			}
			$tvars['vars']['input'].='</select>';
		} else if ($param['type'] = 'manual') {
			$tvars['vars']['input'] = $param['manual'];
		}

		$tpl -> vars('registration.entries', $tvars);
		$entries .= $tpl -> show('registration.entries');

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

	$tvars['vars'] = array('php_self' => $PHP_SELF);
	$tvars['vars']['entries'] = $entries;
	$tpl -> template('registration', tpl_site);
	$tpl -> vars('registration', $tvars);
	$template['vars']['mainblock'] .= $tpl -> show('registration');
}

?>