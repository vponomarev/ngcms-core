<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 mt-5">
			<form id="form" action="" method="post" class="form-horizontal">
				<input id="action" type="hidden" name="action" value="config">
				<input id="stage" type="hidden" name="stage" value="1">
				{hinput}

				<div class="card">
					<div class="card-header">
						<div class="steps">
							<div class="progress">
								<div class="progress-line" data-now-value="28.57" data-number-of-steps="7" style="width: 28.57%;"></div>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-play-circle"></i></div>
								<p>{l_header.menu.begin}</p>
							</div>
							<div class="step active">
								<div class="step-icon"><i class="fa fa-database"></i></div>
								<p>{l_header.menu.db}</p>
							</div>
							<div class="step">
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
						<p>{l_db.textblock}</p>
						<p>{error_message}</p>

						<fieldset>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.type}</label>
								<div class="col-sm-9">
									<select name="reg_dbtype" class="custom-select">
										[pdo]<option value="pdo" {reg_dbtype_PDO}>PDO</option>[/pdo]
										[mysqli]<option value="mysqli" {reg_dbtype_MySQLi}>MySQLi</option>[/mysqli]
										[mysql]<option value="mysql" {reg_dbtype_MySQL}>MySQL</option>[/mysql]
									</select>
									<div class="form-text text-muted">{l_db.type#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.server} <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" name="reg_dbhost" value="{reg_dbhost}" class="form-control">
									<div class="invalid-feedback d-block">{err:reg_dbhost}</div>
									<div class="form-text text-muted">{l_db.server#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.login} <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" name="reg_dbuser" value="{reg_dbuser}" class="form-control" autocomplete="off">
									<div class="invalid-feedback d-block">{err:reg_dbuser}</div>
									<div class="form-text text-muted">{l_db.login#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.password}</label>
								<div class="col-sm-9">
									<input type="password" name="reg_dbpass" value="{reg_dbpass}" class="form-control" autocomplete="off">
									<div class="form-text text-muted">{l_db.password#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.name} <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" name="reg_dbname" value="{reg_dbname}" class="form-control">
									<div class="invalid-feedback d-block">{err:reg_dbname}</div>
									<div class="form-text text-muted">{l_db.name#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_db.dbprefix}</label>
								<div class="col-sm-9">
									<input type="text" name="reg_dbprefix" value="{reg_dbprefix}" class="form-control">
									<div class="form-text text-muted">{l_db.dbprefix#desc}</div>
								</div>
							</div>

							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label"></label>
								<div class="col-sm-9">
									<div class="form-check" data-toggle="collapse" data-target="#collapseAutocreate">
										<input id="reg_autocreate" type="checkbox" name="reg_autocreate" value="1" class="form-check-input" {reg_autocreate}>
										<label for="reg_autocreate" class="form-check-label">{l_db.autocreate}</label>
									</div>
									<div class="form-text text-muted">{l_db.autocreate#desc}</div>
								</div>
							</div>

							<div class="collapse" id="collapseAutocreate">
								<div class="form-row mb-3">
									<label class="col-sm-3 col-form-label">{l_db.dbadminuser}</label>
									<div class="col-sm-9">
										<input type="text" name="reg_dbadminuser" value="{reg_dbadminuser}" class="form-control" autocomplete="off">
										<div class="invalid-feedback d-block">{err:reg_dbadminuser}</div>
										<div class="form-text text-muted">{l_db.dbadminuser#desc}</div>
									</div>
								</div>

								<div class="form-row mb-3">
									<label class="col-sm-3 col-form-label">{l_db.dbadminpass}</label>
									<div class="col-sm-9">
										<input type="password" name="reg_dbadminpass" value="{reg_dbadminpass}" class="form-control" autocomplete="off">
										<div class="form-text text-muted">{l_db.dbadminpass#desc}</div>
									</div>
								</div>
							</div>
						</fieldset>
					</div>

					<div class="card-footer text-right">
						<button type="button" class="btn btn-outline-dark" onclick="stage.value=''; form.submit();">&laquo; {l_button.back}</button>
						<button type="submit" class="btn btn-outline-warning">{l_button.next} &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#collapseAutocreate').collapse({
		toggle: $('#reg_autocreate').prop('checked')
	});
</script>
