<?php

//
// Copyright (C) 2008 Next Generation CMS (http://ngcms.ru/)
// Name: showinfo.php
// Description: Show different informational blocks
// Author: Vitaly Ponomarev
//

include_once '../core.php';

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

header('Content-Type: text/html; charset=utf-8');

if ($_REQUEST['mode'] == 'plugin') {
    $extras = pluginsGetList();
    $plugin = str_replace(['/', '\\', '..'], '', $_REQUEST['plugin']);
    if (!is_array($extras[$plugin])) {
        return;
    }

    if ($_REQUEST['item'] == 'readme') {
        if (file_exists(root.'plugins/'.$plugin.'/readme')) {
            echo '<pre>';
            echo file_get_contents(root.'plugins/'.$plugin.'/readme');
            echo '</pre>';
        }
    }
    if ($_REQUEST['item'] == 'history') {
        if (file_exists(root.'plugins/'.$plugin.'/history')) {
            echo '<pre>';
            echo file_get_contents(root.'plugins/'.$plugin.'/history');
            echo '</pre>';
        }
    }
}
