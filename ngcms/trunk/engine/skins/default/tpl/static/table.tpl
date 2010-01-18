

<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="50%">{l_per_page} <form action="{php_self}" method="GET" name="options_bar"><input type="hidden" name="mod" value="static" /><input style="text-align: center" name="per_page" value="{per_page}" type="text" size="3" /> <input type="submit" value="{l_do_show}" class="button" /></form></td>
<td width="50%">[actions]<form action="{php_self}" method="GET" name="options_bar"><input type="hidden" name="mod" value="static" /><input type="hidden" name="action" value="add" /><input type="submit" value="{l_addstatic}" class="button" />[/actions]</td>
</tr>
</table>
</form>
<br />
<form action="{php_self}?mod=static" method="post" name="static">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="45%" class="contentHead">{l_title}</td>
<td width="45%" class="contentHead">{l_url}</td>
<td width="5%" class="contentHead">&nbsp;</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(static)" /></td>
</tr>
[no-static]<tr><td colspan="6"><p>- {l_not_found} -</p></td></tr>[/no-static]
{entries}
<tr>
<td colspan="6" style="border-top: 1px solid #EBEBEB;">&nbsp;</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentEntry2">{pagesss}</td>
<td width="47%" align="right" class="contentEntry2"><div id="submit">
[actions]
<select name="subaction">
<option value="">-- {l_action} --</option>
<option value="do_mass_delete">{l_delete}</option>
<option value="do_mass_approve">{l_approve}</option>
<option value="do_mass_forbidden">{l_forbidden}</option>
</select>
<input type="submit" value="OK" class="button" />
[/actions]
</div></td>
</tr>
</table>
</form>