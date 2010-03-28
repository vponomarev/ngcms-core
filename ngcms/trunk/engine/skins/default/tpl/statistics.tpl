<style>
#modalmsgDialog { position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: none;}
#modalmsgWindow { margin: 5px; padding: 5px; border: 1px solid #CCCCCC; background-color: #F0F0F0; width: 400px; position: absolute; left: 40%; top: 40%; }
#modalmsgWindowText { background-color: #FFFFFF; }
#modalmsgWindowButton { background-color: #FFFFFF; text-align: center; padding: 5px; }
</style>
<script>
function showModal(text) {
 document.getElementById('modalmsgDialog').style.display='block';
 document.getElementById('modalmsgWindowText').innerHTML = text;
}
function _modal_close() {
 document.getElementById('modalmsgDialog').style.display='none';
}
</script>
<div id="modalmsgDialog" onclick="_modal_close();"><span id="modalmsgWindow"><div id="modalmsgWindowText"></div><div id="modalmsgWindowButton"><input type="button" value="OK"/></div></span></div>

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_server}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_os}</td>
<td width="50%" class="contentEntry2">{php_os}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_php_version}</td>
<td width="50%" class="contentEntry2">{php_version}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_mysql_version}</td>
<td width="50%" class="contentEntry2">{mysql_version}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_gd_version}</td>
<td width="50%" class="contentEntry2">{gd_version}</td>
</tr>
</table>
</td>
<td width="50%" style="padding-left:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />Next Generation CMS</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_current_version}</td>
<td width="50%" class="contentEntry2"><span style="font-weight: bold; color: #6cb7ef;">{currentVersion}</span></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_last_version}</td>
<td width="50%" class="contentEntry2"><script type="text/javascript" language="JavaScript" src="http://ngcms.ru/sync/version.php?ver={currentVersion}"></script></td>
</tr>
</table>
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td width="50%"  style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="4" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_size}</td>
</tr>
<tr><td>{l_group}</td><td align="right">{l_amount}</td><td align="right">{l_volume}</td><td> &nbsp; {l_permissions}</td></tr>
<tr><td>{l_group_images}</td><td align="right">{image_amount}</td><td align="right">{image_size}</td><td> &nbsp; {image_perm}</td></tr>
<tr><td>{l_group_files}</td><td align="right">{file_amount}</td><td align="right">{file_size}</td><td> &nbsp; {file_perm}</td></tr>
<tr><td>{l_group_photos}</td><td align="right">{photo_amount}</td><td align="right">{photo_size}</td><td> &nbsp; {photo_perm}</td></tr>
<tr><td>{l_group_avatars}</td><td align="right">{avatar_amount}</td><td align="right">{avatar_size}</td><td> &nbsp; {avatar_perm}</td></tr>
<tr><td>{l_group_backup}</td><td align="right">{backup_amount}</td><td align="right">{backup_size}</td><td> &nbsp; {backup_perm}</td></tr>
</table>

<br/><br/>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_size}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_allowed_size}</td>
<td width="50%" class="contentEntry2">{allowed_size}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_mysql_size}</td>
<td width="50%" class="contentEntry2">{mysql_size}</td>
</tr>
</table>
</td>

<td width="50%" style="padding-left:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_system}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_cats}</td>
<td width="50%" class="contentEntry2">{categories}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_news}</td>
<td width="50%" class="contentEntry2">{news}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_news_unapp}</td>
<td width="50%" class="contentEntry2">{news_unapp}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_comments}</td>
<td width="50%" class="contentEntry2">{comments}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_users}</td>
<td width="50%" class="contentEntry2">{users}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_users_unact}</td>
<td width="50%" class="contentEntry2">{users_unact}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_images}</td>
<td width="50%" class="contentEntry2">{images}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_all_files}</td>
<td width="50%" class="contentEntry2">{files}</td>
</tr>
</table>
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr>
<td width="50%" style="padding-right:10px;" valign="top">
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr><td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_note}</td></tr>
  <tr>
   <td width="50%" colspan="2" class="contentEntry1">
    <form method="post" action="{php_self}?mod=statistics">
     <input type="hidden" name="action" value="save" />
     <textarea name="note" rows="6" cols="70" style="border: 1px solid #ccc; background-color: lightyellow;">{admin_note}</textarea><br />
     <input type="submit" class="button" value="{l_save_note}" />
    </form>
   </td>
  </tr>
 </table>
</td>
<td width="50%" style="padding-left:10px;" valign="top">
[conf_error]
<!-- Configuration errors -->
 <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr><td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" /><font color="red">{l_pconfig.error}</font></td></tr>
  <tr><td>
<table width="100%">
<thead><tr><td>{l_perror.parameter}</td><td>{l_perror.shouldbe}</td><td>{l_perror.set}</td></thead>
<tr><td>Register Globals</td><td>���������</td><td>{flag:register_globals}</td></tr>
<tr><td>Magic Quotes GPC</td><td>���������</td><td>{flag:magic_quotes_gpc}</td></tr>
<tr><td>Magic Quotes Runtime</td><td>���������</td><td>{flag:magic_quotes_runtime}</td></tr>
<tr><td>Magic Quotes Sybase</td><td>���������</td><td>{flag:magic_quotes_sybase}</td></tr>
</table>
<br/>
&nbsp;<a style="cursor: pointer; color: red;" onclick="document.getElementById('perror_resolve').style.display='block';">{l_perror.howto}</a><br/>
<div id="perror_resolve" style="display: none;">
{l_perror.descr}
</div>
  </td></tr>
 </table>
[/conf_error]
</td>
</tr>
</table>