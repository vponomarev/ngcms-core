<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: static.php
// Description: Manage static pages
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('static', 'admin');

// #=======================================#
// # Action selection                      #
// #=======================================#

$action		=	$_REQUEST['action'];
$subaction	=	$_REQUEST['subaction'];



if ($action == "add") {
	if ($subaction == "doadd") {
		addStatic();
	}

	addStaticForm();

} elseif ($action == "edit") {
	if ($subaction == "doedit") {
		editStatic();
	}

	editStaticForm();
} else {

	switch($subaction) {
		case 'do_mass_approve':		massStaticModify('approve = 1', 'msgo_approved',  'approve'); break;
		case 'do_mass_forbidden':	massStaticModify('approve = 0', 'msgo_forbidden', 'forbidden'); break;
		case 'do_mass_delete':		massStaticDelete(); break;
	}
	listStatic();
}


//
// Show list of static pages
//
function listStatic() {
	global $tpl, $mysql, $mod, $userROW, $config;

	$per_page	= intval($_REQUEST['per_page']);
	if (($per_page < 2)||($per_page > 500)) $per_page = 20;

	$pageNo		= intval($_REQUEST['page']);
	if ($pageNo < 1)	$pageNo = 1;

	$query = array();
	$query['sql']		= "select * from ".prefix."_static order by id desc limit ".(($pageNo - 1)* $per_page).", ".$per_page;
	$query['count']		= "select count(*) as cnt from ".prefix."_static ";

	$tpl -> template('entries', tpl_actions.$mod);

	$nCount = 0;
	foreach ($mysql->select($query['sql']) as $row) {
		$nCount++;

		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'home'		=>	home,
			'id'		=>	$row['id']
		);

		if (strlen($row['title']) > 70) {
			$row['title'] = substr($row['title'], 0, 70)." ...";
		}

		$link = checkLinkAvailable('static', '')?
					generateLink('static', '', array('altname' => $row['alt_name'], 'id' => $row['id']), array(), false, true):
					generateLink('core', 'plugin', array('plugin' => 'static'), array('altname' => $row['alt_name'], 'id' => $row['id']), false, true);

		$tvars['vars']['url']		= '<a href="'.$link.'" target="_blank">'.$link.'</a>';
		$tvars['vars']['title']		= str_replace(array("'", "\""), array("&#039;", "&quot;"), $row['title']);
		$tvars['vars']['status']	=	($row['approve']) ? '<img src="'.skins_url.'/images/yes.png" alt="'.$lang['approved'].'" />' : '<img src="'.skins_url.'/images/no.png" alt="'.$lang['unapproved'].'" />';

		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}
	unset($tvars);

	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'per_page'		=>	$per_page,
		'entries'		=>	$entries
	);

	if (!$nCount) {
		$tvars['vars']['[no-static]']		=	'';
		$tvars['vars']['[/no-static]']	=	'';
		$tvars['vars']['entries']		=	'';
		$tvars['vars']['pagesss']		=	'';
	} else {
		$tvars['regx']["'\\[no-static\\].*?\\[/no-static\\]'si"] = '';

		$cnt = $mysql->record($query['count']);
		$all_count_rec = $cnt['cnt'];

		$countPages = ceil($all_count_rec / $per_page);
 		$tvars['vars']['pagesss'] = generateAdminPagelist( array('current' => $pageNo, 'count' => $countPages, 'url' => admin_url.'/admin.php?mod=static&action=list'.($_REQUEST['per_page']?'&per_page='.$per_page:'').'&page=%page%'));

	}

	if($userROW['status'] == 1) {
		$tvars['vars']['[actions]'] = '';
		$tvars['vars']['[/actions]'] = '';
	} else {
		$tvars['regx']['#\[actions\].*?\[/actions\]#si'] = '';
	}

	exec_acts('static_list');

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}


//
// Mass static pages flags modifier
// $setValue  - what to change in table (SQL string)
// $langParam - name of variable in lang file to show on success
// $tag       - tag param to send to plugins
//
function massStaticModify($setValue, $langParam, $tag ='') {
	global $mysql, $lang;

	$selected = $_REQUEST['selected'];

	if (!$selected) {
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}

	foreach ($selected as $id) {
		$mysql->query("UPDATE ".prefix."_static SET $setValue WHERE id=".db_squote($id));
	}
	msg(array("text" => $lang[$langParam]));
}


//
// Mass static pages delete
//
function massStaticDelete() {
	global $mysql, $lang, $PFILTERS;

	$selected = $_REQUEST['selected'];

	if (!$selected) {
		msg(array("type" => "error", "text" => $lang['msge_selectnews'], "info" => $lang['msgi_selectnews']));
		return;
	}

	foreach ($selected as $id) {
		if ($srow = $mysql->record("select * from ".prefix."_static where id = ".db_squote($id))) {
			if (is_array($PFILTERS['static']))
				foreach ($PFILTERS['static'] as $k => $v) { $v->deleteStatic($srow['id'], $srow); }
			$mysql->query("delete from ".prefix."_static where id=".db_squote($id));
		}

	}
	msg(array("text" => $lang['msgo_deleted']));
}


//
// List available templates
//
function staticListTemplates($default = ''){
	global $config;

	$list = ListFiles(tpl_dir.$config['theme'].'/static', 'tpl');
	$output = '<option value=""></option>';

	foreach ($list as $fn) {
		if (preg_match('#\.(print|main)$#', $fn)) { continue; }
		$output .= '<option value="'.$fn.'"'.(($fn==$default)?' selected="selected"':'').'>'.$fn.'</option>';
	}
	return $output;
}


//
// Add static page form
//
function addStaticForm(){
	global $mysql, $lang, $userROW, $parse, $PFILTERS, $config, $PHP_SELF, $tpl;
	global $mod;

	$tvars['vars'] = array(
		'php_self'			=>	$PHP_SELF,
		'quicktags'			=>	QuickTags('currentInputAreaID', 'static'),
		'templateopts'		=> staticListTemplates(''),
	);

	if ($config['use_smilies']) {
		$tvars['vars']['smilies'] = InsertSmilies('content', 20);
	} else {
		$tvars['vars']['smilies'] = '';
	}

	if ($userROW['status'] < 3) {
		$tvars['vars']['[options]'] = "";
		$tvars['vars']['[/options]'] = "";
	} else {
		$tvars['regx']["'\\[options\\].*?\\[/options\\]'si"] = "";
	}

	if ($config['meta']) {
		$tvars['vars']['[meta]'] = "";
		$tvars['vars']['[/meta]'] = "";
	} else {
		$tvars['regx']["'\\[meta\\].*?\\[/meta\\]'si"] = "";
	}

	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}

	$tvars['vars']['disable_flag_main']	= $flock?'disabled':'';
	$tvars['vars']['disable_flag_raw']	= $flock?'disabled':'';
	$tvars['vars']['disable_flag_html'] = $flock?'disabled':'';
	$tvars['vars']['flag_approve']		= 'checked="checked"';
	$tvars['vars']['template']			= '';

	exec_acts('addstatic');

	if (is_array($PFILTERS['static']))
		foreach ($PFILTERS['static'] as $k => $v) { $v->addStaticForm($tvars); }


	$tpl -> template('add', tpl_actions.$mod);
	$tpl -> vars('add', $tvars);
	echo $tpl -> show('add');
}


//
// Add static page
//
function addStatic(){
	global $mysql, $parse, $PFILTERS, $lang, $config, $userROW;

	$title = $_REQUEST['title'];
	$content = $_REQUEST['content'];
	$content = str_replace("\r\n", "\n", $content);

	$alt_name = strtolower($parse->translit( trim($_REQUEST['alt_name']),1));


	if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return 0;
	}

	$SQL['title'] = $title;

	// Check for dup if alt_name is specified
	if ($alt_name) {
		if ( is_array($mysql->record("select id from ".prefix."_static where alt_name = ".db_squote($alt_name)." limit 1")) ) {
			msg(array("type" => "error", "text" => $lang['msge_alt_name'], "info" => $lang['msgi_alt_name']));
			return;
		}
		$SQL['alt_name'] = $alt_name;
	} else {
		// Generate uniq alt_name if no alt_name specified
		$alt_name = strtolower($parse->translit(trim($title),1));
		$i = '';
		while ( is_array($mysql->record("select id from ".prefix."_static where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
			$i++;
		}
		$SQL['alt_name'] = $alt_name.$i;
	}

	if ($config['meta']) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	$SQL['content']		= $content;

	$SQL['template']	= $_REQUEST['template'];
	$SQL['approve']		= intval($_REQUEST['approve']);

	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	$SQL['flags'] = 0;
	switch ($userROW['status']) {
		case 1:		// admin can do anything
			$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0) + ($_REQUEST['flag_MAIN']?4:0);
			break;

		case 2:		// Editor. Check if we have permissions (only admin can have own mainpage)
			if (!$config['htmlsecure_2'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 3:		// Journalists. Check if we have permissions (only admin can have own mainpage)
			if (!$config['htmlsecure_3'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;

		case 4:		// Commentors. Check if we have permissions (only admin can have own mainpage)
			if (!$config['htmlsecure_4'])
				$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0);
			break;
	}


	if (is_array($PFILTERS['static']))
		foreach ($PFILTERS['static'] as $k => $v) { $v->addStatic($tvars, $SQL); }

	$vnames = array(); $vparams = array();
	foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }

	$mysql->query("insert into ".prefix."_static (".implode(",",$vnames).") values (".implode(",",$vparams).")");
	$id = $mysql->result("SELECT LAST_INSERT_ID() as id");

	$link = (checkLinkAvailable('static', '')?
				generateLink('static', '', array('altname' => $SQL['alt_name'], 'id' => $id), array(), false, true):
				generateLink('core', 'plugin', array('plugin' => 'static'), array('altname' => $SQL['alt_name'], 'id' => $id), false, true));

	msg(array(
		"text" => str_replace('{url}',$link , $lang['msg.added']),
		"info" => str_replace(array('{url}', '{url_edit}', '{url_list}'), array($link, $PHP_SELF.'?mod=static&action=edit&id='.$id, $PHP_SELF.'?mod=static'), $lang['msg.added#descr'])));
}


//
// Edit static page :: FORM
//
function editStaticForm(){
	global $lang, $parse, $mysql, $config, $tpl, $mod, $PFILTERS, $tvars, $userROW;
	global $title, $contentshort, $contentfull, $alt_name, $id, $c_day, $c_month, $c_year, $c_hour, $c_minute;

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_static where id = ".db_squote($_REQUEST['id'])))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	$tvars['vars'] = array(
		'php_self'			=>	$PHP_SELF,
		'quicktags'			=>	QuickTags('currentInputAreaID', 'static'),
		'id'				=>	$row['id'],
		'title'				=>	secure_html($row['title']),
		'content'			=>	secure_html($row['content']),
		'alt_name'			=>	$row['alt_name'],
		'template'			=>	$row['template'],
		'templateopts'		=> staticListTemplates($row['template']),
		'description'		=>	$row['description'],
		'keywords'			=>	$row['keywords']
	);

	if ($config['use_smilies']) {
		$tvars['vars']['smilies'] = InsertSmilies('content', 20);
	} else {
		$tvars['vars']['smilies'] = '';
	}

	if ($userROW['status'] < 3) {
		$tvars['vars']['[options]'] = '';
		$tvars['vars']['[/options]'] = '';
		$tvars['regx']["'\\[hidden\\].*?\\[/hidden\\]'si"] = '';
	} else {
		$tvars['vars']['[hidden]'] = '';
		$tvars['vars']['[/hidden]'] = '';
		$tvars['regx']["'\\[options\\].*?\\[/options\\]'si"] = '';
	}

	$tvars['vars']['flag_approve'] = ($row['approve']) ? 'checked' : '';

	if ($config['meta']) {
		$tvars['vars']['[meta]'] = '';
		$tvars['vars']['[/meta]'] = '';
	} else{
		$tvars['regx']["'\\[meta\\].*?\\[/meta\\]'si"] = '';
	}

	// Additional flags
	$tvars['vars']['ifraw']		=	($row['flags'] & 1) ? 'checked' : '';
	$tvars['vars']['ifhtml']	=	($row['flags'] & 2) ? 'checked' : '';
	$tvars['vars']['ifmain']	=	($row['flags'] & 4) ? 'checked' : '';

	$flock = 0;
	switch ($userROW['status']) {
		case 2:		if ($config['htmlsecure_2']) $flock = 1;	break;
		case 3:		if ($config['htmlsecure_3']) $flock = 1;	break;
		case 4:		if ($config['htmlsecure_4']) $flock = 1;	break;
	}
	$tvars['vars']['disable_flag_main']	= ($userROW['status'] > 1)?'disabled':'';
	$tvars['vars']['disable_flag_raw']	= $flock?'disabled':'';
	$tvars['vars']['disable_flag_html']	= $flock?'disabled':'';

	exec_acts('editstatic');

	if (is_array($PFILTERS['static']))
		foreach ($PFILTERS['static'] as $k => $v) { $v->editStaticForm($row['id'], $row, $tvars); }

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	echo $tpl -> show('edit');
}


//
// Edit static page
//
function editStatic(){
	global $mysql, $parse, $PFILTERS, $lang, $config, $userROW;

	$id			=	intval($_REQUEST['id']);
	$title		=	$_REQUEST['title'];
	$content	=	$_REQUEST['content'];
	$alt_name	=	$parse->translit(trim($_REQUEST['alt_name']),1);

	// Try to find news that we're trying to edit
	if (!is_array($row = $mysql->record("select * from ".prefix."_static where id=".db_squote($id)))) {
		msg(array("type" => "error", "text" => $lang['msge_not_found']));
		return;
	}

	if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
		msg(array("type" => "error", "text" => $lang['msge_fields'], "info" => $lang['msgi_fields']));
		return 0;
	}

	$SQL['title'] = $title;

	// Check for dup if alt_name is specified
	if ( is_array($mysql->record("select id from ".prefix."_static where alt_name = ".db_squote($alt_name)." and id <> ".$row['id']." limit 1")) ) {
		msg(array("type" => "error", "text" => $lang['msge_alt_name'], "info" => $lang['msgi_alt_name']));
		return;
	}
	$SQL['alt_name'] = $alt_name;


	if ($config['meta']) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	$SQL['content']		= $content;

	$SQL['template']	= $_REQUEST['template'];
	$SQL['approve']		= intval($_REQUEST['approve']);


	// Variable FLAGS is a bit-variable:
	// 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
	// 1 = HTML enable	[if set, HTML codes may be used in news]

	$SQL['flags'] = 0;
	switch ($userROW['status']) {
		case 1:		// admin can do anything
			$SQL['flags']	=	($_REQUEST['flag_RAW']?1:0) + ($_REQUEST['flag_HTML']?2:0) + ($_REQUEST['flag_MAIN']?4:0);;
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

	if (is_array($PFILTERS['static']))
		foreach ($PFILTERS['static'] as $k => $v) { $v->editStatic($row['id'], $row, $SQL, $tvars); }

	$SQLparams = array();
	foreach ($SQL as $k => $v) { $SQLparams[] = $k.' = '.db_squote($v); }

	$mysql->query("update ".prefix."_static set ".implode(", ",$SQLparams)." where id = ".db_squote($id));

	$link = (checkLinkAvailable('static', '')?
				generateLink('static', '', array('altname' => $SQL['alt_name'], 'id' => $id), array(), false, true):
				generateLink('core', 'plugin', array('plugin' => 'static'), array('altname' => $SQL['alt_name'], 'id' => $id), false, true));
	msg(array(
		"text" => str_replace('{url}',$link , $lang['msg.edited']),
		"info" => str_replace(array('{url}', '{url_edit}', '{url_list}'), array($link, $PHP_SELF.'?mod=static&action=edit&id='.$id, $PHP_SELF.'?mod=static'), $lang['msg.edited#descr'])));

}