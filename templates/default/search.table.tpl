<form method="get" action="{form_url}">
<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36"></td>
				<td background="{tpl_url}/images/2z_41.gif" width="100%">
				<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="0">
					<tr>
						<td width="30%" align="center"><font color="#FFFFFF">{l_search.filter.author}: <input class="mw_search_f" type="text" name="author" value="{author}" size="20" maxlength="80" /></font></td>
						<td width="30%" align="center"><font color="#FFFFFF" size="1">{l_search.filter.category}: {catlist}</font></td>
						<td width="30%" align="center"><font color="#FFFFFF">{l_search.filter.date}: <select class="mw_search_f" name="postdate"><option value=""></option>{datelist}</select></font></td>
					</tr>
				</table>
				</td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table4" cellspacing="0" cellpadding="0">
			<tr>
				<td background="{tpl_url}/images/2z_54.gif" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				<table border="0" width="100%" id="table8" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><br /><input type=text name="search" size="40" value="{search}" class="story" />
 <input class="button" type="submit" value="{l_search.submit}" /></td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
[found]{l_search.found}: <b>{count}</b>[/found]
[notfound]{l_search.notfound}[/notfound]
[error]<font color="red"><b>{l_search.error}</b></font>[/error]
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					
				</table>
				</td>
				<td background="{tpl_url}/images/2z_59.gif" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table6" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4"></td>
				<td background="{tpl_url}/images/2z_69.gif" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
{entries}

