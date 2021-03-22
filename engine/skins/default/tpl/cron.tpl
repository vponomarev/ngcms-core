<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang.cron.title }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang.cron.title }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<div class="alert alert-info">
	{{ lang.cron['title#desc'] }}
</div>

<form action="{{ php_self }}?mod=cron" method="post">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="mod" value="cron" />
	<input type="hidden" name="action" value="commit" />

	<div class="card mb-3">
		<div class="card-header">
			<div class="row">
				<div class="col text-right">
					<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#legendModal">
						<i class="fa fa-question"></i>
					</button>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-sm">
				<thead>
					<tr>
						<th>Plugin</th>
						<th>Handler</th>
						<th>Min</th>
						<th>Hour</th>
						<th>Day</th>
						<th>Month</th>
						<th>D.O.W.</th>
					</tr>
				</thead>
				<tbody>
					{% for entry in entries %}
					<tr>
						<td>
							<input name="data[{{ entry.id }}][plugin]" value="{{ entry.plugin }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][handler]" value="{{ entry.handler }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][min]" value="{{ entry.min }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][hour]" value="{{ entry.hour }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][day]" value="{{ entry.day }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][month]" value="{{ entry.month }}" class="form-control" />
						</td>
						<td>
							<input name="data[{{ entry.id }}][dow]" value="{{ entry.dow }}" class="form-control" />
						</td>
					</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>

		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{{ lang.cron['commit_change'] }}</button>
		</div>
	</div>
</form>

<div id="legendModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="legendModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				{{ lang.cron['legend'] }}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
