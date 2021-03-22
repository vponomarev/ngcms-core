<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['users_title'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['users_title'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<!-- Filter form: BEGIN -->
<div id="collapseUsersFilter" class="collapse">
	<div class="card mb-4">
		<div class="card-body">
			<form action="{{ php_self }}" method="get">
				<input type="hidden" name="mod" value="users" />
				<input type="hidden" name="action" value="list" />

				<div class="row">
					<!--Block 1-->
					<div class="col-lg-4">
						<div class="form-group">
							<label>{{ lang['name'] }}</label>
							<input type="text" name="name" value="{{ name }}" class="form-control" />
						</div>
					</div>

					<!--Block 2-->
					<div class="col-lg-4">
						<div class="form-group">
							<label>{{ lang['group'] }}</label>
							<select name="group" class="custom-select">
								<option value="0">-- {{ lang['any'] }} --</option>
								{% for g in ugroup %}
								<option value="{{ g.id }}" {{ group == g.id ? 'selected' : ''}}>{{ g.name }}</option>
								{% endfor %}
							</select>
						</div>
					</div>

					<!--Block 3-->
					<div class="col-lg-4">
						<div class="form-group">
							<label>{{ lang['per_page'] }}&nbsp;</label>
							<input type="number" name="rpp" value="{{ rpp }}" class="form-control" />
						</div>

						<div class="form-group mb-0 text-right">
							<button type="submit" class="btn btn-outline-primary">{{ lang['sortit'] }}</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Mass actions form: BEGIN -->
<form id="form_users" action="{{ php_self }}" method="get" name="form_users">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="mod" value="users" />
	<input type="hidden" name="name" value="{{ name }}" />
	<input type="hidden" name="how" value="{{ how_value }}" />
	<input type="hidden" name="sort" value="{{ sort_value }}" />
	<input type="hidden" name="page" value="{{ page_value }}" />
	<input type="hidden" name="per_page" value="{{ rpp }}" />

	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					{% if flags.canModify %}
					<button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#adduserModal">{{ lang['adduser'] }}</button>
					{% endif %}
					<button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#collapseUsersFilter" aria-expanded="false" aria-controls="collapseUsersFilter">
						<i class="fa fa-filter"></i>
					</button>
				</div>
			</div>
		</div>

		<table class="table table-sm mb-0">
			<thead>
				<tr>
					<th width="5%">
						<a href="{{ sortLink['i']['link'] }}">#</a> {{ sortLink['i']['sign'] }}
					</th>
					<th width="20%">
						<a href="{{ sortLink['n']['link'] }}">{{ lang['name'] }}</a> {{ sortLink['n']['sign'] }}
					</th>
					<th width="20%">
						<a href="{{ sortLink['r']['link'] }}">{{ lang['regdate'] }}</a> {{ sortLink['r']['sign'] }}
					</th>
					<th width="20%">
						<a href="{{ sortLink['l']['link'] }}">{{ lang['last_login'] }}</a> {{ sortLink['l']['sign'] }}
					</th>
					<th width="10%">
						<a href="{{ sortLink['p']['link'] }}">{{ lang['all_news2'] }}</a> {{ sortLink['p']['sign'] }}
					</th>
					{% if flags.haveComments %}
					<th width="10%">{l_listhead.comments}</th>
					{% endif %}
					<th width="15%">
						<a href="{{ sortLink['g']['link'] }}">{{ lang['groupName'] }}</a> {{ sortLink['g']['sign'] }}
					</th>
					<th width="5%">&nbsp;</th>
					<th width="5%">
						{% if flags.canModify %}
						<input type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form_users)" />
						{% endif %}
					</th>
				</tr>
			</thead>
			<tbody>
				{% for entry in entries %}
				<tr>
					<td>{{ entry.id }}</td>
					<td>
						{% if flags.canView %}
							<a href="{{ php_self }}?mod=users&action=editForm&id={{ entry.id }}">{{ entry.name }}</a>
						{% else %}
							{{ entry.name }}
						{% endif %}
					</td>
					<td>{{ entry.regdate }}</td>
					<td>{{ entry.lastdate }}</td>
					<td>
						{% if entry.cntNews > 0 %}
						<a href="{{ php_self }}?mod=news&aid={{ id }}">{{ entry.cntNews }}</a>
						{% else %}-{% endif %}
					</td>
					{% if flags.haveComments %}
					<td width="10%">
						{{ entry.cntComments ?: '-'}}
					</td>
					{% endif %}
					<td>{{ entry.groupName }}</td>
					<td>
						{% if entry.flags.isActive %}
							<i class="fa fa-check text-success" title="{{ lang['active'] }}"></i>
						{% else %}
							<i class="fa fa-times text-danger" title="{{ lang['unactive'] }}"></i>
						{% endif %}
					</td>
					<td>
						{% if (flags.canModify and flags.canMassAction) %}
							<input type="checkbox" name="selected_users[]" value="{{ entry.id }}" />
						{% endif %}
					</td>
				</tr>
				{% endfor %}
			</tbody>
		</table>

		<div class="card-footer">
			<div class="row">
				<div class="col-lg-6 mb-2 mb-lg-0">{{ pagination }}</div>

				<div class="col-lg-6">
					{% if flags.canModify %}
					<div class="input-group">
						<select name="action" class="custom-select">
							<option value="">-- {{ lang['action'] }} --</option>
							<option value="massActivate">{{ lang['activate'] }}</option>
							<option value="massLock">{{ lang['lock'] }}</option>
							<option value="" class="bg-light" disabled>===================</option>
							<option value="massDel">{{ lang['delete'] }}</option>
							<option value="massDelInactive">{{ lang['delete_unact'] }}</option>
							<option value="" class="bg-light" disabled>===================</option>
							<option value="massSetStatus">{{ lang['setstatus'] }} &raquo;</option>
						</select>
						<select name="newstatus" class="custom-select">
							<option value=""></option>
							{% for grp in ugroup|reverse %}
							<option value="{{ grp.id }}">{{ grp.id }} ({{ grp.name }})</option>
							{% endfor %}
						</select>

						<div class="input-group-append">
							<button type="submit" class="btn btn-outline-warning">{{ lang['submit'] }}</button>
						</div>
					</div>
					{% endif %}
				</div>
			</div>
		</div>
	</div>
</form>
<!-- Mass actions form: END -->

{% if flags.canModify %}
<div id="adduserModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="adduserModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" action="{{ php_self }}?mod=users">
				<input type="hidden" name="action" value="add" />
				<input type="hidden" name="token" value="{{ token }}" />

				<div class="modal-header">
					<h5 id="adduserModalLabel" class="modal-title">{{ lang.adduser }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">{{ lang.name }}</label>
						<div class="col-sm-8">
							<input type="text" name="regusername" class="form-control" />
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-4 col-form-label">{{ lang.password }}</label>
						<div class="col-sm-8">
							<input type="text" name="regpassword" class="form-control" />
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-4 col-form-label">{{ lang.email }}</label>
						<div class="col-sm-8">
							<input type="email" name="regemail" class="form-control" />
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-4 col-form-label">{{ lang.status }}</label>
						<div class="col-sm-8">
							<select name="reglevel" class="custom-select">
								{% for grp in ugroup %}
								<option value="{{ grp.id }}">{{ grp.id }} ({{ grp.name }})</option>
								{% endfor %}
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-outline-success">{{ lang.adduser }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
{% endif %}

<script type="text/javascript">
	$(document).ready(function () {
		$('#form_users').on('input', function(event) {
			$(this.elements.newstatus).toggle(
				'massSetStatus' === $(this.elements.action).val()
			);
		})
		.on('submit', function(event) {
			event.preventDefault();

			var action = $(this.elements.action).val();
			var newstatus = $(this.elements.newstatus).val();

			if ('' == action) {
				return alert('Необходимо выбрать действие!');
			}

			if (('massSetStatus' == action) && !newstatus) {
				return alert(NGCMS.lang.msge_setstatus);
			}

			this.submit();
		})
		.trigger('input');
	});
</script>
