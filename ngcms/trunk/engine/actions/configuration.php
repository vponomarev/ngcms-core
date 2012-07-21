<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: configuration.php
// Description: Configuration managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang		= LoadLang('configuration', 'admin');


//
// Save system config
function systemConfigSave(){
	global $lang, $config;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id), array('action' => 'saveConfig'), null, array(0, 'SECURITY.PERM'));
		return false;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.configuration'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id), array('action' => 'saveConfig'), null, array(0, 'SECURITY.TOKEN'));
		return false;
	}

	$save_con	= $_REQUEST['save_con'];
	if (is_null($save_con) || !is_array($save_con))
		return false;

	// Save our UUID or regenerate LOST UUID
	$save_con['UUID'] = $config['UUID'];
	if ($save_con['UUID'] == '') {
		$save_con['UUID'] = md5(mt_rand().mt_rand()).md5(mt_rand().mt_rand());
	}

	// Prepare resulting config content
	$fcData = "<?php\n".'$config = '.var_export($save_con, true)."\n;?>";

	// Try to save config
	$fcHandler = @fopen(confroot.'config.php', 'w');
	if ($fcHandler) {
		fwrite($fcHandler, $fcData);
		fclose($fcHandler);

		msg(array("text" => $lang['msgo_saved']));
	} else {
		msg(array("type" => 'error', "text" => $lang['msge_save_error'], "info" => $lang['msge_save_error#desc']));
		return false;
	}

	ngSYSLOG(array('plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id), array('action' => 'saveConfig', 'list' => $fcData), null, array(1, ''));

	return true;
}

//
// Show configuration form
function systemConfigEditForm(){
	global $lang, $tpl, $AUTH_CAPABILITIES;

	// Check for token
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'details')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id), array('action' => 'saveConfig'), null, array(0, 'SECURITY.PERM'));
		return false;
	}

	$tpl -> template('configuration', tpl_actions);

	$auth_modules = array();
	$auth_dbs = array();

	foreach ($AUTH_CAPABILITIES as $k => $v) {
		if ($v['login'])	{ $auth_modules[$k] = $k;	}
		if ($v['db'])		{ $auth_dbs[$k] = $k;		}
	}

	// Load config file from configuration
	// Now in $config we have original version of configuration data
	include confroot.'config.php';

	$tvars['vars'] = array(
		'php_self'					=>	$PHP_SELF,
		'timestamp_active_now'		=>	LangDate($config['timestamp_active'], time()),
		'timestamp_updated_now'		=>	LangDate($config['timestamp_updated'], time()),
		'lock'						=>	MakeDropDown(array(1=>$lang[yesa],0=>$lang[noa]), "save_con[lock]", $config['lock']),
		'language_selection'		=>	MakeDropDown(ListFiles('lang', ''), "save_con[default_lang]", $config['default_lang']),
		'captcha_font'				=>	MakeDropDown(ListFiles('trash', 'ttf'), "save_con[captcha_font]", $config['captcha_font']),
		'list_themes'				=>	MakeDropDown(ListFiles('../templates',''), "save_con[theme]", $config['theme']),
		'users_selfregister'		=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']), "save_con[users_selfregister]", $config['users_selfregister']),
		'register_type'				=>	MakeDropDown(array(0 => $lang['register_extremly'], 1 => $lang['register_simple'], 2 => $lang['register_activation'], 3 => $lang['register_manual'], 4 => $lang['register_manual_confirm']), "save_con[register_type]", $config['register_type']),
		'blocks_for_reg'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[blocks_for_reg]", $config['blocks_for_reg']),
		'extended_more'				=>	MakeDropDown(array(0 => $lang['noa'],  1 => $lang['yesa']), "save_con[extended_more]", $config['extended_more']),
		'meta'						=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[meta]", $config['meta']),
		'auto_backup'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[auto_backup]", $config['auto_backup']),
		'use_gzip'					=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_gzip]", $config['use_gzip']),
		'use_captcha'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_captcha]", $config['use_captcha']),
		'use_cookies'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_cookies]", $config['use_cookies']),
		'news_without_content'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[news_without_content]", $config['news_without_content']),
		'use_sessions'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_sessions]", $config['use_sessions']),
		'category_counters'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[category_counters]", $config['category_counters']),
		'news.edit.split'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[news.edit.split]", $config['news.edit.split']),
		'use_smilies'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_smilies]", $config['use_smilies']),
		'use_bbcodes'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_bbcodes]", $config['use_bbcodes']),
		'use_htmlformatter'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_htmlformatter]", $config['use_htmlformatter']),
		'htmlsecure_4'				=>	MakeDropDown(array(0 => $lang['noa'],  1 => $lang['yesa']), "save_con[htmlsecure_4]", $config['htmlsecure_4']),
		'htmlsecure_3'				=>	MakeDropDown(array(0 => $lang['noa'],  1 => $lang['yesa']), "save_con[htmlsecure_3]", $config['htmlsecure_3']),
		'htmlsecure_2'				=>	MakeDropDown(array(0 => $lang['noa'],  1 => $lang['yesa']), "save_con[htmlsecure_2]", $config['htmlsecure_2']),
		'use_avatars'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_avatars]", $config['use_avatars']),
		'avatars_gravatar'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[avatars_gravatar]", $config['avatars_gravatar']),
		'use_photos'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[use_photos]", $config['use_photos']),
		'send_notice'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[send_notice]", $config['send_notice']),
		'remember'					=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),  "save_con[remember]", $config['remember']),
		'auth_module'				=>	MakeDropDown($auth_modules, "save_con[auth_module]", $config['auth_module']),
		'auth_db'					=>	MakeDropDown($auth_dbs, "save_con[auth_db]", $config['auth_db']),
		'mydomains'					=>	$config['mydomains'],
		'debug'						=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']), "save_con[debug]", $config['debug']),
		'debug_queries'				=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']), "save_con[debug_queries]", $config['debug_queries']),
		'debug_profiler'			=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']), "save_con[debug_profiler]", $config['debug_profiler']),
		'default_newsorder'			=>	MakeDropDown(array('id desc' => $lang['order_id_desc'], 'id asc' => $lang['order_id_asc'], 'postdate desc' => $lang['order_postdate_desc'], 'postdate asc' => $lang['order_postdate_asc'], 'title desc' => $lang['order_title_desc'], 'title asc' => $lang['order_title_asc']), "save_con[default_newsorder]", $config['default_newsorder']),
		'thumb_mode'				=>	MakeDropDown(array(0 => $lang['mode_demand'], 1 => $lang['mode_forbid'], 2 => $lang['mode_always']),   "save_con[thumb_mode]",   $config['thumb_mode']),
		'shadow_mode'				=>	MakeDropDown(array(0 => $lang['mode_demand'], 1 => $lang['mode_forbid'], 2 => $lang['mode_always']),   "save_con[shadow_mode]",  $config['shadow_mode']),
		'shadow_place'				=>	MakeDropDown(array(0 => $lang['mode_orig'],   1 => $lang['mode_copy'],   2 => $lang['mode_origcopy']), "save_con[shadow_place]", $config['shadow_place']),
		'stamp_mode'				=>	MakeDropDown(array(0 => $lang['mode_demand'], 1 => $lang['mode_forbid'], 2 => $lang['mode_always']),   "save_con[stamp_mode]",   $config['stamp_mode']),
		'stamp_place'				=>	MakeDropDown(array(0 => $lang['mode_orig'],   1 =>$lang['mode_copy'],    2 => $lang['mode_origcopy']), "save_con[stamp_place]",  $config['stamp_place']),
		'404_mode'					=>	MakeDropDown(array(0 => $lang['404.int'],     1 =>$lang['404.ext'],      2 => $lang['404.http']),      "save_con[404_mode]",     $config['404_mode']),
		'libcompat'					=>	MakeDropDown(array(1 => $lang['yesa'], 0 => $lang['noa']),      "save_con[libcompat]",     $config['libcompat']),
		'sql_error'					=>	MakeDropDown(array(0 => $lang['sql_error_0'],   1 =>$lang['sql_error_1'],    2 => $lang['sql_error_2']), "save_con[sql_error_show]",  $config['sql_error_show']),
		'url_external_nofollow'		=>	MakeDropDown(array(0 => $lang['noa'], 1 => $lang['yesa']),  "save_con[url_external_nofollow]", $config['url_external_nofollow']),
		'url_external_target_blank'	=>	MakeDropDown(array(0 => $lang['noa'], 1 => $lang['yesa']),  "save_con[url_external_target_blank]", $config['url_external_target_blank']),
		'photos_thumb_size_x'		=>	isset($config['photos_thumb_size_x'])?intval($config['photos_thumb_size_x']):intval($config['photos_thumb_size']),
		'photos_thumb_size_y'		=>	isset($config['photos_thumb_size_y'])?intval($config['photos_thumb_size_y']):intval($config['photos_thumb_size']),
		'thumb_size_x'				=>	isset($config['thumb_size_x'])?intval($config['thumb_size_x']):intval($config['thumb_size']),
		'thumb_size_y'				=>	isset($config['thumb_size_y'])?intval($config['thumb_size_y']):intval($config['thumb_size']),
		'token'						=> genUToken('admin.configuration'),
		'home_url'					=> $config['home_url'],
		'admine_url'				=> $config['admin_url'],
		'news_multicat_url'			=>	MakeDropDown(array(0 => $lang['news_multicat:0'], 1 => $lang['news_multicat:1']),  "save_con[news_multicat_url]", $config['news_multicat_url']),
	);

	// Prepare file name for STAMP
	$stampFileName = '';
	if (file_exists(root.'trash/'.$config['wm_image'].'.gif')) {
		$stampFileName = $config['wm_image'].'.gif';
	} else if (file_exists(root.'trash/'.$config['wm_image'])) {
		$stampFileName = $config['wm_image'];
	}

	$tvars['vars']['wm_image']		= MakeDropDown(ListFiles('trash', array('gif', 'png'), 2), "save_con[wm_image]", $config['wm_image']);


	//
	// Fill parameters for multiconfig
	//
	$tmpline = '';
	if (is_array($multiconfig)) {
		foreach ($multiconfig as $mid => $mline) {
			$tmpdom = implode("\n",$mline['domains']);
			$tmpline .= "<tr class='contentEntry1'><td>".($mline['active']?'On':'Off')."</td><td>$mid</td><td>".($tmpdom?$tmpdom:'-не указано-')."</td><td>&nbsp;</td></tr>\n";
		}
	}
	$tvars['vars']['multilist'] = $tmpline;
	$tvars['vars']['defaultSection'] = (isset($_REQUEST['selectedOption']) && $_REQUEST['selectedOption'])?htmlspecialchars($_REQUEST['selectedOption']):'news';

	$tpl -> vars('configuration', $tvars);
	echo $tpl -> show('configuration');
}



//
//
// Check if SAVE is requested and SAVE was successfull
if (isset($_REQUEST['subaction']) && ($_REQUEST['subaction'] == "save") && ($_SERVER['REQUEST_METHOD'] == "POST") &&systemConfigSave()) {
	@include(confroot.'config.php');
}

// Show configuration form
systemConfigEditForm();


