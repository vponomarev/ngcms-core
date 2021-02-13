<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{mode_text}: {plugin}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="{php_self}"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item"><a href="{php_self}?mod=extras">{l_extras}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{mode_text}: {plugin}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form action="{php_self}?mod=extras" method="get">
	<input type=hidden name="mod" value="extras" />

	<div class="card">
		<h5 class="card-header">{plugin}</h5>

		<div class="table-responsive">
			<table class="table table-sm">
				<tbody>
					{entries}
				</tbody>
			</table>
		</div>

		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{msg}</button>
		</div>
	</div>
</form>
