<form method="GET" action="{form_url}">
<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%">
				<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="0">
					<tr>
						<td width="30%" align="center">{l_search.filter.author}: <input type="text" name="author" value="{author}" size="20" maxlength="80" /></td>
						<td width="30%" align="center">{l_search.filter.category}: {catlist}</td>
						<td width="30%" align="center">{l_search.filter.date}: <select name="postdate"><option value=""></option>{datelist}</select></td>
					</tr>
				</table>
				</td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table4" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
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
				<td width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table6" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%"></td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
{entries}

