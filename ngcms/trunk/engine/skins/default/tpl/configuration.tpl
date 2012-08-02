<script type="text/javascript">
function ChangeOption(selectedOption) {
 var defaultOption='system';
 var myList = new Array('db', 'security', 'system', 'news', 'users', 'images', 'files', 'auth', 'multi');
 var found = 0, i = 0;

 for (i=0; i<myList.length; i++) {
  if (myList[i] == selectedOption) found = 1;
  document.getElementById(myList[i]).style.display=(myList[i] == selectedOption?'':'none');
  document.getElementById('b_'+myList[i]).style.fontWeight=(myList[i] == selectedOption?'bold':'normal');
 }

 if (!found) {
	document.getElementById(defaultOption).style.display='';
	document.getElementById('b_'+defaultOption).style.fontWeight='bold';
 } else {
	document.getElementById('selectedOption').value = selectedOption;
 }
}
</script>

<form method="post" action="{php_self}?mod=configuration&amp;subaction=save">
<input type="hidden" name="token" value="{token}"/>
<input type=hidden name="selectedOption" id="selectedOption" />

<!-- Navigation bar -->
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=configuration">{l_configuration_title}</a></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" class="contentNav" align="center" valign="top">
<input id="b_db"       style="width:90px;"  type="button" onclick="javascript:ChangeOption('db')" value="{l_db}" class="navbutton" />
<input id="b_security" style="width:100px;" type="button" onclick="javascript:ChangeOption('security')" value="{l_security}" class="navbutton" />
<input id="b_system"   style="width:140px;" type="button" onclick="javascript:ChangeOption('system')" value="{l_syst}" class="navbutton" />
<input id="b_news"     style="width:80px;"  type="button" onclick="javascript:ChangeOption('news')" value="{l_sn}" class="navbutton" />
<input id="b_users"    style="width:100px;" type="button" onclick="javascript:ChangeOption('users')" value="{l_users}" class="navbutton" />
<input id="b_images"   style="width:100px;" type="button" onclick="javascript:ChangeOption('images')" value="{l_img}" class="navbutton" />
<input id="b_files"    style="width:80px;"  type="button" onclick="javascript:ChangeOption('files')" value="{l_files}" class="navbutton" />
<input id="b_auth"     style="width:90px;"  type="button" onclick="javascript:ChangeOption('auth')" value="{l_auth}" class="navbutton" />
<input id="b_multi"    style="width:80px;"  type="button" onclick="javascript:ChangeOption('multi')" value="{l_multi}" class="navbutton" />
</td>
</tr>
</table>

<br/>

<!-- (((((((((( CONTENT BLOCK )))))))))) -->
<!-- ########## DB            ########## -->
<div id="db" style="display: none;">
<!-- TABLE DB//Connection -->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_db_connect}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_dbhost}<br /><small>{l_example} localhost</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[dbhost]' value='{c_dbhost}' size="40" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_dbname}<br /><small>{l_example} ng</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[dbname]' value='{c_dbname}' size="40" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_dbuser}<br /><small>{l_example} root</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[dbuser]' value='{c_dbuser}' size="40" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_dbpass}<br /><small>{l_example} password</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input class="password" type="password" name='save_con[dbpasswd]' value='{c_dbpasswd}' size="40" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_dbprefix}<br /><small>{l_example} ng</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[prefix]' value='{c_prefix}' size="40" /></td>
</tr>
</table>
<!-- END: TABLE DB//Connection -->
<!-- TABLE DB//Backup -->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_db_backup}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_auto_backup}<br /><small>{l_auto_backup_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle">{auto_backup}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_auto_backup_time}<br /><small>{l_auto_backup_time_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='save_con[auto_backup_time]' value='{c_auto_backup_time}' size="5" maxlength="5" /></td>
</tr>
</table>
<!-- END: TABLE DB//Backup -->
</div>

<!-- ########## SECURYTY      ########## -->
<div id="security" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_logging}</td>
</tr>
<tr>
<td class="contentEntry1">{l_syslog}<br /><small>{l_syslog_desc}</small></td>
<td class="contentEntry2" valign="middle">{syslog}</td>
</tr>
<tr>
<td class="contentEntry1">{l_load}<br /><small>{l_load_desc}</small></td>
<td class="contentEntry2" valign="middle">{load}</td>
</tr>
<tr>
<td class="contentEntry1">{l_load_profiler}<br /><small>{l_load_profiler_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name="save_con[load_profiler]" value="{load_profiler}" /></td>
</tr>
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_security}</td>
</tr>
<tr>
<td class="contentEntry1">{l_flood_time}<br /><small>{l_flood_time_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[flood_time]' value='{c_flood_time}' size="6" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_use_captcha}<br /><small>{l_use_captcha_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_captcha}</td>
</tr>
<tr>
<td class="contentEntry1">{l_captcha_font}<br /><small>{l_captcha_font_desc}</small></td>
<td class="contentEntry2" valign="middle">{captcha_font}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_cookies}<br /><small>{l_use_cookies_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_cookies}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_sessions}<br /><small>{l_use_sessions_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_sessions}</td>
</tr>
<tr>
<td class="contentEntry1">{l_sql_error}<br /><small>{l_sql_error_desc}</small></td>
<td class="contentEntry2" valign="middle">{sql_error}</td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_debug_generate}</td>
</tr>
<tr>
<td class="contentEntry1">{l_debug}<br /><small>{l_debug_desc}</small></td>
<td class="contentEntry2" valign="middle">{debug}</td>
</tr>
<tr>
<td class="contentEntry1">{l_debug_queries}<br /><small>{l_debug_queries_desc}</small></td>
<td class="contentEntry2" valign="middle">{debug_queries}</td>
</tr>
<tr>
<td class="contentEntry1">{l_debug_profiler}<br /><small>{l_debug_profiler_desc}</small></td>
<td class="contentEntry2" valign="middle">{debug_profiler}</td>
</tr>
</table>
</div>

<!-- ########## SYSTEM        ########## -->
<div id="system" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_syst}</td>
</tr>
<tr>
<td class="contentEntry1">{l_home_url}<br /><small>{l_example} http://server.com</small></td>
<td class="contentEntry2" valign="middle"><input class="home" type="text" name='save_con[home_url]' value='{home_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_admin_url}<br /><small>{l_example} http://server.com/engine</small></td>
<td class="contentEntry2" valign="middle"><input class="home" type="text" name='save_con[admin_url]' value='{admin_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_home_title}<br /><small>{l_example} NGcms</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[home_title]' value='{c_home_title}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_admin_mail}<br /><small>{l_example} admin@server.com</small></td>
<td class="contentEntry2" valign="middle"><input class="email" type="text" name='save_con[admin_mail]' value='{c_admin_mail}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_mailfrom_name}<br /><small>{l_example} Administrator</small></td>
<td class="contentEntry2" valign="middle"><input class="mailfrom_name" type="text" name='save_con[mailfrom_name]' value='{c_mailfrom_name}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_mailfrom}<br /><small>{l_example} mailbot@server.com</small></td>
<td class="contentEntry2" valign="middle"><input class="mailfrom" type="text" name='save_con[mailfrom]' value='{c_mailfrom}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_lock}<br /><small>{l_lock_desc}</small></td>
<td class="contentEntry2" valign="middle">{lock}</td>
</tr>
<tr>
<td class="contentEntry1">{l_lock_reason}<br /><small>{l_lock_reason_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[lock_reason]' value='{c_lock_reason}' size="40" maxlength="200" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_meta}<br /><small>{l_meta_desc}</small></td>
<td class="contentEntry2" valign="middle">{meta}</td>
</tr>
<tr>
<td class="contentEntry1">{l_description}<br /><small>{l_description_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name="save_con[description]" value="{c_description}" size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_keywords}<br /><small>{l_keywords_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name="save_con[keywords]" value="{c_keywords}" size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_theme}<br /><small>{l_theme_desc}</small></td>
<td class="contentEntry2" valign="middle">{list_themes}</td>
</tr>
<tr>
<td class="contentEntry1">{l_lang}<br /><small>{l_lang_desc}</small></td>
<td class="contentEntry2" valign="middle">{language_selection}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_gzip}<br /><small>{l_use_gzip_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_gzip}</td>
</tr>
<tr>
<td class="contentEntry1">{l_404_mode}<br /><small>{l_404_mode_desc}</small></td>
<td class="contentEntry2" valign="middle">{404_mode}</td>
</tr>
<tr>
<td class="contentEntry1">{l_libcompat}<br /><small>{l_libcompat_desc}</small></td>
<td class="contentEntry2" valign="middle">{libcompat}</td>
</tr>
<tr>
<td class="contentEntry1">{l_url_external_nofollow}<br /><small>{l_url_external_nofollow_desc}</small></td>
<td class="contentEntry2" valign="middle">{url_external_nofollow}</td>
</tr>
<tr>
<td class="contentEntry1">{l_url_external_target_blank}<br /><small>{l_url_external_target_blank_desc}</small></td>
<td class="contentEntry2" valign="middle">{url_external_target_blank}</td>
</tr>
</table>
</div>

<!-- ########## NEWS          ########## -->
<div id="news" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_sn}</td>
</tr>
<tr>
<td class="contentEntry1">{l_number}</td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[number]' value='{c_number}' size="6" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_news_multicat_url}<br /><small>{l_news_multicat_url#desc}</small></td>
<td class="contentEntry2" valign="middle">{news_multicat_url}</td>
</tr>
<tr>
<td class="contentEntry1">{l_nnavigations}<br/><small>{l_nnavigations_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[newsNavigationsCount]' value='{c_newsNavigationsCount}' size="6" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_category_counters}<br /><small>{l_category_counters_desc}</small></td>
<td class="contentEntry2" valign="middle">{category_counters}</td>
</tr>
<tr>
<td class="contentEntry1">{l_news_view_counters}<br /><small>{l_news_view_counters#desc}</small></td>
<td class="contentEntry2" valign="middle">{news_view_counters}</td>
</tr>

<!--
<tr>
<td class="contentEntry1">{l_category_link}<br /><small>{l_category_link_desc}</small></td>
<td class="contentEntry2" valign="middle">{category_link}</td>
</tr>
-->
<tr>
<td class="contentEntry1">{l_news.edit.split}<br /><small>{l_news.edit.split#desc}</small></td>
<td class="contentEntry2" valign="middle">{news.edit.split}</td>
</tr>
<tr>
<td class="contentEntry1">{l_news_without_content}<br /><small>{l_news_without_content_desc}</small></td>
<td class="contentEntry2" valign="middle">{news_without_content}</td>
</tr>
<tr>
<td class="contentEntry1">{l_date_adjust}<br /><small>{l_date_adjust_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[date_adjust]' value='{c_date_adjust}' size="6" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_timestamp_active}<br /><small>{l_date_help}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='save_con[timestamp_active]' value='{c_timestamp_active}' size="20" /><br /><small>{l_date_now} {timestamp_active_now}</small></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_timestamp_updated}<br /><small>{l_date_help}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='save_con[timestamp_updated]' value='{c_timestamp_updated}' size="20" /><br /><small>{l_date_now} {timestamp_updated_now}</small></td>
</tr>
<tr>
<td class="contentEntry1">{l_smilies}<br /><small>{l_smilies_desc} (<b>,</b>)</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[smilies]' value='{c_smilies}' style="width: 400px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_blocks_for_reg}<br /><small>{l_blocks_for_reg_desc}</small></td>
<td class="contentEntry2" valign="middle">{blocks_for_reg}</td>
</tr>
<tr>
<td class="contentEntry1">{l_extended_more}<br /><small>{l_extended_more_desc}</small></td>
<td class="contentEntry2" valign="middle">{extended_more}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_smilies}<br /><small>{l_use_smilies_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_smilies}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_bbcodes}<br /><small>{l_use_bbcodes_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_bbcodes}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_htmlformatter}<br /><small>{l_use_htmlformatter_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_htmlformatter}</td>
</tr>
<tr>
<td class="contentEntry1">{l_default_newsorder}<br /><small>{l_default_newsorder_desc}</small></td>
<td class="contentEntry2" valign="middle">{default_newsorder}</td>
</tr>
<tr>
<td class="contentEntry1">{l_htmlsecure_4}<br /><small>{l_htmlsecure_4_desc}</small></td>
<td class="contentEntry2" valign="middle">{htmlsecure_4}</td>
</tr>
<tr>
<td class="contentEntry1">{l_htmlsecure_3}</td>
<td class="contentEntry2" valign="middle">{htmlsecure_3}</td>
</tr>
<tr>
<td class="contentEntry1">{l_htmlsecure_2}</td>
<td class="contentEntry2" valign="middle">{htmlsecure_2}</td>
</tr>
</table>
</div>


<!-- ########## USERS         ########## -->
<div id="users" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_users}</td>
</tr>
<tr>
<td class="contentEntry1">{l_users_selfregister}<br /><small>{l_users_selfregister_desc}</small></td>
<td class="contentEntry2" valign="middle">{users_selfregister}</td>
</tr>
<tr>
<td class="contentEntry1">{l_register_type}<br /><small>{l_register_type_desc}</small></td>
<td class="contentEntry2" valign="middle">{register_type}</td>
</tr>
<tr>
<td class="contentEntry1">{l_use_avatars}<br /><small>{l_use_avatars_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_avatars}</td>
</tr>
<tr>
<td class="contentEntry1">{l_avatars_gravatar}<br /><small>{l_avatars_gravatar_desc}</small></td>
<td class="contentEntry2" valign="middle">{avatars_gravatar}</td>
</tr>
<tr>
<td class="contentEntry1">{l_avatars_url}<br /><small>{l_example} http://server.com/uploads/avatars</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[avatars_url]' value='{c_avatars_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_avatars_dir}<br /><small>{l_example} /home/servercom/public_html/uploads/avatars/</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[avatars_dir]' value='{c_avatars_dir}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_avatar_wh}<br /><small>{l_avatar_wh_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[avatar_wh]' value='{c_avatar_wh}' style="width: 40px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_avatar_max_size}<br /><small>{l_avatar_max_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[avatar_max_size]' value='{c_avatar_max_size}' style="width: 40px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_use_photos}<br /><small>{l_use_photos_desc}</small></td>
<td class="contentEntry2" valign="middle">{use_photos}</td>
</tr>
<tr>
<td class="contentEntry1">{l_photos_url}<br /><small>{l_example} http://server.com/uploads/photos</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[photos_url]' value='{c_photos_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_photos_dir}<br /><small>{l_example} /home/servercom/public_html/uploads/photos/</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[photos_dir]' value='{c_photos_dir}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_photos_max_size}<br /><small>{l_photos_max_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[photos_max_size]' value='{c_photos_max_size}' style="width: 40px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_photos_thumb_size}<br /><small>{l_photos_thumb_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[photos_thumb_size_x]' value='{photos_thumb_size_x}' style="width: 40px" /> x <input type="text" name='save_con[photos_thumb_size_y]' value='{photos_thumb_size_y}' style="width: 40px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_user_aboutsize}<br /><small>{l_user_aboutsize_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[user_aboutsize]' value='{c_user_aboutsize}' style="width: 40px"  /></td>
</tr>
</table>
</div>

<!-- ########## IMAGES        ########## -->
<div id="images" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_img}</td>
</tr>
<tr>
<td class="contentEntry1">{l_images_url}<br /><small>{l_example} http://server.com/uploads/images</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[images_url]' value='{c_images_url}' style="width: 400px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_images_dir}<br /><small>{l_example} /home/servercom/public_html/uploads/images/</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[images_dir]' value='{c_images_dir}' style="width: 400px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_images_ext}<br /><small>{l_images_ext_desc}</small></td>
<td class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[images_ext]' value='{c_images_ext}' style="width: 400px" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_images_max_size}<br /><small>{l_images_max_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[images_max_size]' value='{c_images_max_size}' size="6" /></td>
</tr>

<!-- IMAGE transform control -->
<tr><td colspan="2" class="contentHead">&nbsp;</td></tr>
<tr>
<td class="contentEntry1">{l_thumb_mode}<br /><small>{l_thumb_mode_desc}</small></td>
<td class="contentEntry2" valign="middle">{thumb_mode}</td>
</tr>
<tr>
<td class="contentEntry1">{l_thumb_size}<br /><small>{l_thumb_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[thumb_size_x]' value='{thumb_size_x}' size="6" /> x <input type="text" name='save_con[thumb_size_y]' value='{thumb_size_y}' size="6" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_thumb_quality}<br /><small>{l_thumb_quality_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[thumb_quality]' value='{c_thumb_quality}' size="6" /></td>
</tr>
<tr><td colspan="2" class="contentHead">&nbsp;</td></tr>
<tr>
<td class="contentEntry1">{l_shadow_mode}<br /><small>{l_shadow_mode_desc}</small></td>
<td class="contentEntry2" valign="middle">{shadow_mode}</td>
</tr>
<tr>
<td class="contentEntry1">{l_shadow_place}<br /><small>{l_shadow_place_desc}</small></td>
<td class="contentEntry2" valign="middle">{shadow_place}</td>
</tr>
<tr><td colspan="2" class="contentHead">&nbsp;</td></tr>
<tr>
<td class="contentEntry1">{l_stamp_mode}<br /><small>{l_stamp_mode_desc}</small></td>
<td class="contentEntry2" valign="middle">{stamp_mode}</td>
</tr>
<tr>
<td class="contentEntry1">{l_stamp_place}<br /><small>{l_stamp_place_desc}</small></td>
<td class="contentEntry2" valign="middle">{stamp_place}</td>
</tr>
<tr>
<td class="contentEntry1">{l_wm_image}<br /><small>{l_wm_image_desc}</small></td>
<td class="contentEntry2" valign="middle">{wm_image}</td>
</tr>
<tr>
<td class="contentEntry1">{l_wm_image_transition}<br /><small>{l_wm_image_transition_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[wm_image_transition]' value='{c_wm_image_transition}' size="6" /></td>
</tr>
<!-- END: IMAGE transform control -->
</table>
</div>

<!-- ########## FILES         ########## -->
<div id="files" style="display: none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_files}</td>
</tr>
<tr>
<td class="contentEntry1">{l_files_url}<br /><small>{l_example} http://server.com/uploads/files</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[files_url]' value='{c_files_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_files_dir}<br /><small>{l_example} /home/servercom/public_html/uploads/files/</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[files_dir]' value='{c_files_dir}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_attach_url}<br /><small>{l_example} http://server.com/uploads/dsn</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[attach_url]' value='{c_attach_url}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_attach_dir}<br /><small>{l_example} /home/servercom/public_html/uploads/dsn/</small></td>
<td class="contentEntry2" valign="middle"><input class="folder" type="text" name='save_con[attach_dir]' value='{c_attach_dir}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_files_ext}<br /><small>{l_files_ext_desc}</small></td>
<td class="contentEntry2" valign="middle"><input class="important" type="text" name='save_con[files_ext]' value='{c_files_ext}' size="40" /></td>
</tr>
<tr>
<td class="contentEntry1">{l_files_max_size}<br /><small>{l_files_max_size_desc}</small></td>
<td class="contentEntry2" valign="middle"><input type="text" name='save_con[files_max_size]' value='{c_files_max_size}' style="width: 40px" /></td>
</tr>
</table>
</div>

<!-- ########## AUTH          ########## -->
<div id="auth" style="display: none;">
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
 <tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_auth}</td>
 </tr>
 <tr>
 <td class="contentEntry1">{l_remember}<br /><small>{l_remember_desc}</small></td>
 <td class="contentEntry2" valign="middle">{remember}</td>
 </tr>
 <tr>
 <td class="contentEntry1">{l_auth_module}<br /><small>{l_auth_module_desc}</small></td>
 <td class="contentEntry2" valign="middle">{auth_module}</td>
 </tr>
 <tr>
 <td class="contentEntry1">{l_auth_db}<br /><small>{l_auth_db_desc}</small></td>
 <td class="contentEntry2" valign="middle">{auth_db}</td>
 </tr>
</table>
</div>

<!-- ########## MULTI         ########## -->
<div id="multi" style="display: none;">
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
 <tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_multi_info}</td>
 </tr>
 <tr>
 <td width="50%" class="contentEntry1" valign=top>{l_mydomains}<br /><small>{l_mydomains_desc}</small></td>
 <td width="50%" class="contentEntry2" valign="middle"><textarea cols=45 rows=3 name="save_con[mydomains]">{mydomains}</textarea></td>
 </tr>
 <tr><td colspan="2">&nbsp;</td></tr>
 <tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_multisite}</td>
 </tr>
 <tr><td colspan=2>
  <table class="contentNav" width="100%">
   <tr><td><b>{l_status}</b></td><td><b>{l_title}</b></td><td><b>{l_domains}</b></td><td><b>{l_flags}</b></td></tr>
   {multilist}
  </table>
 </td>
 </tr>
</table>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="save" />
<input type="hidden" name="save" value="" />
<input type="submit" value="{l_save}" class="button" />
</td>
</tr>
</table>
<script type="text/javascript">ChangeOption('{defaultSection}');</script>
</td>
</tr>
</table>
</form>