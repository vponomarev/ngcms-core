<div class="body">
	<form action="" method="post" name="form" id="form">
		<input type="hidden" name="action" value="config" id="action"/>
		<input type="hidden" name="stage" value="1"/>
		{hinput}

		<p>
			{l_db.textblock}</p>

		{error_message}
		<table width="100%" align="center" class="content" cellspacing="0" cellpadding="0">
			<tr>
				<td width="70%" class="contentEntry1">{l_db.type}<span class="req">*</span>: <br/>
					<small>{l_db.type#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<select name="reg_dbtype" style="width: 267px;">
						[mysql]<option value="MySQL"{reg_dbtype_MySQL}>MySQL</option>[/mysql]
						[/mysqli]<option value="MySQLi"{reg_dbtype_MySQLi}>MySQLi</option>[/mysqli]
						[/pdo]<option value="PDO"{reg_dbtype_PDO}>PDO</option>[/pdo]
					</select>
				</td>
			</tr>
			<tr>
				<td width="70%" class="contentEntry1">{l_db.server}<span class="req">*</span>: {err:reg_dbhost}<br/>
					<small>{l_db.server#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbhost" value="{reg_dbhost}"/></td>
			</tr>
			<tr>
				<td width="70%" class="contentEntry1">{l_db.login}<span class="req">*</span>: {err:reg_dbuser}<br/>
					<small>{l_db.login#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbuser" value="{reg_dbuser}"/></td>
			</tr>
			<tr>
				<td width="70%" class="contentEntry1">{l_db.password}:<br/>
					<small>{l_db.password#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbpass" value="{reg_dbpass}"/></td>
			</tr>
			<tr class="odd">
				<td width="70%" class="contentEntry1">{l_db.name}:<span class="req">*</span>: {err:reg_dbname}<br/>
					<small>{l_db.name#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbname" value="{reg_dbname}"/></td>
			</tr>
			<tr class="even">
				<td width="70%" class="contentEntry1">{l_db.dbprefix}:<br/>
					<small>{l_db.dbprefix#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbprefix" value="{reg_dbprefix}"/></td>
			</tr>
			<tr class="odd">
				<td width="70%" class="contentEntry1">{l_db.autocreate}<br/>
					<small>{l_db.autocreate#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type=checkbox name="reg_autocreate" value="1" {reg_autocreate}/></td>
			</tr>
			<tr class="even">
				<td width="70%" class="contentEntry1">{l_db.dbadminuser}: {err:reg_dbadminuser}<br/>
					<small>{l_db.dbadminuser}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbadminuser" value="{reg_dbadminuser}"/></td>
			</tr>
			<tr class="odd">
				<td width="70%" class="contentEntry1">{l_db.dbadminpass}:<br/>
					<small>{l_db.dbadminpass#desc}</small>
				</td>
				<td width="30%" class="contentEntry2">
					<input type="text" size="40" name="reg_dbadminpass" value="{reg_dbadminpass}"/></td>
			</tr>
		</table>
		<br/><br/>
		<table width="100%">
			<tr>
				<td>
					<input type="button" value="&laquo;&laquo; {l_button.back}" onclick="document.getElementById('action').value=''; form.submit();" class="filterbutton"/>
				</td>
				<td style="text-align: right;">
					<input type="submit" value="{l_button.next} &raquo;&raquo;" class="filterbutton"/></td>
			</tr>
		</table>
	</form>
</div>