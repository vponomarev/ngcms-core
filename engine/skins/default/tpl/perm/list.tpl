<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['permissions'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['permissions'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<style>
	.pChanged {
		border-color: var(--warning);
	}
</style>

<script type="text/javascript">
	var permDefault = {{ DEFAULT_JSON }};

	function onUpdatePerm(name) {
		var f = document.getElementById('permSubmit');
		var v = permDefault[name];

		f[name].classList.toggle('pChanged', f[name].value != v);
	}
</script>

<!-- Form header -->
<form id="permSubmit" name="permSubmit" method="post">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="save" value="1" />
	<!-- /Form header -->

	<!-- Group menu header -->
	<ul class="nav nav-pills mb-3 d-md-flex d-block" role="tablist">
		{% for group in GRP %}
		<li class="nav-item"><a href="#userTabs-{{ group.id }}" class="nav-link {{ loop.first ? 'active' : '' }}" data-toggle="tab">{{ group.title }}</a></li>
		{% endfor %}
	</ul>

	<!-- Group content header -->
	<div id="userTabs" class="tab-content">
		{% for group in GRP %}
		<!-- Content for group [{{ group.id }}] {{ group.title }} -->
		<div id="userTabs-{{ group.id }}" class="tab-pane {{ loop.first ? 'show active' : '' }}">
			<div class="alert alert-info">
				{{ lang['permissions_for_user_group'] }}: <b>{{ group.title }}</b>
			</div>

			{% for block in CONFIG %}
			<div class="pconf">
				<h3>{{ block.title }}</h3>
				{% if (block.description) %}<div class="alert alert-info">{{ block.description }}</div>{% endif %}

				{% for area in block.items %}
				<h4>{{ area.title }}</h4>
				{% if (area.description) %}<div class="alert alert-info">{{ area.description }}</div>{% endif %}

				<table class="table table-sm">
					<thead>
						<tr>
							<th>#ID</th>
							<th>{{ lang['description'] }}</th>
							<th>{{ lang['access'] }}</th>
						</tr>
					</thead>
					<tbody>
						{% for entry in area.items %}
						<tr class="contentEntry1">
							<td width="220"><b>{{ entry.id }}</b></td>
							<td>{{ entry.title }}</td>
							<td width="110">
								<select name="{{ entry.name }}|{{ group.id }}" onchange="onUpdatePerm('{{ entry.name }}|{{ group.id }}');" class="custom-select custom-select-sm">
									<option value="-1">--</option>
									<option value="0" {{ isSet(entry.perm[group.id]) and (not entry.perm[group.id]) ? 'selected' : '' }}>{{ lang['noa'] }}</option>
									<option value="1" {{ isSet(entry.perm[group.id]) and (entry.perm[group.id]) ? 'selected' : '' }}>{{ lang['yesa'] }}</option>
								</select>
							</td>
						</tr>
						{% endfor %}
					</tbody>
				</table>
				<br />
				{% endfor %}
			</div>
			{% endfor %}
		</div>
		<!-- /Content for group [{{ group.id }}] {{ group.title }} -->
		{% endfor %}
	</div>

	<div class="form-group my-3 text-center">
		<button type="submit" class="btn btn-outline-success">{{ lang['save'] }}</button>
	</div>
</form>
