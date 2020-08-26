<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 mt-5">
			<form id="form" action="" method="post" class="form-horizontal">
				<input id="action" type="hidden" name="action" value="install">
				<input id="stage" type="hidden" name="stage" value="1">
				{hinput}

				<div class="card">
					<div class="card-header">
						<div class="steps">
							<div class="progress">
								<div class="progress-line" data-now-value="85.71" data-number-of-steps="7" style="width: 85.71%;"></div>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-play-circle"></i></div>
								<p>{l_header.menu.begin}</p>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-database"></i></div>
								<p>{l_header.menu.db}</p>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-server"></i></div>
								<p>{l_header.menu.perm}</p>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-puzzle-piece"></i></div>
								<p>{l_header.menu.plugins}</p>
							</div>
							<div class="step activated">
								<div class="step-icon"><i class="fa fa-paint-brush"></i></div>
								<p>{l_header.menu.template}</p>
							</div>
							<div class="step active">
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
						<p>{l_common.textblock}</p>
						<fieldset>
							<legend>{l_common.params}</legend>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_common.parameters.addr}</label>
								<div class="col-sm-9">
									<input type="text" name="home_url" value="{home_url}" class="form-control">
								</div>
							</div>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_common.parameters.title}</label>
								<div class="col-sm-9">
									<input type="text" name="home_title" value="{home_title}" class="form-control">
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>{l_common.admin}</legend>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_common.admin.login}</label>
								<div class="col-sm-9">
									<input type="text" name="admin_login" id="admin_login" value="{admin_login}" class="form-control">
									<div id="loginErrorInfo" style="display: none;">{l_common.admin.login.required}</div>
								</div>
							</div>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_common.admin.pass}</label>
								<div class="col-sm-9">
									<input type="password" name="admin_password" id="admin_password" value="{admin_password}" class="form-control">
									<div id="passwordErrorInfo" style="display: none;">{l_common.admin.pass.required}</div>
								</div>
							</div>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label">{l_common.admin.email}</label>
								<div class="col-sm-9">
									<input type="text" name="admin_email" value="{admin_email}" class="form-control">
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>{l_common.auto}</legend>
							<div class="form-row mb-3">
								<label class="col-sm-3 col-form-label"></label>
								<div class="col-sm-9">
									<label><input type="checkbox" value="1" disabled="disabled" name="autodata" {autodata_checked} /> {l_common.auto.turn}</label>
									<span class="help-block">{l_common.auto.desc}</span>
								</div>
							</div>
						</fieldset>
					</div>

					<div class="card-footer text-right">
						<button type="button" class="btn btn-outline-dark" onclick="action.value='config'; stage.value='3'; form.submit();">&laquo; {l_button.back}</button>
						<button type="submit" class="btn btn-outline-warning" onclick="return validateAdminInfo();">{l_button.next} &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	function validateAdminInfo() {
		if ($("#admin_login").val() == "") {
			$("#admin_login").focus();
			$("#loginErrorInfo").show();
			return false;
		}
		if ($("#admin_password").val() == "") {
			$("#admin_password").focus();
			$("#passwordErrorInfo").show();
			return false;
		}
		return true;
	}
</script>
