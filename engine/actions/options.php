<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('options', 'admin');

$tpl -> template('options', tpl_actions);
$tvars['vars'] = array('php_self' => $PHP_SELF);
$tpl -> vars('options', $tvars);
echo $tpl -> show('options');
