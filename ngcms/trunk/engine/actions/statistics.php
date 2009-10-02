<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: statistics.php
// Description: Generate system statistics
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('statistics', 'admin');


// Gather information about directories
$STATS = array();
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
  	$STATS[$id.'_perm'] = '<font color="red"><b>'.$perms.'</b></font> [<a href="#" onclick="showModal('."'Неверные правила'".');">Ошибка</a>]';
  } else {
   $STATS[$id.'_perm'] = '<font color="green"><b>'.$perms.'</b></font>';
  }
  //$STATS[$id.'_perm'] = $perms;


  list ($size, $count) = directoryWalk($dir);
  $STATS[$id.'_size'] = Formatsize($size);
  $STATS[$id.'_amount'] = $count;
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
	'mysql_size'		=>	$mysql_size,
	'allowed_size'		=>	$df,
	'avatars'			=>	$avatars,
	'backup'			=>	$backup,
	'upfiles'			=>	$upfiles,
	'upimages'			=>	$upimages,
	'photos'			=>	$photos,
	'news'				=>	$mysql->result("SELECT count(id) FROM ".prefix."_news"),
	'news_unapp'		=>	$news_unapp,
	'comments'			=>	getPluginStatusInstalled('comments')?$mysql->result("SELECT count(id) FROM ".prefix."_comments"):'-',
	'users'				=>	$mysql->result("SELECT count(id) FROM ".uprefix."_users"),
	'users_unact'		=>	$users_unact,
	'images'			=>	$mysql->result("SELECT count(id) FROM ".prefix."_images"),
	'files'				=>	$mysql->result("SELECT id FROM ".prefix."_files"),
	'categories'		=>	$mysql->result("SELECT count(id) FROM ".prefix."_category"),
	'admin_note'		=>	($note) ? $note : $lang['no_notes']
);

$tvars['vars'] = $tvars['vars'] + $STATS;

$tpl -> vars('statistics', $tvars);
echo $tpl -> show('statistics');
?>