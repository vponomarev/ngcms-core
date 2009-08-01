<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: static.php
// Description: Static pages display sub-engine
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('static', 'site');

// Params - Static page characteristics
// * id			- page ID
// * altname	- alt. name of the page
function showStaticPage($params) {
	global $config, $tpl, $mysql, $userROW, $parse, $template, $lang, $SYSTEM_FLAGS, $PFILTERS;

	load_extras('static');

	$limit = '';
	if (intval($params['id'])) {
		$limit = "id = ".db_squote($params['id']);
	} elseif ($params['altname']) {
		$limit = "alt_name = ".db_squote($params['altname']);
	}

	if ((!$limit)||(!is_array($row = $mysql->record("select * from ".prefix."_static where approve = 1 and ".$limit)))) {
		if (!$params['FFC']) {
			error404();
		}
		return false;
	}

	$content	= $row['content'];

	// If HTML code is not permitted - LOCK it
	if (!($row['flags'] & 2))
		$content	= str_replace('<', '&lt;', $content);

	if ($config['blocks_for_reg'])	$content = $parse -> userblocks($content);
	if ($config['use_htmlformatter'] && (!($row['flags'] & 1)))	$content = $parse -> htmlformatter($content);
	if ($config['use_bbcodes'])		$content = $parse -> bbcodes($content);
	if ($config['use_smilies'])		$content = $parse -> smilies($content);

	$SYSTEM_FLAGS['info']['title']['item'] = secure_html($row['title']);

	$template['vars']['titles'] .= " : ".$row['title'];
	$tvars['vars'] = array(
		'title'		=> $row['title'],
		'content'	=> $content
	);

	if (is_array($userROW) && ($userROW['status'] == 1 || $userROW['status'] == 2)) {
		$tvars['vars']['[edit-static]'] = "<a href=\"".admin_url."/admin.php?mod=static&action=edit&id=".$row['id']."\" target=\"_blank\">";
		$tvars['vars']['[/edit-static]'] = "</a>";
		$tvars['vars']['[del-static]'] = "<a onClick=\"confirmit('".admin_url."/admin.php?mod=static&subaction=do_mass_delete&selected[]=".$row['id']."', '".$lang['sure_del']."')\" target=\"_blank\" style=\"cursor: pointer;\">";
		$tvars['vars']['[/del-static]'] = "</a>";
	} else {
		$tvars['regx']["'\\[edit-static\\].*?\\[/edit-static\\]'si"] = "";
		$tvars['regx']["'\\[del-static\\].*?\\[/del-static\\]'si"] = "";
	}

	if ($config['blocks_for_reg']) {
		if (is_array($userROW)) {
			$tvars['vars']['[hide]'] = "";
			$tvars['vars']['[/hide]'] = "";
		} else {
			$tvars['regx']["'\\[hide\\].*?\\[/hide\\]'si"] = "<div class=\"not_logged\">".$lang['not_logged']."</div>";
		}
	} else {
		$tvars['regx']["'\\[hide\\].*?\\[/hide\\]'si"] = "$1";
	}

	if (is_array($PFILTERS['static']))
		foreach ($PFILTERS['static'] as $k => $v) { $v->showStatic($row['id'], $row, $tvars, array()); }

	exec_acts('static', $row);

	if (!$row['template']) {
		$row['template'] = "static/default";
	} else {
		$row['template'] = 'static/'.$row['template'];
	}

	$tpl -> template($row['template'], tpl_dir.$config['theme']);
	$tpl -> vars($row['template'], $tvars);
	$template['vars']['mainblock'] .= $tpl -> show($row['template']);

	// Set meta tags for news page
	$SYSTEM_FLAGS['meta']['description'] = $row['description'];
	$SYSTEM_FLAGS['meta']['keywords']    = $row['keywords'];

	return true;
}
