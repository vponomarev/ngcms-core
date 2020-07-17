<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 pt-5">
			<form id="form" action="" method="post" class="form-horizontal">
				<input id="action" type="hidden" name="action" value="config">
				<input id="stage" type="hidden" name="stage" value="0">

				<div class="card">
					<div class="card-header">
						<div class="steps">
							<div class="progress">
								<div class="progress-line" data-now-value="14.28" data-number-of-steps="7" style="width: 14.28%;"></div>
							</div>
							<div class="step active">
								<div class="step-icon"><i class="fa fa-play-circle"></i></div>
								<p>{l_header.menu.begin}</p>
							</div>
							<div class="step">
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
						<p>{l_welcome.choose_lang}</p>

						<div class="dropdown">
							<button id="dropdownMenuButton" type="button" class="btn btn-outline-warning dropdown-toggle mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Language
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="install.php?language=english">English</a>
								<a class="dropdown-item" href="install.php">Русский</a>
							</div>
						</div>

						<p>{l_welcome.textblock2}</p>
						<p>{l_welcome.textblock1}</p>

						<fieldset>
							<p>{l_welcome.licence}</p>
							<div class="form-group">
								<div class="form-control" style="height: 288px; padding: 5px; overflow: auto;">
									{license}
								</div>
							</div>
							<div class="form-group">
								<div class="form-check">
									<input id="agree" type="checkbox" name="agree" value="1" class="form-check-input" {ad}>
									<label for="agree" class="form-check-label">{l_welcome.licence.accept}</label>
								</div>
							</div>
						</fieldset>
					</div>

					<div class="card-footer text-right">
						<button type="submit" class="btn btn-outline-warning">{l_welcome.continue} &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
