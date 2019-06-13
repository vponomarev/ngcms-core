<div class="body">
	<form action="" method="post" name="db" id="db">
		<input type="hidden" name="action" value="install" id="action"/>
		<input type="hidden" name="stage" value="3" id="stage"/>
		{hinput}
		<p>
			{l_common.textblock}
		</p>
		<fieldset>
			<legend><b>{l_common.params}</b></legend>
			<table>
				<tr>
					<td>{l_common.parameters.addr}:</td>
					<td><input type="text" name="home_url" value="{home_url}" size="60"/></td>
				</tr>
				<tr>
					<td>{l_common.parameters.title}:</td>
					<td><input type="text" name="home_title" value="{home_title}" size="60"/></td>
				</tr>
			</table>
		</fieldset>
		<br/>
		<fieldset>
			<legend><b>{l_common.admin}</b></legend>
			<table>
				<tr>
					<td>{l_common.admin.login}:</td>
					<td><input type="text" name="admin_login" value="{admin_login}"/></td>
				</tr>
				<tr>
					<td>{l_common.admin.pass}:</td>
					<td><input type="text" name="admin_password" value="{admin_password}"/></td>
				</tr>
				<tr>
					<td>{l_common.admin.email}:</td>
					<td><input type="text" name="admin_email" value="{admin_email}"/></td>
				</tr>
			</table>
		</fieldset>
		<br/>
		<fieldset>
			<legend><b>{l_common.auto}</b></legend>
			<table>
				<tr>
					<td>{l_common.auto.turn}:</td>
					<td><input type="checkbox" value="1" disabled="disabled" name="autodata" {autodata_checked}/></td>
				</tr>
			</table>
			<small>{l_common.auto.desc}</small>
		</fieldset>

		<div style="float: left; width: 99%;">
			<br/>
			<table width="100%">
				<tr>
					<td width="33%">
						<input type="button" value="&laquo;&laquo; {l_button.back}" onclick="document.getElementById('stage').value='3';document.getElementById('action').value='config'; form.submit();" class="filterbutton"/>
					</td>
					<td></td>
					<td width="33%" style="text-align: right;">
						<input style="font-weight: bold;" type="submit" value="{l_button.startinstall}" class="filterbutton"/>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>