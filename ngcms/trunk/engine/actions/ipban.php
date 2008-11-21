<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: ipban.php
// Description: adding news
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('ipban', 'admin');

if ($action == "add") {
        $add_ip = trim($_REQUEST['add_ip']);
        $desc = $_REQUEST['desc'];

	if (preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/',$add_ip, $match) && (($match[1]<256) && ($match[2]<256) && ($match[3]<256) && ($match[4]<256))) {
		if ($mysql->record("select ip from ".prefix."_ipban where ip = ".db_squote($add_ip))) {
			msg(array("type" => "error", "text" => $lang['msge_exists'], "info" => $lang['msgi_exists']));
		} else {
			$mysql->query("insert into `".prefix."_ipban` (`ip`,`descr`,`counter`) VALUES (".db_squote($add_ip).",".db_squote(secure_html($desc)).",'0')");
			msg(array("text" => sprintf($lang['msgo_blocked'], $add_ip)));
		}
	}
	else {
		msg(array("type" => "error", "text" => $lang['msge_field'], "info" => $lang['msgi_field']));
	}
}
elseif ($action == "remove") {
        $remove_ip = trim($_REQUEST['remove_ip']);
	if ($remove_ip) {
		$mysql->query("delete from ".prefix."_ipban where ip=".db_squote($remove_ip));
		msg(array("text" => sprintf($lang['msgo_unblocked'], $remove_ip)));
	}
	else {
		msg(array("type" => "error", "text" => $lang['msge_field'], "info" => $lang['msgi_field']));
	}
}

foreach ($mysql->select("select * from ".prefix."_ipban order by ip") as $row) {
	$tpl -> template('entries', tpl_actions.$mod);
	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'ip'		=>	$row['ip'],
		'descr'		=>	$row['descr'],
		'counter'	=>	$row['counter']
	);
	$tpl -> vars('entries', $tvars);
	$entries .= $tpl -> show('entries');
}

$tpl -> template('table', tpl_actions.$mod);
$tvars['vars'] = array('php_self' => $PHP_SELF, 'entries' => $entries);
$tpl -> vars('table', $tvars);
echo $tpl -> show('table');
