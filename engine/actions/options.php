<?php

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

LoadLang('options', 'admin', 'options');

$tVars = [
    'php_self' => $PHP_SELF,
    'perm'     => [
        'static'        => checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'view'),
        'categories'    => checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'view'),
        'addnews'       => checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'add'),
        'editnews'      => (checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'personal.list') || checkPermission(['plugin' => '#admin', 'item' => 'news'], null, 'other.list')),
        'configuration' => checkPermission(['plugin' => '#admin', 'item' => 'configuration'], null, 'details'),
        'dbo'           => checkPermission(['plugin' => '#admin', 'item' => 'dbo'], null, 'details'),
        'cron'          => checkPermission(['plugin' => '#admin', 'item' => 'cron'], null, 'details'),
        'rewrite'       => checkPermission(['plugin' => '#admin', 'item' => 'rewrite'], null, 'details'),
        'templates'     => checkPermission(['plugin' => '#admin', 'item' => 'templates'], null, 'details'),
        'ipban'         => checkPermission(['plugin' => '#admin', 'item' => 'ipban'], null, 'view'),
        'users'         => checkPermission(['plugin' => '#admin', 'item' => 'users'], null, 'view'),
    ],
];

$xt = $twig->loadTemplate('skins/default/tpl/options.tpl');
$main_admin = $xt->render($tVars);
