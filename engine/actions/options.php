<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadLang('options', 'admin', 'options');

$tVars = array(
	'php_self' => $PHP_SELF,
	'perm'     => array(
		'static'        => checkPermission(array('plugin' => '#admin', 'item' => 'static'), null, 'view'),
		'categories'    => checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'view'),
		'addnews'       => checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, 'add'),
		'editnews'      => (checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, 'personal.list') || checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, 'other.list')),
		'configuration' => checkPermission(array('plugin' => '#admin', 'item' => 'configuration'), null, 'details'),
		'dbo'           => checkPermission(array('plugin' => '#admin', 'item' => 'dbo'), null, 'details'),
		'cron'          => checkPermission(array('plugin' => '#admin', 'item' => 'cron'), null, 'details'),
		'rewrite'       => checkPermission(array('plugin' => '#admin', 'item' => 'rewrite'), null, 'details'),
		'templates'     => checkPermission(array('plugin' => '#admin', 'item' => 'templates'), null, 'details'),
		'ipban'         => checkPermission(array('plugin' => '#admin', 'item' => 'ipban'), null, 'view'),
		'users'         => checkPermission(array('plugin' => '#admin', 'item' => 'users'), null, 'view'),
	),
);

$xt = $twig->loadTemplate('skins/default/tpl/options.tpl');
$main_admin = $xt->render($tVars);
