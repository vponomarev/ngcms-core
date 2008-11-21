<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: templates.php
// Description: Manage/Edit templates
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('templates', 'admin');

function GetFiles($dir, $path, $list = false) {
	global $global;
	//print "call GetFiles('$dir', '$path', '$list')<br/>\n";
	$files		=	array();
	$directories=	array();
	$indir		=	$dir;
	$dir		=	(!$list) ? ($dir ? '/'.$dir.'/' : '') : '';
	$handle		=	$path.$dir;
	$fo			=	opendir($handle);

	while ($fp = readdir($fo)) {
		if ($fp != "." && $fp != "..") {
			if (is_dir($handle.'/'.$fp)) {
				if (!$list) {
					//print "ready to call ($indir)($fp)($path)<br/>\n";
					$array = GetFiles(($indir?($indir.'/'):'').$fp, $path);
					$files = array_merge($files, $array['files']);
					$directories = array_merge($directories, $array['directories']);
				}
				$directories[] = array('parent' => $dir, 'name' => $fp);
			}
			else {
				if (!$global) {
					if (ereg('.tpl', $fp)) {
						$files[] = array('dir' => ($dir ? $dir : ''), 'name' => $fp);
					}
				}
				else {
					$files[] = array('dir' => ($dir ? $dir : ''), 'name' => $fp);
				}
			}
		}
	}
	closedir($fo);

	return array('files' => $files, 'directories' => $directories);
}

function GetFilesArray($theme, $where) {

	if ($where == "actions") { $path = root.'skins/'.$theme.'/tpl'; }
	elseif ($where == "extras") { $path = root.'plugins/'; }
	elseif ($where == "site") { $path = tpl_dir.$theme; }

	$gf = GetFiles('', $path);
	//print "<pre>"; var_dump($gf); print "</pre>";
	return $gf;
}

function GetFilesGlobal($theme, $where) {

	if ($where == "actions") { $path = root.'skins/'.$theme; }
	elseif ($where == "site") { $path = tpl_dir.$theme; }

	return GetFiles('', $path);
}

function GetThemes($where, $select = true) {
	global $theme, $skin;

	if ($where == "actions") { $path = root.'skins/'; $what = $skin; }
	elseif ($where == "site") { $path = tpl_dir; $what = $theme; }

	$array = GetFiles('', $path, true);

	foreach ($array['directories'] as $dir) {
		if ($dir['name'] == $what) {
			$selected = ' selected';
		}
		else {
			$selected = '';
		}

		$whats .= '<option value="'.$dir['name'].'"'.$selected.'>'.$dir['name'].'</option>';
	}

	return ($select ? $whats : $array);
}

//
// ================================================================== //
//                              CODE                                  //
// ================================================================== //
//

// Check & clean filename
$action			= $_REQUEST['action'];
$filename		= str_replace(array(' ', chr(0), '/../'), '', $_REQUEST['filename']);
$newfilename	= str_replace(array(' ', chr(0), '/../'), '', $_REQUEST['newfilename']);
$skin			= 'default';
$theme			= str_replace(array(' ', chr(0), '/../'), '', $_REQUEST['theme']);
$theme_name		= str_replace(array(' ', chr(0), '/../'), '', $_REQUEST['theme_name']);
$new_theme_name	= str_replace(array(' ', chr(0), '/../'), '', $_REQUEST['new_theme_name']);

switch($_REQUEST['where']){
	case 'extras':
	case 'site': $where = $_REQUEST['where']; break;
	default: $where = '';
}


if ($action == "edit") {

        if (substr($filename, 0, 1) == '/') $filename = substr($filename,1);
	switch ($where) {
		case 'actions':	$path = root.'skins/'.$skin.'/tpl/'.$filename; break;
		case 'site':	$path = tpl_dir.$theme.'/'.$filename; break;
		case 'extras':	$path = root.'/plugins/'.$filename; break;
		default:		$path = '';
	}
	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', array('vars' => array(
		'php_self'	=>	$PHP_SELF,
		'filename'	=>	$filename,
		'theme'		=>	$theme,
		'skin'		=>	$skin,
		'where'		=>	$where,
		'new'		=>	'',
		'filebody'	=>	str_replace('[','&#91;',str_replace('{','&#123;',htmlspecialchars(file_get_contents($path))))
	)));
	echo $tpl -> show('edit');
} elseif ($action == "save" && $filename) {

	if ($where == "actions") { $path = root.'skins/'.$skin.'/tpl/'.$filename; }
	elseif ($where == "site") {
		$path = tpl_dir.$theme.'/'.$filename;

		if ($new) {
			$path = tpl_dir.$theme.'/custom/'.$filename;
		}
	} elseif ($where == 'extras') {
		$path = root.'/plugins/'.$filename;
	}

	$fp = fopen($path, 'wb+');
	fputs($fp, $_REQUEST['filebody']);
	fclose($fp);
	msg(array("text" => $lang['msgo_saved']));
} elseif ($action == "rename" && $filename && $newfilename) {
	$path = tpl_dir.$theme;
	@rename($path.$filename, $path.'/custom/'.$newfilename);
} elseif ($action == "delete" && $filename) {
	unlink(tpl_dir.$theme.$filename);
	msg(array("text" => $lang['msgo_tdeleted']));
} elseif ($action == "newfile" && $filename) {
	$path = tpl_dir.$theme.'/'.$filename;

	$tpl -> template('edit', tpl_actions.$mod);
	$tpl -> vars('edit', array('vars' => array(
		'php_self'	=>	$PHP_SELF,
		'filename'	=>	$newfilename,
		'theme'		=>	$theme,
		'skin'		=>	'',
		'where'		=>	"site",
		'new'		=>	'1',
		'filebody'	=>	htmlspecialchars(file_get_contents($path))
	)));
	echo $tpl -> show('edit');
} elseif ($action == "themerename" && $theme_name && $new_theme_name) {

	if ($where == "actions") { $path = root.'skins/'; }
	elseif ($where == "site") { $path = tpl_dir; }

	rename($path.$theme_name, $path.$new_theme_name);
	msg(array("text" => $lang['msgo_trenamed']));
} elseif ($action == "themedelete" && $theme_name) {

	$global = true;

	if ($where == "actions") { $path = root.'skins/'; }
	elseif ($where == "site") { $path = tpl_dir; }

	$array = GetFilesGlobal($theme_name, $where);

	sort($array['directories']);

	foreach ($array['directories'] as $dir) {
		$dirs[$dir['name']] = array($dir['parent'], $dir['name']);
	}

	foreach ($array['files'] as $fp) {
		$directory = str_replace("/", "", $fp['dir']);
		if ($dirs[$directory][0] && ereg('.tpl', $fp['name'])) {
			$folder = $dirs[$directory][0].$fp['dir'];
		}
		else {
			$folder = $fp['dir'];
		}
		unlink($path.$theme_name.($folder ? $folder : '/').$fp['name']);
	}

	rsort($array['directories']);

	foreach ($array['directories'] as $dir) {
		rmdir($path.$theme_name.($dir['parent'] ? $dir['parent'] : '/').$dir['name']);
	}

	rmdir($path.$theme_name);

	msg(array("text" => $lang['msgo_tdeleted']));
} elseif ($action == "newtheme" && $theme_name && $new_theme_name) {

	$global = true;

	if ($where == "actions") { $path = root.'skins/'; }
	elseif ($where == "site") { $path = tpl_dir; }

	$theme = $parse->translit($parse->slashes(trim($new_theme_name)));

	$array = GetFilesGlobal($theme_name, $where);

	@mkdir($path.$theme);

	sort($array['directories']);

	foreach ($array['directories'] as $dir) {
		mkdir($path.$theme.($dir['parent'] ? $dir['parent'] : '/').$dir['name']);
	}

	foreach ($array['directories'] as $dir) {
		$dirs[$dir['name']] = array($dir['parent'], $dir['name']);
	}

	foreach ($array['files'] as $fp) {
		$directory = str_replace("/", "", $fp['dir']);
		if ($dirs[$directory][0] && ereg('.tpl', $fp['name'])) {
			$folder = $dirs[$directory][0].$fp['dir'];
		}
		else {
			$folder = $fp['dir'];
		}
		@copy($path.$theme_name.($folder ? $folder : '/').$fp['name'], $path.$theme.($folder ? $folder : '/').$fp['name']);
	}

	@touch($path.$theme.'/index.html');
	msg(array("text" => $lang['msgo_tcreated']));
} else {

	if (!$theme) {
		$theme = $config['theme'];
	}
	if (!$where) {
		$where = "site";
	}

	$tpl -> template('entries', tpl_actions.$mod);

	$templates	=	GetFilesArray($skin, 'actions');

	sort($templates['files']);

	foreach ($templates['files'] as $file) {
		$tvarz['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'filename'	=>	($file['dir'] ? $file['dir'] : '').$file['name'],
			'theme'		=>	'',
			'where'		=>	'actions',
			'skin'		=>	$skin
		);

		$tvarz['regx']["'\\[if-delete\\].*?\\[/if-delete\\]'si"] = '';
		$tvarz['regx']["'\\[if-rename\\].*?\\[/if-rename\\]'si"] = '';
		$tvarz['regx']["'\\[if-new\\].*?\\[/if-new\\]'si"] = '';

		$tpl -> vars('entries', $tvarz);
		$tvars['vars']['entries_actions'] .= $tpl -> show('entries');
	}

	unset($tvarz);

	// Load data for plugins
	$templates	=	GetFilesArray('', 'extras');
	sort($templates['files']);

	foreach ($templates['files'] as $file) {
		$tvarz['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'filename'	=>	($file['dir'] ? $file['dir'] : '').$file['name'],
			'where'		=>	'extras',
			'skin'		=>	'',
			'theme'		=>	''
		);

		$tvarz['regx']["'\\[if-delete\\].*?\\[/if-delete\\]'si"] = '';
		$tvarz['regx']["'\\[if-rename\\].*?\\[/if-rename\\]'si"] = '';
		$tvarz['regx']["'\\[if-new\\].*?\\[/if-new\\]'si"] = '';

		$tpl -> vars('entries', $tvarz);
		$tvars['vars']['entries_extras'] .= $tpl -> show('entries');
	}

	unset($tvarz);

	// Load data for template

	$templates	=	GetFilesArray($theme, 'site');

	sort($templates['files']);

	foreach ($templates['files'] as $file) {
		$tvarz['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'filename'	=>	($file['dir'] ? $file['dir'] : '').$file['name'],
			'name'		=>	$file['name'],
			'theme'		=>	$theme,
			'where'		=>	'site',
			'skin'		=>	''
		);

		if ($file['dir']) {
			$tvarz['vars']['[if-rename]'] = '';
			$tvarz['vars']['[/if-rename]'] = '';
			$tvarz['vars']['[if-delete]'] = '';
			$tvarz['vars']['[/if-delete]'] = '';
		} else {
			$tvarz['regx']["'\\[if-delete\\].*?\\[/if-delete\\]'si"] = '';
			$tvarz['regx']["'\\[if-rename\\].*?\\[/if-rename\\]'si"] = '';
		}

		if (($file['name'] == "news.full.tpl" || $file['name'] == "news.short.tpl" || $file['name'] == "comments.form.tpl" || $file['name'] == "comments.show.tpl" || $file['name'] == "rss.tpl" || $file['name'] == "print.tpl") && !$file['dir']) {
			$tvarz['vars']['[if-new]'] = '';
			$tvarz['vars']['[/if-new]'] = '';
		} else {
			$tvarz['regx']["'\\[if-new\\].*?\\[/if-new\\]'si"] = '';
		}

		$tpl -> vars('entries', $tvarz);
		$tvars['vars']['entries_site'] .= $tpl -> show('entries');
	}

	unset($tvarz);

	$tpl -> template('themes', tpl_actions.$mod);

	$themes = GetThemes('actions', false);

	sort($themes['directories']);

	foreach ($themes['directories'] as $file) {
		$tvarz['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'dirname'	=>	$file['name'],
			'where'		=>	'actions'
		);

		$tpl -> vars('themes', $tvarz);
		$tvars['vars']['themes_entries_actions'] .= $tpl -> show('themes');
	}

	unset($tvarz);

	$themes = GetThemes('site', false);

	sort($themes['directories']);

	foreach ($themes['directories'] as $file) {
		$tvarz['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'dirname'	=>	$file['name'],
			'where'		=>	'site'
		);

		$tpl -> vars('themes', $tvarz);
		$tvars['vars']['themes_entries_site'] .= $tpl -> show('themes');
	}

	unset($tvarz);

	$tvars['vars']['show_adm']	=	($where == "actions") ? '' : 'display: none;';
	$tvars['vars']['show_ext']	=	($where == "extras")  ? '' : 'display: none;';
	$tvars['vars']['show_site']	=	($where == "site")    ? '' : 'display: none;';

	$tvars['vars']['theme']			=	$theme;
	$tvars['vars']['skin']			=	$skin;


	// ================================================
	// Prepare template selection block
	$tlist = loadTemplateVersions();

	// Display template selection
	$tpl->template('tselect', tpl_actions.$mod);

	$tsel = '';
	foreach ($tlist as $tver) {
		$tsv['vars'] = array(
			'template_name'		=> $tver['name'],
			'template_title'	=> $tver['title'],
			'template_author'	=> $tver['author'],
			'template_version'	=> $tver['version'],
			'template_reldate'  => $tver['reldate'],
			'class'				=> 'tselect'.(($theme==$tver['name'])?'_active':''),
		);
		$tpl->vars('tselect', $tsv);
		$tsel .= $tpl->show('tselect');
	}

	$tvars['vars']['template_select'] = $tsel;

	$tpl -> template('table', tpl_actions.$mod);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}



//
// Preload templates version files
//
function loadTemplateVersions(){
	$tDir = root.'../templates';
	$tlist = array();
	if ($dRec = opendir($tDir)) {
		while (($dName = readdir($dRec)) !== false) {
			if (($dName == '.')||($dName == '..'))
				continue;
			if (is_dir($tDir.'/'.$dName) && file_exists($vfn = $tDir.'/'.$dName.'/version') && (filesize($vfn)) && ($vf = @fopen($vfn, 'r'))) {

				$tRec = array('name' => $dName);
				while (!feof($vf)) {
					$line = fgets($vf);
					if (preg_match("/^(.+?) *\: *(.+?) *$/i", trim($line), $m)) {
						if (in_array(strtolower($m[1]), array('id', 'title', 'author', 'version', 'reldate', 'plugins', 'image', 'imagepreview')))
							$tRec[strtolower($m[1])] = $m[2];
					}
			        }
			        fclose($vf);
			        if (isset($tRec['id']) && isset($tRec['title']))
			        	array_push($tlist, $tRec);
			}
		}
		closedir($dRec);
	}
	return $tlist;
}
