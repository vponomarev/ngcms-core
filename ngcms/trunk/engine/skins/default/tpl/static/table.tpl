<!-- Navigation bar -->
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=static">{l_static_title}</a></td>
</tr>
</table>

<!-- Info content -->
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="100%" align="right">{l_per_page} <form action="{php_self}" method="get" name="options_bar"><input type="hidden" name="mod" value="static" /><input style="text-align: center" name="per_page" value="{per_page}" type="text" size="3" /> <input type="submit" value="{l_do_show}" class="button" /></form> &nbsp;</td>
</tr>
</table>

<form action="{php_self}?mod=static" method="post" name="static">
<input type="hidden" name="token" value="{token}"/>
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left" class="contHead">
<td width="20">[perm.modify]<input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(static)" />[/perm.modify]</td>
<td width="50">Состояние</td>
<td width="45%">{l_title}</td>
<td>{l_list.altname}</td>
<td>{l_list.template}</td>
<td width="100">{l_list.date}</td>
</tr>
[no-static]<tr><td colspan="6"><p>- {l_not_found} -</p></td></tr>[/no-static]
{entries}
<tr>
<td colspan="6">&nbsp;</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="47%" align="left" class="contentEdit">
<div id="submit">
[perm.modify]
<select name="subaction">
<option value="">-- {l_action} --</option>
<option value="do_mass_delete">{l_delete}</option>
<option value="do_mass_approve">{l_approve}</option>
<option value="do_mass_forbidden">{l_forbidden}</option>
</select>
<input type="submit" value="OK" class="button" />
[/perm.modify]
</div></td>
<td width="50%" class="contentEdit" align="right">[perm.modify]<input type="button" value="{l_addstatic}" onclick="document.location='?mod=static&action=add'; return false;" class="button" />[/perm.modify] &nbsp;</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td colspan="2">{pagesss}</td>
</table>
</form>