<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{l_images_title}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=perm">{{ lang['permissions'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">
			{{ lang['result'] }}
		</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<div class="alert {{ execResult ? 'alert-success' : 'alert-danger'}}">
	{{ lang['result'] }}: {{ execResult ? lang['success'] : lang['error'] }}
</div>

{% if updateList %}
<div class="card mb-5">
	<div class="card-header">
		{{ lang['list_changes_performed'] }}
	</div>

	<table class="table table-sm mb-0">
		<thead>
			<tr>
				<th>{{ lang['group'] }}</th>
				<th>ID</th>
				<th>{{ lang['name'] }}</th>
				<th nowrap>{{ lang['old_value'] }}</th>
				<th nowrap>{{ lang['new_value'] }}</th>
			</tr>
		</thead>
		<tbody>
			{% for entry in updateList %}
				<tr>
					<td>{{ GRP[entry.group]['title'] }}</td>
					<td>{{ entry.id }}</td>
					<td>{{ entry.title }}</td>
					<td>{{ entry.displayOld }}</td>
					<td>{{ entry.displayNew }}</td>
				</tr>
			{% endfor %}
		</tbody>
	</table>
</div>
{% endif %}
