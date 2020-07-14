<div class="page-title">
	<h2>{{ lang['user_groups'] }}</h2>
</div>

<div class="card">
	<div class="card-header">
		<div class="row">
			<div class="col text-right">
				{% if (flags.canAdd) %}
					<form method="get" action="">
						<input type="hidden" name="mod" value="ugroup" />
						<input type="hidden" name="action" value="addForm" />

						<button type="submit" class="btn btn-outline-success">{{ lang['add_group'] }}</button>
					</form>
				{% endif %}
			</div>
		</div>
	</div>

	<table class="table table-sm mb-0">
		<thead>
			<tr>
				<th width="5%">#</th>
				<th>{{ lang['identifier'] }}</th>
				<th>{{ lang['name'] }}</th>
				<th>{{ lang['users_in_group'] }}</th>
				<th>{{ lang['action'] }}</th>
			</tr>
		</thead>
		<tbody id="admCatList">
			{% for entry in entries %}
			<tr>
				<td>{{ entry.id }}</td>
				<td>{{ entry.identity }}</td>
				<td>{{ entry.name }}</td>
				<td>{{ entry.count }}</td>
				<td class="text-right">
					<div class="btn-group btn-group-sm" role="group">
						{% if (entry.flags.canEdit) %}
						<a href="{{ php_self }}?mod=ugroup&action=editForm&id={{ entry.id }}" class="btn btn-outline-primary"><i class="fa fa-pencil"></i></a>
						{% endif %}
						{% if (entry.flags.canDelete) %}
						<a href="{{ php_self }}?mod=ugroup&action=delete&id={{ entry.id }}&token={{ token }}" class="btn btn-outline-danger"><i class="fa fa-trash"></i></a>
						{% endif %}
					</div>
				</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>
