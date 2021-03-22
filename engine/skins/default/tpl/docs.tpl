<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6 d-none d-md-block">
				<h1 class="m-0 text-dark">{{ lang['docs'] }}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
					<li class="breadcrumb-item active" aria-current="page">
						{{ lang['docs'] }}
					</li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div>
</div>
<section class="content">
	<div class="container-fluid">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-md-3">
				<div class="docs__menu mx-2 my-5 py-4" style="border: 1px solid #ccc;">{{ menu }}</div>
			</div>
			<div class="col-md-9">
				<div class="docs__contents mx-2 my-5">
					{% if docs %}
					{{ docs }}
					{% else %}
					404
					{% endif %}
				</div>
			</div>
		</div>
	</div><!-- /.container-fluid -->
</section>
