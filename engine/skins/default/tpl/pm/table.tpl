<form name="form" method="POST" action="{php_self}?mod=pm&action=delete">
	<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="100%" style="padding-right:10px;" valign="top">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=pm">{l_pm}</a>
						</td>
					</tr>
					<tr>
						<td width=100% colspan="5">&nbsp;</td>
					</tr>
					<tr align="center" class="contHead">
						<td width="15%" class="contentHead">{l_pmdate}</td>
						<td width="40%" class="contentHead">{l_title}</td>
						<td width="25%" class="contentHead">{l_from}</td>
						<td width="15%" class="contentHead">{l_status}</td>
						<td width="5%" class="contentHead">
							<input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form)">
						</td>
					</tr>
					{entries}
					<tr>
						<td width=100% colspan="5">&nbsp;</td>
					</tr>
					<tr align="center">
						<td width="100%" colspan="5" class="contentEdit">
							<input class="button" type="submit" value="{l_delete}">
</form>
<form name="pm" method="POST" action="{php_self}?mod=pm&action=write">
	<input class="button" type="submit" value="{l_write}">
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>