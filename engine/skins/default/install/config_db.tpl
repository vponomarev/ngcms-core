<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form name="form" id="form" action="" method="post" class="f1 form-horizontal">
				<input type="hidden" name="action" id="action" value="config">
				<input type="hidden" name="stage" id="stage" value="1">
				{hinput}
				
				<div class="f1-steps">
					<div class="f1-progress">
						<div class="f1-progress-line" data-now-value="28.57" data-number-of-steps="7" style="width: 28.57%;"></div>
					</div>
					<div class="f1-step activated">
						<div class="f1-step-icon"><i class="fa fa-user"></i></div>
						<p>{l_header.menu.begin}</p>
					</div>
					<div class="f1-step active">
						<div class="f1-step-icon"><i class="fa fa-key"></i></div>
						<p>{l_header.menu.db}</p>
					</div>
					<div class="f1-step">
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
				
				<p>{l_db.textblock}</p>
				<p>{error_message}</p>
				<fieldset>
					<h4></h4>
					<div class="form-group has-feedback">
						<label class="col-sm-3 control-label">{l_db.type}</label>
						<div class="col-sm-9">					
					        <select name="reg_dbtype" class="form-control">
						     [mysql]<option value="MySQL"{reg_dbtype_MySQL}>MySQL</option>[/mysql]
						     [/mysqli]<option value="MySQLi"{reg_dbtype_MySQLi}>MySQLi</option>[/mysqli]
						     [/pdo]<option value="PDO"{reg_dbtype_PDO}>PDO</option>[/pdo]
					        </select>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-3 control-label">{l_db.server}</label>
						<div class="col-sm-9">
							<input type="text" name="reg_dbhost" value="{reg_dbhost}" class="form-control">
							<span class="help-block">{err:reg_dbhost} {l_db.server#desc}</span>
							<span class="req form-control-feedback">*</span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-3 control-label">{l_db.login}</label>
						<div class="col-sm-9">
							<input type="text" name="reg_dbuser" value="{reg_dbuser}" class="form-control">
							<span class="help-block">{err:reg_dbuser} {l_db.login#desc}</span>
							<span class="req form-control-feedback">*</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_db.password}</label>
						<div class="col-sm-9">
							<input type="text" name="reg_dbpass" value="{reg_dbpass}" class="form-control">
							<span class="help-block">{l_db.password#desc}</span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-3 control-label">{l_db.name}</label>
						<div class="col-sm-9">
							<input type="text" name="reg_dbname" value="{reg_dbname}" class="form-control">
							<span class="help-block">{err:reg_dbname} {l_db.name#desc}</span>
							<span class="req form-control-feedback">*</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">{l_db.dbprefix}</label>
						<div class="col-sm-9">
							<input type="text" name="reg_dbprefix" value="{reg_dbprefix}" class="form-control">
							<span class="help-block">{l_db.dbprefix#desc}</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<label data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="text-danger"><input type="checkbox" name="reg_autocreate" value="1" {reg_autocreate}/> {l_db.autocreate}</label>
							<span class="help-block">{l_db.autocreate#desc}</span>
						</div>
					</div>
					<div class="collapse" id="collapseExample">
						<div class="form-group">
							<label class="col-sm-3 control-label">{l_db.dbadminuser}</label>
							<div class="col-sm-9">
								<input type="text" name="reg_dbadminuser" value="{reg_dbadminuser}" class="form-control">
								<span class="help-block">{err:reg_dbadminuser} {l_db.dbadminpass#desc}</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{l_db.dbadminpass}</label>
							<div class="col-sm-9">
								<input type="text" name="reg_dbadminpass" value="{reg_dbadminpass}" class="form-control">
								<span class="help-block">{err:reg_dbadminpass} {l_db.dbadminpass#desc}</span>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="f1-buttons">
					<button type="button" class="btn btn-previous" onclick="document.getElementById('action').value=''; form.submit();">&laquo; {l_button.back}</button>
					<button type="submit" class="btn btn-next">{l_button.next} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div>
