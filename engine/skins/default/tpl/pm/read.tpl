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

<form method="post" action="?mod=pm&action=reply&pmid={{ id }}">
	<input type="hidden" name="title" value="{{ title }}" />
	<input type="hidden" name="from" value="{{ from }}" />

	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<h5 class="card-header">{{ title }}</h5>
				<div class="card-body">{{ content }}</div>
				<div class="card-footer text-center">
					<button type="submit" class="btn btn-outline-success">{{ lang.reply }}</button>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card mb-4">
				<div class="card-header">{{ lang.msgi_info }}</div>
				<div class="card-body">
					<ul class="list-unstyled mb-0">
						<li>{{ lang.from }}: <b>{{ fromID }} ({{ fromName }})</b></li>
						<li>{{ lang.receiver }}: <b>{{ toID }} ({{ toName }})</b></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</form>
