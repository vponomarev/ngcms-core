<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: profile.php
// Description: user's profile managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('profile', 'site');
$situation = "profile";
@include_once root.'includes/classes/upload.class.php';

function manage_delete($type, $userID){
	global $mysql, $userROW;

	$localUpdate = 0;
	$userID = intval($userID);

	if ($userID != $userROW['id']) {
		if (!is_array($uRow = $mysql->record("select * from ".uprefix."_users where id = ".$userID)))
		 return;
	} else {
		$uRow = $userROW;
		$localUpdate = 1;
	}

	// Search for avatar record in mySQL table
	if (is_array($imageRow = $mysql->record("select * from ".prefix."_images where owner_id = ".$userID." and category = ".($type=='avatar'?1:2)))) {
		// Info was found in SQL table
		$fmanager = new file_managment();
		$fmanager->file_delete(array('type' => $type, 'id' => $imageRow['id']));
		//unlink(avatars_dir.$imageRow['name']);
	} else if ($uRow[$type]) {
		// Try to delete all avatars of this user
		@unlink($avatar_dir.$uRow['id'].'.*');
	}
	$mysql->query("update ".uprefix."_users set ".($type=='photo'?'photo':'avatar')." = '' where id = ".$userID);
	if ($localUpdate) $userROW[$type] = '';
}


if (is_array($userROW)) {

	// Make page title
	$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_profile'];

	if ($subaction == "save") {
		if ($_REQUEST['delavatar']) {
			manage_delete('avatar', $userROW['id']);
		} else {
			$avatar = $userROW['avatar'];
		}

		if ($_REQUEST['delphoto']) {
			manage_delete('photo', $userROW['id']);
		} else {
			$photo = $userROW['photo'];
		}

		// UPLOAD AVATAR
		if ($_FILES['newavatar']['name']) {

			// Delete an avatar if user already has it
			manage_delete('avatar', $userROW['id']);

			$fmanage = new file_managment();
			$imanage = new image_managment();
			$up = $fmanage->file_upload(array('type' => 'avatar', 'http_var' => 'newavatar', 'replace' => 1, 'manualfile' => $userROW['id'].'.'.strtolower($_FILES['newavatar']['name'])));
			if (is_array($up)) {
				// Now fetch information about size and prepare to write info into DB
				if (is_array($sz = $imanage->get_size($config['avatars_dir'].$subdirectory.'/'.$up[1]))) {
					$fmanage->get_limits('avatar');

					// Check avatar size limit (!!!)
					$lwh = intval($config['avatar_wh']);
					if ($lwh && (($sz[1] > $lwh)||($sz[2] > $lwh))) {
						// Fatal: uploaded avatar mismatch size limits !
						msg(array("type" => "error", "text" => $lang['msge_size'], "info" => sprintf($lang['msgi_size'], $lwh.'x'.$lwh)));
						$fmanage->file_delete(array('type' => 'avatar', 'id' => $up[0]));
					} else {
						$mysql->query("update ".prefix."_".$fmanage->tname." set width=".db_squote($sz[1]).", height=".db_squote($sz[2])." where id = ".db_squote($up[0]));
						$avatar = $up[1];
					}
				} else {
					// We were unable to fetch image size. Damaged file, delete it!
					msg(array("type" => "error", "text" => $lang['msge_damaged']));
					$fmanage->file_delete(array('type' => 'avatar', 'id' => $up[0]));
				}
			}
		}

		// UPLOAD PHOTO
		if ($_FILES['newphoto']['name']) {

			// Delete a photo if user already has it
			manage_delete('photo', $userROW['id']);

			$fmanage = new file_managment();
			$imanage = new image_managment();
			$up = $fmanage->file_upload(array('type' => 'photo', 'http_var' => 'newphoto', 'replace' => 1, 'manualfile' => $userROW['id'].'.'.strtolower($_FILES['newphoto']['name'])));
			if (is_array($up)) {
				// Now write info about image into DB
				if (is_array($sz = $imanage->get_size($config['photos_dir'].$subdirectory.'/'.$up[1]))) {
					$fmanage->get_limits('photo');

					// Create preview for photo
					$tsz = intval($config['photos_thumb_size']);
					if (($tsz < 10)||($tsz > 1000)) $tsz = 150;
					$thumb = $imanage->create_thumb($config['photos_dir'].$subdirectory, $up[1], $tsz,$tsz);

					// If we were unable to create thumb - delete photo, it's damaged!
					if (!$thumb) {
						msg(array("type" => "error", "text" => $lang['msge_damaged']));
						$fmanage->file_delete(array('type' => 'avatar', 'id' => $up[0]));
					} else {
						$mysql->query("update ".prefix."_".$fmanage->tname." set width=".db_squote($sz[1]).", height=".db_squote($sz[2]).", preview=1 where id = ".db_squote($up[0]));
						$photo = $up[1];
					}
				} else {
					// We were unable to fetch image size. Damaged file, delete it!
					msg(array("type" => "error", "text" => $lang['msge_damaged']));
					$fmanage->file_delete(array('type' => 'avatar', 'id' => $up[0]));
				}
			}
		}

		$sqlFields = array ( 'avatar' => $avatar, 'photo' => $photo, 'mail' => $_REQUEST['editmail'], 'site' => $_REQUEST['editsite'], 'icq' => is_numeric($_REQUEST['editicq'])?$_REQUEST['editicq']:'', 'where_from' => $_REQUEST['editfrom'], 'info' => (intval($config['user_aboutsize'])?substr($_REQUEST['editabout'],0,$config['user_aboutsize']):$_REQUEST['editabout']));
		if ($_REQUEST['editpassword'] != '') {
			if (method_exists($auth_db, 'save_profile')) {
				$auth_db->save_profile($userROW['id'], array('password' => $_REQUEST['editpassword']));
			}
			$sqlFields['pass'] = EncodePassword($_REQUEST['editpassword']);
		}


		// Prepare SQL line
		$sqlF = array();
		foreach ($sqlFields as $f => $v)
			array_push($sqlF, $f . " = " . db_squote($v));

		$sqlUpdate = "update ".uprefix."_users set ".join(", ",$sqlF)." where id = ".db_squote($userROW['id']);
		$mysql->query($sqlUpdate);

		exec_acts('profile', $userROW['id']);

		// Redirect back if we do not have any messages
		if (!$template['vars']['mainblock'])
			@header("Location: ".$HTTP_REFERER);
	}

	if ($config['use_avatars']) {
		if ($userROW['avatar'] !== "") {
			// Check for new style of avatar storing
			if (preg_match('/^'.$userROW['id'].'\./', $userROW['avatar'])) {
				$avatar = $userROW['avatar'];
			} else {
				$avatar = $userROW['id'].'.'.$userROW['avatar'];
			}

			$imgavatar = '<img src="'.avatars_url.'/'.$avatar.'" style="margin: 5px; border: 0px;" alt="" />';
			$delavatar = '<input type="checkbox" name="delavatar" id="delavatar" class="check" />&nbsp;<label for="delavatar">'.$lang["delete"].'</label>';
		}
		$showrow_avatar = '<input type="file" name="newavatar" size="40" /><br />'.$imgavatar.'<br />'.$delavatar;
	} else {
		$showrow_avatar = $lang['avatars_denied'];
	}

	if ($config['use_photos']) {
		if ($userROW['photo'] !== "") {
			// Check for new style of avatar storing
			if (preg_match('/^'.$userROW['id'].'\./', $userROW['photo'])) {
				$photo = $userROW['photo'];
			} else {
				$photo = $userROW['id'].'.'.$userROW['photo'];
			}
			$imgphoto = '<a href="'.photos_url.'/'.$photo.'" target="_blank"><img src="'.photos_url.'/thumb/'.$photo.'" style="margin: 5px; border: 0px;" alt="" /></a>';
			$delphoto = '<input type="checkbox" name="delphoto" id="delphoto" class="check" />&nbsp;<label for="delphoto">'.$lang["delete"].'</label>';
		}
		$showrow_photo = '<input type="file" name="newphoto" size="40" /><br />'.$imgphoto.'<br />'.$delphoto;
	} else {
		$showrow_photo = $lang['photos_denied'];
	}

	if 		($userROW['status'] == "4")	{ $status = $lang['commenter']; }
	elseif	($userROW['status'] == "3")	{ $status = $lang['journalist']; }
	elseif	($userROW['status'] == "2")	{ $status = $lang['editor']; }
	elseif	($userROW['status'] == "1")	{ $status = $lang['administrator']; }

	if ($info) { msg(array("text" => $lang["msgo_saved"])); }

	$tpl -> template('profile', tpl_site);
	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'name'		=>	$userROW['name'],
		'regdate'	=>	LangDate("l, j Q Y - H:i", $userROW['reg']),
		'last'		=>	(empty($userROW['last'])) ? $lang['no_last'] : LangDate("l, j Q Y - H:i", $userROW['last']),
		'status'	=>	$status,
		'news'		=>	$userROW['news'],
		'comments'	=>	$userROW['com'],
		'email'		=>	secure_html($userROW['mail']),
		'ifchecked'	=>	$ifchecked,
		'site'		=>	secure_html($userROW['site']),
		'icq'		=>	secure_html($userROW['icq']),
		'from'		=>	secure_html($userROW['where_from']),
		'about'		=>	secure_html($userROW['info']),
		'about_sizelimit_text'	=> (intval($config['user_aboutsize'])?sprintf($lang['about_sizelimit'],intval($config['user_aboutsize'])):''),
		'about_sizelimit'	=> intval($config['user_aboutsize']),
		'avatar'	=>	$showrow_avatar,
		'photo'		=>	$showrow_photo
	);

//	if ($config['use_rating']) {
//		$row = $mysql->record("select sum(rating) as rating, sum(votes) as votes from ".prefix."_news where author=".db_squote($userROW['name']));
//		$rating = @round($row['rating'] / $row['votes'], 0);
//		$tvars['vars']['rating'] = RatingImg($rating);
//	} else {
		$tvars['vars']['rating'] = '';
//	}

	exec_acts('profile_edit', $userROW, '');

	$tpl -> vars('profile', $tvars);

	$template['vars']['mainblock'] .= $tpl -> show('profile');
} else {
	msg(array("type" => "error", "text" => $lang['msge_notlogged']));
}
