<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: configuration.rpc.php
// Description: RPC library for CONFIGURATION module
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    die('HAL');
}

// Load library
@include_once root . 'includes/classes/upload.class.php';
$lang = LoadLang('configuration', 'admin');

function admConfigurationTestDB($params)
{

    global $mysql, $userROW, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

    if (!is_array($userROW)) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
    }

    // Check for permissions
    if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
    }

    if (!is_array($params) || !isset($params['dbhost']) || !isset($params['dbname']) || !isset($params['dbpasswd']) || !isset($params['dbname'])) {
        return array('status' => 0, 'errorCode' => 2, 'errorText' => 'Wrong params type');
    }

    if ($params['token'] != genUToken('admin.configuration')) {
        return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Wrong security code');
    }

    // Check if DB connection params are correct
    try {
        $sx = NGEngine::getInstance();
        
        switch ($params['dbtype']) {
            case 'mysqli':
                $sx->set('db', new NGMYSQLi(array('host' => $params['dbhost'], 'user' => $params['dbuser'], 'pass' => $params['dbpasswd'], 'db' => $params['dbname'])));
                break;
            case 'pdo':
                $sx->set('db', new NGPDO(array('host' => $params['dbhost'], 'user' => $params['dbuser'], 'pass' => $params['dbpasswd'], 'db' => $params['dbname'])));
                break;
        }
        
        $sx->set('legacyDB', new NGLegacyDB(false));
        $sx->getLegacyDB()->connect('', '', '');
        $sqlTest = $sx->getLegacyDB();
    } catch (Exception $e) {
        switch ($e->getCode()) {
            case 1045:
                return array('status' => 0, 'errorCode' => 4, 'errorText' => $lang['dbcheck_noconnect']);
            break;
            case 1049:
                return array('status' => 0, 'errorCode' => 5, 'errorText' => $lang['dbcheck_nodb']);
            break;
            default:
                return array('status' => 0, 'errorCode' => 5, 'errorText' => $e->getCode().' '. $lang['dbcheck_noconnect']);
            break;
        }
    }
    
    return (array('status' => 1, 'errorCode' => 0, 'errorText' => $lang['dbcheck_ok']));
}

function admConfigurationTestMemcached($params)
{

    global $mysql, $userROW, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

    if (!is_array($userROW)) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
    }

    // Check for permissions
    if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => 'Permission denied');
    }

    if (!is_array($params) || !isset($params['ip']) || !isset($params['port']) || !isset($params['prefix'])) {
        return array('status' => 0, 'errorCode' => 2, 'errorText' => 'Wrong params type');
    }

    if ($params['token'] != genUToken('admin.configuration')) {
        return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Wrong security code');
    }

    // Check if DB connection params are correct
    if (!extension_loaded('memcached') || !class_exists('Memcached')) {
        return array('status' => 0, 'errorCode' => 4, 'errorText' => $lang['memcached_noextension']);
    }

    // Connect to Memcached
    $cacheTest = new cacheClassMemcached(array('prefix' => $params['prefix']));
    $cacheTest->connect($params['ip'], $params['port']);

    // Try to set some value
    $testValue = uniq(time());
    $cacheTest->set('#core', 'connTester', $testValue);

    // Check result code
    if ($cacheTest->getResultCode() != 0) {
        return array('status' => 0, 'errorCode' => 5, 'errorText' => $lang['memcached_error'] . ' [' . $cacheTest->getResultCode() . ']: ' . $cacheTest->getResultMessage());
    }

    // Compare SET == GET values
    if ($cacheTest->get('#core', 'connTest') != $testValue) {
        // Some problems
        return array('status' => 0, 'errorCode' => 6, 'errorText' => $lang['memcached_error_values']);
    }

    return (array('status' => 1, 'errorCode' => 0, 'errorText' => $lang['memcached_ok']));
}

// Test e-mail message sending
function admConfigurationTestEMail($params)
{

    global $mysql, $userROW, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

    if (!is_array($userROW)) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => $lang['permission_denied']);
    }

    // Check for permissions
    if (!checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'modify')) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => $lang['permission_denied']);
    }

    // Check if requred params are sent
    if (!is_array($params['from']) || !is_array($params['to']) || !$params['from']['email'] || !$params['to']['email']) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => $lang['mail_address_not_specified']);
    }

    if ($params['token'] != genUToken('admin.configuration')) {
        return array('status' => 0, 'errorCode' => 3, 'errorText' => $lang['wrong_security_code']);
    }

    // Init $mail client
    $mail = new PHPMailer();

    $fromName = ($params['from']['name'] ? $params['from']['name'] : 'NGCMS Mail Agent');

    $mail->setFrom($params['from']['email'], $fromName);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = str_replace(array('{server_name}'), array($_SERVER['SERVER_NAME']), $lang['smtp_test_subject']);
    $mail->AddAddress($params['to']['email'], $params['to']['email']);
    $mail->ContentType = 'text/html';
    $mail->Body = str_replace(array('{email}', '{server_name}'), array($params['to']['email'], $_SERVER['SERVER_NAME']), $lang['smtp_test_body']);

    $sendResult = false;
    switch ($params['mode']) {
        default:
        case 'mail':
            $mail->isMail();
            $sendResult = $mail->send();
            break;
        case 'sendmail':
            $mail->isSendmail();
            $sendResult = $mail->send();
            break;
        case 'smtp':
            if (!$params['smtp']['host'] || !$params['smtp']['port']) {
                return array('status' => 0, 'errorCode' => 1, 'errorText' => $lang['smtp_test_not_specified']);
            }
            $mail->isSMTP();
            $mail->Host = $params['smtp']['host'];
            $mail->Port = $params['smtp']['port'];
            $mail->SMTPAuth = ($params['smtp']['auth']) ? true : false;
            $mail->Username = $params['smtp']['login'];
            $mail->Password = $params['smtp']['pass'];
            $mail->SMTPSecure = $params['smtp']['secure'];
            $sendResult = $mail->send();
            break;
    }

    if (!$sendResult) {
        return array('status' => 0, 'errorCode' => 1, 'errorText' => $lang['smtp_test_error'] . ' ' . $mail->ErrorInfo);
    }

    return (array('status' => 1, 'errorCode' => 0, 'errorText' => str_replace(array('{email}'), array($params['to']['email']), $lang['smtp_test_successfully'])));

}

if (function_exists('rpcRegisterAdminFunction')) {
    rpcRegisterAdminFunction('admin.configuration.dbCheck', 'admConfigurationTestDB');
    rpcRegisterAdminFunction('admin.configuration.memcachedCheck', 'admConfigurationTestMemcached');
    rpcRegisterAdminFunction('admin.configuration.emailCheck', 'admConfigurationTestEMail');
}
