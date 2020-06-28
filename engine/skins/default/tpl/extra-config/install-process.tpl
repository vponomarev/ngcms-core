<form method="get" action="{php_self}?mod=extras">
	<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="100%" colspan="2" class="contentHead">
				<img src="{skins_url}/images/nav.gif" hspace="8" alt=""/><a href="admin.php?mod=extras">{l_extras}</a>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt=""/>{mode_text}:
				{plugin}
			</td>
		</tr>
		{entries}
		<tr align="center">
			<td width="100%" colspan="2" class="contentEdit" valign="top">
				<input type=hidden name=mod value=extras>
				<input type="submit" value="{msg}" class="button"/>
			</td>
		</tr>
	</table>
</form>