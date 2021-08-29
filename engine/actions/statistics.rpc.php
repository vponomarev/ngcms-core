<?php

//
// Copyright (C) 2020-2020 Next Generation CMS (http://ngcms.ru/)
// Name: statistics.rpc.php
// Description: RPC library for STATISTICS module
// Author: NGCMS Development Team
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// ==============================================================
//  Module functions
// ==============================================================
@include_once root.'includes/inc/httpget.inc.php';

// Calculate cache size
function getCacheSize($params)
{

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'cache'], null, 'modify')) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'cache'], ['action' => 'getCacheSize'], null, [0, 'SECURITY.PERM']);

        return ['status' => 0, 'errorCode' => 2, 'errorText' => 'Access denied (perm)'];
    }

    // Check for security token
    if ((!isset($params['token'])) || ($params['token'] != genUToken('admin.statistics'))) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'rewrite'], ['action' => 'modify'], null, [0, 'SECURITY.TOKEN']);

        return ['status' => 0, 'errorCode' => 2, 'errorText' => 'Access denied (token)'];
    }

    $dir = root.'cache/';
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

    $stat = [
        'numFiles' => 0,
        'numDir'   => 0,
        'size'     => 0,
        'error'    => '',
    ];

    try {
        foreach ($files as $fname => $fileinfo) {
            // Skip .htaccess
            if ($fileinfo->getFilename() == '.htaccess') {
                continue;
            }

            if ($fileinfo->isDir()) {
                $stat['numDir']++;
            } else {
                $stat['numFiles']++;
                $stat['size'] += filesize($fname);
            }
        }
    } catch (UnexpectedValueException $e) {
        $stat['error'] = $e->getMessage(); //'Error reading data from cache directory';

        return ['status' => 0, 'errorCode' => 1, 'errorText' => $stat['error']];
    }

    return ['status' => 1, 'errorCode' => 0, 'errorText' => 'Done', 'numFiles' => $stat['numFiles'], 'numDir' => $stat['numDir'], 'size' => Formatsize($stat['size'])];
}

// Clean file cache
function cleanCache($params)
{

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'cache'], null, 'modify')) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'cache'], ['action' => 'getCacheSize'], null, [0, 'SECURITY.PERM']);

        return ['status' => 0, 'errorCode' => 2, 'errorText' => 'Access denied (perm)'];
    }

    // Check for security token
    if ((!isset($params['token'])) || ($params['token'] != genUToken('admin.statistics'))) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'rewrite'], ['action' => 'modify'], null, [0, 'SECURITY.TOKEN']);

        return ['status' => 0, 'errorCode' => 2, 'errorText' => 'Access denied (token)'];
    }

    $dir = root.'cache/';
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

    try {
        foreach ($files as $fname => $fileinfo) {
            // Skip .htaccess
            if ($fileinfo->getFilename() == '.htaccess') {
                continue;
            }

            if ($fileinfo->isDir()) {
                rmdir($fname);
            } else {
                unlink($fname);
            }
        }
    } catch (UnexpectedValueException $e) {
        $stat['error'] = $e->getMessage(); //'Error reading data from cache directory';

        return ['status' => 0, 'errorCode' => 1, 'errorText' => $stat['error']];
    }

    return ['status' => 1, 'errorCode' => 0, 'errorText' => 'Done'];
}

function getVersionInfo()
{
    global $config;
    if (($vms = cacheRetrieveFile('coreversion.dat', 86400)) === false) {
        $paramList = ['ver' => urlencode(engineVersion), 'type' => urlencode(engineVersionType), 'build' => urlencode(engineVersionBuild), 'uuid' => $config['UUID'], 'pdo' => ((extension_loaded('PDO') && extension_loaded('pdo_mysql') && class_exists('PDO')) ? 'yes' : 'no')];
        $req = new http_get();
        $vms = $req->get('http://ngcms.ru/sync/versionInfo.php'.'?'.http_build_query($paramList), 1, 1);
        cacheStoreFile('coreversion.dat', $vms);
    }

    return $vms;
}

function coreVersionSync($params)
{
    getVersionInfo();

    return ['status' => 1, 'errorCode' => 0, 'errorText' => 'Done'];
}

if (function_exists('rpcRegisterAdminFunction')) {
    rpcRegisterAdminFunction('admin.statistics.getCacheSize', 'getCacheSize');
    rpcRegisterAdminFunction('admin.statistics.cleanCache', 'cleanCache');
    rpcRegisterAdminFunction('admin.statistics.coreVersionSync', 'coreVersionSync');
}
