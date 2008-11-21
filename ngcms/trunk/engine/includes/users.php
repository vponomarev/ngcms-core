<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: users.php
// Description: Display information about specified user
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('users', 'site');
$situation = "profile";

if ($user) {
	$row = locateUser($user);

	if (!$row['id']) {
		msg(array("type" => "error", "text" => $lang['msge_no_user']));
	} else {

		// Make page title
		$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_userinfo'];
		$SYSTEM_FLAGS['info']['title']['item']	= $row['name'];

		switch ($row['status']) {
			case 1: $status = $lang['administrator'];	break;
			case 2: $status = $lang['editor'];			break;
			case 3: $status = $lang['journalist'];		break;
			case 4: $status = $lang['commenter'];		break;
		}

		// Check for new style of photos storing
		if (preg_match('/^'.$row['id'].'\./', $row['photo'])) {
			$uphoto = $row['photo'];
		} else {
			$uphoto = $row['id'].'.'.$row['photo'];
		}

		// Check for new style of avatars storing
		if (preg_match('/^'.$row['id'].'\./', $row['avatar'])) {
			$uavatar = $row['avatar'];
		} else {
			$uavatar = $row['id'].'.'.$row['avatar'];
		}

		$photo	= photos_url.'/'.(($row['photo'] != "")?'thumb/'.$uphoto:'nophoto.gif');
		
		// GRAVATAR.COM integration
		if ($row['avatar'] != '') {
			$avatar	= avatars_url.'/'.$uavatar;
		} else {
			if ($config['avatars_gravatar']) {
                        	$avatar	= 'http://www.gravatar.com/avatar/'.md5(strtolower($userROW['mail'])).'.jpg?s='.$config['avatar_wh'].'&d='.urlencode(avatars_url."/noavatar.gif");
                        } else {
                        	$avatar = avatars_url."/noavatar.gif";
                        }	
		}
		$tpl -> template('users', tpl_site);
		$tvars['vars'] = array(
			'user'		=>	$row['name'],
			'news'		=>	$row['news'],
			'com'		=>	$row['com'],
			'status'	=>	$status,
			'last'		=>	langdate("j Q Y", $row['last']),
			'reg'		=>	langdate("j Q Y", $row['reg']),
			'site'		=>	secure_html($row['site']),
			'icq'		=>	is_numeric($row['icq']) ? '<a target="_blank" href="http://www.icq.com/people/about_me.php?uin='.$row['icq'].'">'.$row['icq'].'</a>' : secure_html($row['icq']),
			'icqimg'	=>	is_numeric($row['icq']) ? '<img src="http://status.icq.com/online.gif?icq='.$row['icq'].'&img=1" />' : '',
			'from'		=>	secure_html($row['where_from']),
			'info'		=>	secure_html($row['info']),
			'photo'		=>	$photo,
			'photo_link'=>	($row['photo'] != "") ? photos_url.'/'.$uphoto:'',
			'avatar'	=>	$avatar
		);

		exec_acts('users', '', $row);

	//	if ($config['use_rating']) {
	//		$row						=	$mysql->record("SELECT SUM(rating) as rating, SUM(votes) as votes FROM ".prefix."_news WHERE author_id=".db_squote($row['id']));
	//		$rating						=	@round($row['rating'] / $row['votes'], 0);
	//		$tvars['vars']['rating']	=	RatingImg($rating);
	//	}
	//	else {
	//		$tvars['vars']['rating']	=	'';
	//	}
		$tvars['vars']['rating'] = '';

		$tpl -> vars('users', $tvars);
		$template['vars']['mainblock'] .= $tpl -> show('users');
	}
} else {
	msg(array("type" => "error", "text" => $lang['msge_no_user']));
}
