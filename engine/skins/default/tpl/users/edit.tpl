<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['profile_of'] }} [{{ name }}]</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=users">{{ lang['users_title'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">
			{{ lang['profile_of'] }} [{{ name }}]
		</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->


<form action="{{ php_self }}?mod=users" method="post">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="id" value="{{ id }}" />

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card mb-4">
				<div class="card-body">
					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['groupName'] }}</label>
						<div class="col-lg-9">
							<select name="status" class="custom-select">
								{{ status }}
							</select>
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['new_pass'] }}</label>
						<div class="col-lg-9">
							<input type="text" name="password" class="form-control" />
							<small class="form-text text-muted">{{ lang['pass_left'] }}</small>
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['email'] }}</label>
						<div class="col-lg-9">
							<input type="email" name="mail" value="{{ mail }}" class="form-control" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['site'] }}</label>
						<div class="col-lg-9">
							<input type="text" name="site" value="{{ site }}" class="form-control" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['icq'] }}</label>
						<div class="col-lg-9">
							<input type="text" name="icq" value="{{ icq }}" class="form-control" maxlength="10" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['from'] }}</label>
						<div class="col-lg-9">
							<input type="text" name="where_from" value="{{ where_from }}" class="form-control" maxlength="60" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang['about'] }}</label>
						<div class="col-lg-9">
							<textarea name="info" class="form-control" rows="7" cols="60">{{ info }}</textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Right edit column -->
		<div id="rightBar" class="col col-lg-4">
			<div class="card mb-4">
				<div class="card-body">
					<ul class="list-unstyled mb-0">
						<li>{{ lang['regdate'] }}: <b>{{ regdate }}</b></li>
						<li>{{ lang['last_login'] }}: <b>{{ last }}</b></li>
						<li>{{ lang['last_ip'] }}: <b>{{ ip }}</b> <a href="http://www.nic.ru/whois/?ip={{ ip }}" title="{{ lang['whois'] }}">{{ lang['whois'] }}</a></li>
						<li>{{ lang['all_news'] }}: <b>{{ news }}</b></li>
						<li>{{ lang['all_comments'] }}: <b>{{ com }}</b></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col col-lg-8">
			<div class="row">
				{% if (perm.modify) %}
					<div class="col-md-6 mb-4">
						<button type="button" class="btn btn-outline-dark" onclick="history.back();">
							{{ lang['cancel'] }}
						</button>
					</div>

					<div class="col-md-6 mb-4 text-right">
						<button type="submit" class="btn btn-outline-success">
							<span class="d-xl-none"><i class="fa fa-floppy-o"></i></span>
							<span class="d-none d-xl-block">{{ lang['save'] }}</span>
						</button>
					</div>
				{% endif %}
			</div>
		</div>
	</div>
</form>

{% if (pluginIsActive('xfields')) %}
<div class="row my-5">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">Доп. поля в профиле пользователя (только просмотр)</div>

			<table class="table table-sm">
				<thead>
					<tr>
						<th>ID поля</th>
						<th>Название поля</th>
						<th>Тип поля</th>
						<th>Блок</th>
						<!-- <th>V</th> -->
						<th>Значение</th>
					</tr>
				</thead>
				<tbody>
					{% for xFN,xfV in p.xfields.fields %}
					<tr>
						<td>{{ xFN }}</td>
						<td>{{ xfV.title }}</td>
						<td>{{ xfV.data.type }}</td>
						<td>{{ xfV.data.area }}</td>
						<!-- 	<td>{% if (xfV.data.type == "select") and (xfV.data.storekeys) %}<span style="font-color: red;"><b>{{ xfV.secure_value }}{% else %}&nbsp;{% endif %}</td> -->
						<td>{{ xfV.input }}</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>
	</div>
</div>
{% endif %}
