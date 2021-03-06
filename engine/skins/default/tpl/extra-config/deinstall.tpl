<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0 text-dark"  style="padding: 20px 0 0 0;">{l_deinstall_text}: {plugin}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="{php_self}"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{php_self}?mod=extras">{l_extras}</a></li>
		<li class="breadcrumb-item active" aria-current="page">{l_deinstall_text}: {plugin}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form method="post" action="{php_self}?mod=extra-config">
	<input type="hidden" name="plugin" value="{plugin}" />
	<input type="hidden" name="stype" value="install" />
	<input type="hidden" name="action" value="commit" />

	<div class="card">
		<h5 class="card-header">{plugin}</h5>

		<div class="card-body">{install_text}</div>

		<div class="card-footer text-center">
			<button type="submit" class="btn btn-outline-success">{l_commit_deinstall}</button>
		</div>
	</div>
</form>
