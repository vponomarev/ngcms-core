<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: functions.php
// Description: Common system functions
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// SQL security string escape
//
function db_squote($string) {
	return "'".mysql_real_escape_string($string)."'";
}

function db_dquote($string) {
	return "'".mysql_real_escape_string($string).'"';
}

//
// HTML & special symbols protection
//
function secure_html($string) {
	return str_replace(array("{","<", ">"), array("&#123;","&lt;", "&gt;"), htmlspecialchars($string));
}

function Formatsize($file_size) {

	if ($file_size >= 1073741824) {
		$file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
	} elseif ($file_size >= 1048576) {
		$file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
	} elseif ($file_size >= 1024) {
		$file_size = round($file_size / 1024 * 100) / 100 . " Kb";
	} else {
		$file_size = $file_size . " b";
	}
	return $file_size;
}


function checkIP() {
	if (getenv("REMOTE_ADDR")) {
		return getenv("REMOTE_ADDR");
	} elseif ($_SERVER["REMOTE_ADDR"]) {
		return $_SERVER['REMOTE_ADDR'];
	}
	return "unknown";
}


function gzip() {
	global $config;

	if ($config['use_gzip'] == "1" && extension_loaded('zlib') && function_exists('ob_gzhandler')) {
		@ob_start('ob_gzhandler');
	}
}


// Generate backup for table list. If no list is given - backup ALL tables with system prefix
function dbBackup($fname, $gzmode, $tlist = ''){
	global $mysql;

	if ($gzmode && (!function_exists('gzopen')))
		$gzmode = 0;

	if ($gzmode)	$fh = gzopen($fname, "w");
	 else			$fh = fopen($fname, "w");

	if ($fh === false)
		return 0;

	// Generate a list of tables for backup
	if (!is_array($tlist)) {
		$tlist = array();

		foreach ($mysql->select("show tables like '".prefix."_%'") as $tn)
			$tlist [] = $tn[0];
	}

	// Now make a header
	$out  = "# ".str_repeat('=', 60)."\n# Backup file for `Next Generation CMS`\n# ".str_repeat('=', 60)."\n# DATE: ".gmdate("d-m-Y H:i:s", time())." GMT\n# VERSION: ".version."\n#\n";
	$out .= "# List of tables for backup: ".join(", ", $tlist)."\n#\n";

	// Write a header
	if ($gzmode)	gzwrite($fh, $out);
	 else			fwrite($fh, $out);

	// Now, let's scan tables
	foreach ($tlist as $tname) {
		// Fetch create syntax for table and after - write table's content
		if (is_array($csql = $mysql->record("show create table `".$tname."`"))) {
			$out  = "\n#\n# Table `".$tname."`\n#\n";
			$out .= "DROP TABLE IF EXISTS `".$tname."`;\n";
			$out .= $csql[1].";\n";

			if ($gzmode)	gzwrite($fh, $out);
			 else			fwrite($fh, $out);

			// Now let's make content of the table
			$query = mysql_query("select * from `".$tname."`", $mysql->connect);
			$rowNo = 0;
			while ($row = mysql_fetch_row($query)) {
				$out = "insert into `".$tname."` values (";
				$rowNo++;
				$colNo = 0;
				foreach ($row as $v)
					$out .= (($colNo++)?', ':'').db_squote($v);
				$out .= ");\n";

				if ($gzmode)	gzwrite($fh, $out);
				 else			fwrite($fh, $out);
			}

			$out = "# Total records: $rowNo\n";

			if ($gzmode)	gzwrite($fh, $out);
			 else			fwrite($fh, $out);
		} else {
			$out = "#% Error fetching information for table `$tname`\n";

			if ($gzmode)	gzwrite($fh, $out);
			 else			fwrite($fh, $out);
		}
	}
	if ($gzmode)	gzclose($fh);
	 else			fclose($fh);

	return 1;
}

function AutoBackup() {
	global $config;

	$backupFile = root."cache/last_backup.tmp";

	$last_backup	=	@file_get_contents($backupFile);
	$time_now		=	time();

	if ($time_now > ($last_backup + $config['auto_backup_time'] * 3600)) {
	        // Try to open temp file for writing
	        $fx = is_file($backupFile)?@fopen($backupFile,"r+"):@fopen($backupFile,"w+");
	        if ($fx) {
			$filename	=	root."backups/backup_".date("Y_m_d_H_i", $time_now).".gz";

			// We need to create file with backup
			dbBackup($filename, 1);

			rewind($fx);
			fwrite($fx, $time_now);
			ftruncate($fx,ftell($fx));
		}
	}
}

function LangDate($format, $timestamp) {
	global $lang;

	$weekdays		=	explode(",", $lang['weekdays']);
	$short_weekdays	=	explode(",", $lang['short_weekdays']);
	$months			=	explode(",", $lang['months']);
	$months_s		=	explode(",", $lang['months_s']);
	$short_months	=	explode(",", $lang['short_months']);

	foreach ($weekdays as $name => $value)
	$weekdays[$name] = preg_replace("/./", "\\\\\\0", $value);

	foreach ($short_weekdays as $name => $value)
	$short_weekdays[$name] = preg_replace("/./", "\\\\\\0", $value);

	foreach ($months as $name => $value)
	$months[$name] = preg_replace("/./", "\\\\\\0", $value);

	foreach ($months_s as $name => $value)
	$months_s[$name] = preg_replace("/./", "\\\\\\0", $value);

	foreach ($short_months as $name => $value)
	$short_months[$name] = preg_replace("/./", "\\\\\\0", $value);

	$format = @preg_replace("/(?<!\\\\)D/", $short_weekdays[date("w", $timestamp)], $format);
	$format = @preg_replace("/(?<!\\\\)F/", $months[date("n", $timestamp) - 1], $format);
	$format = @preg_replace("/(?<!\\\\)Q/", $months_s[date("n", $timestamp) - 1], $format);
	$format = @preg_replace("/(?<!\\\\)l/", $weekdays[date("w", $timestamp)], $format);
	$format = @preg_replace("/(?<!\\\\)M/", $short_months[date("n", $timestamp) - 1], $format);

	return @date($format, $timestamp);
}

//
// Generate a list of smilies to show
function InsertSmilies($insert_location, $break_location = false, $area = false) {
	global $config, $tpl;

	if ($config['use_smilies']) {
		$smilies = explode(",", $config['smilies']);

		// For smilies in comments, try to use 'smilies.tpl' from site template
		$templateDir = (($insert_location == 'comments') && is_readable(tpl_dir.$config['theme'].'/smilies.tpl'))?tpl_dir.$config['theme']:tpl_actions;

		foreach ($smilies as $null => $smile) {
			$i++;
			$smile = trim($smile);

			$tvars['vars'] = array(
				'area'		=>	$area,
				'smile'		=>	$smile
			);

			$tpl -> template('smilies', $templateDir);
			$tpl -> vars('smilies', $tvars);
			$output .= $tpl -> show('smilies');

			if ($i%$break_location == "0" && $break_location) {
				$output .= "<br />";
			} else {
				$output .= "&nbsp;";
			}
		}
		return $output;
	}
}


function phphighlight($content = '') {

	$f	=	array('<br>', '<br />', '<p>', '&lt;', '&gt;', '&amp;', '&#124;', '&quot;', '&#036;', '&#092;', '&#039;','&nbsp;', '\"');
	$r	=	array("\n", "\n", "\n", '<', '>', '&', '\|', '"', '$', '', '\'', '', '"');
	$content	=	str_replace($f, $r, $content);
	$content	=	highlight_string($content, true);

	return $content;
}


function QuickTags($area = false, $template = false) {
	global $config, $lang, $tpl;

	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'area'		=>	$area
	);

	if (!in_array($template, array('pmmes', 'editcom', 'news', 'static')))
		return false;

	$tplname = 'qt_'.$template;

	$tpl->template($tplname, tpl_actions);
	$tpl->vars($tplname, $tvars);
	return $tpl->show($tplname);
}


function BBCodes($area = false) {
	global $config, $lang, $tpl;

	if ($config['use_bbcodes'] == "1") {
		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'area'		=>	$area
		);

		$tpl -> template('bbcodes', tpl_site);
		$tpl -> vars('bbcodes', $tvars);

		return $tpl -> show('bbcodes');
	}
}


function Padeg($n, $s) {

	$n	=	abs($n);
	$a	=	split(",", $s);
	$l1	=	$n - ((int)($n / 10)) * 10;
	$l2	=	$n - ((int)($n / 100)) * 100;

	if ("11" <= $l2 && $l2 <= "14") {
		$e = $a[2];
	} else {
		if ($l1 == "1") {
			$e = $a[0];
		}

		if ("2" <= $l1 && $l1 <= "4") {
			$e = $a[1];
		}

		if (("5" <= $l1 && $l1 <= "9") || $l1 == "0") {
			$e=$a[2];
		}
	}

	if ($e == "") {
		$e = $a[0];
	}

	return($e);
}


//
// Perform BAN check
// $ip		- IP address of user
// $act		- action type ( 'users', 'comments', 'news',... )
// $subact	- subaction type ( for comments this may be 'add' )
// $userRec	- record of user (in case of logged in)
// $name	- name entered by user (in case it was entered)
function checkBanned($ip, $act, $subact, $userRec, $name) {
	global $mysql;

	$check_ip = sprintf("%u", ip2long($ip));

	// Currently we use limited mode. Try to find row
	if ($ban_row = $mysql->record("select * from ".prefix."_ipban where addr_start <= ".db_squote($check_ip)." and addr_stop >= ".db_squote($check_ip)." order by netlen limit 1")) {
		// Row is found. Let's check for event type. STATIC CONVERSION
		$mode = 0;
		if		(($act == 'users') &&		($subact == 'register'))	{ $mode = 1; }
		else if	(($act == 'users') && 		($subact == 'auth'))		{ $mode = 2; }
		else if	(($act == 'comments') &&	($subact == 'add'))			{ $mode = 3; }
		if (($locktype = intval(substr($ban_row['flags'], $mode, 1))) > 0) {
			$mysql->query("update ".prefix."_ipban set hitcount=hitcount+1 where id=".db_squote($ban_row['id']));
			return $locktype;
		}
	}
	return 0;
}

//
// Perform FLOOD check
// $mode	- WORKING MODE ( 0 - check only, 1 - update )
// $ip		- IP address of user
// $act		- action type ( 'comments', 'news',... )
// $subact	- subaction type ( for comments this may be 'add' )
// $userRec	- record of user (in case of logged in)
// $name	- name entered by user (in case it was entered)
function checkFlood($mode, $ip, $act, $subact, $userRec, $name){
	global $mysql, $config;

	// Return if flood protection is disabled
	if (!$config['flood_time']) {
		return 0;
	}

	$this_time = time() + ($config['date_adjust'] * 60) - $config['flood_time'];

	// If UPDATE mode is used - update data
	if ($mode) {
		$this_time = time() + ($config['date_adjust'] * 60);
		$mysql->query("insert into ".prefix."_flood (ip, id) values (".db_squote($ip).", ".db_squote($this_time).") on duplicate key update id=".db_squote($this_time));
		return 0;
	}

	// Delete expired records
	$mysql->query("DELETE FROM ".prefix."_flood WHERE id < ".db_squote($this_time));

	// Check if we have record
	if ($mysql->rows($mysql->query("SELECT * FROM ".prefix."_flood WHERE id > ".db_squote($this_time)." AND ip = ".db_squote($ip))) > "0") {
		// Flood found
		return 1;
	}
	return 0;
}


function zzMail($to, $subject, $message, $filename = false, $mail_from = false) {
	global $lang, $config;

	$mail_from	=	(!$mail_from) ? "mailbot@".str_replace("www.", "", $_SERVER['SERVER_NAME']) : $mail_from;
	$uniqid		=	md5(uniqid(time()));

	$headers	=	'From: '.$mail_from."\n";
	$headers	.=	'Reply-to: '.$mail_from."\n";
	$headers	.=	'Return-Path: '.$mail_from."\n";
	$headers	.=	'Message-ID: <'.$uniqid.'@'.$_SERVER['SERVER_NAME'].">\n";
	$headers	.=	'MIME-Version: 1.0'."\n";
	$headers	.=	'Date: '.gmdate('D, d M Y H:i:s', time())."\n";
	$headers	.=	'X-Priority: 3'."\n";
	$headers	.=	'X-MSMail-Priority: Normal'."\n";
	$headers	.=	'X-Mailer: '.engineName.' : '.engineVersion."\n";
	$headers	.=	'X-MimeOLE: '.engineName.' : '.engineVersion."\n";
	$headers	.=	'Content-Type: multipart/mixed;boundary="----------'.$uniqid.'"'."\n\n";
	$headers	.=	'------------'.$uniqid."\n";
	$headers	.=	'Content-Type: text/html;charset='.$lang['encoding'].''."\n";
	$headers	.=	'Content-Transfer-Encoding: 8bit';

	if (is_file($filename)){
		$file		=	fopen($filename, 'rb');
		$message	.=	"\n".'------------'.$uniqid."\n";
		$message	.=	'Content-Type: application/octet-stream;name="'.basename($filename).'"'."\n";
		$message	.=	'Content-Transfer-Encoding: base64'."\n";
		$message	.=	'Content-Disposition: attachment;';
		$message	.=	'filename="'.basename($filename).'"'."\n\n";
		$message	.=	chunk_split(base64_encode(fread($file, filesize($filename))))."\n";
	}

	@mail($to, $subject, $message, $headers);
}


function msg($msg_arr) {
	global $config, $lang, $template, $PHP_SELF, $how;

	if ($msg_arr["type"] == "error") {
		$msg = '<div class="msge"><img src="'.skins_url.'/images/error.gif" hspace="10" />'.$lang["msge_error"].$msg_arr["text"].'</div>';
		if ($PHP_SELF == "admin.php" || $how) { echo $msg; } else { $template['vars']['mainblock'] .= $msg; }

		if ($msg_arr["info"] && $msg_arr["info"] != "") {
			$msg = '<div class="msgi"><img src="'.skins_url.'/images/info.gif" hspace="10" />'.$lang["msgi_info"].$msg_arr["info"].'</div>';
			if ($PHP_SELF == "admin.php" || $how) { echo $msg; } else { $template['vars']['mainblock'] .= $msg; }
		}
	} elseif ($msg_arr["type"] == "info") {
		$msg = '<div class="msgi"><img src="'.skins_url.'/images/info.gif" hspace="10" />'.$lang["msgi_info"].$msg_arr["info"].'</div>';
		if ($PHP_SELF == "admin.php" || $how) { echo $msg; } else { $template['vars']['mainblock'] .= $msg; }
	} else {
		$msg = '<div class="msgo"><img src="'.skins_url.'/images/msg.gif" hspace="10" />'.$msg_arr["text"].'</div>';
		if ($PHP_SELF == "admin.php" || $how) { echo $msg; } else { $template['vars']['mainblock'] .= $msg; }

		if ($msg_arr["info"] && $msg_arr["info"] != "") {
			$msg = '<div class="msgi"><img src="'.skins_url.'/images/info.gif" hspace="10" />'.$lang["msgi_info"].$msg_arr["info"].'</div>';
			if ($PHP_SELF == "admin.php" || $how) { echo $msg; } else { $template['vars']['mainblock'] .= $msg; }
		}
	}
}


function DirSize($directory) {

	if (!is_dir($directory)) return -1;
	$size = 0;

	if ($dir = opendir($directory)) {
		while (($dirfile = readdir($dir)) !== false) {
			if (is_link($directory . '/' . $dirfile) || $dirfile == '.' || $dirfile == '..') {
				continue;
			}
			if (is_file($directory . '/' . $dirfile)) {
				$size += filesize($directory . '/' . $dirfile);
			} elseif (is_dir($directory . '/' . $dirfile)) {
				$dirSize = dirsize($directory . '/' . $dirfile);
				if ($dirSize >= 0) {
					$size += $dirSize;
				} else {
					return -1;
				}
			}
		}
		closedir($dir);
	}
	return $size;
}


// Scans directory and returns it's size and file count
// Return array with size, count
function directoryWalk($dir, $blackmask = null, $whitemask = null) {
 if (!is_dir($dir)) return array( -1, -1);

 $size  = 0;
 $count = 0;
 $flag  = 0;
 $path  = array($dir);
 $wpath = array();
 $files = array();
 $od = array();
 $dfile = array();
 $od[1] = opendir($dir);

 while (count($path)) {
  $level = count($path);
  $sd    = join("/", $path );
  $wsd   = join("/", $wpath);
  while (($dfile[$level] = readdir($od[$level])) !== false) {
   if (is_link($sd . '/' . $dfile[$level]) || $dfile[$level] == '.' || $dfile[$level] == '..')
    continue;

   if (is_file($sd . '/' . $dfile[$level])) {
    // Check for black list

    $size += filesize($sd . '/' . $dfile[$level]);
    $files []= ($wsd?$wsd.'/':'').$dfile[$level];
    $count ++;
   } elseif (is_dir($sd . '/' . $dfile[$level])) {
    array_push($path, $dfile[$level]);
    array_push($wpath, $dfile[$level]);
    $od[$level+1] = opendir(join("/", $path));
    $flag = 1;
    break;
   }
  }
  if ($flag) {
  	$flag = 0;
  	continue;
  }
  array_pop($path);
  array_pop($wpath);
 }
 return array($size, $count, $files);
}


// makeCategoryList - make <SELECT> list of categories
// Params: set via named array
// * name      - name field of <SELECT>
// * selected  - ID of category to be selected or array of IDs to select (in list mode)
// * skip      - ID of category to skip or array of IDs to skip
// * doempty   - add empty category to the beginning ("no category"), value = 0
// * doall     - all category named "ALL" to the beginning, value is empty
// * nameval   - use DB field "name" instead of ID in HTML option value
// * resync    - flag, if set - we make additional lookup into database for new category list
// * checkarea - flag, if set - generate a list of checkboxes instead of <SELECT>
function makeCategoryList($params = array() /*selected=0, $my=0, $noempty=0, $name='category'*/){
	global $catz, $lang, $mysql;

	if (!is_array($params['skip'])) { $params['skip'] = $params['skip']?array($params['skip']):array(); }
	$name = array_key_exists('name', $params)?$params['name']:'category';

	if (!$params['checkarea']) {
	 $out = "<select name=\"$name\" id=\"catmenu\"".($params['class']?' class="'.$params['class'].'"':'').">\n";
	 if ($params['doempty'])	{ $out.= "<option value=\"0\">".$lang['no_cat']."</option>\n"; }
	 if ($params['doall'])	{ $out.= "<option value=\"\">".$lang['sh_all']."</option>\n"; }
	}
	if ($params['resync'])  {
		$catz = array();
		foreach ($mysql->select("select * from `".prefix."_category` order by posorder asc", 1) as $row) {
			$catz[$row['alt']] = $row;
			$catmap[$row['id']] = $row['alt'];
		}
	}

	foreach($catz as $k => $v){
		if (in_array($v['id'], $params['skip'])) { continue; }
		if ($params['checkarea']) {
			$out .= str_repeat('&#8212; ', $v['poslevel']).'<label><input type="checkbox" name="'.$name.'_'.$v['id'].'" value="1"'.((is_array($params['selected']) && in_array($v['id'], $params['selected']))?' checked="checked"':'').'/> '.$v['name']."</label><br/>\n";
		} else {
			$out.="<option value=\"".($params['nameval']?$v['name']:$v['id'])."\"".(($v['id']==$params['selected'])?' selected="selected"':'').">".str_repeat('&#8212; ', $v['poslevel']).$v['name']."</option>\n";
		}
	}
	if (!$params['checkarea']) {
		$out.="</select>";
	}
	return $out;
}


function OrderList($value) {
	global $lang, $catz;

	$output = "<select name=\"orderby\">\n";
	foreach (array('id desc', 'id asc', 'postdate desc', 'postdate asc', 'title desc', 'title asc', 'rating desc', 'rating asc') as $v) {
	        $vx = str_replace(' ','_',$v);
		$output.='<option value="'.$v.'"'.(($value==$v)?' selected="selected"':'').'>'.$lang["order_$vx"]."</option>\n";
	}
	$output.="</select>\n";
	return $output;
}


function ChangeDate($time = 0) {
	global $lang, $langShortMonths;

	if ($time <= 0) { $time = time(); }

	$result = '<div id="cdate"><select name="c_day">';
	for ($i=1; $i <= 31; $i++)
		$result .= '<option value="'.$i.'"'.((date('j', $time)==$i)?' selected="selected"':'').'>'.$i.'</option>';

	$result .= '</select><select id="c_month" name="c_month">';

	foreach ($langShortMonths as $k => $v)
		$result .= '<option value="'.($k+1).'"'.((date('n', $time)==($k+1))?' selected="selected"':'').'>'.$v.'</option>';

	$result .= '</select>
	<input type="text" id="c_year" name="c_year" size="4" maxlength="4" value="'.date('Y',$time).'" />
	<input type="text" id="c_hour" name="c_hour" size="2" maxlength="2" value="'.date('H',$time).'" /> :
	<input type="text" id="c_minute" name="c_minute" size="2" maxlength="2" value="'.date('i',$time).'" />
	</div>';

	return $result;
}


function ListFiles($path, $ext) {

	$file_list = array();

	if (!$handle = opendir("$path")) {
		echo "<p>Can not open directory</p> ";
	}

	while (false !== ($file = readdir($handle))) {
		if(eregi(".$ext", $file)) {
			$file_arr = explode(".", $file);
			$file_list["$file_arr[0]"]= $file_arr[0];
		}
	}
	closedir($handle);
	return $file_list;
}


function ListDirs($folder, $category = false, $alllink = true) {
	global $lang;

	$select = '<select name="category">'.($alllink?'<option value="">- '.$lang['all'] .' -</option>':'');

	if ($folder == "files") {
		$def_dir	=	files_dir;
		$dir		=	opendir(files_dir);
	}

	if ($folder == "images") {
		$def_dir	=	images_dir;
		$dir		=	opendir(images_dir);
	}

	while($file = readdir($dir)) {
		$in_dir[] = $file;
	}

	natcasesort($in_dir);
	reset($in_dir);

	foreach ($in_dir as $file) {
		if (is_dir($def_dir."/".$file) && $file != "." && $file != "..")
			$select .= "<option value=\"".$file."\"".($category==$file?' selected="selected"':'').">".$file."</option>\n";
	}
	$select .= '</select>';

	return $select;
}


function MakeDropDown($options, $name, $selected = "FALSE") {
	$output = "<select size=1 name=\"".$name."\">";
	foreach ($options as $k=>$v)
		$output .= "<option value=\"".$k."\"".(($selected==$k)?" selected=\"selected\"":'').">".$v."</option>";
	$output .= "</select>";

	return $output;
}


function LoadLang($what, $where = '') {
	global $config, $lang;

	$where = ($where) ? '/'.$where : '';

	if (!file_exists($toinc = root.'lang/'.$config['default_lang'].$where.'/'.$what.'.ini')) {
		$toinc = root.'lang/english/'.$where.'/'.$what.'.ini';
	}
	if (file_exists($toinc)) {
		$lang = (!is_array($lang)) ? parse_ini_file($toinc, true) : array_merge($lang, parse_ini_file($toinc, true));
	}
	return $lang;
}

// Return plugin dir
function GetPluginDir($name) {
	global $EXTRA_CONFIG;

	$extras = get_extras_list();
	if (!$extras[$name]) { return 0; }
	return extras_dir.'/'.$extras[$name]['dir'];
}

function GetPluginLangDir($name) {
	global $config;
	$lang_dir = GetPluginDir($name).'/lang';
	if (!$lang_dir) { return 0; }
	if (is_dir($lang_dir.'/'.$config['default_lang'])) { $lang_dir = $lang_dir.'/'.$config['default_lang']; }
	else if (is_dir($lang_dir.'/english')) { $lang_dir = $lang_dir.'/english'; }
	else if (is_dir($lang_dir.'/russian')) { $lang_dir = $lang_dir.'/russian'; }
	return $lang_dir;
}

// Load LANG file for plugin
function LoadPluginLang($plugin, $file, $group = '', $prefix = '', $delimiter = '_') {
	global $config, $lang, $EXTRA_CONFIG;

	if (!$prefix) { $prefix = $plugin; }
	// If requested plugin is activated, we can get 'dir' information from active array
	$active = get_active_array();
	if (!$active['active'][$plugin]) {
		// No, plugin is not active. Let's load plugin list
		$extras = get_extras_list();

		// Exit if no data about this plugin is found
		if (!$extras[$plugin]) { return 0; }
		$lang_dir = extras_dir.'/'.$extras[$plugin]['dir'].'/lang';
	} else {
		$lang_dir = extras_dir.'/'.$active['active'][$plugin].'/lang';
	}

	// Exit if no lang dir
	if (!is_dir($lang_dir)) { return 0; }

	// find if we have 'lang' dir in plugin directory
	// Try to load langs in order: default / english / russian
	if (is_dir($lang_dir.'/'.$config['default_lang'])) { $lang_dir = $lang_dir.'/'.$config['default_lang']; }
	else if (is_dir($lang_dir.'/english')) { $lang_dir = $lang_dir.'/english'; }
	else if (is_dir($lang_dir.'/russian')) { $lang_dir = $lang_dir.'/russian'; }
	else { return 0; }
	// load file
	$plugin_lang = parse_ini_file($lang_dir.'/'.($group?$group.'/':'').$file.'.ini');

	// merge values
	if (is_array($plugin_lang)) {
		foreach ($plugin_lang as $p => $v) {
			$lang[$prefix.$delimiter.$p] = $v;
		}
	}
	return 1;
}


function GetAllCategories($cats) {
	global $catz;

	foreach ($cats as $k => $v) {
		foreach ($catz as $row) {
			if ($v == $row['id']) {
				$catline .= ", ".$row['name'];
			}
		}
	}

	return preg_replace('[^([, ]+)]', '', $catline);
}


function MakeRandomPassword() {
	global $config;
	return substr(md5($config['crypto_salt'].uniqid(rand(),1)),0,10);
}


function EncodePassword($pass) {
	$pass = md5(md5($pass));
	return $pass;
}

function generateAdminNavigations($current, $start, $stop, $link, $navigations){
	$result = '';
	//print "call generateAdminNavigations(current=".$current.", start=".$start.", stop=".$stop.")<br>\n";
	//print "Navigations: <pre>"; var_dump($navigations); print "</pre>";
	for ($j=$start; $j<=$stop; $j++) {
		if ($j == $current) {
			$result .= str_replace('%page%',$j,$navigations['current_page']);
		} else {
			$row['page'] = $j;
			$result .= str_replace('%page%',$j,str_replace('%link%',str_replace('%page%', $j, $link), $navigations['link_page']));
		}
	}
	return $result;
}


// Generate page list for admin panel
// * current - number of current page
// * count   - total count of pages
// * url	 - URL of page, %page% will be replaced by page number
function generateAdminPagelist($param){
	global $tpl;

	if ($param['count'] < 2) return '';

	$nav = getNavigations(tpl_actions);
	$tpl -> template('pages', tpl_actions);

	// Prev page link
	if ($param['current'] > 1) {
		$prev = $param['current'] - 1;
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',str_replace('%page%', $prev, $param['url']), $nav['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$no_prev = true;
	}

	// ===[ TO PUT INTO CONFIG ]===
	$pages = '';
	$maxNavigations 		= 10;

	$sectionSize	= floor($maxNavigations / 3);
	if ($param['count'] > $maxNavigations) {
		// We have more than 10 pages. Let's generate 3 parts
		// Situation #1: 1,2,3,4,[5],6 ... 128
		if ($param['current'] < ($sectionSize * 2)) {
			$pages .= generateAdminNavigations($param['current'], 1, $sectionSize * 2, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateAdminNavigations($param['current'], $param['count']-$sectionSize, $param['count'], $param['url'], $nav);
		} elseif ($param['current'] > ($param['count'] - $sectionSize * 2 + 1)) {
			$pages .= generateAdminNavigations($param['current'], 1, $sectionSize, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateAdminNavigations($param['current'], $param['count']-$sectionSize*2 + 1, $param['count'], $param['url'], $nav);
		} else {
			$pages .= generateAdminNavigations($param['current'], 1, $sectionSize, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateAdminNavigations($param['current'], $param['current']-1, $param['current']+1, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateAdminNavigations($param['current'], $param['count']-$sectionSize, $param['count'], $param['url'], $nav);
		}
	} else {
		// If we have less then 10 pages
		$pages .= generateAdminNavigations($param['current'], 1, $param['count'], $param['url'], $nav);
	}


	$tvars['vars']['pages'] = $pages;
	if ($prev + 2 <= $param['count']) {
		$next = $prev + 2;
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',str_replace('%page%', $next, $param['url']), $nav['nextlink']));
	} else {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		$no_next = true;
	}
	$tpl -> vars('pages', $tvars);
	return $tpl -> show('pages');
}

function GetLink($what, $row = array(), $rawlink = 0){
	global $config, $linkz, $catz, $catmap;

	if (($what == 'category') && ($catz[$row['alt']]['alt_url'])) { return $catz[$row['alt']]['alt_url']; }

	$entire = $linkz[ $config['mod_rewrite']? 'rewrite' : 'plain'][$what];
	if (is_array($entire)) {
		$link = $entire[ $config['category_link']? 'by_cat' : 'by_date'];
	} else {
		$link = $entire;
	}

	if ($row['catid']) {
		$cats = array();
		foreach (explode(',', $row['catid']) as $catid) {
			if ($catmap[$catid] != '')
				$cats[] = $catmap[$catid];
		}
		$categories = implode("-", $cats);
	} else {
		$categories = "none";
	}

	$tr = 	array(
		'&'			=>	'&amp;',
		'{alt}'			=>	$row['alt'],
		'{id}'			=>	$row['id'],
		'{year}'		=>	date('Y', $row['postdate']),
		'{month}'		=>	date('m', $row['postdate']),
		'{day}'			=>	date('d', $row['postdate']),
		'{alt_name}'	=>	$row['alt_name'],
		'{catlink}'		=>	$categories,
		'{author}'		=>	($row['author']) ? urlencode($row['author']) : $row['name'],
		'{page}'		=>	$row['page'],
		'{userid}'		=>	$row['userid'],
		'{code}'		=>	$row['code'],
		'{plugin_name}'		=>	$row['plugin_name']);
	if ($rawlink)
		array_shift($tr);

	$link = strtr($link, $tr);

	preg_match_all('/\((.*?)\)/i', $link, $array);

	for ($i = 0; $i < sizeof($array[1]); $i++){
		$that = reset(explode("|", $array[1][$i]));
		$link = str_replace('('.$array[1][$i].')', $that, $link);
	}
	return home."/".$link;
}


$letters = array('%A8' => '%D0%81', '%B8' => '%D1%91', '%C0' => '%D0%90', '%C1' => '%D0%91', '%C2' => '%D0%92', '%C3' => '%D0%93', '%C4' => '%D0%94', '%C5' => '%D0%95', '%C6' => '%D0%96', '%C7' => '%D0%97', '%C8' => '%D0%98', '%C9' => '%D0%99', '%CA' => '%D0%9A', '%CB' => '%D0%9B', '%CC' => '%D0%9C', '%CD' => '%D0%9D', '%CE' => '%D0%9E', '%CF' => '%D0%9F', '%D0' => '%D0%A0', '%D1' => '%D0%A1', '%D2' => '%D0%A2', '%D3' => '%D0%A3', '%D4' => '%D0%A4', '%D5' => '%D0%A5', '%D6' => '%D0%A6', '%D7' => '%D0%A7', '%D8' => '%D0%A8', '%D9' => '%D0%A9', '%DA' => '%D0%AA', '%DB' => '%D0%AB', '%DC' => '%D0%AC', '%DD' => '%D0%AD', '%DE' => '%D0%AE', '%DF' => '%D0%AF', '%E0' => '%D0%B0', '%E1' => '%D0%B1', '%E2' => '%D0%B2', '%E3' => '%D0%B3', '%E4' => '%D0%B4', '%E5' => '%D0%B5', '%E6' => '%D0%B6', '%E7' => '%D0%B7', '%E8' => '%D0%B8', '%E9' => '%D0%B9', '%EA' => '%D0%BA', '%EB' => '%D0%BB', '%EC' => '%D0%BC', '%ED' => '%D0%BD', '%EE' => '%D0%BE', '%EF' => '%D0%BF', '%F0' => '%D1%80', '%F1' => '%D1%81', '%F2' => '%D1%82', '%F3' => '%D1%83', '%F4' => '%D1%84', '%F5' => '%D1%85', '%F6' => '%D1%86', '%F7' => '%D1%87', '%F8' => '%D1%88', '%F9' => '%D1%89', '%FA' => '%D1%8A', '%FB' => '%D1%8B', '%FC' => '%D1%8C', '%FD' => '%D1%8D', '%FE' => '%D1%8E', '%FF' => '%D1%8F');
//$chars = array('%C2%A7' => '&#167;', '%C2%A9' => '&#169;', '%C2%AB' => '&#171;', '%C2%AE' => '&#174;', '%C2%B0' => '&#176;', '%C2%B1' => '&#177;', '%C2%BB' => '&#187;', '%E2%80%93' => '&#150;', '%E2%80%94' => '&#151;', '%E2%80%9C' => '&#147;', '%E2%80%9D' => '&#148;', '%E2%80%9E' => '&#132;', '%E2%80%A6' => '&#133;', '%E2%84%96' => '&#8470;', '%E2%84%A2' => '&#153;', '%C2%A4' => '&curren;', '%C2%B6' => '&para;', '%C2%B7' => '&middot;', '%E2%80%98' => '&#145;', '%E2%80%99' => '&#146;', '%E2%80%A2' => '&#149;');
// TEMPORARY SOLUTION AGAINST '&' quoting
$chars = array('%D0%86' => '[CYR_I]', '%D1%96' => '[CYR_i]', '%D0%84' => '[CYR_E]', '%D1%94' => '[CYR_e]', '%D0%87' => '[CYR_II]', '%D1%97' => '[CYR_ii]', '%C2%A7' => chr(167), '%C2%A9' => chr(169), '%C2%AB' => chr(171), '%C2%AE' => chr(174), '%C2%B0' => chr(176), '%C2%B1' => chr(177), '%C2%BB' => chr(187), '%E2%80%93' => chr(150), '%E2%80%94' => chr(151), '%E2%80%9C' => chr(147), '%E2%80%9D' => chr(148), '%E2%80%9E' => chr(132), '%E2%80%A6' => chr(133), '%E2%84%96' => '&#8470;', '%E2%84%A2' => chr(153), '%C2%A4' => '&curren;', '%C2%B6' => '&para;', '%C2%B7' => '&middot;', '%E2%80%98' => chr(145), '%E2%80%99' => chr(146), '%E2%80%A2' => chr(149));
$byary = array_flip($letters);


function convert($content) {
	global $byary, $chars;

	$content = strtr(urlencode($content), $byary);
	$content = strtr($content, $chars);
	$content = urldecode($content);

	return $content;
}

function utf2cp1251($text) { return convert($text); }

function GetCategories($catid, $plain = false) {
	global $catz, $catmap;

	$cats = is_array($catid)?$catid:explode(",", $catid);
	foreach ($cats as $v) {
		if (isset($catmap[$v])) {
			$row = $catz[$catmap[$v]];
			$catline[] = ($plain) ? $row['name'] : "<a href=\"".GetLink('category', $row)."\">".$row['name']."</a>";
		}
	}

	return ($catline ? implode(", ", $catline) : '');
}


//
// New category menu generator
function generateCategoryMenu(){
	global $mysql, $catz, $tpl, $config;

	$tpl -> template('categories', tpl_site);
	foreach($catz as $k => $v){
		if (!$v['cat_show']) continue;

		$tvars['vars'] = array(
			'if_active'	=>	(category && category == $v['alt'])?'active_cat':'',
			'link'		=>	GetLink('category', array ('alt' => $v['alt'])),
			'mark'		=>	str_repeat('&#8212;', $v['poslevel']),
			'cat'		=>	$v['name'],
			'counter'	=>	($config['category_counters'] && $v['posts'])?('['.$v['posts'].']'):'',
			'icon'		=>	$v['icon'],
		);
		$tvars['regx']['[\[icon\](.*)\[/icon\]]'] = trim($v['icon'])?'$1':'';

		$tpl -> vars('categories', $tvars);

		$result .= $tpl -> show('categories');
	}
	return $result;
}

//
// make an array for filtering from text line like 'abc-def,dfg'
function generateCategoryArray($categories){
	global $catz;

	$carray = array();
	foreach(explode(",", $categories) as $v){
		$xa = array();
		foreach(explode("-", $v) as $n) {
			if (is_array($catz[trim($n)]))
				array_push($xa, $catz[trim($n)]['id']);
		}
		if (count($xa))
			array_push($carray, $xa);
	}
	return $carray;
}

//
// make a SQL filter for specified array
function generateCategoryFilter(){

}

// Fill variables for news:
// * $row - SQL row
// * $fullMode - flag if desired mode is full
// * $page - page No to show in full mode
//function Prepare($row, $page) {
function newsFillVariables($row, $fullMode, $page = 0, $disablePagination = 0) {
	global $config, $parse, $lang;

	$tvars = array ( 'vars' => array( 'pagination' => '', 'title' => $row['title']));

	$url = GetLink('full', $row);

	$tvars['vars']['author'] = "<a href=\"".GetLink('user', $row)."\" target=\"_blank\">".$row['author']."</a>";

	// Divide into short and full content
	if ($config['extended_more']) {
		if (preg_match('#^(.+?)\<\!--more(?:\="(.+?)"){0,1}--\>(.+)$#is', $row['content'], $pres)) {
			$short	= $pres[1];
			$full	= $pres[3];
			$more	= $pres[2];
		} else {
			$short	= $row['content'];
			$full	= '';
			$more	= '';
		}
	} else {
		list ($short, $full) = explode('<!--more-->', $row['content'], 2);
		$more = '';
	}

	// Check if long part is divided into several pages
	if ($full && (!$disablePagination) && (strpos($full, "<!--nextpage-->") !== false)) {
		if (!is_numeric($page) || ($page <1)) $page = 1;

		$pagination		=	'';
		$pages			=	explode("<!--nextpage-->", $full);

		if (($pcnt = count($pages)) > 1) {
			if ($page > 1) {
				$row['page']	=	$page - 1;
				$pagination		.=	" <a href=\"".GetLink('full_page', $row)."\">".$lang['prevpage']."</a>&nbsp;\n";
				$tvars['vars']['short-story'] = '';
			}

			for ($i = 1; $i <= $pcnt; $i++) {
				if ($i == $page) {
					$pagination		.=	"[<b>".$i."</b>]\n";
				} else {
					$row['page'] = $i;
					$pagination		.=	"<a href=\"".GetLink('full_page', $row)."\">$i</a>\n";
				}
			}

			if ($page != $pcnt) {
				$row['page']	=	$page + 1;
				$pagination		.=	"&nbsp;<a href=\"".GetLink('full_page', $row)."\">".$lang['nextpage']."</a>\n";
			}
			$tvars['vars']['pagination']	= $pagination;
			$full							= $pages[$page-1];
		}
	}

	if ($pagination) {
		$tvars['vars']['[pagination]'] = '';
		$tvars['vars']['[/pagination]'] = '';
	} else {
			$tvars['regx']["'\[pagination\].*?\[/pagination\]'si"] = '';
	}

	// Delete "<!--nextpage-->" if pagination is disabled
	if ($disablePagination)
		$full = str_replace("<!--nextpage-->", "\n", $full);

	// If HTML code is not permitted - LOCK it
	$title = $row['title'];

	if (!($row['flags'] & 2)) {
		$short	= str_replace('<', '&lt;', $short);
		$full	= str_replace('<', '&lt;', $full);
		$title	= secure_html($title);
	}
	$tvars['vars']['title'] = $title;

	// Make conversion
	if ($config['blocks_for_reg'])		{ $short = $parse -> userblocks($short);	$full = $parse -> userblocks($full); }
	if ($config['use_htmlformatter'] && (!($row['flags'] & 1)))	{
		$short = $parse -> htmlformatter($short);	$full = $parse -> htmlformatter($full);
	}
	if ($config['use_bbcodes'])			{ $short = $parse -> bbcodes($short);		$full = $parse -> bbcodes($full); }
	if ($config['use_smilies'])			{ $short = $parse -> smilies($short);		$full = $parse -> smilies($full); }

	$tvars['vars']['short-story']	= $short;
	$tvars['vars']['full-story']	= $full;

	// Activities for short mode
	if (!$fullMode) {
		// Make link for full news
		$tvars['vars']['[full-link]']	=	"<a href=\"".$url."\">";
		$tvars['vars']['[/full-link]']	=	"</a>";

		$tvars['vars']['[link]']	=	"<a href=\"".$url."\">";
		$tvars['vars']['[/link]']	=	"</a>";

		$tvars['vars']['full-link']	= $url;

		// Make blocks [fullnews] .. [/fullnews] and [nofullnews] .. [/nofullnews]
		if (strlen($full)) {
			// we have full news
			$tvars['vars']['[fullnews]'] = '';
			$tvars['vars']['[/fullnews]'] = '';

			$tvars['regx']["'\[nofullnews\].*?\[/nofullnews\]'si"] = '';
		} else {
			// we have ONLY short news
			$tvars['vars']['[nofullnews]'] = '';
			$tvars['vars']['[/nofullnews]'] = '';

			$tvars['regx']["'\[fullnews\].*?\[/fullnews\]'si"] = '';

		}

	} else {
		$tvars['regx']["'\\[full-link\\].*?\\[/full-link\\]'si"] = '';
		$tvars['regx']["'\\[link\\](.*?)\\[/link\\]'si"] = '$1';
	}

	$tvars['vars']['pinned']	=	($row['pinned']) ? "news_pinned" : "";
	$tvars['vars']['category']	=	@GetCategories($row['catid']);

	$tvars['vars']['[print-link]']	=	"<a href=\"".GetLink('print', $row)."\">";
	$tvars['vars']['[/print-link]']	=	"</a>";
	$tvars['vars']['news_link']		=	$url;


	$tvars['vars']['news-id']	=	$row['id'];
	$tvars['vars']['php-self']	=	$PHP_SELF;

	if ($row['editdate']) {
		$tvars['regx']['[\[update\](.*)\[/update\]]'] = '$1';
		$tvars['vars']['update'] = LangDate($config['timestamp_updated'], $row['editdate']);
	} else {
		$tvars['regx']['[\[update\](.*)\[/update\]]'] = '';
		$tvars['vars']['update'] = '';
	}

	if ($more == '') {
		$tvars['vars']['[more]']	= '';
		$tvars['vars']['[/more]']	= '';
	} else {
		$tvars['regx']['#\[more\](.+?)\[/more\]#is'] = $more;
	}


	return $tvars;
}


//
// Navigation line generator for news / static pages
//
function generateNavigations($current, $start, $stop, $which_link, $row, $navigations){
	$result = '';
	for ($j=$start; $j<=$stop; $j++) {
		if ($j == $current) {
			$result .= str_replace('%page%',$j,$navigations['current_page']);
		} else {
			$row['page'] = $j;
			$result .= str_replace('%page%',$j,str_replace('%link%',GetLink($which_link, $row), $navigations['link_page']));
		}
	}
	return $result;
}



function GetNewsTitle($sep, $name) {
	global $config, $lang, $mysql, $catz;

	if ($config['lock']) {
		return $sep.$lang['lock'];
	}

	if (altname) {
		if ($config["category_link"] == "0") {
			$where['catid']		=	"";
			$where['date']		=	" postdate > '".mktime(0, 0, 0, month, day, year)."' AND postdate < '".mktime(23, 59, 59, month, day, year)."'";
		} else {
			if (category == "none") {
				$where['catid']		=	" catid = ''";
				$where['date']		=	"";
			} else {
				$category = explode("-", category);

				foreach ($category as $cat) {
					$catids[] = $catz[$cat]['id'];
				}
				$categories = implode(",", $catids);
				$where['catid']		=	" catid regexp '[[:<:]]($categories)[[:>:]]'";
				$where['date']		=	"";
			}
		}

		$title = $mysql->result("SELECT title FROM ".prefix."_news WHERE".$where['catid'].$where['date']." AND alt_name = ".db_squote($name));

		if ($config["category_link"] == "1") {
			$echo_name = $sep.($categories ? GetCategories($categories, true) : "Uncategorized").$sep.secure_html($title);
		} else {
			$echo_name = $sep.LangDate(timestamp, mktime("0", "0", "0", month, day, year)).$sep.secure_html($title);
		}
	}
	elseif (id) {
		$row = $mysql->record("SELECT title, catid, month(from_unixtime(postdate)) as mm, year(from_unixtime(postdate)) as yy, day(from_unixtime(postdate)) as dd FROM ".prefix."_news WHERE id = ".db_squote(id));

		if ($config["category_link"] == "0") {
			if ($row['catid']) {
				foreach ($row['catid'] as $cat) {
					$catids[] = $catz[$cat]['id'];
				}
			} else {
				$catids = array();
			}

			$categories = implode(",", $catids);
			$echo_name = $sep.($categories ? GetCategories($categories, true) : "Uncategorized").$sep.secure_html($row['title']);
		} else {
			$echo_name = $sep.LangDate(timestamp, mktime("0", "0", "0", $row['mm'], $row['dd'], $row['yy'])).$sep.secure_html($row['title']);
		}
	} else {
		if (category && !altname) {
			$cat_name	=	$catz[category]['name'];
			$echo_name	=	$sep.$cat_name;
		}
		if (year && (!month && !altname)) {
			$echo_name = $sep.LangDate("Y", mktime(0, 0, 0, 12, 7, year));
		}
		if (month && (!category && !altname)) {
			$echo_name = $sep.LangDate("F Y", mktime(0, 0, 0, month, 7, year));
		}
		if (month && day && (!category && !altname)) {
			$echo_name = $sep.LangDate("j Q Y", mktime("0", "0", "0", month, day, year));
		}
		if (!category && !day && !month && !year && !altname && !id) {
			if ($_POST['action'] == "search") {
				$echo_name = $sep.$lang['search'];
			} else {
				$echo_name = $sep.$lang['mainpage'];
			}
		}
	}

	return(stripslashes($echo_name));
}


function GetMetatags() {
	global $config, $mysql, $catz, $category, $SYSTEM_FLAGS;

	if (!$config['meta'])
		return;

	$meta['description']	=	$config['description'];
	$meta['keywords']		=	$config['keywords'];

	if ($category) {
		if ($catz[$category]['description']) {
			$meta['description'] = $catz[$category]['description'];
		}
		if ($catz[$category]['keywords']) {
			$meta['keywords'] = $catz[$category]['keywords'];
		}
	}

	if (isset($SYSTEM_FLAGS['meta']['description']) && ($SYSTEM_FLAGS['meta']['description'] != ''))
		$meta['description'] = $SYSTEM_FLAGS['meta']['description'];

	if (isset($SYSTEM_FLAGS['meta']['keywords']) && ($SYSTEM_FLAGS['meta']['keywords'] != ''))
		$meta['keywords'] = $SYSTEM_FLAGS['meta']['keywords'];

	$result  = ($meta['description'] != '')?"<meta name=\"description\" content=\"".secure_html($meta['description'])."\" />\r\n":'';
	$result .= ($meta['keywords'] != '')?"<meta name=\"keywords\" content=\"".secure_html($meta['keywords'])."\" />\r\n":'';

	return $result;
}

function getNavigations($tpldir) {
	if (is_file($tpldir.'/navigation.ini')) {
		$navigation = parse_ini_file($tpldir.'/navigation.ini');
	} else {
		$navigation = array('prevlink' => '<a href="%link%">%page%</a> ','nextlink' => '<a href="%link%">%page%</a> ','current_page' => ' [%page%] ','link_page' => '<a href="%link%">%page%</a> ','dots' => ' ... ');
	}
	return $navigation;
}


//
// Generate navigations panel ( like: 1.2.[3].4. ... 25 )
// $current		- current page
// $start		- first page in navigations
// $end			- last page in navigations
// $maxnav		- maximum number of navigtions to show
// $link		- link to info page with text {page} that will be replaced by page number
function generatePagination($current, $start, $end, $maxnav, $link, $navigations){
	// Generate pagination block
	function generatePaginationBlock($current, $start, $end, $link, $navigations){
		$result = '';
		for ($j=$start; $j<=$end; $j++) {
			if ($j == $current) {
				$result .= str_replace('%page%',$j,$navigations['current_page']);
			} else {
				$row['page'] = $j;
				$result .= str_replace('%page%',$j,str_replace('%link%', str_replace('{page}', $j, $link), $navigations['link_page']));
			}
		}
		return $result;
	}


	$pages_count = $end - $start + 1;

	if ($pages_count > $maxnav) {
		// We have more than 10 pages. Let's generate 3 parts
		$sectionSize	= floor($maxnav / 3);

		// Section size should be not less 1 item
		if ($sectionSize < 1)
			$sectionSize = 1;

		// Situation #1: 1,2,3,4,[5],6 ... 128
		if ($start < ($sectionSize * 2)) {
			$pages .= generatePaginationBlock($current, 1, $sectionSize * 2, $link, $navigations);
			$pages .= " ... ";
			$pages .= generatePaginationBlock($current, $pages_count-$sectionSize, $pages_count, $link, $navigations);
		} elseif ($start > ($pages_count - $sectionSize * 2 + 1)) {
			$pages .= generatePaginationBlock($current, 1, $sectionSize, $link, $navigations);
			$pages .= " ... ";
			$pages .= generatePaginationBlock($current, $pages_count-$sectionSize*2 + 1, $pages_count, $link, $navigations);
		} else {
			$pages .= generatePaginationBlock($current, 1, $sectionSize, $link, $navigations);
			$pages .= " ... ";
			$pages .= generatePaginationBlock($current, $cstart-1, $cstart+1, $link, $navigations);
			$pages .= " ... ";
			$pages .= generatePaginationBlock($current, $pages_count-$sectionSize, $pages_count, $link, $navigations);
		}
	} else {
		// If we have less then 10 pages
		$pages .= generatePaginationBlock($current, 1, $pages_count, $link, $navigations);
	}
	return $pages;
}


//
// Return user record by login
//
function locateUser($login) {
	global $mysql;
	if ($row = $mysql->record("select * from ".uprefix."_users where name = ".db_squote($login))) {
		return $row;
	}
	return array();
}


function GetCategoryById($id) {
	global $catz;

	foreach ($catz as $cat) {
		if ($cat['id'] == $id) {
			return $cat;
		}
	}
	return array();
}


if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

// Parse params
function parseParams($paramLine){

	// Start scanning
	// State:
	// 0 - waiting for name
	// 1 - scanning name
	// 2 - waiting for '='
	// 3 - waiting for value
	// 4 - scanning value
	// 5 - complete
	$state = 0;
	// 0 - no quotes activated
	// 1 - single quotes activated
	// 2 - double quotes activated
	$quotes = 0;

	$keyName = '';
	$keyValue = '';
	$errorFlag = 0;

	$keys = array();

	for ($sI = 0; $sI < strlen($paramLine); $sI ++) {
		// act according current state
		$x = $paramLine{$sI};

		switch ($state) {
			case 0:  if      ($x == "'") { $quotes = 1; $state = 1; $keyName = '';}
					 else if ($x == "'") { $quotes = 2; $state = 1; $keyName = ''; }
					 else if ((($x >='A')&&($x <='Z'))||(($x >='a')&&($x <='z'))) { $state = 1; $keyName = $x; }
					 break;
			case 1:  if ((($quotes == 1)&&($x == "'"))||(($quotes == 2)&&($x == '"'))) { $quotes = 0; $state=2; }
					 else if ((($x >='A')&&($x <='Z'))||(($x >='a')&&($x <='z'))) { $keyName .= $x; }
					 else if ($x == '=') { $state = 3; }
					 else if (($x == ' ')||($x == chr(9))) { $state = 2; }
					 else { $erorFlag = 1; }
					 break;
			case 2:  if ($x == '=') { $state = 3; }
					 else if (($x == ' ')||($x == chr(9))) { ; }
					 else { $errorFlag = 1; }
					 break;
			case 3:  if      ($x == "'") { $quotes = 1; $state = 4; $keyValue = '';}
					 else if ($x == '"') { $quotes = 2; $state = 4; $keyValue = ''; }
					 else if ((($x >='A')&&($x <='Z'))||(($x >='a')&&($x <='z'))) { $state = 4; $keyValue = $x; }
					 break;
			case 4:  if ((($quotes == 1)&&($x == "'"))||(($quotes == 2)&&($x == '"'))) { $quotes = 0; $state=5; }
					 else if (!$quotes &&  (($x == ' ')||($x == chr(9)))) { $state = 5; }
					 else { $keyValue .= $x; }
					 break;
		}

		// Action in case when scanning is complete
		if ($state == 5) {
			$keys [ strtolower($keyName) ] = $keyValue;
			$state = 0;
		}
	}

	// If we finished and we're in stete "scanning value" - register this field
	if ($state == 4) {
		$keys [ strtolower($keyName) ] = $keyValue;
		$state = 0;
	}

	// If we have any other state - report an error
	if ($state) {
		$errorFlag = 1; // print "EF ($state)[".$paramLine."].";
	}

	if ($errorFlag) {
		return -1;
	}
	return $keys;
}

//
// Print output HTTP headers
//
function printHTTPheaders() {
	global $SYSTEM_FLAGS;

	foreach ($SYSTEM_FLAGS['http.headers'] as $hkey => $hvalue) {
		@header($hkey.': '.$hvalue);
	}
}