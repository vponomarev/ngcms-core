<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={{ lang['encoding'] }}" />
<title>{{ home_title }} - {{ lang['adminpanel'] }}</title>
<link rel="stylesheet" href="{{ skins_url }}/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="{{ skins_url }}/ftr_panel.css" type="text/css" />
<link rel="stylesheet" href="{{ home }}/lib/jqueryui/core/themes/cupertino/jquery-ui.min.css" type="text/css"/>
<link rel="stylesheet" href="{{ home }}/lib/jqueryui/core/themes/cupertino/jquery-ui.theme.min.css" type="text/css"/>
<link rel="stylesheet" href="{{ home }}/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css" type="text/css"/>
<!--<link rel="stylesheet" href="{{ home }}/lib/jqueryui/plugins/jquery-ui-multiselect-widget-1.17/jquery.multiselect.css" type="text/css"/>-->
<script type="text/javascript" src="{{ home }}/lib/jq/jquery.min.js"></script>
<script type="text/javascript" src="{{ home }}/lib/jqueryui/core/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ home }}/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js"></script>
<!--<script type="text/javascript" src="{{ home }}/lib/jqueryui/plugins/jquery-ui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>-->
<!--<script type="text/javascript" src="{{ home }}/lib/jqueryui/plugins/jquery-ui-multiselect-widget/src/jquery.multiselect.min.js"></script>-->
<script type="text/javascript" src="{{ home }}/lib/functions.js"></script>
<script type="text/javascript" src="{{ home }}/lib/admin.js"></script>
<script language="javascript" type="text/javascript">
{{ datetimepicker_lang }}
</script>
</head>
<body>
<div id="loading-layer"><img src="{{ skins_url }}/images/loading.gif" alt="" /> {{ lang['loading'] }} ...</div>
<table border="0" width="1000" align="center" cellspacing="0" cellpadding="0">
<tr>
<td width="100%">
<div id="topNavigator">
	<span><a href="{{ home }}" title="{{ lang['mainpage_t'] }}" target="_blank">{{ lang['mainpage'] }}</a></span>
	<span{{ h_active_options }}><a href="{{ php_self }}?mod=options" title="{{ lang['options_t'] }}">{{ lang['options'] }}</a></span>
	<span{{ h_active_extras }}><a href="{{ php_self }}?mod=extras" title="{{ lang['extras_t'] }}">{{ lang['extras'] }}</a></span>
	<span{{ h_active_addnews }}><a href="{{ php_self }}?mod=news&amp;action=add" title="{{ lang['addnews_t'] }}">{{ lang['addnews'] }}</a></span>
	<span{{ h_active_editnews }}><a href="{{ php_self }}?mod=news" title="{{ lang['editnews_t'] }}">{{ lang['editnews'] }}</a>{{ unapproved }}</span>
	<span{{ h_active_images }}><a href="{{ php_self }}?mod=images" title="{{ lang['images_t'] }}">{{ lang['images'] }}</a></span>
	<span{{ h_active_files }}><a href="{{ php_self }}?mod=files" title="{{ lang['files_t'] }}">{{ lang['files'] }}</a></span>
	<span{{ h_active_pm }}><a href="{{ php_self }}?mod=pm" title="{{ lang['pm_t'] }}">{{ lang['pm'] }}</a> [ {{ newpm }} ]</span>
	<span><a href="{{ php_self }}?action=logout" title="{{ lang['logout_t'] }}">{{ lang['logout'] }}</a></span>
</div>
<div id="adminDataBlock" style="text-align : left;">
{{ notify }}
{{ main_admin }}

</div>
<div class="clear_20"></div>
<div class="clear_ftr"></div>
<div id="footpanel">
    <ul id="mainpanel">
        <li><a href="http://ngcms.ru" target="_blank" class="home">© 2008-{{ year }} <strong>Next Generation</strong> CMS <small>{{ lang['ngcms_site'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=news&amp;action=add" class="add_news">{{ lang['addnews_t'] }}<small>{{ lang['addnews_t'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=news" class="add_edit">{{ lang['editnews'] }}<small>{{ lang['editnews'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=images" class="add_images">{{ lang['images'] }}<small>{{ lang['images'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=files" class="add_files">{{ lang['files'] }}<small>{{ lang['files'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=extras" class="add_plugins">{{ lang['extras'] }}<small>{{ lang['extras'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=categories" class="add_category">{{ lang['categories'] }}<small>{{ lang['categories'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=users" class="add_user">{{ lang['users'] }}<small>{{ lang['users'] }}</small></a></li>
        <li><a href="{{ php_self }}?mod=configuration" class="add_system_option">{{ lang['options_t'] }}<small>{{ lang['options_t'] }}</small></a></li>
        <li id="alertpanel"><a href="http://rocketvip.ru/" target="_blank" class="rocket">{{ lang['design'] }}- RocketBoy</a></li>
        <li id="chatpanel"><a href="http://ngcms.ru/forum/" target="_blank" class="chat">{{ lang['forum'] }}</a></li>

    </ul>
</div>
</td>
</table>
</body>
</html>