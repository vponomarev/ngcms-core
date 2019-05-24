<?php

//
// Copyright (C) 2006-2015 Next Generation CMS (http://ngcms.ru/)
// Name: cmodules.php
// Description: Common CORE modules
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

function coreActivateUser() {

	global $lang, $config, $SYSTEM_FLAGS, $mysql, $CurrentHandler;

	$lang = LoadLang('activation', 'site');
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['loc_activation'];

	if (($CurrentHandler['pluginName'] == 'core') &&
		($CurrentHandler['handlerName'] == 'activation')
	) {
		$userid = isset($CurrentHandler['params']['userid']) ? $CurrentHandler['params']['userid'] : $_REQUEST['userid'];
		$code = isset($CurrentHandler['params']['code']) ? $CurrentHandler['params']['code'] : $_REQUEST['code'];
	} else {
		$userid = $_REQUEST['userid'];
		$code = $_REQUEST['code'];
	}

	// Check if user exists with ID = $userid
	if (is_array($uRow = $mysql->record("select * from " . prefix . "_users where id=" . db_squote($userid)))) {
		// User found. Check activation.
		if ($uRow['activation']) {
			if ($uRow['activation'] == $code) {
				// Yeah, activate user!
				$mysql->query("update `" . uprefix . "_users` set activation = '' where id = " . db_squote($userid));

				msg(array("text" => $lang['msgo_activated'], "info" => sprintf($lang['msgi_activated'], admin_url)));
				$SYSTEM_FLAGS['module.usermenu']['redirect'] = $config['home_url'] . '/';
			} else {
				// Incorrect activation code
				msg(array("text" => $lang['msge_activate'], "info" => sprintf($lang['msgi_activate'], admin_url)));
			}
		} else {
			// Already activated
			print "ALREADY";
			msg(array("text" => $lang['msge_activation'], "info" => sprintf($lang['msgi_activation'], admin_url)));
		}
	} else {
		// User not found
		error404();
	}
}

function coreRegisterUser() {

	global $ip, $lang, $config, $AUTH_METHOD, $SYSTEM_FLAGS, $userROW, $PFILTERS, $mysql;

	$lang = LoadLang('registration', 'site');
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['loc_registration'];

	// If logged in user comes to us - REDIRECT him to main page
	if (is_array($userROW)) {
		@header('Location: ' . $config['home_url']);

		return;
	}

	// Check for ban
	if ($ban_mode = checkBanned($ip, 'users', 'register', $userROW, $userROW['name'])) {
		msg(array("type" => "error", "text" => ($ban_mode == 1) ? $lang['register.banned'] : $lang['msge_regforbid']));

		return;
	}

	if (!$_REQUEST['type'] && $config['users_selfregister']) {
		// Receiving parameter list during registration
		$auth = $AUTH_METHOD[$config['auth_module']];
		$params = $auth->get_reg_params();
		generate_reg_page($params);
	} else if ($_REQUEST['type'] == "doregister" && $config['users_selfregister']) {
		// Receiving parameter list during registration
		$auth = $AUTH_METHOD[$config['auth_module']];
		$params = $auth->get_reg_params();
		$values = array();

		foreach ($params as $param) {
			$values[$param['name']] = $_POST[$param['name']];
		}

		$msg = '';

		// Check captcha
		if ($config['use_captcha']) {
			$captcha = $_REQUEST['vcode'];
			if (!$captcha || ($_SESSION['captcha'] != $captcha)) {
				// Fail
				$msg = $lang['msge_vcode'];
			}
		}

		// Execute filters - check if user is allowed to register
		if ((!$msg) && is_array($PFILTERS['core.registerUser']))
			foreach ($PFILTERS['core.registerUser'] as $k => $v) {
				if (!$v->registerUser($params, $msg)) {
					break;
				}
			}

		// Trying register
		if (!$msg && ($uid = $auth->register($params, $values, $msg))) {
			// OK, fetch user record
			if ($uid > 1) {
				// ** COMPAT: exec action only if $uid > 1
				$urec = $mysql->record("select * from " . uprefix . "_users where id = " . intval($uid));

				// LOG: Successully registered
				ngSYSLOG(array('plugin' => 'core', 'item' => 'register'), array('action' => 'register'), $urec, array(1, ''));

				// Execute filters - add additional variables
				if (is_array($urec) && is_array($PFILTERS['core.registerUser']))
					foreach ($PFILTERS['core.registerUser'] as $k => $v) {
						$v->registerUserNotify($uid, $urec);
					}
			}
		} else {
			// LOG: Registration failed
			ngSYSLOG(array('plugin' => 'core', 'item' => 'register'), array('action' => 'register'), 0, array(0, 'Registration failed'));

			// Fail
			generate_reg_page($params, $values, $msg);
		}
	} else {
		msg(array("type" => "error", "text" => $lang['msge_regforbid']));
	}
}

// Registration page generation
function generate_reg_page($params, $values = array(), $msg = '') {

	global $tpl, $template, $PHP_SELF, $config, $PFILTERS, $twig, $twigLoader, $lang;

	$tVars = array(
		'entries' => array(),
		'flags'   => array(),
	);

	if ($msg) {
		msg(array("text" => $msg));
	}

	// prepare variable list
	foreach ($params as $param) {
		$tRow = array(
			'name'       => $param['name'],
			'title'      => $param['title'],
			'descr'      => $param['descr'],
			'error'      => '',
			'input'      => '',
			'flags'      => array(),
			'type'       => $param['type'],
			'html_flags' => $param['html_flags'],
			'value'      => $param['value'],
			'values'     => $param['values'],
			'manual'     => $param['manual'],
		);

		if (isset($param['id']) && $param['id'])
			$tRow['id'] = $param['id'];

		if ($param['error']) {
			$tRow['flags']['isError'] = true;
			$tRow['error'] = str_replace('%error%', $param['error'], $lang['param_error']);
		}
		if ($values[$param['name']]) {
			$tRow['value'] = $values[$param['name']];
			$param['value'] = $values[$param['name']];
		}
		$tVars['entries'][] = $tRow;
	}

	// Execute filters - add additional variables
	if (is_array($PFILTERS['core.registerUser']))
		foreach ($PFILTERS['core.registerUser'] as $k => $v) {
			$v->registerUserForm($tVars);
		}

	// Generate inputs
	foreach ($tVars['entries'] as $k => $param) {
		$tInput = '';
		if ($param['type'] == 'text') {
			$tInput = '<textarea ' . ((isset($param['id']) && $param['id']) ? 'id="' . $param['id'] . '" ' : '') . 'name="' . $param['name'] . '" title="' . $param['title'] . '" ' . $param['html_flags'] . '>' . secure_html($param['value']) . '</textarea>';
		} else if ($param['type'] == 'input') {
			$tInput = '<input ' . ((isset($param['id']) && $param['id']) ? 'id="' . $param['id'] . '" ' : '') . 'name="' . $param['name'] . '" type="text" title="' . $param['title'] . '" ' . $param['html_flags'] . ' value="' . secure_html($param['value']) . '"/>';
		} else if (($param['type'] == 'password') || ($param['type'] == 'hidden')) {
			$tInput = '<input ' . ((isset($param['id']) && $param['id']) ? 'id="' . $param['id'] . '" ' : '') . 'name="' . $param['name'] . '" type="' . $param['type'] . '" title="' . $param['title'] . '" ' . $param['html_flags'] . ' value="' . secure_html($param['value']) . '"/>';
		} else if ($param['type'] == 'select') {
			$tInput = '<select ' . ((isset($param['id']) && $param['id']) ? 'id="' . $param['id'] . '" ' : '') . 'name="' . $param['name'] . '" title="' . $param['title'] . '" ' . $param['html_flags'] . '>';
			foreach ($param['values'] as $oid => $oval) {
				$tInput .= '<option value="' . $oid . '"' . ($param['value'] == $oid ? ' selected' : '') . '>' . $oval . '</option>';
			}
			$tInput .= '</select>';
		} else if ($param['type'] = 'manual') {
			$tInput = $param['manual'];
		}

		$tVars['entries'][$k]['input'] = $tInput;
	}

	if ($config['use_captcha']) {
		$tVars['flags']['hasCaptcha'] = true;
		$_SESSION['captcha'] = rand(00000, 99999);
	} else {
		$tVars['flags']['hasCaptcha'] = false;
	}

	$tVars['form_action'] = checkLinkAvailable('core', 'registration') ?
		generateLink('core', 'registration', array()) :
		generateLink('core', 'plugin', array('plugin' => 'core', 'handler' => 'registration'));

	// Prepare REGEX conversion table
	$conversionConfigRegex = array(
		"#\[captcha\](.*?)\[/captcha\]#si" => '{% if (flags.hasCaptcha) %}$1{% endif %}',
		"#{entries}#si"                    => '{% for entry in entries %}{% include "registration.entries.tpl" %}{% endfor %}',
	);

	$conversionConfig = array(
		'{form_action}' => '{{ form_action }}',
	);

	$conversionConfigEntries = array(
		'{name}'  => '{{ entry.name }}',
		'{title}' => '{{ entry.title }}',
		'{descr}' => '{{ entry.descr }}',
		'{error}' => '{{ entry.error }}',
		'{input}' => '{{ entry.input }}',
	);

	$twigLoader->setConversion('registration.tpl', $conversionConfig, $conversionConfigRegex);
	$twigLoader->setConversion('registration.entries.tpl', $conversionConfigEntries);
	$xt = $twig->loadTemplate('registration.tpl');
	$template['vars']['mainblock'] .= $xt->render($tVars);

}

function coreRestorePassword() {

	global $lang, $userROW, $config, $AUTH_METHOD, $SYSTEM_FLAGS, $mysql, $CurrentHandler;

	$lang = LoadLang('lostpassword', 'site');
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['loc_lostpass'];

	if (is_array($userROW)) {
		@header('Location: ' . $config['home_url']);

		return;
	}

	if (($CurrentHandler['pluginName'] == 'core') &&
		($CurrentHandler['handlerName'] == 'lostpassword')
	) {
		$userid = isset($CurrentHandler['params']['userid']) ? $CurrentHandler['params']['userid'] : $_REQUEST['userid'];
		$code = isset($CurrentHandler['params']['code']) ? $CurrentHandler['params']['code'] : $_REQUEST['code'];
	} else {
		$userid = $_REQUEST['userid'];
		$code = $_REQUEST['code'];
	}

	// Confirmation
	if ($userid && $code) {
		$auth = $AUTH_METHOD[$config['auth_module']];
		$msg = '';

		if ($auth->confirm_restorepw($msg, $userid, $code)) {
			// OK
			msg(array("text" => $msg));
		} else {
			// Fail
			msg(array("type" => "error", "text" => $msg));
		}
	} else if ($_REQUEST['type'] == 'send') {
		// PROCESSING REQUEST

		// Receiving parameter list during password recovery
		$auth = $AUTH_METHOD[$config['auth_module']];
		$params = $auth->get_restorepw_params();
		$values = array();

		foreach ($params as $param) {
			$values[$param['name']] = $_POST[$param['name']];
		}

		$msg = '';

		// Check captcha
		if ($config['use_captcha']) {
			$captcha = $_REQUEST['vcode'];
			if (!$captcha || ($_SESSION['captcha'] != $captcha)) {
				// Fail
				$msg = $lang['msge_vcode'];
			}
		}

		// Trying password recovery
		if (($msg == '') && $auth->restorepw($params, $values, $msg)) {
			// OK
			// ...
		} else {
			// Fail and reloading page
			generate_restorepw_page($params, $values, $msg);
		}
	} else {
		// DEFAULT: SHOW RESTORE PW SCREEN

		// Receiving parameter list during password recovery
		$auth = $AUTH_METHOD[$config['auth_module']];
		$params = $auth->get_restorepw_params();
		if (!is_array($params)) {
			msg(array("type" => "error", "text" => $lang['msge_lpforbid']));

			return;
		}
		generate_restorepw_page($params);

	}
}

// Registration page generation
function generate_restorepw_page($params, $values = array(), $msg = '') {

	global $tpl, $template, $PHP_SELF, $config, $lang;
	$tpl->template('lostpassword.entries', tpl_site);
	$tpl->template('lostpassword.entry-full', tpl_site);

	if ($msg) {
		msg(array("text" => $msg));
	}

	foreach ($params as $param) {
		$tvars['vars'] = array(
			'name'  => $param['name'],
			'title' => $param['title'],
			'descr' => $param['descr'],
			'error' => '',
			'input' => '',
			'text'  => $param['text']
		);

		if ($param['error']) {
			$tvars['vars']['error'] = str_replace('%error%', $param['error'], $lang['param_error']);
		}
		if ($values[$param['name']]) {
			$param['value'] = $values[$param['name']];
		}

		if ($param['type'] == 'text') {
			$tvars['vars']['input'] = '<textarea name="' . $param['name'] . '" title="' . secure_html($param['title']) . '" ' . $param['html_flags'] . '>' . secure_html($param['value']) . '</textarea>';
		} else if (($param['type'] == 'input') || ($param['type'] == 'password') || ($param['type'] == 'hidden')) {
			$tvars['vars']['input'] = '<input name="' . $param['name'] . '" type="' . (($param['type'] == 'input') ? 'text' : $param['type']) . '" title="' . secure_html($param['title']) . '" ' . $param['html_flags'] . ' value="' . secure_html($param['value']) . '" />';
		} else if ($param['type'] == 'select') {
			$tvars['vars']['input'] = '<select name="' . $param['name'] . '" title="' . secure_html($param['title']) . '" ' . $param['html_flags'] . '>';
			foreach ($param['values'] as $oid => $oval) {
				$tvars['vars']['input'] .= '<option value="' . secure_html($oid) . '"' . ($param['value'] == $oid ? ' selected' : '') . '>' . secure_html($oval) . '</option>';
			}
			$tvars['vars']['input'] .= '</select>';
		} else if ($param['type'] == 'manual') {
			$tvars['vars']['input'] = $param['manual'];
		}

		if ($param['text']) {
			$tpl->vars('lostpassword.entry-full', $tvars);
			$entries .= $tpl->show('lostpassword.entry-full');
		} else {
			$tpl->vars('lostpassword.entries', $tvars);
			$entries .= $tpl->show('lostpassword.entries');
		}
	}

	if ($config['use_captcha']) {
		$_SESSION['captcha'] = rand(00000, 99999);
		$tvars['vars']['captcha'] = '';
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '$1';
	} else {
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '';
	}

	$tvars['vars']['form_action'] = checkLinkAvailable('core', 'lostpassword') ?
		generateLink('core', 'lostpassword', array()) :
		generateLink('core', 'plugin', array('plugin' => 'core', 'handler' => 'lostpassword'));
	$tvars['vars']['entries'] = $entries;
	$tpl->template('lostpassword', tpl_site);
	$tpl->vars('lostpassword', $tvars);
	$template['vars']['mainblock'] .= $tpl->show('lostpassword');
}

//
// Execute an action for coreLogin() function
// This is a workaround for 2-stage AUTH functions (like openID)
// Parameter: user's record [row]
function coreLoginAction($row = null, $redirect = null) {

	global $auth, $auth_db, $username, $userROW, $is_logged, $is_logged_cookie, $SYSTEM_FLAGS, $HTTP_REFERER;
	global $tpl, $template, $config, $lang, $ip;

	$lang = LoadLang('login', 'site');
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['loc_login'];

	// Try to auth && check for bans
	if (is_array($row) && (!($ban_mode = checkBanned($ip, 'users', 'auth', $row, $row['name'])))) {

		$auth_db->save_auth($row);
		$username = $row['name'];
		$userROW = $row;
		$is_logged_cookie = true;
		$is_logged = true;

		// LOG: Successully logged in
		ngSYSLOG(array('plugin' => 'core', 'item' => 'login'), array('action' => 'login', 'list' => array('login' => $username)), null, array(1, ''));

		// Redirect back
		@header('Location: ' . ($redirect ? $redirect : home));
	} else {
		// LOG: Login error
		ngSYSLOG(array('plugin' => 'core', 'item' => 'login'), array('action' => 'login', 'list' => array('errorInfo' => $row)), null, array(0, 'Login failed.'));

		$SYSTEM_FLAGS['auth_fail'] = 1;
		$result = true;
		$is_logged_cookie = false;

		// Show login template
		$tvars = array();
		$tvars['vars']['form_action'] = generateLink('core', 'login');
		$tvars['vars']['redirect'] = isset($_POST['redirect']) ? $_POST['redirect'] : $HTTP_REFERER;

		if (preg_match('#^ERR:NEED.ACTIVATE#', $row, $null)) {
			$tvars['regx']['#\[need\.activate\](.+?)\[/need\.activate\]#is'] = '$1';
			$tvars['regx']['#\[error\](.+?)\[/error\]#is'] = '';
			$tvars['regx']['#\[banned\](.+?)\[/banned\]#is'] = '';
		} else if ($row == 'ERR:NOT_ENTERED') {
			$tvars['regx']['#\[need\.activate\](.+?)\[/need\.activate\]#is'] = '';
			$tvars['regx']['#\[error\](.+?)\[/error\]#is'] = '';
			$tvars['regx']['#\[banned\](.+?)\[/banned\]#is'] = '';
		} else {
			$tvars['regx']['#\[error\](.+?)\[/error\]#is'] = ($ban_mode != 1) ? '$1' : '';
			$tvars['regx']['#\[banned\](.+?)\[/banned\]#is'] = ($ban_mode == 1) ? '$1' : '';
			$tvars['regx']['#\[need\.activate\](.+?)\[/need\.activate\]#is'] = '';
		}

		$tpl->template('login', tpl_site);
		$tpl->vars('login', $tvars);
		$template['vars']['mainblock'] = $tpl->show('login');
	}
}

function coreLogin() {

	global $auth, $auth_db, $username, $userROW, $is_logged, $is_logged_cookie, $SYSTEM_FLAGS, $HTTP_REFERER;
	global $tpl, $template, $config, $lang, $ip;

	// If user ALREADY logged in - redirect to main page
	if (is_array($userROW)) {
		@header('Location: ' . $config['home_url']);

		return;
	}

	// Determine redirect point
	// If POST fiels (ONLY POST) 'redirect' is set - redirect
	$redirect = '';
	if (isset($_POST['redirect']) && $_POST['redirect']) {
		$redirect = $_POST['redirect'];
	} else if (isset($_REQUEST['redirect_home']) && $_REQUEST['redirect_home']) {
		$redirect = $config['home_url'];
	} else if (preg_match('#^http\:\/\/#', $HTTP_REFERER, $tmp)) {
		$redirect = $HTTP_REFERER;
	} else {
		$redirect = $config['home_url'];
	}

	// Auth can work ONLY via POST method
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		coreLoginAction('ERR:NOT_ENTERED');

		return;
	}

	// Try to auth
	$row = $auth->login();
	coreLoginAction($row, $redirect);
}

function coreLogout() {

	global $auth_db, $userROW, $username, $is_logged, $HTTP_REFERER, $config;

	$auth_db->drop_auth();
	@header("Location: " . (preg_match('#^http\:\/\/#', $HTTP_REFERER, $tmp) ? $HTTP_REFERER : $config['home_url']));

	unset($userROW);
	unset($username);
	$is_logged = false;
}
