<form method="post" action="{php_self}?mod=extra-config">
	<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt=""/>{mode_text}:
				{plugin}
			</td>
		</tr>
		<tr>
			<td>
				<div class="telo">{install_text}</div>
			</td>
		</tr>
		<tr align="center">
			<td width="100%" colspan="2" class="contentEdit" valign="top">
				<input type="hidden" name="plugin" value="{plugin}"/>
				<input type="hidden" name="stype" value="{stype}"/>
				<input type="hidden" name="action" value="commit"/>
				<input type="submit" value="{mode_commit}" class="button"/>
			</td>
		</tr>
	</table>
</form>