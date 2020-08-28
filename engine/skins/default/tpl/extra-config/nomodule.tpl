<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0 text-dark"  style="padding: 20px 0 0 0;">{{action}}: {{ plugin }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=extras">{{ lang['extras'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">{{action}}: {{ plugin }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<div class="alert alert-danger">
	{{ action_text }}
</div>
