<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('index', 'admin');

if (is_array($userROW)) {
	$newpm = $mysql->result("SELECT count(pmid) FROM ".prefix."_users_pm WHERE to_id = ".db_squote($userROW['id'])." AND viewed = '0'");
	$newpm = ($newpm != "0") ? '<b>'.$newpm.'</b>' : '0';

	// Calculate number of un-approved news
	$unapproved = '';
	if ($userROW['status'] == 1 || $userROW['status'] == 2) {
		$unapp = $mysql->result("SELECT count(id) FROM ".prefix."_news WHERE approve = '0'");
		if ($unapp)
			$unapproved = ' [ <a href="?mod=news&amp;status=2"><font color="red"><b>'.$unapp.'</b></font></a> ] ';
	}
}

$skins_url = skins_url;

$mod = $_REQUEST['mod'];
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

$h_active_options = (in_array($mod, array('options', 'categories', 'static')))?' class="active"':'';
$h_active_extras = (($mod=='extra-config')||($mod=='extras'))?' class="active"':'';
$h_active_addnews = (($mod=='news')&&($action=='add'))?' class="active"':'';
$h_active_editnews = (($mod=='news')&&($action!='add'))?' class="active"':'';
$h_active_images = ($mod=='images')?' class="active"':'';
$h_active_files = ($mod=='files')?' class="active"':'';
$h_active_pm = ($mod=='pm')?' class="active"':'';

$datetimepicker_lang_default = "
$.datepicker.setDefaults($.datepicker.regional['".$lang['langcode']."']);
$.timepicker.setDefaults($.timepicker.regional['".$lang['langcode']."']);
";
$datetimepicker_lang = ($lang['langcode'] == 'ru') ? $datetimepicker_lang_default : "";

$year = date("Y");

$skin_header = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$lang[langcode]" lang="$lang[langcode]" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$lang[encoding]" />
<title>$config[home_title] - $lang[adminpanel]</title>
<link rel="stylesheet" href="$skins_url/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="$skins_url/ftr_panel.css" type="text/css" />
<link rel="stylesheet" href="$config[home_url]/lib/jqueryui/core/themes/cupertino/jquery-ui.min.css" type="text/css"/>
<link rel="stylesheet" href="$config[home_url]/lib/jqueryui/core/themes/cupertino/jquery-ui.theme.min.css" type="text/css"/>
<link rel="stylesheet" href="$config[home_url]/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css" type="text/css"/>
<!--<link rel="stylesheet" href="$config[home_url]/lib/jqueryui/plugins/jquery-ui-multiselect-widget-1.17/jquery.multiselect.css" type="text/css"/>-->
<script type="text/javascript" src="$config[home_url]/lib/jq/jquery.min.js"></script>
<script type="text/javascript" src="$config[home_url]/lib/jqueryui/core/jquery-ui.min.js"></script>
<script type="text/javascript" src="$config[home_url]/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js"></script>
<!--<script type="text/javascript" src="$config[home_url]/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>-->
<!--<script type="text/javascript" src="$config[home_url]/lib/jqueryui/plugins/jquery-ui-multiselect-widget/src/jquery.multiselect.min.js"></script>-->
<script type="text/javascript" src="$config[home_url]/lib/functions.js"></script>
<script type="text/javascript" src="$config[home_url]/lib/admin.js"></script>
<script language="javascript" type="text/javascript">
$datetimepicker_lang
</script>
</head>
<body>
<div id="loading-layer"><img src="$skins_url/images/loading.gif" alt="" /> Loading ...</div>
<table border="0" width="1000" align="center" cellspacing="0" cellpadding="0">
<tr>
<td width="100%">
<div id="topNavigator">
	<span><a href="$config[home_url]" title="$lang[mainpage_t]" target="_blank">$lang[mainpage]</a></span>
	<span${h_active_options}><a href="$PHP_SELF?mod=options" title="$lang[options_t]">$lang[options]</a></span>
	<span${h_active_extras}><a href="$PHP_SELF?mod=extras" title="$lang[extras_t]">$lang[extras]</a></span>
	<span${h_active_addnews}><a href="$PHP_SELF?mod=news&amp;action=add" title="$lang[addnews_t]">$lang[addnews]</a></span>
	<span${h_active_editnews}><a href="$PHP_SELF?mod=news" title="$lang[editnews_t]">$lang[editnews]</a>$unapproved</span>
	<span${h_active_images}><a href="$PHP_SELF?mod=images" title="$lang[images_t]">$lang[images]</a></span>
	<span${h_active_files}><a href="$PHP_SELF?mod=files" title="$lang[files_t]">$lang[files]</a></span>
	<span${h_active_pm}><a href="$PHP_SELF?mod=pm" title="$lang[pm_t]">$lang[pm]</a> [ $newpm ]</span>
	<span><a href="$PHP_SELF?action=logout" title="$lang[logout_t]">$lang[logout]</a></span>
</div>
<div id="adminDataBlock" style="text-align : left;">
HTML;


$skin_footer = <<<HTML
</div>
<div class="clear_20"></div>
<div class="clear_ftr"></div>
<div id="footpanel">
    <ul id="mainpanel">
        <li><a href="http://ngcms.ru" target="_blank" class="home">© 2008-$year <strong>Next Generation</strong> CMS <small>$lang[ngcms_site]</small></a></li>
        <li><a href="$PHP_SELF?mod=news&amp;action=add" class="add_news">$lang[addnews_t]<small>$lang[addnews_t]</small></a></li>
        <li><a href="$PHP_SELF?mod=news" class="add_edit">$lang[editnews]<small>$lang[editnews]</small></a></li>
        <li><a href="$PHP_SELF?mod=images" class="add_images">$lang[images]<small>$lang[images]</small></a></li>
        <li><a href="$PHP_SELF?mod=files" class="add_files">$lang[files]<small>$lang[files]</small></a></li>
        <li><a href="$PHP_SELF?mod=extras" class="add_plugins">$lang[extras]<small>$lang[extras]</small></a></li>
        <li><a href="$PHP_SELF?mod=categories" class="add_category">$lang[categories]<small>$lang[categories]</small></a></li>
        <li><a href="$PHP_SELF?mod=users" class="add_user">$lang[users]<small>$lang[users]</small></a></li>
        <li><a href="$PHP_SELF?mod=configuration" class="add_system_option">$lang[options_t]<small>$lang[options_t]</small></a></li>
        <li id="alertpanel"><a href="http://rocketvip.ru/" target="_blank" class="rocket">$lang[design]- RocketBoy</a></li>
        <li id="chatpanel"><a href="http://ngcms.ru/forum/" target="_blank" class="chat">$lang[forum]</a></li>

    </ul>
</div>
</td>
</table>
</body>
</html>
HTML;
?>