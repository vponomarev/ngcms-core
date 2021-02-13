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

<div class="card">
	<div class="card-body">
		{l_commited}
	</div>

	<div class="card-footer">
		<a href="{php_self}?mod=extra-config&plugin={plugin}" class="btn btn-outline-success">{plugin}</a>
	</div>
</div>
