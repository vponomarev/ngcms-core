<div class="page-title">
	<h2>{{ lang.dbo.title }}</h2>
</div>

<!-- FORM: Perform actions with tables -->
<form name="form" method="post" action="{{ php_self }}?mod=dbo">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="subaction" value="modify" />
	<input type="hidden" name="massbackup" value="" />
	<input type="hidden" name="cat_recount" value="" />
	<input type="hidden" name="masscheck" value="" />
	<input type="hidden" name="massrepair" value="" />
	<input type="hidden" name="massoptimize" value="" />
	<input type="hidden" name="massdelete" value="" />

	<div class="card mb-5">
		<table class="table table-sm">
			<thead>
				<tr>
					<th width="15%">{{ lang.dbo.table }}</th>
					<th width="15%">{{ lang.dbo.rows }}</th>
					<th width="15%">{{ lang.dbo.data }}</th>
					<th width="15%">{{ lang.dbo.overhead }}</th>
					<th width="5%">
						<input type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form, 'tables')" />
					</th>
				</tr>
			</thead>
			<tbody>
				{% for tbl in tables %}
				<tr>
					<td>{{ tbl.table }}</td>
					<td>{{ tbl.rows }}</td>
					<td>{{ tbl.data }}</td>
					<td>{{ tbl.overhead }}</td>
					<td><input type="checkbox" name="tables[]" value="{{ tbl.table }}" />
					</td>
				</tr>
				{% endfor %}
			</tbody>
		</table>
		<div class="card-footer">
			<div class="btn-group" role="group">
				<button type="submit" class="btn btn-outline-dark" onclick="document.forms['form'].cat_recount.value = 'true';">{{ lang.dbo.cat_recount }}</button>
				<button type="submit" class="btn btn-outline-dark" onclick="document.forms['form'].masscheck.value = 'true';">{{ lang.dbo.check }}</button>
				<button type="submit" class="btn btn-outline-dark" onclick="document.forms['form'].massrepair.value = 'true';">{{ lang.dbo.repair }}</button>
				<button type="submit" class="btn btn-outline-dark" onclick="document.forms['form'].massoptimize.value = 'true';">{{ lang.dbo.optimize }}</button>
				<button type="submit" class="btn btn-outline-danger" onclick="document.forms['form'].massdelete.value = 'true';">{{ lang.dbo.delete }}</button>
			</div>
		</div>
		<div class="card-footer">
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<label class="mb-0">
							<input type="checkbox" name="gzencode" value="1" />
							{{ lang.dbo.gzencode }}
						</label>
					</div>
					<div class="input-group-text">
						<label class="mb-0">
							<input type="checkbox" name="email_send" value="1" />
							{{ lang.dbo.email_send }}
						</label>
					</div>
				</div>
				<div class="input-group-append">
					<button type="submit" class="btn btn-outline-success ml-auto" onclick="document.forms['form'].massbackup.value = 'true';">{{ lang.dbo.backup }}</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- FORM: Perform actions with backups -->
<form name="backups" method="post" action="{{ php_self }}?mod=dbo">
	<input type="hidden" name="subaction" value="modify" />
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="delbackup" value="" />
	<input type="hidden" name="massdelbackup" value="" />
	<input type="hidden" name="restore" value="" />

	<div class="card my-5">
		<div class="card-body">
			<div class="input-group">
				{{ restore }}
				<div class="input-group-append">
					<button type="submit" class="btn btn-outline-warning" onclick="document.forms['backups'].restore.value = 'true';">{{ lang.dbo.restore }}</button>
					<button type="submit" class="btn btn-outline-danger" onclick="document.forms['backups'].delbackup.value = 'true';">{{ lang.dbo.delete }}</button>
					<button type="submit" class="btn btn-outline-danger" onclick="document.forms['backups'].massdelbackup.value = 'true';">{{ lang.dbo.deleteall }}</button>
				</div>
			</div>
		</div>
	</div>
</form>
