<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 form-box">
			<form name="db" id="db" action="" method="post" class="f1 form-horizontal">
				<input type="hidden" name="action" id="action" value="config">
				<input type="hidden" name="stage" id="stage" value="4">
				{hinput}
				
				<p></p>
				<fieldset>
					<legend>—писок выполненных действий:</legend>
					<div class="form-group">
						<div class="col-sm-12">{actions}<!--/div>
					</div>
				</fieldset>
				<div class="f1-buttons">
					<button type="button" class="btn btn-previous" onclick="document.getElementById('stage').value='0';document.getElementById('action').value='config'; form.submit();">&laquo; {l_button.back}</button>
					<button type="submit" class="btn btn-next">{l_button.startinstall} &raquo;</button>
				</div>
			</form>
		</div>
	</div>
</div-->
