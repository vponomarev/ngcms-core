<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: pm.php
// Description: adding news
// Author: Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('pm', 'admin');


function pm_send() {
	global $mysql, $config, $lang, $userROW;

	$time = time() + ($config['date_adjust'] * 60);

	$sendto  = trim($_REQUEST['sendto']);
	$title   = secure_html($_REQUEST['title']);
	$content = $_REQUEST['content'];

	if (!$title || strlen($title) > "50") {
		msg(array("type" => "error", "text" => $lang['msge_title'], "info" => $lang['msgi_title']));
		return;
	}
	if (!$content || strlen($content) > "3000") {
		msg(array("type" => "error", "text" => $lang['msge_content'], "info" => $lang['msgi_content']));
		return;
	}

	if ($sendto && ($torow = $mysql->record("select * from ".uprefix."_users where ".(is_numeric($sendto)?"id = ".db_squote($sendto):"name = ".db_squote($sendto))))) {
		$content = secure_html(trim($content));
		exec_acts('pm_send');
		$mysql->query("insert into ".uprefix."_users_pm (from_id, to_id, pmdate, title, content) VALUES (".db_squote($userROW['id']).", ".db_squote($torow['id']).", ".db_squote($time).", ".db_squote($title).", ".db_squote($content).")");
		msg(array("text" => $lang['msgo_sent']));
	} else {
		msg(array("type" => "error", "text" => $lang['msge_nouser'], "info" => $lang['msgi_nouser']));
	}
}


function pm_list (){
	global $mysql, $config, $lang, $userROW, $tpl, $mod;

	foreach($mysql->select("select * from ".uprefix."_users_pm pm left join ".uprefix."_users u on pm.from_id=u.id where pm.to_id = ".db_squote($userROW['id'])." order by pmid desc limit 0, 30") as $row) {
		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'pmid'		=>	$row['pmid'],
			'pmdate'	=>	LangDate($config['timestamp_comment'], $row['pmdate']),
			'title'		=>	$row['title'],
			'link'		=>	(!$row['name']) ? $lang['messaging'] : '<a href="'.GetLink('user', $row).'">'.$row['name'].'</a>',
			'viewed'	=>	$row['viewed'] = ($row['viewed'] == 1 ? $lang["viewed"] : "<font color=green><b>$lang[unviewed]</b></font>")
		);
		$tpl -> template('entries', tpl_actions.$mod);
		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	$tpl -> template('table', tpl_actions.$mod);
	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'entries'	=>	$entries
	);
	exec_acts('pm');
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}

function pm_read(){
	global $mysql, $config, $lang, $userROW, $tpl, $mod, $parse;

	$pmid = $_REQUEST['pmid'];
	if ($row = $mysql->record("select * from ".uprefix."_users_pm where pmid = ".db_squote($pmid)."and (to_id = ".db_squote($userROW['id'])." or from_id=".db_squote($userROW['id']).")")) {
		$tpl -> template('read', tpl_actions.$mod);
		$tvars['vars'] = array(
			'php_self'		=>	$PHP_SELF,
			'pmid'			=>	$row['pmid'],
			'title'			=>	$row['title'],
			'from'			=>	$row['from'],
			'content'		=>	$parse->htmlformatter($parse->smilies($parse->bbcodes($row['content'])))
		);
		exec_acts('pm_read');
		$tpl -> vars('read', $tvars);
		echo $tpl -> show('read');
		if ((!$row['viewed'])&&($row['to_id'] == $userROW['id'])) {
			$mysql->query("update ".uprefix."_users_pm set `viewed` = '1' WHERE `pmid` = ".db_squote($row['pmid']));
		}
	} else {
		msg(array("type" => "error", "text" => $lang['msge_bad']));
	}
}

function pm_reply(){
	global $mysql, $config, $lang, $userROW, $tpl, $mod, $parse;

	$pmid = $_REQUEST['pmid'];
	if ($row = $mysql->record("select * from ".uprefix."_users_pm where pmid = ".db_squote($pmid)."and (to_id = ".db_squote($userROW['id'])." or from_id=".db_squote($userROW['id']).")")) {
		if (!$row['from_id']) {
			msg(array("type" => "error", "text" => $lang['msge_reply']));
			return;
		}

		$tpl -> template('reply', tpl_actions.$mod);
		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'pmid'		=>	$row['pmid'],
			'title'		=>	'Re:'.$row['title'],
			'sendto'	=>	$row['from_id'],
			'quicktags'	=>	QuickTags(false, "pmmes")
		);
		$tvars['vars']['smilies'] = ($config['use_smilies'] == "1") ? InsertSmilies("content", 10) : '';
		exec_acts('pm_reply');
		$tpl -> vars('reply', $tvars);
		echo $tpl -> show('reply');
	} else {
		msg(array("type" => "error", "text" => $lang['msge_bad']));
	}
}

function pm_write(){
	global $mysql, $config, $lang, $userROW, $tpl, $mod;

	$tpl -> template('write', tpl_actions.$mod);
	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'quicktags'	=>	QuickTags(false, "pmmes")
	);
	$tvars['vars']['smilies'] = ($config['use_smilies'] == "1") ? InsertSmilies("content", 10) : '';
	exec_acts('pm_write');
	$tpl -> vars('write', $tvars);
	echo $tpl -> show('write');
}

function pm_delete(){
	global $mysql, $config, $lang, $userROW, $tpl, $mod;

	$selected_pm = $_REQUEST['selected_pm'];

	if(!$selected_pm) {
		msg(array("type" => "error", "text" => $lang['msge_select']));
		return;
	}
	foreach ($selected_pm as $id) {
		$mysql->query("delete from ".uprefix."_users_pm where `pmid`=".db_squote($id)." and (from_id=".db_squote($userROW['id'])." or to_id=".db_squote($userROW['id']).")");
	}
	msg(array("text" => $lang['msgo_deleted']));
}

switch($action){
	case "read"   : pm_read();   break;
	case "reply"  : pm_reply();  break;
	case "send"   : pm_send();   break;
	case "write"  : pm_write();  break;
	case "delete" : pm_delete(); break;
	default       : pm_list();
}
