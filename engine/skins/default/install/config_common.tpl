<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form name="db" id="db" action="" method="post" class="f1 form-horizontal">
				<input type="hidden" name="action" id="action" value="install">
				<input type="hidden" name="stage" id="stage" value="1">
				{hinput}
				
				<div class="f1-steps">
					<div class="f1-progress">
						<div class="f1-progress-line" data-now-value="85.71" data-number-of-steps="7" style="width: 85.71%;"></div>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-user"></i></div>
						<p>{l_header.menu.begin}</p>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-key"></i></div>
						<p>{l_header.menu.db}</p>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.perm}</p>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.plugins}</p>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.template}</p>
					</div>
					<div class="f1-step active">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.common}</p>
					</div>
					<div class="f1-step">
						<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
						<p>{l_header.menu.install}</p>
					</div>
				</div>
				
				<p>{l_common.textblock}</p>
				<fieldset>
					<legend>{l_common.params}</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_common.parameters.addr}</label>
						<div class="col-sm-9">
							<input type="text" name="home_url" value="{home_url}" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_common.parameters.title}</label>
						<div class="col-sm-9">
							<input type="text" name="home_title" value="{home_title}" class="form-control">
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{l_common.admin}</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_common.admin.login}</label>
						<div class="col-sm-9">
							<input type="text" name="admin_login" value="{admin_login}" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_common.admin.pass}</label>
						<div class="col-sm-9">
							<input type="password" name="admin_password" value="{admin_password}" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_common.admin.email}</label>
						<div class="col-sm-9">
							<input type="text" name="admin_email" value="{admin_email}" class="form-control">
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend>{l_common.auto}</legend>
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<label><input type="checkbox" value="1" disabled="disabled" name="autodata"{autodata_checked}/> {l_common.auto.turn}</label>
							<span class="help-block">{l_common.auto.desc}</span>
						</div>
					</div>
				</fieldset>
				<div class="f1-buttons">
					<button type="button" class="btn btn-previous" onclick="document.getElementById('stage').value='3';document.getElementById('action').value='config'; form.submit();">&laquo; {l_button.back}</button>
					<button type="submit" class="btn btn-next">{l_button.startinstall} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div>
