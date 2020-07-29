<?php

//
// Copyright (C) 2012 Next Generation CMS (http://ngcms.ru/)
// Name: templates.rpc.php
// Description: Externally available library for TEMPLATES manipulation
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// Load library
$lang = LoadLang('templates', 'admin');

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: Get list of files from TEMPLATE
// ///////////////////////////////////////////////////////////////////////////
function admTemplatesWalkPTemplates($dir)
{

    // Scan directory with plugins
    $dirBase = extras_dir;

    $lDirs = [];
    $lFiles = [];

    $flagList = false;
    if ($dir == '') {
        $flagList = true;
    } else {
        if (strpos($dir, '/') < 1) {
            // print "strange dir: [$dir]";
            // Return nothing if plugin is not identified
            return [$ldirs, $lfiles];
        }
        $pluginID = substr($dir, 0, strpos($dir, '/'));
        $pluginPath = substr($dir, strpos($dir, '/'));

        if (!is_dir($dirBase.'/'.$pluginID) || !is_dir($dirBase.'/'.$pluginID.'/tpl/')) {
            // Return nothing if plugin doesn't have [tpl/] directory or doesn't have target directory
            return [$ldirs, $lfiles];
        }

        $dirBase = $dirBase.'/'.$pluginID.'/tpl/'.$pluginPath;
    }

    //	print "pluginID: ".$pluginID.", pluginPath: ".$pluginPath.", dirBase: ".$dirBase;

    $d = opendir($dirBase);
    while ($f = readdir($d)) {
        if (($f == '.') || ($f == '..')) {
            continue;
        }

        // Skip plugins that don't have subdirectory [tpl/]
        if ($flagList && (!is_dir($dirBase.'/'.$f) || !is_dir($dirBase.'/'.$f.'/tpl/'))) {
            continue;
        }

        if (is_dir($dirBase.'/'.$f)) {
            $lDirs[] = $f;
        } elseif (!$flagList && is_file($dirBase.'/'.$f)) {
            $lFiles[] = $f;
        }
    }

    return [$lDirs, $lFiles];
}

function admTemplatesListFiles($params)
{

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'details')) {
        // ACCESS DENIED
        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied'];
    }

    // Scan incoming params
    if (!is_array($params) || !isset($params['template']) || !isset($params['dir']) || !isset($params['token'])) {
        return ['status' => 0, 'errorCode' => 4, 'errorText' => 'Wrong params type'];
    }

    // Check for security token
    if ($params['token'] != genUToken('admin.templates')) {
        return ['status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code'];
    }

    // Prepare arrays for result
    $lDirs = [];
    $lFiles = [];

    $dir = str_replace('/../', '', $params['dir']);
    if ($dir == '/') {
        $dir = '';
    }

    // Check if [plugins] mode is used
    if ($params['template'] == '#plugins') {
        list($lDirs, $lFiles) = admTemplatesWalkPTemplates($dir);
    } else {
        // Check if specified directory exists [ and secure it ]
        $template = str_replace('/', '', $params['template']);
        $dirBase = tpl_dir;

        if (is_dir($dirBase.'/'.$template.'/'.$dir)) {
            $scanDir = $dirBase.'/'.$template.'/'.$dir;
            $d = opendir($scanDir);

            while ($f = readdir($d)) {
                if (($f == '.') || ($f == '..')) {
                    continue;
                }

                if (is_dir($scanDir.'/'.$f)) {
                    $lDirs[] = $f;
                } elseif (is_file($scanDir.'/'.$f)) {
                    $lFiles[] = $f;
                }
            }
            closedir($d);
        }
    }

    // Sort resulting arrays
    natcasesort($lDirs);
    natcasesort($lFiles);

    $result = '';
    if (count($lDirs) || count($lFiles)) {
        $result = '<ul class="jqueryFileTree" style="display: none;">';
        foreach ($lDirs as $x) {
            $result .= '<li class="directory collapsed"><a href="#" rel="'.htmlentities($dir.$x).'/">'.htmlentities($x).'</a></li>';
        }
        foreach ($lFiles as $x) {
            $ext = '';
            if (strrpos($x, '.') > 1) {
                $ext = substr($x, strrpos($x, '.') + 1);
            }
            if (!in_array($ext, ['tpl', 'ini', 'css', 'js', 'gif', 'png', 'jpg'])) {
                $ext = 'file';
            }
            $result .= '<li class="file ext_'.$ext.'"><a href="#" rel="'.htmlentities($dir.$x).'">'.htmlentities($x).'</a></li>';
        }
    }

    return ['status' => 1, 'errorCode' => 0, 'content' => $result];
}

function admTemplatesGetFile($params)
{
    $resultFileName = '';
    $dirBase = '';

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'details')) {
        // ACCESS DENIED
        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied'];
    }

    // Scan incoming params
    if (!is_array($params) || !isset($params['template']) || !isset($params['file']) || !isset($params['token'])) {
        return ['status' => 0, 'errorCode' => 4, 'errorText' => 'Wrong params type'];
    }

    // Check for security token
    if ($params['token'] != genUToken('admin.templates')) {
        return ['status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code'];
    }

    $template = str_replace('/', '', $params['template']);
    $file = str_replace('/../', '', $params['file']);
    $dirBase = tpl_dir;

    if ($template == '#plugins') {
        if (strpos($file, '/') < 1) {
            return ['status' => 0, 'errorCode' => 7, 'errorText' => 'Strange request path'];
        }
        $pluginID = substr($file, 0, strpos($file, '/'));
        $pluginFile = substr($file, strpos($file, '/') + 1);

        $dirBase = extras_dir;
        $resultFileName = $dirBase.'/'.$pluginID.'/tpl/'.$pluginFile;
        $resultFileURL = admin_url.'/plugins/'.$pluginID.'/tpl/'.$pluginFile;
    } else {
        $dirBase = tpl_dir;
        $resultFileName = $dirBase.$template.'/'.$file;
        $resultFileURL = home.'/templates/'.$template.'/'.$file;
    }

    if (!is_file($resultFileName)) {
        return ['status' => 0, 'errorCode' => 6, 'errorText' => 'File not found ['.$resultFileName.']'];
    }

    $ext = '';
    if (strrpos($file, '.') > 1) {
        $ext = substr($file, strrpos($file, '.') + 1);
    }

    $type = 'text';
    if (in_array($ext, ['gif', 'png', 'jpg'])) {
        list($imgW, $imgH, $imgType, $imgAttr) = @getimagesize($resultFileName);
        $data = 'Image size: <b>'.$imgW.' px</b> * <b>'.$imgH.' px</b><br/><img border="1" style="max-height: 500px; max-width: 700px;" src="'.$resultFileURL.'"/>';
        $type = 'image';
    } else {
        $data = file_get_contents($resultFileName);
    }
    $fileTime = @filemtime($resultFileName);
    if ($fileTime > 0) {
        $fileTimeStr = strftime('%d-%m-%Y %H:%M', $fileTime);
    } else {
        $fileTimeStr = 'unknown';
    }

    return ['status' => 1, 'errorCode' => 0, 'content' => $data, 'size' => @filesize($resultFileName), 'lastChange' => $fileTimeStr, 'type' => $type];
}

function admTemplatesUpdateFile($params)
{

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'modify')) {
        // ACCESS DENIED
        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied'];
    }

    // Scan incoming params
    if (!is_array($params) || !isset($params['template']) || !isset($params['file']) || !isset($params['content']) || !isset($params['token'])) {
        return ['status' => 0, 'errorCode' => 4, 'errorText' => 'Wrong params type'];
    }

    // Check for security token
    if ($params['token'] != genUToken('admin.templates')) {
        return ['status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code'];
    }

    $template = str_replace('/', '', $params['template']);
    $file = str_replace('/../', '', $params['file']);
    $dirBase = tpl_dir;

    if ($template == '#plugins') {
        if (strpos($file, '/') < 1) {
            return ['status' => 0, 'errorCode' => 7, 'errorText' => 'Strange request path'];
        }
        $pluginID = substr($file, 0, strpos($file, '/'));
        $pluginFile = substr($file, strpos($file, '/') + 1);

        $dirBase = extras_dir;
        $resultFileName = $dirBase.'/'.$pluginID.'/tpl/'.$pluginFile;
        $resultFileURL = admin_url.'/plugins/'.$pluginID.'/tpl/'.$pluginFile;
    } else {
        $dirBase = tpl_dir;
        $resultFileName = $dirBase.$template.'/'.$file;
        $resultFileURL = home.'/templates/'.$template.'/'.$file;
    }

    if (!is_file($resultFileName)) {
        return ['status' => 0, 'errorCode' => 6, 'errorText' => 'File does not exists ['.$resultFileName.']'];
    }

    if (!is_writable($resultFileName)) {
        return ['status' => 0, 'errorCode' => 8, 'errorText' => 'Dont have write privileges for ['.$resultFileName.']'];
    }

    $newData = $params['content'];
    $origData = file_get_contents($resultFileName);

    // Notify if file was not changed
    if ($newData == $origData) {
        return ['status' => 1, 'errorCode' => 0, 'content' => 'File was not modified'];
    }

    if (($fp = @fopen($resultFileName, 'wb+')) !== false) {
        fwrite($fp, $newData);
        fclose($fp);

        return ['status' => 1, 'errorCode' => 0, 'content' => 'Update complete ['.$resultFileName.']'];
    }

    return ['status' => 0, 'errorCode' => 9, 'errorText' => 'Error writing into file ['.$resultFileName.']'];
}

if (function_exists('rpcRegisterAdminFunction')) {
    rpcRegisterAdminFunction('admin.templates.listFiles', 'admTemplatesListFiles');
    rpcRegisterAdminFunction('admin.templates.getFile', 'admTemplatesGetFile');
    rpcRegisterAdminFunction('admin.templates.updateFile', 'admTemplatesUpdateFile');
}
