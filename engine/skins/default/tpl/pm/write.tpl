<div class="page-title">
	<h2>{l_pm}</h2>
</div>

<form name="form" action="{php_self}?mod=pm&action=send" method="post">
	<!--
	 -->

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card">
				<div class="card-body">
					<div class="form-row mb-3">
						<label class="col-lg-5 col-form-label">{l_title}</label>
						<div class="col-lg-7">
							<input type="text" name="title" value="" class="form-control" maxlength="50" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-5 col-form-label">
							{l_receiver}
						</label>
						<div class="col-lg-7">
							<input type="text" name="sendto" value="" class="form-control" maxlength="70" />
							<small class="form-text text-muted">{l_receiver_desc}</small>
						</div>
					</div>

					{quicktags}
					<!-- SMILES -->
					<div id="modal-smiles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="smiles-modal-label" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 id="smiles-modal-label" class="modal-title">Вставить смайл</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									{smilies}
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div>

					<div class="mb-3">
						<!-- {l_content} -->
						<textarea id="content" name="content" rows="10" cols="60" maxlength="3000"/></textarea>
					</div>
				</div>

				<div class="card-footer text-center">
					<button type="submit" class="btn btn-outline-success">{l_send}</button>
				</div>
			</div>
		</div>
	</div>
</form>
