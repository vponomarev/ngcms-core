<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: comments.show.php
// Description: Routines for showing comments
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang("comments", "site");

//
// Show comments for a news
// $newsID - [required] ID of the news for that comments should be showed
// $commID - [optional] ID of comment for showing in case if we just added it
// $commDisplayNum - [optional] num that is showed in 'show comment' template
// $callingParams
//		'plugin'  => if is called from plugin - ID of plugin
//		'overrideTemplateName' => alternative template for display
//		'overrideTemplatePath' => alternative path for searching of template
function comments_show($newsID, $commID = 0, $commDisplayNum = 0, $callingParams = array()){
	global $mysql, $tpl, $template, $config, $userROW, $parse, $lang;

	// -> desired template path
	$templatePath = ($callingParams['overrideTemplatePath'])?$callingParams['overrideTemplatePath']:tpl_dir.$config['theme'];

	// -> desired template
	if ($callingParams['overrideTemplateName']) {
		$templateName = $callingParams['overrideTemplateName'];
	} else {
		$templateName = 'comments.show';
	}

	$tpl -> template($templateName, $templatePath);

	if ($config['use_avatars']) {
		$sql = "select c.*, u.avatar from ".prefix."_comments c left join ".uprefix."_users u on c.author_id = u.id where c.post=".db_squote($newsID).($commID?(" and c.id=".db_squote($commID)):'')." order by c.id".($config['reverse_comments']?' desc':'');
	} else {
		$sql = "select c.* from ".prefix."_comments c WHERE c.post=".db_squote($newsID).($comment_id?(" and c.id=".db_squote($comment_id)):'')." order by c.id".($config['reverse_comments']?' desc':'');
	}

	$comnum = 0;
	foreach ($mysql->select($sql) as $row) {
		$comnum++;
		$tvars['vars']['id']		=	$row['postdate'];
		$tvars['vars']['author']	=	$row['author'];
		$tvars['vars']['mail']		=	$row['mail'];
		$tvars['vars']['date']		=	LangDate(ctimestamp, $row['postdate']);

		if ($row['reg']) {
			$tvars['vars']['profile_link'] = GetLink('user', $row);
			$tvars['regx']["'\[profile\](.*?)\[/profile\]'si"] = '$1';
		} else {
			$tvars['vars']['profile_link'] = '';
			$tvars['regx']["'\[profile\](.*?)\[/profile\]'si"] = '';
		}

		// Add [hide] tag processing
		$text	= $row['text'];

		if ($config['blocks_for_reg'])		{ $text = $parse -> userblocks($text); }
		if ($config['use_htmlformatter'])	{ $text = $parse -> htmlformatter($text); }
		if ($config['use_bbcodes'])			{ $text = $parse -> bbcodes($text); }
		if ($config['use_smilies'])			{ $text = $parse -> smilies($text); }


		if (intval($config['com_wrap']) && (strlen($text) > $config['com_wrap'])) {
			$tvars['vars']['comment-short']	=	substr($text, 0, $config['com_wrap']);
			$tvars['vars']['comment-full']	=	substr($text, $config['com_wrap']);
			$tvars['regx']["'\[comment_full\](.*?)\[/comment_full\]'si"] = '$1';
		} else {
			$tvars['vars']['comment-short'] = $text;
			$tvars['regx']["'\[comment_full\](.*?)\[/comment_full\]'si"] = '';
		}
		if ($commID && $commDisplayNum)
			$comnum = $commDisplayNum;

		$tvars['vars']['comnum'] = $comnum;
		$tvars['vars']['alternating'] = ($comnum%2) ? "comment_even" : "comment_odd";

		if ($config['use_avatars']) {
			if ($row['avatar']) {
				$tvars['vars']['avatar'] = "<img src=\"".avatars_url."/".$row['avatar']."\" alt=\"".$row['author']."\" />";
			} else {
				// If gravatar integration is active, show avatar from GRAVATAR.COM
				if ($config['avatars_gravatar']) {
					$tvars['vars']['avatar'] = '<img src="http://www.gravatar.com/avatar/'.md5(strtolower($row['mail'])).'.jpg?s='.$config['avatar_wh'].'&d='.urlencode(avatars_url."/noavatar.gif").'" alt=""/>';
				} else {
					$tvars['vars']['avatar'] = "<img src=\"".avatars_url."/noavatar.gif\" alt=\"\" />";
				}
			}
		} else {
			$tvars['vars']['avatar'] = '';
		}

		if ($config['use_bbcodes']) {
			$tvars['regx']["'\[quote\](.*?)\[/quote\]'si"] = '$1';
		} else {
			$tvars['regx']["'\[quote\](.*?)\[/quote\]'si"] = '';
		}

		if ($row['answer'] != '') {
			$answer = $row['answer'];

			if ($config['blocks_for_reg'])		{ $answer = $parse -> userblocks($answer); }
			if ($config['use_htmlformatter'])	{ $answer = $parse -> htmlformatter($answer); }
			if ($config['use_bbcodes'])			{ $answer = $parse -> bbcodes($answer); }
			if ($config['use_smilies'])			{ $answer = $parse -> smilies($answer); }

			$tvars['vars']['answer']	=	$answer;
			$tvars['vars']['name']		=	$row['name'];
			$tvars['regx']["'\[answer\](.*?)\[/answer\]'si"] = '$1';
		} else {
			$tvars['regx']["'\[answer\](.*?)\[/answer\]'si"] = '';
		}

		if (is_array($userROW) && (($userROW['status'] == "1") || ($userROW['status'] == "2"))) {
			$tvars['vars']['[edit-com]'] = "<a href=\"".admin_url."/admin.php?mod=editcomments&amp;newsid=$newsID&amp;comid=$row[id]\" target=\"_blank\" title=\"".$lang['addanswer']."\">";
			$tvars['vars']['[/edit-com]'] = "</a>";
			$tvars['vars']['[del-com]'] = "<a href=\"".admin_url."/admin.php?mod=editcomments&amp;subaction=deletecomment&amp;newsid=$newsID&amp;comid=$row[id]&amp;oster=$row[author]\" title=\"".$lang['comdelete']."\">";
			$tvars['vars']['[/del-com]'] = "</a>";
			$tvars['vars']['ip'] = "<a href=\"http://www.nic.ru/whois/?ip=$row[ip]\" title=\"".$lang['whois']."\">".$lang['whois']."</a>";
		} else {
			$tvars['regx']["'\\[edit-com\\].*?\\[/edit-com\\]'si"]	=	'';
			$tvars['regx']["'\\[del-com\\].*?\\[/del-com\\]'si"]	=	'';
			$tvars['vars']['ip'] = '';
		}

		exec_acts('comments', $row);
		$tpl -> vars($templateName, $tvars);

		if ($commID) { echo $tpl -> show($templateName); }
		$template['vars']['mainblock'] .= $tpl -> show($templateName);

	}
}

// $callingParams
//		'plugin'  => if is called from plugin - ID of plugin
//		'overrideTemplateName' => alternative template for display
//		'overrideTemplatePath' => alternative path for searching of template
function comments_showform($newsID, $callingParams = array()){
	global $mysql, $config, $template, $tpl, $userROW;

	// -> desired template path
	$templatePath = ($callingParams['overrideTemplatePath'])?$callingParams['overrideTemplatePath']:tpl_dir.$config['theme'];

	// -> desired template
	if ($callingParams['overrideTemplateName']) {
		$templateName = $callingParams['overrideTemplateName'];
	} else {
		$templateName = 'comments.form';
	}

	$tpl -> template($templateName, $templatePath);

	if($config['use_smilies']) {
		$tvars['vars']['smilies'] = InsertSmilies('comments', 10);
	} else {
		$tvars['vars']['smilies'] = "";
	}

	if ($_COOKIE['com_username'] && trim($_COOKIE['com_username']) != "") {
		$savedname = urldecode($_COOKIE['com_username']);
		$tvars['vars']['savedname'] = $savedname;
		$tvars['vars']['savedmail'] = $_COOKIE['com_usermail'];
		$tvars['vars']['savedurl'] = $_COOKIE['com_userurl'];
	} else {
		$template_form = str_replace("{savedname}", "", $template_form);
		$tvars['vars']['savedname'] = '';
		$tvars['vars']['savedmail'] = '';
		$tvars['vars']['savedurl'] = '';
	}

	if (!is_array($userROW)) {
		$tvars['vars']['[not-logged]'] = "";
		$tvars['vars']['[/not-logged]'] = "";
	} else {
		$tvars['regx']["'\\[not-logged\\].*?\\[/not-logged\\]'si"] = "";
	}

	if ($config['use_captcha']) {
		$tvars['vars']['admin_url'] = admin_url;

		if (!is_array($userROW)) {
			@session_register('captcha');
			$_SESSION['captcha'] = rand(00000, 99999);
			$number = $_SESSION['captcha'];
		}	else {
			$number = $_SESSION['captcha'];
		}

		$tvars['vars']['captcha'] = '';
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '$1';
	} else {
		$tvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '';
	}

	$tvars['vars']['bbcodes']		=	BBCodes();
	$tvars['vars']['skins_url']		=	skins_url;
	$tvars['vars']['newsid']		=	$newsID;
	exec_acts('comments_form', $row);
	$tpl -> vars($templateName, $tvars);
	$template['vars']['mainblock'] .= $tpl -> show($templateName);
}

// preload plugins
load_extras('comments');
load_extras('comments:show');
