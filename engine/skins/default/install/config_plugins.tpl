<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form name="db" id="db" action="" method="post" class="f1 form-horizontal">
				<input type="hidden" name="action" id="action" value="config">
				<input type="hidden" name="stage" id="stage" value="3">
				{hinput}
				
				<div class="f1-steps">
					<div class="f1-progress">
						<div class="f1-progress-line" data-now-value="57.14" data-number-of-steps="7" style="width: 57.14%;"></div>
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
					<div class="f1-step active">
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
				
				<p>{l_plugins.textblock}</p>
				<fieldset>
					<h4></h4>
					<table class="table table-condensed plugTable">
						<thead>
							<tr>
								<td><input type="checkbox" class="select-all"></td>
								<td>ID</td>
								<td>{l_plugins.title}</td>
								<td>{l_plugins.description}</td>
							</tr>
						</thead>
						{plugins}
					</table>
				</fieldset>
				<div class="f1-buttons">
					<button type="button" class="btn btn-previous" onclick="document.getElementById('stage').value='1'; document.getElementById('db').submit();">&laquo; {l_button.back}</button>
					<button type="submit" class="btn btn-next">{l_button.next} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	/* Select/unselect all */
	$('table .select-all').click(function() {
		$(this).parents('table').find('input:checkbox:not([disabled])').prop('checked', $(this).prop('checked'));
	});
</script>