<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 mt-5">
			<form id="form" action="" method="post" class="form-horizontal">
				<input id="action" type="hidden" name="action" value="config">
				<input id="stage" type="hidden" name="stage" value="2">
				{hinput}

				<div class="card">
					<div class="card-header">
						<div class="steps">
							<div class="progress">
								<div class="progress-line" data-now-value="42.86" data-number-of-steps="7" style="width: 42.86%;"></div>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-play-circle"></i></div>
								<p>{l_header.menu.begin}</p>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-database"></i></div>
								<p>{l_header.menu.db}</p>
							</div>
							<div class="step active">
								<div class="step-icon"><i class="fa fa-server"></i></div>
								<p>{l_header.menu.perm}</p>
							</div>
							<div class="step">
								<div class="step-icon"><i class="fa fa-puzzle-piece"></i></div>
								<p>{l_header.menu.plugins}</p>
							</div>
							<div class="step">
								<div class="step-icon"><i class="fa fa-paint-brush"></i></div>
								<p>{l_header.menu.template}</p>
							</div>
							<div class="step">
								<div class="step-icon"><i class="fa fa-cogs"></i></div>
								<p>{l_header.menu.common}</p>
							</div>
							<div class="step">
								<div class="step-icon"><i class="fa fa-check"></i></div>
								<p>{l_header.menu.install}</p>
							</div>
						</div>
					</div>

					<div class="card-body">
						<p>{error_message}</p>
						<fieldset>
							<table>
								<tr>
									<td valign="top" width="450">
										<div class="block">
											<div class="head">{l_perm.minreq}</div>
											<div class="data">
												<table class="table table-sm">
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
											<div class="head">{l_perm.php}</div>
											<div class="data">
												<table class="table table-sm">
													<thead>
														<tr>
															<td>{l_perm.php.param}</td>
															<td>{l_perm.php.recommended}</td>
															<td>{l_perm.php.installed}</td>
														</tr>
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
										<div class="block">
											<div class="head">{l_perm.files}</div>
											<div class="data">
												<table class="table table-sm">
													<thead>
														<tr>
															<td>{l_perm.file}</td>
															<td>CHMOD</td>
															<td>{l_perm.status}</td>
														</tr>
													</thead>
													{chmod}
												</table>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</fieldset>
					</div>

					<div class="card-footer text-right">
						<button type="button" class="btn btn-outline-dark" onclick="stage.value='0'; form.submit();">&laquo; {l_button.back}</button>
						[error_button]
						<button type="button" class="btn btn-outline-danger" onclick="stage.value='1'; form.submit();">Повторить проверку</button>
						[/error_button]
						<button type="submit" class="btn btn-outline-warning">{l_button.next} &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
