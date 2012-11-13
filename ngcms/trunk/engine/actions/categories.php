<?php

//
// Copyright (C) 2006-2011 Next Generation CMS (http://ngcms.ru/)
// Name: categories.php
// Description: Category management
// Author: Vitaly Ponomarev
//


// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


// Load library
@include_once(root.'actions/categories.rpc.php');

$lang = LoadLang('categories', 'admin');

function listSubdirs($dir) {
	$list = array();
	if ($h = @opendir($dir)) {
		while (($fn = readdir($h)) !== false) {
			if (($fn != '.') && ($fn != '..') && is_dir($dir.'/'.$fn))
				array_push($list, $fn);
		}
		closedir($h);
	};

	return $list;
}




// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: form for adding category
// ///////////////////////////////////////////////////////////////////////////
//
function admCategoryAddForm(){
	global $mysql, $tpl, $mod, $PHP_SELF, $config, $lang, $AFILTERS;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	$tpl -> template('add', tpl_actions.$mod);

	$tpl_list = '<option value="">* '.$lang['cat_tpldefault']." *</option>\n";
	foreach (listSubdirs(tpl_site.'ncustom/') as $k) {
		$tpl_list .= '<option value="'.secure_html($k).'"'.(($row['tpl'] == $k)?' selected="selected"':'').'>'.secure_html($k)."</option>\n";
	}

	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'parent'	=>	makeCategoryList(array('name' => 'parent', 'doempty' => 1, 'resync' => ($_REQUEST['action']?1:0))),
		'orderlist'	=>	OrderList(''),
		'token'		=> genUToken('admin.categories'),
		'tpl_list'	=> $tpl_list
	);

	if ($config['meta']) {
		$tvars['vars']['[meta]']	=	'';
		$tvars['vars']['[/meta]']	=	'';
	} else{
		$tvars['regx']["'\\[meta\\].*?\\[/meta\\]'si"] = "";
	}
	$tvars['vars']['extend'] = '';

	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) { $v->addCategoryForm($tvars); }

	$tpl -> vars('add', $tvars);
	echo $tpl -> show('add');
}

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: add new category
// ///////////////////////////////////////////////////////////////////////////
//
function admCategoryAdd() {
	global $mysql, $lang, $mod, $tpl, $parse, $config, $AFILTERS;

	$SQL			= array();
	$SQL['name']	= secure_html(trim($_REQUEST['name']));
	$SQL['alt']		= trim($_REQUEST['alt']);
	$SQL['parent']	= intval($_REQUEST['parent']);
	$SQL['icon']	= $_REQUEST['icon'];
	$SQL['alt_url']	= $_REQUEST['alt_url'];
	$SQL['orderby']	= $_REQUEST['orderby'];
	$SQL['tpl']		= $_REQUEST['tpl'];
	$SQL['number']	= intval($_REQUEST['number']);

	$SQL['flags']	= intval($_REQUEST['cat_show'])?'1':'0';
	$SQL['flags']  .= (string) (abs(intval($_REQUEST['show_link'])<=2)?abs(intval($_REQUEST['show_link'])):'0');

	$category		= intval($_REQUEST['category']);

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.categories'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	if (!$SQL['name']) {
		msg(array("type" => "error", "text" => $lang['msge_name'], "info" => $lang['msgi_name']));
		return;
	}

	// IF alt name is set:
	if ($SQL['alt'] != '') {
		// - check for allowed chars
		if (!$parse->nameCheck($SQL['alt'])) {
			// ERROR
			msg(array("type" => "error", "text" => $lang['category.err.wrongalt'], "info" => $lang['category.err.wrongalt#desc']));
			return;
		}

		// - check for duplicate alt name
		if (is_array($mysql->record("select * from ".prefix."_category where lower(alt) = ".db_squote($SQL['alt'])))) {
			msg(array("type" => "error", "text" => $lang['category.err.dupalt'], "info" => $lang['category.err.dupalt#desc']));
			return;
		}
	} else {
		// alt name was not set, generate new alt name in automatic mode
		$SQL['alt']		= strtolower($parse->translit($SQL['name']));

		$i = '';
		while ( is_array($mysql->record("select id from ".prefix."_category where alt = ".db_squote($SQL['alt'].$i)." limit 1")) ) {
			$i++;
		}
		$SQL['alt'] = $SQL['alt'].$i;
	}

	if ($config['meta']) {
		$SQL['description']	= secure_html(trim($_REQUEST['description']));
		$SQL['keywords']	= secure_html(trim($_REQUEST['keywords']));
	}

	$pluginNoError = 1;
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) {
			if (!($pluginNoError = $v->addCategory($tvars, $SQL))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$SQLout = array();
	foreach ($SQL as $k => $v)
		$SQLout[$k] = db_squote($v);

	// Add new record into SQL table
	$mysql->query("insert into ".prefix."_category (".join(", ", array_keys($SQLout)).") values (".join(", ", array_values($SQLout)).")");
	$rowID = $mysql->record("select LAST_INSERT_ID() as id");

	$fmanager = new file_managment();
	$imanager = new image_managment();

	// Check if new image was attached
	if (isset($_FILES) && isset($_FILES['image']) && is_array($_FILES['image']) && isset($_FILES['image']['error']) && ($_FILES['image']['error'] == 0)) {
		// new file is uploaded
		$up = $fmanager->file_upload(array('dsn' => true, 'linked_ds' => 2, 'linked_id' => $rowID['id'], 'type' => 'image', 'http_var' => 'image', 'http_varnum' => 0));
		//print "OUT: <pre>".var_export($up, true)."</pre>";
		if (is_array($up)) {
			// Image is uploaded. Let's update image params
			//print "<pre>UPLOADED. ret:".var_export($up, true)."</pre>";

			$img_width = 0;
			$img_height = 0;
			$img_preview = 0;
			$img_pwidth = 0;
			$img_pheight = 0;

			if (is_array($sz = $imanager->get_size($config['attach_dir'].$up[2].'/'.$up[1]))) {
				//print "<pre>IMG SIZE. ret:".var_export($sz, true)."</pre>";

				$img_width = $sz[1];
				$img_height = $sz[2];

				$tsz = intval($config['thumb_size']);
				if (($tsz < 10)||($tsz > 1000)) $tsz = 150;
				$thumb = $imanager->create_thumb($config['attach_dir'].$up[2], $up[1], $tsz,$tsz, $config['thumb_quality']);
				if ($thumb) {
					$img_preview = 1;
					$img_pwidth = $thumb[0];
					$img_pheight = $thumb[1];
					//print "<pre>THUMB CREATED. ret:".var_export($thumb, true)."</pre>";
				}
			}

			// Update table 'images'
			$mysql->query("update ".prefix."_images set width=".db_squote($img_width).", height=".db_squote($img_height).", preview=".db_squote($img_preview).", p_width=".db_squote($img_pwidth).", p_height=".db_squote($img_pheight)." where id = ".db_squote($up[0]));

			// Update table 'categories'
			$mysql->query("update ".prefix."_category set image_id = ".db_squote($up[0])." where id = ".db_squote($rowID['id']));
		}

	}


	// Report about adding new category
	msg(array("text" => $lang['msgo_added']));
}

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: form for editing category
// ///////////////////////////////////////////////////////////////////////////
//
function admCategoryEditForm(){
	global $mysql, $lang, $mod, $tpl, $config, $AFILTERS;

	// Check for permissions
	$permModify		= checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify');
	$permDetails	= checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'details');

	if (!$permModify && !$permDetails) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	$catid = intval($_REQUEST['catid']);
	if (!is_array($row=$mysql->record("select nc.*, ni.id as icon_id, ni.name as icon_name, ni.storage as icon_storage, ni.folder as icon_folder, ni.preview as icon_preview, ni.width as icon_width, ni.height as icon_height, ni.p_width as icon_pwidth, ni.p_height as icon_pheight from `".prefix."_category` as nc left join `".prefix."_images` ni on nc.image_id = ni.id where nc.id = ".db_squote($catid)." order by nc.posorder asc", 1))) {
		msg(array("type" => "error", "text" => $lang['msge_id'], "info" => sprintf($lang['msgi_id'], $PHP_SELF.'?mod=categories')));
		return;
	}

	$tpl_list = '<option value="">* '.$lang['cat_tpldefault']." *</option>\n";
	foreach (listSubdirs(tpl_site.'ncustom/') as $k) {
		$tpl_list .= '<option value="'.secure_html($k).'"'.(($row['tpl'] == $k)?' selected="selected"':'').'>'.secure_html($k)."</option>\n";
	}

	$showLink = '';
	foreach (array('always', 'ifnews', 'never') as $k => $v) {
		$showLink .= '<option value="'.$k.'"'.(($k == intval(substr($row['flags'], 1, 1)))?' selected="selected"':'').'>'.$lang['link.'.$v].'</option>';
	}

	$tvars['vars'] = array(
		'php_self'		=>	$PHP_SELF,
		'parent'		=> makeCategoryList(array('name' => 'parent', 'selected' => $row['parent'], 'skip' => $row['id'], 'doempty' => 1)),
		'catid'			=>	$row['id'],
		'name'			=>	secure_html($row['name']),
		'alt'			=>	secure_html($row['alt']),
		'alt_url'		=>	secure_html($row['alt_url']),
		'orderlist'		=>	OrderList($row['orderby'], true),
		'description'	=>	secure_html($row['description']),
		'keywords'		=>	secure_html($row['keywords']),
		'icon'			=>	secure_html($row['icon']),
		'check'			=>	(substr($row['flags'],0, 1))?'checked="checked"':'',
		'tpl_value'		=>	secure_html($row['tpl']),
		'number'		=>	$row['number'],
		'show.link'		=>  $showLink,
		'tpl_list'		=>	$tpl_list,
		'info'			=>	secure_html($row['info']),
		'token'			=> genUToken('admin.categories'),
	);

	$tvars['regx']['#\[perm\.modify\](.*?)\[\/perm\.modify\]#is'] = $permModify?'$1':'';

	// Check for attached images
	if ($row['icon_id']) {
		$tvars['regx']['#\[is\.attach\](.*?)\[\/is\.attach\]#is'] = '$1';
		$tvars['vars']['attach_url'] = $config['attach_url'].'/'.$row['icon_folder'].'/'.($row['icon_preview']?'thumb/':'').$row['icon_name'];

	} else {
		$tvars['regx']['#\[is\.attach\](.*?)\[\/is\.attach\]#is'] = '';
	}

	if ($config['meta']) {
		$tvars['vars']['[meta]']	=	'';
		$tvars['vars']['[/meta]']	=	'';
	} else{
		$tvars['regx']['/\[meta\].*?\[\/meta\]/si'] = '';
	}

	$tvars['vars']['extend'] = '';
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) { $v->editCategoryForm($catid, $row, $tvars); }

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', $tvars);
	echo $tpl -> show('edit');
}

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: edit category
// ///////////////////////////////////////////////////////////////////////////
//
function admCategoryEdit(){
	global $mysql, $lang, $config, $parse, $catz, $catmap, $AFILTERS;

	//print "<pre>POST DATA:\n".var_export($_POST, true)."\n\nFILES: ".var_export($_FILES, true)."</pre>";

	$SQL			= array();
	$SQL['name']	= $_REQUEST['name'];
	$SQL['info']	= $_REQUEST['info'];
	$SQL['alt']		= trim($_REQUEST['alt']);
	$SQL['parent']	= intval($_REQUEST['parent']);
	$SQL['icon']	= $_REQUEST['icon'];
	$SQL['alt_url']	= $_REQUEST['alt_url'];
	$SQL['orderby']	= $_REQUEST['orderby'];
	$SQL['tpl']		= $_REQUEST['tpl'];
	$SQL['number']	= intval($_REQUEST['number']);

	$SQL['flags']	= intval($_REQUEST['cat_show'])?'1':'0';
	$SQL['flags']  .= (string) (abs(intval($_REQUEST['show_link'])<=2)?abs(intval($_REQUEST['show_link'])):'0');

	$catid			= intval($_REQUEST['catid']);

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'categories'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.categories'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}

	if (!$SQL['name'] || !$catid || (!is_array($SQLold = $catz[$catmap[$catid]]))) {
		msg(array("type" => "error", "text" => $lang['msge_name'], "info" => $lang['msgi_name']));
		return;
	}

	if (!$catid || (!is_array($SQLold = $catz[$catmap[$catid]]))) {
		msg(array("type" => "error", "text" => $lang['msge_id'], "info" => $lang['msgi_id']));
		return;
	}

	// Check alt name in case it was changed
	if ($SQL['alt'] != $catid) {
		// - check for allowed chars
		if (!$parse->nameCheck($SQL['alt'])) {
			// ERROR
			msg(array("type" => "error", "text" => $lang['category.err.wrongalt'], "info" => $lang['category.err.wrongalt#desc']));
			return;
		}

		// - check for duplicate alt name
		if (is_array($mysql->record("select * from ".prefix."_category where (id <> ".db_squote($catid).") and (lower(alt) = ".db_squote($SQL['alt']).")"))) {
			msg(array("type" => "error", "text" => $lang['category.err.dupalt'], "info" => $lang['category.err.dupalt#desc']));
			return;
		}

	}

	if ($config['meta']) {
		$SQL['description']	= secure_html(trim($_REQUEST['description']));
		$SQL['keywords']	= secure_html(trim($_REQUEST['keywords']));
	}


	$fmanager = new file_managment();
	$imanager = new image_managment();

	// Check is existent image should be deleted
	if (isset($_POST['image_del']) && $_POST['image_del'] && ($SQLold['image_id'])) {
		$fmanager->file_delete(array('type' => 'image', 'id' => $SQLold['image_id']));
		$SQL['image_id'] = 0;
	}


	// Check if new image was attached
	if (isset($_FILES) && (!$SQL['image_id']) && isset($_FILES['image']) && is_array($_FILES['image']) && isset($_FILES['image']['error']) && ($_FILES['image']['error'] == 0)) {
		// new file is uploaded
		$up = $fmanager->file_upload(array('dsn' => true, 'linked_ds' => 2, 'linked_id' => $catid, 'type' => 'image', 'http_var' => 'image', 'http_varnum' => 0));
		//print "OUT: <pre>".var_export($up, true)."</pre>";
		if (is_array($up)) {
			// Image is uploaded. Let's update image params
			//print "<pre>UPLOADED. ret:".var_export($up, true)."</pre>";

			$img_width = 0;
			$img_height = 0;
			$img_preview = 0;
			$img_pwidth = 0;
			$img_pheight = 0;

			if (is_array($sz = $imanager->get_size($config['attach_dir'].$up[2].'/'.$up[1]))) {
				//print "<pre>IMG SIZE. ret:".var_export($sz, true)."</pre>";

				$img_width = $sz[1];
				$img_height = $sz[2];

				$tsz = intval($config['thumb_size']);
				if (($tsz < 10)||($tsz > 1000)) $tsz = 150;
				$thumb = $imanager->create_thumb($config['attach_dir'].$up[2], $up[1], $tsz,$tsz, $config['thumb_quality']);
				if ($thumb) {
					$img_preview = 1;
					$img_pwidth = $thumb[0];
					$img_pheight = $thumb[1];
					//print "<pre>THUMB CREATED. ret:".var_export($thumb, true)."</pre>";
				}
			}

			// Update SQL records
			$mysql->query("update ".prefix."_images set width=".db_squote($img_width).", height=".db_squote($img_height).", preview=".db_squote($img_preview).", p_width=".db_squote($img_pwidth).", p_height=".db_squote($img_pheight)." where id = ".db_squote($up[0]));
			$SQL['image_id'] = $up[0];
		}

	}


	$pluginNoError = 1;
	if (is_array($AFILTERS['categories']))
		foreach ($AFILTERS['categories'] as $k => $v) {
			if (!($pluginNoError = $v->editCategory($catid, $SQLold, $SQL, $tvars))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$SQLout = array();
	foreach ($SQL as $var => $val)
		$SQLout []= '`'.$var.'` = '.db_squote($val);

	$mysql->query("update ".prefix."_category set ".join(", ", $SQLout)." where id=".db_squote($catid));
	msg(array("text" => $lang['msgo_saved']));
}


// ////////////////////////////////////////////////////////////////////////////
// MAIN ACTION
// ///////////////////////////////////////////////////////////////////////////
//

if ($action == 'edit') {
	admCategoryEditForm();
} else if ($action == 'add') {
	admCategoryAddForm();
} else {
	$dosort = 1;
	switch ($action) {
		case "doadd" : admCategoryAdd(); break;
		case "remove": category_remove(); break;
		case "doedit": admCategoryEdit(); break;
		default: $dosort = 0;
	}
	if ($dosort) { admCategoryReorder(); }
	admCategoryList();
}