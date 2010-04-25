<?php


//
// ======= FUNCTIONS for FILE MANAGMENT ========
//

// Get count of file in directory
function count_dir($dir){
	if ($d = @opendir($dir)) {
		$cnt = 0;
		while(($file = readdir($d)) !== false)
			if ($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				$cnt++;
	}
	return $cnt;
}

// Delete an image
function manage_delete($type){
	global $mysql, $lang, $fmanager;

	$ok = 0;
	$err = 0;
	$files = $_REQUEST['files'];
	if (is_array($files)) {
		foreach ($files as $file) {
			if ($fmanager->file_delete(array('type' => $type, 'id' => $file))) {
				$ok++;
			} else {
				$err++;
			}
		}
	}
	if ($ok && !$err) {
		msg(array("text" => sprintf($lang['msgo_deleted'], $ok)));
	} elseif ($ok && $err) {
		msg(array("type" => "error", "text" => sprintf($lang['msge_deleted'], $ok, $ok+$err)));
	} elseif (!$ok && !$err) {
		msg(array("type" => "error", "text" => $lang['msge_nodel']));
	}
}

// Move images/files
function manage_move($type){
	global $mysql, $lang, $fmanager;

	$fmanager->get_limits($type);
	$dir = $fmanager->dname;

	if (defined('DEBUG'))
		echo "CALL manage_move($type) [dir: $dir]<br>\n";

	$ok		= 0;
	$fail	= 0;
	$files		= $_REQUEST['files'];
	$category	= $_REQUEST['category'];
	if ($files && is_dir($dir.$category) && is_writable($dir.$category)) {
		foreach ($files as $file) {
			if ($fmanager->file_rename(array('type' => $type, 'id' => $file, 'move' => 1, 'newcategory' => $category))) {
				$ok++;
			} else {
				$fail++;
			}
		}
		msg(array("text" => $lang['msgo_moved']));
	} else {
		msg(array("type" => "error", "text" => $lang['msge_move']));
	}
}

// Manage image action
function manage_upload($type){
	global $config, $mysql, $fmanager, $lang;

	$subdirectory = ($_REQUEST['category']) ? $_REQUEST['category'] : 'default';
	$subdirectory = str_replace(array('\\', '/', chr(0)), array(''), $subdirectory);

	$fmanager->get_limits($type);
	$dir = $fmanager->dname;

	if ($subdirectory == "default" && !is_dir($dir.$subdirectory)) {
		@mkdir($dir.$subdirectory, 0777);
		if ($type == 'image') {
			@mkdir($dir.$subdirectory.'/thumb', 0777);
		}
	}


	if (!is_dir($dir.$subdirectory)) {
		msg(array("type" => "error", "text" => $lang['msge_catnexists']." (".$subdirectory.")"));
		return;
	}

	$imanager = new image_managment();

	// PREPARE a list for upload
	$filelist = array();

	// LOAD URL LIST
	if (is_array($urls = $_REQUEST['userurl'])) {
		// If URL upload
		foreach($urls as $url){
			array_push($filelist, array('type' => $type, 'category' => $subdirectory, 'manual' => 1, 'url' => $url, 'replace' => $_REQUEST['replace']?1:0, 'randprefix' => $_REQUEST['rand']?1:0));
		}
	};

	// LOAD ATTACHED FILE LIST
	if (is_array($_FILES['userfile']['name']))
		foreach($_FILES['userfile']['name'] as $i => $v)
			if ($v != '')
				array_push($filelist, array('type' => $type, 'category' => $subdirectory, 'http_var' => 'userfile', 'http_varnum' => $i, 'replace' => $_REQUEST['replace']?1:0, 'randprefix' => $_REQUEST['rand']?1:0));


	foreach($filelist as $fparam) {
			// Save uploaded file
			$up = $fmanager->file_upload($fparam);
			// Continue cycle if file was not uploaded
			if (!is_array($up)) {
				//print "UP failed<br>\n";
				continue;
			}

			if ($type == 'image') {
				// Check if we need image transformation
				$mkThumb  = (($config['thumb_mode']  == 2) || (!$config['thumb_mode']  && $_REQUEST['thumb']))?1:0;
				$mkStamp  = (($config['stamp_mode']  == 2) || (!$config['stamp_mode']  && $_REQUEST['stamp']))?1:0;
				$mkShadow = (($config['shadow_mode'] == 2) || (!$config['shadow_mode'] && $_REQUEST['shadow']))?1:0;

				// Make thumb if required
				$thumb = 0;

				// Prepare params for STAMP. In older versions we don't store extension and support only .gif files, in
				// newer - store stampFileName with extension and support .gif and .png
				$stampFileName = '';
				if (file_exists(root.'trash/'.$config['wm_image'].'.gif')) {
					$stampFileName = root.'trash/'.$config['wm_image'].'.gif';
				} else if (file_exists(root.'trash/'.$config['wm_image'])) {
					$stampFileName = root.'trash/'.$config['wm_image'];
				}

				if ($mkThumb) {
					$tsz = intval($config['thumb_size']);
					if (($tsz < 10)||($tsz > 1000)) $tsz = 150;
					$thumb = $imanager->create_thumb($config['images_dir'].$subdirectory, $up[1], $tsz,$tsz, $config['thumb_quality']);
					if ($thumb) {
						// If we created thumb - check if we need to transform it
						$stampThumb  = ($mkStamp  && $config['stamp_place'] && ($stampFileName != ''))?1:0;
						$shadowThumb = ($mkShadow && $config['shadow_place'])?1:0;
						if ($shadowThumb || $stampThumb) {
							$stamp = $imanager->image_transform(
								array('image' => $dir.$subdirectory.'/thumb/'.$up[1],
								'stamp' => $stampThumb,
								'stamp_transparency' => $config['wm_image_transition'],
								'shadow' => $shadowThumb,
								'stampfile' => $stampFileName));
						}
					}
				}

				$stampOrig  = ($mkStamp  && ($config['stamp_place'] != 1) && ($stampFileName != ''))?1:0;
				$shadowOrig = ($mkShadow && ($config['shadow_place'] != 1))?1:0;

				if ($shadowOrig || $stampOrig) {
					$stamp = $imanager->image_transform(
						array('image' => $dir.$subdirectory.'/'.$up[1],
						'stamp' => $stampOrig,
						'stamp_transparency' => $config['wm_image_transition'],
						'shadow' => $shadowOrig,
						'stampfile' => $stampFileName));
				}

				// Now write info about image into DB
				if (is_array($sz = $imanager->get_size($dir.$subdirectory.'/'.$up[1]))) {
					$fmanager->get_limits($type);
					$mysql->query("update ".prefix."_".$fmanager->tname." set width=".db_squote($sz[1]).", height=".db_squote($sz[2]).", preview=".db_squote($thumb).", stamp=".db_squote($stamp)." where id = ".db_squote($up[0]));
				}
			}
	}
}


//
// Show list
//
function manage_showlist($type) {
	global $config, $mysql, $tpl, $mod, $lang, $userROW, $fmanager, $langMonths;


	$cstart		= abs(intval($_REQUEST['page'])?intval($_REQUEST['page']):0);
	$start_from	= abs(intval($_REQUEST['start_from'])?intval($_REQUEST['start_from']):0);

	if (!$cstart) { $cstart = 1; }
	$news_per_page = intval($_REQUEST['news_per_page']);
	if (($news_per_page < 1)||($news_per_page > 500))
		$news_per_page = 20;

	if (!$start_from) $start_from = ($cstart - 1)*$news_per_page;


	// Filter category if we work with images
	if ($type == 'image') {
		$filter			= array('category = 0');
	} else {
		$filter			= array();
	}

	if ($_REQUEST['author'])
		array_push($filter, "user = ".db_squote($_REQUEST['author']));

	if ($_REQUEST['category'])
		array_push($filter, "folder = ".db_squote($_REQUEST['category']));

	if ($userROW['status'] > 2)
		array_push($filter, "owner_id=".db_squote($userROW['id']));

	if ($_REQUEST['postdate'] && preg_match('/^(\d{4})(\d{2})$/', $_REQUEST['postdate'], $match))
		array_push($filter, "(month(from_unixtime(date)) = ".db_squote($match[2])." and year(from_unixtime(date)) = ".db_squote($match[1]).")");

	// Determine SQL table / directory for files
	$fmanager->get_limits($type);
	$dir = $fmanager->dname;

	array_push($filter, 'storage = 0');
	$limit				= (count($filter)?"where ".join(" and ",$filter):'');
	$query['sql']		= "select * from ".prefix."_".$fmanager->tname." ".$limit." order by date desc limit ".$start_from.", ".$news_per_page;
	$query['count']		= "select count(*) as cnt from ".prefix."_".$fmanager->tname." ".$limit;


	$nCount = 0;
	foreach ($mysql->select($query['sql']) as $row) {
		$nCount++;
		$folder				=	$row['folder']?$row['folder'].'/':'';
		$fname				=	$fmanager->dname.$folder.$row['name'];
		$fileurl			=	$fmanager->uname.'/'.$folder.$row['name'];
		$thumburl			=	$fmanager->uname.'/'.$folder.'thumb/'.$row['name'];
		if (is_readable($fname)) {
			$fsize		=	FormatSize(filesize($fname));
		} else {
			$fsize		=	'-';
		}

		list($html_thumb, $html_file, $html_preview) =	str_replace(
									array ('{file_url}', '{thumb_url}', '{file_name}', '{fsize}', '{image_height}', '{image_width}'),
									array ( $fileurl, $thumburl, $row['name'], $fsize, $row['height'], $row['width']),
									array($lang['insert_thumb'], $lang['insert_file'], $lang['insert_preview']));

		$addtime			=	LangDate("d.m.Y", $row['date']);
		$rename				=	($userROW['status'] == 1 || $userROW['status'] == 2 || (is_array($userROW) && ($row['ownerID'] == $userROW['id']))) ? "<a href=\"?mod=".($type=="image"?'images':'files')."&amp;subaction=rename&amp;of=$row[name]&amp;category=$row[folder]&amp;id=$row[id]\" onclick=\"if(ren=window.prompt('$lang[name]:','$row[name]')){ window.location.href=this.href+'&rf='+ren; } return false;\">".'<img src="'.skins_url.'/images/rename.gif" border="0"/></a>' : '';
		if ($type == 'image') {
		} else {
			$file_link			=	'<a href="'.$fileurl.'" title="'.$row['name'].'" target="_blank">'.$row['orig_name'].'</a> ';
		}

		$tpl -> template('entries', tpl_actions.$mod);
		$tvars['vars'] = array(
			'rename'		=>	$rename,
			'code'			=>	$html_code,
//			'insert_file'	=>	$insert_file,
//			'insert_thumb'	=>	$row['preview'] ? $insert_thumb : '',
//			'insert_preview'=>  $row['preview'] ? $insert_preview : '',
			'view_file'		=>	$view_file,
			'view_thumb'	=>	$row['preview'] ? $row['view_thumb'] : '',
			'file_link'		=>	$file_link,
			'file_name'		=>	$row['orig_name'],
			'id'			=>	$row['id'],
			'width'			=>	$row['width'],
			'height'		=>	$row['height'],
			'preview_size'	=>	(($type == 'image') && $row['p_width'] && $row['p_height'])?$row['p_width'].' x '.$row['p_height']:'',
			'preview_img'	=>  (($type == 'image') && $row['preview'])?'<img src="'.$thumburl.'"/><br/>':'',
			'p_width'		=>  $row['p_width'],
			'p_height'		=>  $row['p_height'],
			'size'			=>	$fsize,
			'folder'		=>	$row['folder'],
			'user'			=>	$row['user'],
		);

		if ($type == 'image') {
			$tvars['vars']['insert_file']    = '<a href="javascript:insertimage(\''.$html_file.'\', \''.$_REQUEST['ifield'].'\')" title="Insert file"><img src="'.skins_url.'/images/insert_image.png" border="0"/></a>';
			$tvars['vars']['insert_thumb']   = $row['preview'] ? '<a href="javascript:insertimage(\''.$html_thumb.'\', \''.$_REQUEST['ifield'].'\')"><img src="'.skins_url.'/images/insert_thumb.png" border="0"/></a>' : '';
			$tvars['vars']['insert_preview'] = $row['preview'] ? '<a href="javascript:insertimage(\''.$html_preview.'\', \''.$_REQUEST['ifield'].'\')"><img src="'.skins_url.'/images/insert_preview.png" border="0"/></a>' : '';

			$tvars['vars']['view_file']      = '<a target="_blank" href="'.$fileurl.'"><img src="'.skins_url.'/images/insert_image.png" border="0"/></a>';
			$tvars['vars']['view_thumb']     = $row['preview'] ? '<a target="_blank" href="'.$thumburl.'"><img src="'.skins_url.'/images/insert_thumb.png" border="0"/></a>' : '';
		} else {
			$tvars['vars']['insert_file']    = '<a href="javascript:insertimage(\''.$html_file.'\', \''.$_REQUEST['ifield'].'\')">'.$lang['insert'].'</a>';

		}

		$tvars['regx']['#\[preview\](.+?)\[/preview\]#is'] = $_COOKIE['img_preview']?'$1':'';

		if (($type == 'image') && ($row['preview'])) {
//			$tvars['vars']['preview_img']  = '';
//			$tvars['vars']['preview_size'] = '';
		} else {
//			$tvars['vars']['preview_img']  = '';
//			$tvars['vars']['preview_size'] = '';
		}

		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	foreach ($mysql->select("SELECT DISTINCT FROM_UNIXTIME(date,'%Y%m') as monthes, COUNT(date) AS cnt FROM ".prefix."_".$fmanager->tname." GROUP BY monthes ORDER BY date DESC") as $row) {
	        if (preg_match('/^(\d{4})(\d{2})$/', $row['monthes'], $match)) {
	        	$dateslist .= "<option value=\"".$row['monthes']."\"".($row['monthes'] == $_REQUEST['postdate']?' selected':'').">".$langMonths[$match[2]-1]." ".$match[1]."</option>";
	        }
	}

	if ($userROW['status'] == 4) {
		// Just commentors. They will see only their files
		$authorlist = "<option value=\"".$userROW['name']."\">".$userROW['name']."</option>";
	} else {
		foreach ($mysql->select("select user, owner_id, count(id) cnt from ".prefix."_".$fmanager->tname." group by owner_id order by user") as $row) {
			$authorlist .= "<option value=\"".$row['user']."\"".($row['user']==$_REQUEST['author']?' selected':'').">".$row['user']."(".$row['cnt'].")</option>\n";
		}
	}

	if (is_array($pcnt = $mysql->record($query['count']))) {
		$itemCount = $pcnt['cnt'];
		$pagesCount = ceil($itemCount / $news_per_page);

		if ($pagesCount) {
			$pagesss = generateAdminPagelist( array('current' => $cstart, 'count' => $pagesCount, 'url' => admin_url.'/admin.php?mod='.$type.'s&action=list'.($_REQUEST['news_per_page']?'&news_per_page='.$news_per_page:'').($_REQUEST['author']?'&author='.$_REQUEST['author']:'').($_REQUEST['category']?'&category='.$_REQUEST['category']:'').($_REQUEST['postdate']?'&postdate='.$_REQUEST['postdate']:'').'&page=%page%'));
		}
	}

	if (!$nCount) {
		$entries = "<tr><td colspan=7><p align=center><b>".$lang['not_found']."</b></p></td></tr>";
	}

	$dirlist	=	ListDirs($type.'s', false, 0);
	$dirlistcat	=	ListDirs($type.'s', $_REQUEST['category']);

	$tpl -> template('table', tpl_actions.$mod);
	$tvars['vars'] = array(
		'php_self'			=>	$PHP_SELF,
		'dateslist'			=>	$dateslist,
		'dirlist'			=>	$dirlist,
		'authorlist'		=>	$authorlist,
		'news_per_page'		=>	$news_per_page,
		'entries'			=>	$entries,
		'pagesss'			=>	$pagesss,
		'dirlistcat'		=>	$dirlistcat,
		'news_per_page'		=>	$news_per_page,
		'area'				=>	($area) ? $area : '',
		'shadow_mode'		=>	$config['shadow_mode']?'disabled':'',
		'stamp_mode'		=>	$config['stamp_mode']?'disabled':'',
		'thumb_mode'		=>	$config['thumb_mode']?'disabled':'',
		'shadow_checked'	=>	($config['shadow_mode'] == 2)?' checked':'',
		'stamp_checked'		=>	($config['stamp_mode'] == 2)?' checked':'',
		'thumb_checked'		=>	($config['thumb_mode'] == 2)?' checked':'',
		'box_preview'	=> ($_COOKIE['img_preview']?' checked="checked"':''),
	);

	$tvars['regx']['#\[preview\](.+?)\[/preview\]#is'] = $_COOKIE['img_preview']?'$1':'';

	if ($userROW['status'] < "3") {
		$tvars['vars']['[status]']	=	'';
		$tvars['vars']['[/status]']	=	'';
	} else {
		$tvars['regx']["'\\[status\\].*?\\[/status\\]'si"] = "";
	}
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}