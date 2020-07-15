<div class="page-title">
	<h2>{l_pm}</h2>
</div>

<form action="{php_self}?mod=pm&action=delete" method="post" name="form">
	<div class="card">
		<div class="card-header text-right">
			<a href="{php_self}?mod=pm&action=write" class="btn btn-outline-success">{l_write}</a>
		</div>

		<div class="table-responsive">
			<table class="table table-sm mb-0">
				<thead>
					<tr>
						<th width="15%">{l_pmdate}</th>
						<th width="40%">{l_title}</th>
						<th nowrap>{l_from}</th>
						<th width="15%">{l_status}</th>
						<th width="5%">
							<input type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form)">
						</th>
					</tr>
				</thead>
				<tbody>
					{entries}
				</tbody>
			</table>
		</div>

		<div class="card-footer text-right">
			<button type="submit" class="btn btn-outline-danger">{l_delete}</button>
		</div>
	</div>
</form>
