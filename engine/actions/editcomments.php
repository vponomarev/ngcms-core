<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: editcomments.php
// Description: News comments managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('editcomments', 'admin');


// List comments
function commentsList($newsID){
	global $mysql;


}

if ($subaction == "doeditcomment") {
	if (!trim($_REQUEST['poster'])) {
		msg(array("type" => "error", "text" => $lang['msge_namefield']));
	}
	else {
		$comment = str_replace("{","&#123;",str_replace("\r\n", "<br />", htmlspecialchars(trim($_REQUEST['comment']))));
		$content = str_replace("{","&#123;",str_replace("\r\n", "<br />", htmlspecialchars(trim($_REQUEST['content']))));

		$mail = trim($_REQUEST['mail']);

		$mysql->query("UPDATE ".prefix."_comments SET mail=".db_squote($mail).", text=".db_squote($comment).", answer=".db_squote($content).", name=".db_squote($userROW['name'])." WHERE id=".db_squote($comid));

		if ($content && $_REQUEST['send_notice'] && $mail) {
			$row = $mysql->record("select * from ".prefix."_news where id=".db_squote($newsid));
			$newsLink = $config['home_url'].newsGenerateLink($row);
			zzMail($mail, $lang['comanswer'], sprintf($lang['notice'], $userROW['name'], $content, $newsLink), 'html');
		}

		msg(array("text" => $lang['msgo_saved']));
	}
}

if ($subaction == "deletecomment") {
	if ($row = $mysql->record("select * from ".prefix."_comments where id=".db_squote($comid))) {
		$mysql->query("delete from ".prefix."_comments where id=".db_squote($comid));
		$mysql->query("update ".uprefix."_users set com=com-1 where id=".db_squote($row['author_id']));
		$mysql->query("update ".prefix."_news set com=com-1 where id=".db_squote($row['post']));
		msg(array("text" => $lang['msgo_deleted'], "info" => sprintf($lang['msgi_deleted'], "admin.php?mod=editnews&action=editnews&id=".$newsid)));
	} else {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
	}
}

if ($subaction != "deletecomment") {
	$row = $mysql->record("select * from ".prefix."_comments where id = ".db_squote($comid));
	if ($row) {
		$row['text']	=	str_replace("<br />", "\r\n", $row['text']);
		$row['answer']	=	str_replace("<br />", "\r\n", $row['answer']);

		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'quicktags'	=>	QuickTags(false, "editcom"),
			'ip'		=>	$row['ip'],
			'author'	=>	$row['author'],
			'mail'		=>	$row['mail'],
			'text'		=>	$row['text'],
			'answer'	=>	$row['answer'],
			'newsid'	=>	$newsid,
			'comid'		=>	$comid
		);
		$tvars['vars']['smilies'] = ($config['use_smilies'] == "1") ? InsertSmilies("content", 5) : '';
		$tvars['vars']['comdate'] = LangDate(pluginGetVariable('comments', 'timestamp'), $row['postdate']);

		if ($userROW['status'] < "3"){
			$tvars['vars']['[answer]']	=	'';
			$tvars['vars']['[/answer]']	=	'';
		} else {
			$tvars['regx']['[\[answer\](.*)\[/answer\]]'] = '';
		}

		if ($row['text'] != '') {
			$tpl -> template('editcomments', tpl_actions);
			$tpl -> vars('editcomments', $tvars);
			echo $tpl -> show('editcomments');
		}
	} else {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
	}
}
?>