<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang.pm }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang.pm }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form action="?mod=pm&action=delete" method="post" name="form">
	<input type="hidden" name="token" value="{{ token }}">
	<div class="card">
		<div class="card-header text-right">
			<a href="?mod=pm&action=write" class="btn btn-outline-success">{{ lang.write }}</a>
		</div>

		<div class="table-responsive">
			<table class="table table-sm mb-0">
				<thead>
					<tr>
						<th width="15%">{{ lang.pmdate }}</th>
						<th width="40%">{{ lang.title }}</th>
						<th nowrap>{{ lang.from }}</th>
						<th width="15%">{{ lang.status }}</th>
						<th width="5%">
							<input type="checkbox" name="master_box" title="{{ lang.select_all }}" onclick="javascript:check_uncheck_all(form)">
						</th>
					</tr>
				</thead>
				<tbody>
{% for entry in entries %}
					<tr>
						<td>{{ entry.date }}</td>
						<td><a href="?mod=pm&action=read&pmid={{ entry.id }}&token={{ token }}">{{ entry.title }}</a></td>
						<td nowrap>{% if entry.flags.haveSender %}<a href="{{ entry.senderProfileURL }}">{{ entry.senderName }}</a>{% else %}{{ entry.senderName }}{% endif %}</td>
						<td>{% if entry.flags.viewed %}{{ lang.viewed }}{% else %}{{ lang.unviewed }}{% endif %}</td>
						<td><input type="checkbox" name="selected_pm[]" value="{{ entry.id }}" /></td>
					</tr>
{% endfor %}
				</tbody>
			</table>
		</div>

		<div class="card-footer text-right">
			<button type="submit" class="btn btn-outline-danger">{{ lang.delete }}</button>
		</div>
	</div>
</form>
