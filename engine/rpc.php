<?php

//
// Copyright (C) 2006-2020 Next Generation CMS (http://ngcms.ru)
// Name: rpc.php
// Description: Service functions controller
// Author: NGCMS Development Team
//

@include_once 'core.php';

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// Load additional handlers [ common ]
loadActionHandlers('rpc');
loadActionHandlers('rpc:'.(is_array($userROW) ? 'active' : 'inactive'));

// Function to preload ADMIN rpc funcs
function loadAdminRPC($mod)
{
    if (in_array($mod, ['categories', 'extras', 'files', 'templates', 'configuration', 'statistics'])) {
        @include_once './actions/'.$mod.'.rpc.php';

        return true;
    }

    return false;
}

// Register RPC ADMIN function
function rpcRegisterAdminFunction($name, $instance, $permanent = false)
{
    global $RPCADMFUNC;
    $RPCADMFUNC[$name] = $instance;
}

//
// We support two types of RPC calls: HTTP/JSON-RPC and XML-RPC
//

if (isset($_REQUEST['json']) || isset($_GET['methodName'])) {
    processJSON();
} else {
    echo '200: Method is not supported.';
}

//
// HTTP/JSON-RPC processor
//
function processJSON()
{
    global $RPCFUNC, $RPCADMFUNC;

    // Set correct content/type
    header('Content-Type: application/json; charset=UTF-8');

    // Decode passed params
    $params = json_decode($_POST['params'], true);

    $methodName = (isset($_POST['methodName'])) ? $_POST['methodName'] : (isset($_GET['methodName']) ? $_GET['methodName'] : '');
    switch ($methodName) {
        case 'admin.rewrite.submit':
            $out = rpcRewriteSubmit($params);
            break;
        case 'core.users.search':
            $out = rpcAdminUsersSearch($params);
            break;
        case 'core.registration.checkParams':
            $out = coreCheckRegParams($params);
            break;
        default:
            if (isset($RPCFUNC[$methodName])) {
                $out = call_user_func($RPCFUNC[$methodName], $params);
            } elseif (preg_match('#^plugin\.(.+?)\.#', $methodName, $m) && loadPlugin($m[1], 'rpc') && isset($RPCFUNC[$methodName])) {
                // If method "plugin.NAME.something" is called, try to load action "rpc" for plugin "NAME"
                $out = call_user_func($RPCFUNC[$methodName], $params);
            } elseif (preg_match('#^admin\.(.+?)\.#', $methodName, $m) && loadAdminRPC($m[1]) && isset($RPCADMFUNC[$methodName])) {
                // If method "plugin.NAME.something" is called, try to load action "rpc" for plugin "NAME"
                $out = call_user_func($RPCADMFUNC[$methodName], $params);
            } else {
                $out = rpcDefault($methodName, $params);
                break;
            }
    }
    //print "<pre>JSON OUTPUT: ".json_encode($out)."</pre>";
    // Print output
    echo json_encode($out);
}

//
//
//
function rpcDefault($methodName = '', $params = [])
{
    return ['status' => 0, 'errorCode' => 1, 'errorText' => 'rpcDefault: method ['.$methodName.'] is unknown'];
}

//
// RPC function: rewrite.submit
// Description : Submit changes into REWRITE library
function rpcRewriteSubmit($params)
{
    global $userROW;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'rewrite'], null, 'modify')) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'rewrite'], ['action' => 'modify'], null, [0, 'SECURITY.PERM']);

        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied (perm)'];
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.rewrite'))) {
        ngSYSLOG(['plugin' => '#admin', 'item' => 'rewrite'], ['action' => 'modify'], null, [0, 'SECURITY.TOKEN']);

        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied (token)'];
    }

    @include_once 'includes/classes/uhandler.class.php';
    $ULIB = new urlLibrary();
    $ULIB->loadConfig();

    $UHANDLER = new urlHandler();
    $UHANDLER->loadConfig();

    // Scan incoming params
    if (!is_array($params)) {
        return ['status' => 0, 'errorCode' => 999, 'errorText' => 'Wrong params type'];
    }

    $hList = [];

    // Scan all params
    foreach ($params as $pID => $pData) {
        // Skip empty elements
        if ($pData == null) {
            continue;
        }

        $rcall = $UHANDLER->populateHandler($ULIB, $pData);
        if ($rcall[0][0]) {
            // Error
            return ['status' => 0, 'errorCode' => 4, 'errorText' => 'Parser error: '.$rcall[0][1], 'recID' => $pID];
        }
        $hList[] = $rcall[1];
    }

    // Now let's overwrite current config
    $UHANDLER->hList = [];
    foreach ($hList as $handler) {
        $UHANDLER->registerHandler(-1, $handler);
    }
    if (!$UHANDLER->saveConfig()) {
        return ['status' => 0, 'errorCode' => 5, 'errorText' => 'Error writing to disk'];
    }

    ngSYSLOG(['plugin' => '#admin', 'item' => 'rewrite'], ['action' => 'modify', 'list' => $params], null, [1, '']);

    return ['status' => 1, 'errorCode' => 0, 'errorText' => var_export($rcall[1], true)];
}

// Admin panel: search for users
function rpcAdminUsersSearch($params)
{
    global $userROW, $mysql, $lang;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'users'], $userROW, 'view')) {
        // ACCESS DENIED
        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied'];
    }

    $searchName = $params;

    // Check search mode
    // ! - show TOP users by posts
    if ($searchName == '!') {
        $SQL = 'select name, news from '.uprefix.'_users where news > 0 order by news desc limit 20';
    } else {
        // Return a list of users
        $SQL = 'select name, news from '.uprefix.'_users where name like '.db_squote('%'.$searchName.'%').' and news > 0 order by news desc limit 20';
    }

    // Scan incoming params
    $output = [];
    foreach ($mysql->select($SQL) as $row) {
        $output[] = [$row['name'], $row['news'].' '.$lang['news']];
    }

    return ['status' => 1, 'errorCode' => 0, 'data' => [$params, $output]];
}

// Online check if registration params are correct (login, email,...)
function coreCheckRegParams($params)
{
    global $config, $AUTH_METHOD;

    // Scan incoming params
    if (!is_array($params)) {
        return ['status' => 0, 'errorCode' => 999, 'errorText' => 'Wrong params type'];
    }

    $auth = $AUTH_METHOD[$config['auth_module']];
    if (method_exists($auth, 'onlineCheckRegistration')) {
        $output = $auth->onlineCheckRegistration($params);
    }

    return ['status' => 1, 'errorCode' => 0, 'data' => $output];
}
