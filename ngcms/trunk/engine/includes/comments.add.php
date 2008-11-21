<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: comments.add.php
// Description: Routines for adding comments
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

include_once "../core.php";

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


@header('Content-type: text/html; charset='.$lang['encoding']);
LoadLang("comments", "site");

//
// Params for filtering and processing
//
function comments_add(){
	global $mysql, $config, $AUTH_METHOD, $userROW, $ip, $lang, $parse, $HTTP_REFERER, $catmap, $catz;

	if ($config['forbid_comments'])
		return;

	// Check membership
	// If login/pass is entered (either logged or not)
	if ($_POST['name'] && $_POST['password']) {
		$auth	= $AUTH_METHOD[$config['auth_module']];
		$user	= $auth->login(0, $_POST['name'], $_POST['password']);
		if (!is_array($user)) {
			msg(array("type" => "error", "text" => $lang['msge_password']));
			return;
		}
	}

	// Entered data have higher priority then login data
	if (is_array($user)) {
		$params['user_name']	= $user['name'];
		$params['author_id']	= $user['id'];
		$params['mail']			= $user['mail'];
		$is_member				= 1;
	} else if (is_array($userROW)) {
		$params['user_name']	= $userROW['name'];
		$params['author_id']	= $userROW['id'];
		$params['mail']			= $userROW['mail'];
		$is_member				= 1;
	} else {
		$params['user_name']	= secure_html(convert(trim($_POST['name'])));
		$params['author_id']	= 0;
		$params['mail']			= secure_html(trim($_POST['mail']));
		$is_member				= 0;
	}

	$params['newsid']	=	intval($_POST['newsid']);
	$params['content']	=	secure_html(convert(trim($_POST['content'])));

	if (!$is_member) {
		if ($config['use_captcha']) {
			$vcode = $_POST['vcode'];

			if ($vcode != $_SESSION['captcha']) {
				msg(array("type" => "error", "text" => $lang['msge_vcode']));
				return;
			}
		}

		if ($config['com_for_reg']) {
			msg(array("type" => "error", "text" => $lang['msge_comforreg']));
			return;
		}
		if (!$params['user_name']) {
			msg(array("type" => "error", "text" => $lang['msge_name']));
			return;
		}
		if (!$params['mail']) {
			msg(array("type" => "error", "text" => $lang['msge_mail']));
			return;
		}
		if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $params['user_name']) || strlen($params['user_name']) > 60) {
			msg(array("type" => "error", "text" => $lang['msge_badname']));
			return;
		}
		if (strlen($params['mail']) > 70 || !preg_match("/^[\.A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $params['mail'])) {
			msg(array("type" => "error", "text" => $lang['msge_badmail']));
			return;
		}
	}

	if (strlen($params['content']) > $config['com_length'] || strlen($params['content']) < 2) {
		msg(array("type" => "error", "text" => sprintf($lang['msge_badtext'], $config['com_length'])));
		return;
	}

	if ($config['flood_time']) {
		if (Flooder($ip)) {
			msg(array("type" => "error", "text" => sprintf($lang['msge_flood'], $config['flood_time'])));
			return;
		}
	}

	if ($ban_row = $mysql->record("select * from ".prefix."_ipban where ip=".db_squote($ip))) {
		$mysql->query("update ".prefix."_ipban set counter=counter+1 where ip=".db_squote($ip));
		msg(array("type" => "error", "text" => $lang['msge_ip'], "info" => sprintf($lang['msgi_ip'], $ban_row['descr'])));
		return;
	}

	// Locate news
	if ($news_row = $mysql->record("select * from ".prefix."_news where id = ".db_squote($params['newsid']))) {
		if (!$news_row['allow_com']) {
			msg(array("type" => "error", "text" => $lang['msge_comforbid']));
			return;
		}
	} else {
		msg(array("type" => "error", "text" => $lang['msge_notfound']));
		return;
	}

	// Check for multiple comments block [!!! ADMINS CAN DO IT IN ANY CASE !!!]
	if ($config['block_many_com'] && (!is_array($userROW) || ($userROW['status'] != 1))) {

		// Locate last comment for this news
		if (is_array($lpost = $mysql->record("select author_id, author, ip, mail from ".prefix."_comments where post=".db_squote($params['newsid'])." order by id desc limit 1"))) {
			// Check for post from the same user
			if (is_array($userROW)) {
				 if ($userROW['id'] == $lpost['author_id']) {
					msg(array("type" => "error", "text" => $lang['msge_many_com']));
					return;
				}
			} else {
				//print "Last post: ".$lpost['id']."<br>\n";
				if (($lpost['author'] == $params['user_name'])||($lpost['mail'] == $params['mail'])) {
					msg(array("type" => "error", "text" => $lang['msge_many_com']));
					return;
				}
			}
		}
	}



	//
	// Run interceptors
	//
	exec_acts('addcomment','', &$params);

	// Break if interceptor blocks comment adding
	if ($params['stop'])
		return;

	$time = time() + ($config['date_adjust'] * 60);

	if ($config['auto_wrap'] > 1){
		$params['content'] = preg_replace('/(\S{'.intval($config['auto_wrap']).'})(?!\s)/', '$1 ', $params['content']);

		if (strlen($params['user_name']) > $config['auto_wrap']) {
			$params['user_name'] = substr($params['user_name'], 0, $config['auto_wrap'])." ...";
		}
	}
	$params['content'] = str_replace("\r\n", "<br />", $params['content']);

	$mysql->query("insert into ".prefix."_comments (`postdate`, `post`, `author`, `author_id`, `mail`, `text`, `ip`, `reg`) VALUES (".db_squote($time).", ".db_squote($params['newsid']).", ".db_squote($params['user_name']).", ".db_squote($params['author_id']).", ".db_squote($params['mail']).", ".db_squote($params['content']).", ".db_squote($ip).", '".(($is_member) ? '1' : '0')."')");
	$comment_id = $mysql->result("select LAST_INSERT_ID() as id");

	$mysql->query("update ".prefix."_news set com=com+1 where id=".db_squote($params['newsid']));
	$comment_no = $mysql->result("select com from ".prefix."_news where id=".db_squote($params['newsid']));


	if ($params['author_id']) {
		$mysql->query("update ".prefix."_users set com=com+1 where id = ".db_squote($params['author_id']));
	}

	if ($mysql->rows($mysql->query("SELECT id FROM ".prefix."_flood WHERE ip = ".db_squote($ip))) > "0") {
		$mysql->query("UPDATE ".prefix."_flood SET id=".db_squote($time)." WHERE ip = ".db_squote($ip));
	} else {
		$mysql->query("INSERT INTO ".prefix."_flood (`ip`, `id`) VALUES (".db_squote($ip).", ".db_squote($time).")");
	}

	if ($config['send_notice']) {
		$body = str_replace(
			array(	'{username}',
					'[userlink]',
					'[/userlink]',
					'{comment}',
					'{newslink}',
					'{newstitle}'),
			array(	$params['user_name'],
					($params['author_id'])?'<a href="'.GetLink('user', array('author' => $params['user_name'])).'">':'',
					($params['author_id'])?'</a>':'',
					$parse->bbcodes($parse->smilies(secure_html($params['content']))),
					GetLink('full', $news_row),
					$news_row['title'],
					),
			$lang['notice']
		);

		zzMail($config['admin_mail'], $lang['newcomment'], $body, 'html');
	}

	@setcookie("com_username", urlencode($params['user_name']), 0, '/');
	@setcookie("com_usermail", $params['mail'], 0, '/');

	// Check if we need to override news template
	$callingCommentsParams = array();

	// Set default template path
	$templatePath = tpl_dir.$config['theme'];

	// Find first category
	$fcat = array_shift(explode(",", $news_row['catid']));
	// Check if there is a custom mapping
	if ($fcat && $catmap[$fcat] && ($ctname = $catz[$catmap[$fcat]]['tpl'])) {
		// Check if directory exists
		if (is_dir($templatePath.'/ncustom/'.$ctname))
			$callingCommentsParams['overrideTemplatePath'] = $templatePath.'/ncustom/'.$ctname;
	}

	include root.'/includes/comments.show.php';
	comments_show($params['newsid'], $comment_id, $news_row['com']+1, $callingCommentsParams);
	return 1;
}

// preload plugins
load_extras('comments');
load_extras('comments:add');


if (!comments_add()) {
		$tpl -> template('error', tpl_site);
		$tpl -> vars('error', array( 'vars' => array('content' => $template['vars']['mainblock'])));
		echo $tpl -> show('error');
}


