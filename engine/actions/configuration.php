<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: configuration.php
// Description: Configuration managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('configuration', 'admin');

function twigmkSelect($params)
{
    $values = '';
    if (isset($params['values']) && is_array($params['values'])) {
        foreach ($params['values'] as $k => $v) {
            $values .= '<option value="'.$k.'"'.(($k == $params['value']) ? ' selected="selected"' : '').'>'.$v.'</option>';
        }
    }

    return '<select '.((isset($params['id']) && $params['id']) ? 'id="'.$params['id'].'" ' : '').'name="'.$params['name'].'">'.$values.'</select>';
}

function twigmkSelectYN($params)
{
    global $lang;
    $params['values'] = [1 => $lang['yesa'], 0 => $lang['noa']];

    return twigmkSelect($params);
}

function twigmkSelectNY($params)
{
    global $lang;
    $params['values'] = [0 => $lang['noa'], 1 => $lang['yesa']];

    return twigmkSelect($params);
}

$twig->addFunction(new \Twig\TwigFunction('mkSelect', 'twigmkSelect'));
$twig->addFunction(new \Twig\TwigFunction('mkSelectYN', 'twigmkSelectYN'));
$twig->addFunction(new \Twig\TwigFunction('mkSelectNY', 'twigmkSelectNY'));

//
// Save system config
function systemConfigSave()
{
    global $lang, $config, $mysql, $notify;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'modify')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);
        ngSYSLOG(['plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id], ['action' => 'saveConfig'], null, [0, 'SECURITY.PERM']);

        return false;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.configuration'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);
        ngSYSLOG(['plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id], ['action' => 'saveConfig'], null, [0, 'SECURITY.TOKEN']);

        return false;
    }

    $save_con = $_REQUEST['save_con'];
    if (is_null($save_con) || !is_array($save_con)) {
        return false;
    }

    // Check if DB connection params are correct
    try {
        $sx = NGEngine::getInstance();
        $sx->set('db', new NGPDO(['host' => $save_con['dbhost'], 'user' => $save_con['dbuser'], 'pass' => $save_con['dbpasswd'], 'db' => $save_con['dbname']]));

        $sx->set('legacyDB', new NGLegacyDB(false));
        $sx->getLegacyDB()->connect('', '', '');
        $sqlTest = $sx->getLegacyDB();
    } catch (Exception $e) {
        msgSticker($lang['dbcheck_error'], 'error');

        return false;
    }

    // Save our UUID or regenerate LOST UUID
    $save_con['UUID'] = $config['UUID'];
    if ($save_con['UUID'] == '') {
        $save_con['UUID'] = md5(mt_rand().mt_rand()).md5(mt_rand().mt_rand());
    }

    // Manage "load_profiler" variable
    $save_con['load_profiler'] = intval($save_con['load_profiler']);
    if (($save_con['load_profiler'] > 0) && ($save_con['load_profiler'] < 86400)) {
        $save_con['load_profiler'] = time() + $save_con['load_profiler'];
    } else {
        $save_con['load_profiler'] = 0;
    }

    // Prepare resulting config content
    $fcData = "<?php\n".'$config = '.var_export($save_con, true)."\n;?>";

    // Try to save config
    $fcHandler = @fopen(confroot.'config.php', 'w');
    if ($fcHandler) {
        fwrite($fcHandler, $fcData);
        fclose($fcHandler);

        msgSticker($lang['msgo_saved']);
    //msg(array("text" => $lang['msgo_saved']));
    } else {
        msg(['type' => 'error', 'text' => $lang['msge_save_error'], 'info' => $lang['msge_save_error#desc']]);

        return false;
    }

    ngSYSLOG(['plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id], ['action' => 'saveConfig', 'list' => $fcData], null, [1, '']);

    return true;
}

//
// Show configuration form
function systemConfigEditForm()
{
    global $lang, $AUTH_CAPABILITIES, $PHP_SELF, $twig, $multiconfig;

    // Check for token
    if (!checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'details')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);
        ngSYSLOG(['plugin' => '#admin', 'item' => 'configuration', 'ds_id' => $id], ['action' => 'showConfig'], null, [0, 'SECURITY.PERM']);

        return false;
    }

    $auth_modules = [];
    $auth_dbs = [];

    foreach ($AUTH_CAPABILITIES as $k => $v) {
        if ($v['login']) {
            $auth_modules[$k] = $k;
        }
        if ($v['db']) {
            $auth_dbs[$k] = $k;
        }
    }

    // Load config file from configuration
    // Now in $config we have original version of configuration data
    include confroot.'config.php';

    $load_profiler = $config['load_profiler'] - time();
    if (($load_profiler < 0) || ($load_profiler > 86400)) {
        $config['load_profiler'] = 0;
    }

    $mConfig = [];
    if (is_array($multiconfig)) {
        foreach ($multiconfig as $k => $v) {
            $v['key'] = $k;
            $mConfig[] = $v;
        }
    }

    // Set default timeZone if it's empty
    if (!$config['timezone']) {
        $config['timezone'] = 'Europe/Moscow';
    }

    $tVars = [
        //	SYSTEM CONFIG is available via `config` variable
        'config'                => $config,
        'list'                  => [
            'captcha_font' => ListFiles('trash', 'ttf'),
            'theme'        => ListFiles('../templates', ''),
            'default_lang' => ListFiles('lang', ''),
            'wm_image'     => ListFiles('trash', ['gif', 'png'], 2),
            'auth_module'  => $auth_modules,
            'auth_db'      => $auth_dbs,
            'timezoneList' => timezone_identifiers_list(),
        ],
        'php_self'              => $PHP_SELF,
        'timestamp_active_now'  => LangDate($config['timestamp_active'], time()),
        'timestamp_updated_now' => LangDate($config['timestamp_updated'], time()),
        'token'                 => genUToken('admin.configuration'),
        'multiConfig'           => $mConfig,
    ];

    //
    // Fill parameters for multiconfig
    //
    $multiList = [];

    $tmpline = '';
    if (is_array($multiconfig)) {
        foreach ($multiconfig as $mid => $mline) {
            $tmpdom = implode("\n", $mline['domains']);
            $tmpline .= "<tr class='contentEntry1'><td>".($mline['active'] ? 'On' : 'Off')."</td><td>$mid</td><td>".($tmpdom ? $tmpdom : '-не указано-')."</td><td>&nbsp;</td></tr>\n";
        }
    }
    $tvars['vars']['multilist'] = $tmpline;
    $tvars['vars']['defaultSection'] = (isset($_REQUEST['selectedOption']) && $_REQUEST['selectedOption']) ? htmlspecialchars($_REQUEST['selectedOption'], ENT_COMPAT | ENT_HTML401, 'UTF-8') : 'news';

    $xt = $twig->loadTemplate('skins/default/tpl/configuration.tpl');

    return $xt->render($tVars);
}

//
//
// Check if SAVE is requested and SAVE was successfull
if (isset($_REQUEST['subaction']) && ($_REQUEST['subaction'] == 'save') && ($_SERVER['REQUEST_METHOD'] == 'POST') && systemConfigSave()) {
    @include confroot.'config.php';
}

// Show configuration form
$main_admin = systemConfigEditForm();
