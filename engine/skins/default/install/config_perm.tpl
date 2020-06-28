<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form name="db" id="db" action="" method="post" class="f1 form-horizontal">
				<input type="hidden" name="action" id="action" value="config">
				<input type="hidden" name="stage" id="stage" value="2">
				{hinput}

				<div class="f1-steps">
					<div class="f1-progress">
						<div class="f1-progress-line" data-now-value="42.86" data-number-of-steps="7" style="width: 42.86%;"></div>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-user"></i></div>
						<p>{l_header.menu.begin}</p>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-key"></i></div>
						<p>{l_header.menu.db}</p>
					</div>
					<div class="f1-step active">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.perm}</p>
					</div>
					<div class="f1-step">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.plugins}</p>
					</div>
					<div class="f1-step">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.template}</p>
					</div>
					<div class="f1-step">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.common}</p>
					</div>
					<div class="f1-step">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.install}</p>
					</div>
				</div>

				<p>{error_message}</p>
				<fieldset>
					<h4></h4>
					<table>
						<tr>
							<td valign="top" width="450">
								<div class="block">
									<div class="head">{l_perm.minreq}</div>
									<div class="data">
										<table class="table table-condensed">
											<tr><td>{l_perm.minreq.php}</td><td>{php_version}</td></tr>
											<tr><td>{l_perm.minreq.mysql}</td><td>{sql_version}</td></tr>
											<tr><td>{l_perm.minreq.zlib}</td><td>{gzip}</td></tr>
											<tr><td>{l_perm.minreq.pdo}</td><td>{pdo}</td></tr>
											<tr><td>{l_perm.minreq.mb}</td><td>{mb}</td></tr>
											<tr><td>{l_perm.minreq.gd}</td><td>{gdlib}</td></tr>
										</table>
									</div>
									<div class="head">{l_perm.php}</div>
									<div class="data">
										<table class="table table-condensed">
										<thead><tr><td>{l_perm.php.param}</td><td>{l_perm.php.recommended}</td><td>{l_perm.php.installed}</td></tr></thead>
										<tr><td>Register Globals</td><td>{l_perm.php.off}</td><td>{flag:register_globals}</td></tr>
										<tr><td>Magic Quotes GPC</td><td>{l_perm.php.off}</td><td>{flag:magic_quotes_gpc}</td></tr>
										<tr><td>Magic Quotes Runtime</td><td>{l_perm.php.off}</td><td>{flag:magic_quotes_runtime}</td></tr>
										<tr><td>Magic Quotes Sybase</td><td>{l_perm.php.off}</td><td>{flag:magic_quotes_sybase}</td></tr>
										</table>
									</div>
								</div>
							</td>

							<td width="10">&nbsp;</td>

							<td valign="top" width="500">
								<div class="block">
									<div class="head">{l_perm.files}</div>
									<div class="data">
										<table class="table table-condensed">
										<thead><tr><td>{l_perm.file}</td><td>CHMOD</td><td>{l_perm.status}</td></tr></thead>
										{chmod}
										</table>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</fieldset>
				<div class="f1-buttons">
					<button type="button" class="btn btn-previous" onclick="document.getElementById('stage').value=''; form.submit();">&laquo; {l_button.back}</button>
					[error_button]
					<button type="button" class="btn btn-danger" onclick="document.getElementById('stage').value='1'; form.submit();">Повторить проверку</button>
					[/error_button]
					<button type="submit" class="btn btn-next">{l_button.next} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div>
