<div class="container">
	<div class="row">
		<div class="col-sm-10 offset-sm-1 mt-5">
			<form id="form" action="" method="post" class="form-horizontal">
				<input id="action" type="hidden" name="action" value="config">
				<input id="stage" type="hidden" name="stage" value="3">
				{hinput}

				<div class="card">
					<div class="card-header">
						<div class="steps">
							<div class="progress">
								<div class="progress-line" data-now-value="57.14" data-number-of-steps="7" style="width: 57.14%;"></div>
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
							<div class="step active">
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
						<p>{l_plugins.textblock}</p>
						<fieldset>
							<table class="table table-sm">
								<thead class="thead-dark">
									<tr>
										<th>{l_plugins.activate}</th>
										<th>ID</th>
										<th>{l_plugins.title}</th>
										<th>{l_plugins.description}</th>
									</tr>
								</thead>
								<tbody>
									{plugins}
								</tbody>
							</table>
						</fieldset>
					</div>

					<div class="card-footer text-right">
						<button type="button" class="btn btn-outline-dark" onclick="stage.value='1'; form.submit();">&laquo; {l_button.back}</button>
						<button type="submit" class="btn btn-outline-warning">{l_button.next} &raquo;</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
