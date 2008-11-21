<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form name="form" method="post" action="{php_self}?mod=static">
<table id="maincontent" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_title}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_alt_name}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" class="important" size="40" name="title" value="{title}" tabindex="1" /></td>
<td width="50%" class="contentEntry1"><input type="text" name="alt_name" value="{alt_name}" size="40" tabindex="2" /></td>
</tr>
<tr>
<td width=100% class="contentHead" colspan="2"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_content}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags}<br />{smilies}<br /><textarea name="content" rows="15" cols="100">{content}</textarea></td>
</tr>
[meta]
<tr>
<td width=50% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_description}</td>
<td width=50% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_keywords}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="description" value="{description}" maxlength="255" size="40" /></td>
<td width="50%" class="contentEntry1"><input type="text" name="keywords" value="{keywords}" maxlength="255" size="40" /></td>
</tr>
[/meta]
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_template}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_additional}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="template" value="{template}" maxlength="255" size="40" /></td>
<td width="50%" class="contentEntry1">
[options]
<input type="checkbox" name="approve" value="1" {ifapp} class="check" id="approve"> <label for="approve">{l_approve}</label>
<br /><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {ifraw} {disable_flag_raw}> <label for="flag_RAW">{l_flag_raw}</label>
<br /><input type="checkbox" name="flag_HTML" value="1" class="check" id="flag_HTML" {ifhtml} {disable_flag_html}> <label for="flag_HTML">{l_flag_html}</label>
[/options]
</td>
</tr>
</table>
<br />
<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="subaction" value="doedit" />
<input type="hidden" name="action" value="edit">
<input type="submit" value="{l_do_editnews}" accesskey="s" class="button" />&nbsp;
<input type="button" value="{l_delete}" onClick="confirmit('{php_self}?mod=static&subaction=do_mass_delete&selected[]={id}', '{l_sure_del}')" class="button" />
</td>
</tr>
</table>
</form>