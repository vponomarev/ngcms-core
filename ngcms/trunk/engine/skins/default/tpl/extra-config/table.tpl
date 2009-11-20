<form method="post" action="{php_self}?mod=extra-config" name="form">
<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100%" colspan="2" class="pluginConfigHeader"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" /><a href="admin.php?mod=extras">{l_extras}</a></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_config_text}: {plugin}</td>
</tr>
{entries}
<tr align="center">
<td width="100%" colspan="2" class="contentEdit" valign="top">
<input type="hidden" name="plugin" value="{plugin}" />
<input type="hidden" name="action" value="commit" />
<input type="submit" value="{l_commit_change}" class="button" />
</td>
</tr>
</table>
</form>