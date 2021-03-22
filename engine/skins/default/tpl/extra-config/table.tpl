<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{plugin}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="{php_self}"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item"><a href="{php_self}?mod=extras">{l_extras}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{plugin}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form method="post" action="{php_self}?mod=extra-config" name="form">
	<input type="hidden" name="token" value="{token}" />
	<input type="hidden" name="plugin" value="{plugin}" />
	<input type="hidden" name="action" value="commit" />

	<div class="card">
		<h5 class="card-header">{plugin}</h5>

		<table class="table table-sm extra-config">
			<tbody>
				{entries}
			</tbody>
		</table>

		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{l_commit_change}</button>
		</div>
	</div>
</form>
