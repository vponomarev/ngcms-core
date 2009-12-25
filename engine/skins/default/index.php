<?php

$lang = LoadLang('index', 'admin');

if (is_array($userROW)) {
	$newpm = $mysql->result("SELECT count(pmid) FROM ".prefix."_users_pm WHERE to_id = ".db_squote($userROW['id'])." AND viewed = '0'");
	$newpm = ($newpm != "0") ? '<b>'.$newpm.'</b>' : '0';

	// Calculate number of un-approved news
	$unapproved = '';
	if ($userROW['status'] == 1 || $userROW['status'] == 2) {
		$unapp = $mysql->result("SELECT count(id) FROM ".prefix."_news WHERE approve = '0'");
		if ($unapp)
			$unapproved = ' [ <a href="?mod=editnews&status=1"><font color="red"><b>'.$unapp.'</b></font></a> ] ';
	}
}

$skins_url = skins_url;

$h_active_options = ($mod=='options')?' class="active"':'';
$h_active_extras = (($mod=='extra-config')||($mod=='extras'))?' class="active"':'';
$h_active_addnews = ($mod=='addnews')?' class="active"':'';
$h_active_editnews = ($mod=='editnews')?' class="active"':'';
$h_active_images = ($mod=='images')?' class="active"':'';
$h_active_files = ($mod=='files')?' class="active"':'';
$h_active_pm = ($mod=='pm')?' class="active"':'';

$skin_header = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$lang[langcode]" lang="$lang[langcode]" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$lang[encoding]" />
<title>$config[home_title] - $lang[adminpanel]</title>
<link rel="stylesheet" href="$skins_url/style.css" type="text/css" media="screen" />
<script type="text/javascript" src="$config[admin_url]/includes/js/functions.js"></script>
</head>
<body>
<table border="0" width="1000" align="center" cellspacing="0" cellpadding="0">
<tr>
<td width="100%">
<div id="topNavigator">
	<span><a href="$config[home_url]" title="$lang[mainpage_t]" target="_blank">$lang[mainpage]</a></span>
	<span${h_active_options}><a href="$PHP_SELF?mod=options" title="$lang[options_t]">$lang[options]</a></span>
	<span${h_active_extras}><a href="$PHP_SELF?mod=extras" title="$lang[extras_t]">$lang[extras]</a></span>
	<span${h_active_addnews}><a href="$PHP_SELF?mod=addnews" title="$lang[addnews_t]">$lang[addnews]</a></span>
	<span${h_active_editnews}><a href="$PHP_SELF?mod=editnews" title="$lang[editnews_t]">$lang[editnews]</a>$unapproved</span>
	<span${h_active_images}><a href="$PHP_SELF?mod=images" title="$lang[images_t]">$lang[images]</a></span>
	<span${h_active_files}><a href="$PHP_SELF?mod=files" title="$lang[files_t]">$lang[files]</a></span>
	<span${h_active_pm}><a href="$PHP_SELF?mod=pm" title="$lang[pm_t]">$lang[pm]</a> [ $newpm ]</span>
	<span><a href="$PHP_SELF?action=logout" title="$lang[logout_t]">$lang[logout]</a></span>
</div>
<div style="text-align : left;">
HTML;


$skin_footer = <<<HTML
</div>
<div class="footer">$lang[copyright]</div>
</td>
</table>
</body>
</html>
HTML;
?>