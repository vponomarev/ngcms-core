<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: images.php
// Description: Images managment
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('images', 'admin');
@include_once root.'includes/classes/upload.class.php';
@include_once root.'includes/inc/file_managment.php';

// =======================================
// BODY
// =======================================

// Init file managment class
$fmanager = new file_managment();

// Check perms
$perms = checkPermission(['plugin' => '#admin', 'item' => 'images'], null, [
    'modify',
    'details',
]);

if (!$perms['modify'] && !$perms['details']) {
    msg(['type' => 'error', 'text' => $lang['msge_mod']]);
}

switch ($subaction) {
    case 'newcat':
        $main_admin = $fmanager->category_create('image', $_REQUEST['newfolder']);
        break;
    case 'delcat':
        $main_admin = $fmanager->category_delete('image', $_REQUEST['category']);
        break;
    case 'delete':
        $main_admin = manage_delete('image');
        break;
    case 'rename':
        $main_admin = $fmanager->file_rename(['type' => 'image', 'id' => $_REQUEST['id'], 'newname' => $_REQUEST['rf']]);
        break;
    case 'move':
        $main_admin = manage_move('image');
        break;
    case 'upload':
    case 'uploadurl':
        $main_admin = manage_upload('image');
        break;
    case 'editForm':
        $main_admin = manage_editForm('image', $_REQUEST['id']);
        break;
    case 'editApply':
        $main_admin = manage_editApply('image', $_POST['id']);
        break;
}

if (($subaction != 'editForm') && ($subaction != 'editApply')) {
    $main_admin = manage_showlist('image');
}
