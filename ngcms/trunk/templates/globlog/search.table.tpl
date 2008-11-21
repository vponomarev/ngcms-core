<form method="GET" action="/">
<input type="hidden" name="action" value="search" />
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
						<td width="30%" align="center">{l_author} 
						<input class="mw_search_f" type="text" name="author" value="{author}" size="20" maxlength="80" /></td>
						<td width="30%" align="center">{l_category} {catlist}</font></td>
						<td width="30%" align="center">{l_date} <select class="mw_search_f" name="postdate"><option value=""></option>{datelist}</select><</td>
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
 <input class="button" type="submit" value="{l_search}" /></td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">{l_found} {padeg1} <b>{count_all}</b> {padeg2}:</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
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

