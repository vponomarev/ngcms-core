<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: files.php
// Description: File managment
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('files', 'admin');
@include_once root . 'includes/classes/upload.class.php';
@include_once root . 'includes/inc/file_managment.php';

// =======================================
// BODY
// =======================================

// Init file managment class
$fmanager = new file_managment();

if ($userROW['status'] > "3" || ($userROW['status'] != "1" && ($action == "imagedelete" || $action == "move")) || ($userROW['status'] > "3" && $action == "rename")) {
	msg(array("type" => "error", "text" => $lang['msge_mod']));
}

switch ($subaction) {
	case "newcat":
		$main_admin = $fmanager->category_create("file", $_REQUEST['newfolder']);
		break;
	case "delcat":
		$main_admin = $fmanager->category_delete("file", $_REQUEST['category']);
		break;
	case "delete":
		$main_admin = manage_delete('file');
		break;
	case "rename":
		$main_admin = $fmanager->file_rename(array('type' => 'file', 'id' => $_REQUEST['id'], 'newname' => $_REQUEST['rf']));
		break;
	case "move":
		$main_admin = manage_move('file');
		break;
	case "upload":
	case "uploadurl":
		$main_admin = manage_upload('file');
		break;

}

$main_admin = manage_showlist('file');



