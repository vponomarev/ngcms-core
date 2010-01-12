<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: addnews.php
// Description: Adding news (ADMIN or ON-SITE)
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root.'includes/classes/upload.class.php';

$SYSTEM_FLAGS['info']['title']['group']	= $lang['loc_addnews'];

$lang = LoadLang('addnews', defined('ADMIN')?'admin':'site');
$situation = "news";

function news_add(){
	global $mysql, $lang, $userROW, $parse, $PFILTERS, $config, $catz, $catmap;

	$title = $_REQUEST['title'];

	// Fill content
	$content	= '';

	// Check if EDITOR SPLIT feature is activated
	if ($config['news.edit.split']) {
		// Prepare delimiter
		$ed = '<!--more-->';
		if ($config['extended_more'] && ($_REQUEST['content_delimiter'] != '')) {
			// Disable `new line` + protect from XSS
			$ed = '<!--more="'.str_replace(array("\r", "\n", '"'), '', $_REQUEST['content_delimiter']).'"-->';
		}
		$content = $_REQUEST['content_short'].(($_REQUEST['content_full'] != '')?$ed.$_REQUEST['content_full']:'');

	} else {
		$content = $_REQUEST['content'];
	}

	// Rewrite `\r\n` to `\n`
	$content = str_replace("\r\n", "\n", $content);

	$alt_name = $parse->translit( trim($_REQUEST['alt_name']), 1);

	// Check title
	if ( (!strlen(trim($title))) || (!strlen(trim($content))) ) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return 0;
	}

	$SQL['title'] = $title;

	// Check for dup if alt_name is specified
	if ($alt_name) {
		if ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name)." limit 1")) ) {
			msg(array("type" => "error", "text" => $lang['msge_alt_name'], "info" => $lang['msgi_alt_name']));
			return;
		}
		$SQL['alt_name'] = $alt_name;
	} else {
		// Generate uniq alt_name if no alt_name specified
		$alt_name = strtolower($parse->translit(trim($title), 1));
		// Make a conversion:
		// * '.'  to '_'
		// * '__' to '_' (several to one)
		// * Delete leading/finishing '_'
		$alt_name = preg_replace(array('/\./', '/(_{2,20})/', '/^(_+)/', '/(_+)$/'), array('_', '_'), $alt_name);

		// Make alt_name equal to '_' if it appear to be blank after conversion
		if ($alt_name == '') $alt_name = '_';

		$i = '';
		while ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
			$i++;
		}
		$SQL['alt_name'] = $alt_name.$i;
	}

	if ($_REQUEST['customdate']) {
		$SQL['postdate'] = mktime(intval($_REQUEST['c_hour']), intval($_REQUEST['c_minute']), 0, intval($_REQUEST['c_month']), intval($_REQUEST['c_day']), intval($_REQUEST['c_year'])) + ($config['date_adjust'] * 60);
	} else {
		$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
	}

	$SQL['editdate'] = $SQL['postdate'];

	// Fetch MASTER provided categories
	$catids = array ();
	if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
		$catids[intval($_POST['category'])] = 1;
	}

	// Fetch ADDITIONAL provided categories
	foreach ($_POST as $k => $v) {
		if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
			$catids[$match[1]] = 1;
	}

	if ($config['meta']) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	$SQL['author']		= $userROW['name'];
	$SQL['author_id']	= $userROW['id'];
	$SQL['catid']		= implode(",", array_keys($catids));

	// Use flag 'allow comments' only in case when plugin 'comments' is installed
	if (getPluginStatusInstalled('comments'))
		$SQL['allow_com']	= $_REQUEST['allow_com'];

	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	$SQL['flags'] = 0;
	switch ($userROW['status']) {
		case 1:		// admin can do anything
			$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 2:		// Editor. Check if we have permissions
			if (!$config['htmlsecure_2'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 3:		// Journalists. Check if we have permissions
			if (!$config['htmlsecure_3'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 4:		// Commentors. Check if we have permissions
			if (!$config['htmlsecure_4'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;
	}

	// This actions are allowed only for admins & Edtiors
	if (($userROW['status'] == 1)||($userROW['status'] == 2)) {
		$SQL['mainpage']	= intval($_REQUEST['mainpage']);
		$SQL['approve']		= intval($_REQUEST['approve']);
		$SQL['favorite']	= intval($_REQUEST['favorite']);
		$SQL['pinned']		= intval($_REQUEST['pinned']);
	}

	$SQL['content']		= $content;

	exec_acts('addnews');

	$pluginNoError = 1;
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			if (!($pluginNoError = $v->addNews($tvars, $SQL))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$vnames = array(); $vparams = array();
	foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }

	$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");
	$id = $mysql->result("SELECT LAST_INSERT_ID() as id");

	// Update category / user posts counter [ ONLY if news is approved ]
	if ($SQL['approve']) {
		if (count($catids)) {
			$mysql->query("update ".prefix."_category set posts=posts+1 where id in (".implode(", ",array_keys($catids)).")");
			foreach (array_keys($catids) as $catid) {
				$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".db_squote($id).", ".db_squote($catid).")");
			}
		}
		$mysql->query("update ".uprefix."_users set news=news+1 where id=".$SQL['author_id']);
	}

	if (is_array($PFILTERS['news']))
	foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsNotify($tvars, $SQL, $id); }

	exec_acts('addnews_', $id);
	msg(array("text" => $lang['msgo_added'], "info" => sprintf($lang['msgi_added'], admin_url.'/admin.php?mod=editnews&action=editnews&id='.$id, admin_url.'/admin.php?mod=editnews')));


	// Now let's manage attached files
	$fmanager = new file_managment();

	$flagUpdateAttachCount = false;

	// Delete files (if needed)
	foreach ($_POST as $k => $v) {
		if (preg_match('#^delfile_(\d+)$#', $k, $match)) {
			$fmanager->file_delete(array('type' => 'file', 'id' => $match[1]));
			$flagUpdateAttachCount = true;
		}
	}


	//print "<pre>".var_export($_FILES, true)."</pre>";
	// PREPARE a list for upload
	if (is_array($_FILES['userfile']['name']))
		foreach($_FILES['userfile']['name'] as $i => $v) {
			if ($v == '')
				continue;

			$flagUpdateAttachCount = true;
			//
			$up = $fmanager->file_upload(array('dsn' => true, 'linked_ds' => 1, 'linked_id' => $id, 'type' => 'file', 'http_var' => 'userfile', 'http_varnum' => $i));
			//print "OUT: <pre>".var_export($up, true)."</pre>";
			if (!is_array($up)) {
				// Error uploading file
				// ... show error message ...
			}

		}

	// Update attach count if we need this
	if ($flagUpdateAttachCount) {
		$attachCount = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
		$mysql->query("update ".prefix."_news set attach_count = ".intval($attachCount)." where id = ".db_squote($id));
	}

	return 1;
}


//
// We allow adding news in case (or):
// We're in admin panel
// User with status 1..3 tries to add a news
// 'add_onsite' is allowed (in this case all registered users can add news
// === GUESTS CAN'T ADD NEWS ===
if (defined('ADMIN') || (is_array($userROW) && $userROW['status'] < 4) || ($config['add_onsite'] && is_array($userROW))) {
        $JEV = 'null';
	if ($subaction == "add") {
	        // If we have an error - fill all variables again
		if (!news_add()) {
			$jarr = array();
			foreach ($_POST as $k => $v)
				if (is_array($v)) {
					foreach ($v as $vk => $vv)
						if (!is_array($vv))
							$jarr[$k][$vk] = iconv('Windows-1251', 'UTF-8', $vv);
				} else {
					$jarr[$k] = iconv('Windows-1251', 'UTF-8', $v);
				}
			$JEV = json_encode($jarr);
		}
	}

	$tvars['vars'] = array(
		'php_self'			=> $PHP_SELF,
		'changedate'		=> ChangeDate(),
		'mastercat'			=>	makeCategoryList(array('doempty' => 1, 'nameval' => 0)),
		'extcat'			=>  makeCategoryList(array('nameval' => 0, 'checkarea' => 1)),
		'JEV'			=> $JEV
	);

	$tvars['vars']['smilies']	= ($config['use_smilies'])?InsertSmilies('', 20, 'currentInputAreaID'):'';
	$tvars['vars']['quicktags']	= ($config['use_bbcodes'])?QuickTags('currentInputAreaID', 'news'):'';

	if ($userROW['status'] < 3) {
		$tvars['vars']['[options]'] = "";
		$tvars['vars']['[/options]'] = "";
	} else {
		$tvars['regx']['#\[options\].*?\[/options\]#si'] = '';
	}

	if ($config['meta']) {
		$tvars['vars']['[meta]'] = "";
		$tvars['vars']['[/meta]'] = "";
	} else {
		$tvars['regx']['#\[meta\].*?\[/meta\]#si'] = '';
	}

	if ( is_array($userROW) && ($userROW['status']== "1" || $userROW['status']== "2") ) {
		$tvars['vars']['[if-have-perm]'] = "";
		$tvars['vars']['[/if-have-perm]'] = "";
	} else {
		$tvars['regx']["'\\[if-have-perm\\].*?\\[/if-have-perm\\]'si"] = "";
	}

	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}

	$tvars['vars']['disable_flag_raw'] = $flock?'disabled':'';
	$tvars['vars']['disable_flag_html'] = $flock?'disabled':'';

	// Configure flags
	$tvars['vars']['flag_mainpage']  = (($userROW['status'] == 1)||($userROW['status'] == 2))?'checked="checked"':'disabled="disabled"';
	$tvars['vars']['flag_approve']   = (($userROW['status'] == 1)||($userROW['status'] == 2))?'checked="checked"':'disabled="disabled"';
	$tvars['vars']['flag_favorite']  = (($userROW['status'] == 1)||($userROW['status'] == 2))?'':'disabled="disabled"';
	$tvars['vars']['flag_pinned']    = (($userROW['status'] == 1)||($userROW['status'] == 2))?'':'disabled="disabled"';
	$tvars['vars']['flag_allow_com'] = 'checked="checked"';

	// Generate data for content input fields
	if ($config['news.edit.split']) {
		$tvars['regx']['#\[edit\.split\](.+?)\[\\/edit\.split\]#is']		= '$1';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\\/edit\.nosplit\]#is']	= '';
	} else {
		$tvars['regx']['#\[edit\.split\](.+?)\[\\/edit\.split\]#is']		= '';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\\/edit\.nosplit\]#is']	= '$1';
	}

	// Extended <!--more--> support
	$tvars['regx']['#\[extended\.more\](.*?)\[\/extended\.more\]#is']		= $config['extended_more']?'$1':'';

	// Disable flag for comments if plugin 'comments' is not installed
	$tvars['regx']['#\[comments\](.*?)\[\/comments\]#is'] = getPluginStatusInstalled('comments')?'$1':'';

	// Run interceptors
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsForm($tvars); }

	$tpl -> template('addnews', defined('ADMIN')?tpl_actions:tpl_site);
	$tpl -> vars('addnews', $tvars);
	if (defined('ADMIN')) {
		echo $tpl -> show('addnews');
	} else {
		$template['vars']['mainblock'] .= $tpl -> show('addnews');
	}
} else {
	msg(array("type" => "error", "text" => $lang['msge_adding']));
}