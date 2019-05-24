<div class="body">
	<form action="" method="post" name="db" id="db">
		<input type="hidden" name="action" value="config" id="action"/>
		<input type="hidden" name="stage" value="2" id="stage"/>
		{hinput}


		<table border="0">
			<tr>
				<td valign="top" width="450">
					<div class="permBlock">
						<div class="permHead">{l_perm.minreq}</div>
						<div class="permData">
							<table width="100%" cellspacing="0" cellpadding="1">
								<tr>
									<td>{l_perm.minreq.php}</td>
									<td>{php_version}</td>
								</tr>
								<tr>
									<td>{l_perm.minreq.mysql}</td>
									<td>{sql_version}</td>
								</tr>
								<tr>
									<td>{l_perm.minreq.zlib}</td>
									<td>{gzip}</td>
								</tr>
								<tr>
									<td>{l_perm.minreq.pdo}</td>
									<td>{pdo}</td>
								</tr>
								<tr>
									<td>{l_perm.minreq.mb}</td>
									<td>{mb}</td>
								</tr>
								<tr>
									<td>{l_perm.minreq.gd}</td>
									<td>{gdlib}</td>
								</tr>
							</table>
						</div>
					</div>

					<br/>
					<div class="permBlock">
						<div class="permHead">{l_perm.php}</div>
						<div class="permData">
							<table width="100%">
								<thead>
								<tr>
									<td>{l_perm.php.param}</td>
									<td>{l_perm.php.recommended}</td>
									<td>{l_perm.php.installed}</td>
								</thead>
								<tr>
									<td>Register Globals</td>
									<td>{l_perm.php.off}</td>
									<td>{flag:register_globals}</td>
								</tr>
								<tr>
									<td>Magic Quotes GPC</td>
									<td>{l_perm.php.off}</td>
									<td>{flag:magic_quotes_gpc}</td>
								</tr>
								<tr>
									<td>Magic Quotes Runtime</td>
									<td>{l_perm.php.off}</td>
									<td>{flag:magic_quotes_runtime}</td>
								</tr>
								<tr>
									<td>Magic Quotes Sybase</td>
									<td>{l_perm.php.off}</td>
									<td>{flag:magic_quotes_sybase}</td>
								</tr>
							</table>
						</div>
					</div>

				</td>
				<td width="10">&nbsp;</td>
				<td valign="top" width="500">
					<div class="permBlock">
						<div class="permHead">{l_perm.files}</div>
						<div class="permData">
							<table width="100%">
								<thead>
								<tr>
									<td>{l_perm.file}</td>
									<td>CHMOD</td>
									<td>{l_perm.status}</td>
								</thead>
								{chmod}
							</table>
						</div>
					</div>
				</td>
				<td></td>
			</tr>
		</table>
		<br/>
		{error_message}
		<br/>
		<table width="100%">
			<tr>
				<td width="33%">
					<input type="button" value="&laquo;&laquo; {l_button.back}" onclick="document.getElementById('stage').value='0'; form.submit();" class="filterbutton"/>
				</td>
				<td align="center">
					[error_button]<input style="background-color: red; color: white; font-weight: bold;" type="button" value="Повторить проверку" onclick="document.getElementById('stage').value='1'; form.submit();" class="filterbutton"/>[/error_button]
				</td>
				<td width="33%" style="text-align: right;">
					<input type="submit" value="{l_button.next} &raquo;&raquo;" class="filterbutton"/></td>
			</tr>
		</table>
	</form>
</div>