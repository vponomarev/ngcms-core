<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 mt-5">

			<div class="card">
				<div class="card-header">
					<div class="steps">
						<div class="progress">
							<div class="progress-line"></div>
						</div>
						<div class="step">
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
					<fieldset>
						<div class="alert alert-danger" role="alert">
							{l_notagree.not_accept_licence}
						</div>
					</fieldset>
				</div>

				<div class="card-footer text-right">
					<a href="install.php" class="btn btn-outline-dark">&laquo; {l_notagree.back_to_install}</a>
				</div>
			</div>

		</div>
	</div>
</div>
