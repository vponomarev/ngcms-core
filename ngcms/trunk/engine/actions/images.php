<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: images.php
// Description: Images managment
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('images', 'admin');
@include_once root.'includes/classes/upload.class.php';
@include_once root.'includes/inc/file_managment.php';



// =======================================
// BODY
// =======================================

// Init file managment class
$fmanager = new file_managment();



if($userROW['status'] > "3" || ($userROW['status'] != "1" && ($action == "imagedelete" || $action == "move")) || ($userROW['status'] > "3" && $action == "rename")) {
	msg(array("type" => "error", "text" => $lang['msge_mod']));
}



switch($subaction){
	case "newcat":		$fmanager->category_create("image", $_REQUEST['newfolder']);	break;
	case "delcat":		$fmanager->category_delete("image", $_REQUEST['category']);		break;
	case "delete":		manage_delete('image'); break;
	case "rename":		$fmanager->file_rename(array('type' => 'image', 'id' => $_REQUEST['id'], 'newname' => $_REQUEST['rf'])); break;
	case "move":		manage_move('image'); break;
	case "upload":
	case "uploadurl":	manage_upload('image'); break;
	case "editForm":	manage_editForm('image', $_REQUEST['id']); break;
	case "editApply":	manage_editApply('image', $_POST['id']); break;
}

if (($subaction != 'editForm')&&($subaction != 'editApply'))
	manage_showlist('image');



