<form name="form" method="post" action="{php_self}?mod=dbo">
<input type="hidden" name="massbackup" value="" />
<input type="hidden" name="cat_recount" value="" />
<input type="hidden" name="masscheck" value="" />
<input type="hidden" name="massrepair" value="" />
<input type="hidden" name="massoptimize" value="" />
<input type="hidden" name="massdelete" value="" />
<table class="content" border="0" cellspacing="0" cellpadding="0" align="left">
<tr align="left">
<td width="15%" class="contentHead">{l_table}</td>
<td width="15%" class="contentHead">{l_rows}</td>
<td width="15%" class="contentHead">{l_data}</td>
<td width="15%" class="contentHead">{l_overhead}</td>
<td width="20%" class="contentHead" colspan="3">{l_action}</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form, 'tables')" /></td>
</tr>
{entries}
<tr>
<td colspan="8">&nbsp;</td>
</tr>
<tr align="right">
<td width="100%" colspan="8" class="contentEdit">
<input class="button" type="submit" value="{l_cat_recount}" onclick="document.forms['form'].cat_recount.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_check}" onclick="document.forms['form'].masscheck.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_repair}" onclick="document.forms['form'].massrepair.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_optimize}" onclick="document.forms['form'].massoptimize.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_delete}" onclick="document.forms['form'].massdelete.value = 'true';" />
</td>
</tr>
<tr align="right">
<td width="100%" colspan="8">
<input type="checkbox" id="gz" name="gzencode" value="1" class="check" /><label for="gz">{l_gzencode}</label><br />
<input type="checkbox" id="email" name="email_send" value="1" class="check" /><label for="email">{l_email_send}</label>
</td>
<tr align="right">
<td width="100%" colspan="8" class="contentEdit">
<input class="button" type="submit" value="{l_backup}" onclick="document.forms['form'].massbackup.value = 'true';" />
</td>
</tr>
<tr>
<td colspan="8">&nbsp;</td>
</tr>
</table>
</form>
<form name="backups" method="post" action="{php_self}?mod=dbo">
<input type="hidden" name="delbackup" value="" />
<input type="hidden" name="massdelbackup" value="" />
<input type="hidden" name="restore" value="" />
<table class="content" border="0" cellspacing="0" cellpadding="0" align="left">
<tr align="right">
<td width="100%" colspan="8" class="contentEdit">
{restore} <input class="button" type="submit" value="{l_restore}" onclick="document.forms['backups'].restore.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_delete}" onclick="document.forms['backups'].delbackup.value = 'true';" />&nbsp;
<input class="button" type="submit" value="{l_deleteall}" onclick="document.forms['backups'].massdelbackup.value = 'true';" />
</td>
</tr>
</table>
</form>