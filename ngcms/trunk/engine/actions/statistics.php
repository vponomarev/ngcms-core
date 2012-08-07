<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: statistics.php
// Description: Generate system statistics
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('statistics', 'admin');

function phpConfigGetBytes ($size_str)
{
	switch (substr ($size_str, -1))
	{
		case 'M': case 'm': return (int)$size_str * 1048576;
		case 'K': case 'k': return (int)$size_str * 1024;
		case 'G': case 'g': return (int)$size_str * 1073741824;
		default: return $size_str;
	}
}


// Gather information about directories
$STATS = array();
$timeLimit = 0;
foreach (array('backup' => root.'backups', 'avatar' => avatars_dir, 'photo' => photos_dir, 'file' => files_dir, 'image' => images_dir) as $id => $dir) {
	if (!is_dir($dir)) {
		// Directory do not exists
		$STATS[$id.'_amount'] = 'n/a';
		$STATS[$id.'_volume'] = 'n/a';
		$STATS[$id.'_perm'] = 'n/a';
		$STATS[$id.'_size'] = 'n/a';
	} else {
		// Get permissions
		$perms = @fileperms($dir);
		$perms = ($perms === false)?'n/a':(decoct($perms) % 1000);

		// Error - engine can't write into directory
		if (!is_writable($dir)) {
			$STATS[$id.'_perm'] = '<font color="red"><b>'.$perms.'</b></font> [<a href="#" onclick="showModal('."'�������� �������'".');">������</a>]';
		} else {
			$STATS[$id.'_perm'] = '<font color="green"><b>'.$perms.'</b></font>';
		}
		//$STATS[$id.'_perm'] = $perms;


		// Load list of files, ExecTimeLimit = 5 sec (don't allow to work for > 5 sec)
		if (!$timeLimit) {
			list ($size, $count, $null, $timeLimit) = directoryWalk($dir, null, null, false, 5);
			$STATS[$id.'_size'] = Formatsize($size);
			$STATS[$id.'_amount'] = $count. ($timeLimit?'<b>++</b>':'');
		} else {
			$STATS[$id.'_size'] = 'too much';
			$STATS[$id.'_amount'] = 'too much';
		}
	}
}

if (function_exists('gd_info')) {
	$gd_version = gd_info();
}

$mysql_size = 0;

foreach ($mysql->select("SHOW TABLE STATUS FROM `".$config['dbname']."`") as $result) {
	$mysql_size += $result['Data_length'] + $result['Index_length'];
}
$mysql_size = Formatsize($mysql_size);

$backup = @decoct(@fileperms(root."backups")) % 1000;
$avatars = @decoct(@fileperms(avatars_dir)) % 1000;
$photos = @decoct(@fileperms(photos_dir)) % 1000;
$upfiles = @decoct(@fileperms(files_dir)) % 1000;
$upimages = @decoct(@fileperms(images_dir)) % 1000;
$upimages = ($upimages != "777") ? '<span style="color:red;">'.$upimages.'</span>' : '<span style="color:green;"><b>'.$upimages.'</b></span>';
$upfiles = ($upfiles != "777") ? '<span style="color:red;">'.$upfiles.'</span>' : '<span style="color:green;"><b>'.$upfiles.'</b></span>';
$avatars = ($avatars != "777") ? '<span style="color:red;">'.$avatars.'</span>' : '<span style="color:green;"><b>'.$avatars.'</b></span>';
$photos = ($photos != "777") ? '<span style="color:red;">'.$photos.'</span>' : '<span style="color:green;"><b>'.$photos.'</b></span>';
$backup = ($backup != "777") ? '<span style="color:red;">'.$backup.'</span>' : '<span style="color:green;"><b>'.$backup.'</b></span>';

$note_path = root.'trash/'.$parse->translit(strtolower(name)).'_note.inc.txt';

if ($action == "save") {
	$note = secure_html(trim($_POST['note']));

	if (!$note || $note == "") {
		@unlink($note_path);
	}
	elseif (strlen($note) > "3000") {
		msg(array("type" => "error", "text" => $lang['msge_badnote'], "info" => $lang['msgi_badnote']));
	}
	else {
		$fp = fopen($note_path, 'w+');
		fwrite($fp, $note);
		fclose($fp);
		msg(array("text" => $lang['msgo_note_saved']));
	}
}

if (file_exists($note_path)) {
	$fp		=	fopen($note_path, 'r');
	$note	=	fread($fp, filesize($note_path));
	fclose($fp);
}

$tpl -> template('statistics', tpl_actions);

$df_size = @disk_free_space(root);
$df = ($df_size > 1) ? Formatsize($df_size) : 'n/a';

// Calculate number of news
$nCount = array();
foreach ($mysql->select("select approve, count(*) as cnt from ".prefix."_news group by approve") as $rec) {
	$nCount['v_'.$rec['approve']] = $rec['cnt'];
}

$news_unapp = $mysql->result("SELECT count(id) FROM ".prefix."_news WHERE approve = '0'");
$news_unapp = ($news_unapp == "0") ? $news_unapp : '<font color="#ff6600">'.$news_unapp.'</font>';
$users_unact = $mysql->result("SELECT count(id) FROM ".uprefix."_users WHERE activation != ''");
$users_unact = ($users_unact == "0") ? $users_unact : '<font color="#ff6600">'.$users_unact.'</font>';
$tvars['vars'] = array(
	'php_self'			=>	$PHP_SELF,
	'php_os'			=>	PHP_OS,
	'php_version'		=>	phpversion(),
	'mysql_version'		=>	mysql_get_server_info(),
	'gd_version'		=>	(isset($gd_version) && is_array($gd_version))?$gd_version["GD Version"]:'<font color="red"><b>NOT INSTALLED</b></font>',
	'currentVersion'	=>	engineVersion,
	'versionNotifyURL'	=>	'http://ngcms.ru/sync/versionNew.php?ver='.urlencode(engineVersion).'&uuid='.$config['UUID'],
	'mysql_size'		=>	$mysql_size,
	'allowed_size'		=>	$df,
	'avatars'			=>	$avatars,
	'backup'			=>	$backup,
	'upfiles'			=>	$upfiles,
	'upimages'			=>	$upimages,
	'photos'			=>	$photos,
	'news_draft'		=>	intval($nCount['v_-1']),
	'news_unapp'		=>	intval($nCount['v_0']),
	'news'				=>	intval($nCount['v_1']),
	'comments'			=>	getPluginStatusInstalled('comments')?$mysql->result("SELECT count(id) FROM ".prefix."_comments"):'-',
	'users'				=>	$mysql->result("SELECT count(id) FROM ".uprefix."_users"),
	'users_unact'		=>	$users_unact,
	'images'			=>	$mysql->result("SELECT count(id) FROM ".prefix."_images"),
	'files'				=>	$mysql->result("SELECT count(id) FROM ".prefix."_files"),
	'categories'		=>	$mysql->result("SELECT count(id) FROM ".prefix."_category"),
	'admin_note'		=>	($note) ? $note : $lang['no_notes']
);

$tvars['vars'] = $tvars['vars'] + $STATS;

// Check if we have problems with limits for uploads
$minUploadLimits = array(phpConfigGetBytes(ini_get('upload_max_filesize')), phpConfigGetBytes(ini_get('post_max_size')));
$tvars['vars']['minUploadLimit'] = intval(min($minUploadLimits)/1024).'kb';

$tvars['regx']['#\[error_size_files\](.+?)\[\/error_size_files\]#is'] = (min($minUploadLimits) < ($config['files_max_size'] * 1024))?'$1':'';
$tvars['regx']['#\[error_size_images\](.+?)\[\/error_size_images\]#is'] = (min($minUploadLimits) < ($config['images_max_size'] * 1024))?'$1':'';

// PHP errors
$phpErrors = 0;
foreach (array('register_globals', 'magic_quotes_gpc', 'magic_quotes_runtime', 'magic_quotes_sybase') as $flag) {
	$tvars['vars']['flag:'.$flag]     = ini_get($flag)?('<font color="red"><b>'.$lang['perror.on'].'</b></font>'):$lang['perror.off'];
	if (ini_get($flag)) { $phpErrors++; }
}
$tvars['regx']['#\[conf_error\](.+?)\[\/conf_error\]#is'] = ($phpErrors)?'$1':'';


$tpl -> vars('statistics', $tvars);
echo $tpl -> show('statistics');
