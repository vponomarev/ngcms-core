<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form action="" method="post" class="f1">
				<input type="hidden" name="action" value="config">
				<input type="hidden" name="stage" value="0">

				<div class="f1-steps">
					<div class="f1-progress">
						<div class="f1-progress-line" data-now-value="14.28" data-number-of-steps="7" style="width: 14.28%;"></div>
					</div>
					<div class="f1-step active">
						<div class="f1-step-icon"><i class="fa fa-user"></i></div>
						<p>{l_header.menu.begin}</p>
					</div>
					<div class="f1-step">
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
				
				<div class="text-center">
					<div class="btn-group">
						<button type="button" class="btn btn-next dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:158px">Language <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="install.php?language=english">English</a></li>
							<li><a href="install.php">Русский</a></li>
						</ul>
					</div>
				</div><br/>
				<p>{l_welcome.textblock2} {l_welcome.textblock1}</p>
				<fieldset>
					<h4>{l_welcome.licence}</h4>
					<div class="form-group">
						<div class="form-control" style="height: 288px; padding: 5px; overflow: auto;">
							{license}
						</div>
					</div>
					<div class="form-group">
						<label for="agree"><input type="checkbox" name="agree" id="agree" value="1" {ad}/> {l_welcome.licence.accept}</label>
					</div>
				</fieldset>
				<div class="f1-buttons">
					<button type="submit" class="btn btn-next">{l_welcome.continue} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div>
