<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['addnew'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item"><a href="?mod=categories">{{ lang['categories_title'] }}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['addnew'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form method="post" action="{{ php_self }}?mod=categories" enctype="multipart/form-data">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="action" value="doadd" />

	<div class="card mb-4">
		<div class="card-body">
			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['show_main'] }}</label>
				<div class="col-lg-6">
					<div class="custom-control custom-switch py-2 mr-auto">
						<input id="cat_show" type="checkbox" name="cat_show" value="1" class="custom-control-input" checked />
						<label for="cat_show" class="custom-control-label"></label>
					</div>
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['parent'] }}</label>
				<div class="col-lg-6">
					{{ parent }}
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['title'] }}</label>
				<div class="col-lg-6">
					<input type="text" name="name" value="" class="form-control" />
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['alt_name'] }}</label>
				<div class="col-lg-6">
					<input type="text" name="alt" value="" class="form-control" />
				</div>
			</div>

			{% if (flags.haveMeta) %}
				<div class="form-row mb-3">
					<label class="col-lg-6 col-form-label">{{ lang['cat_desc'] }}</label>
					<div class="col-lg-6">
						<textarea name="description" cols="80" class="form-control"></textarea>
					</div>
				</div>

				<div class="form-row mb-3">
					<label class="col-lg-6 col-form-label">{{ lang['cat_keys'] }}</label>
					<div class="col-lg-6">
						<textarea name="keywords" cols="80" class="form-control"></textarea>
					</div>
				</div>
			{% endif %}

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['cat_number'] }}</label>
				<div class="col-lg-6">
					<input type="number" name="number" value="" class="form-control" />
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['show.link'] }}</label>
				<div class="col-lg-6">
					<select name="show_link" class="custom-select">
						<option value="0">{{ lang['link.always'] }}</option>
						<option value="1">{{ lang['link.ifnews'] }}</option>
						<option value="2">{{ lang['link.never'] }}</option>
					</select>
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['cat_tpl'] }}</label>
				<div class="col-lg-6">
					<select name="tpl" class="custom-select">
						{{ tpl_list }}
					</select>
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['template_mode'] }}</label>
				<div class="col-lg-6">
					<select name="template_mode" class="custom-select">
						{{ template_mode }}
					</select>
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['icon'] }} <small class="form-text text-muted">{{ lang['icon#desc'] }}</small></label>
				<div class="col-lg-6">
					<input type="text" name="icon" value="" maxlength="255" class="form-control" />
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['attached_icon'] }} <small class="form-text text-muted">{{ lang['attached_icon#desc'] }}</small></label>
				<div class="col-lg-6">
					<div class="row">
						<div class="col"><input type="file" name="image" /></div>
					</div>
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['alt_url'] }}</label>
				<div class="col-lg-6">
					<input type="text" name="alt_url" value="" class="form-control" />
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['orderby'] }}</label>
				<div class="col-lg-6">
					{{ orderlist }}
				</div>
			</div>

			<div class="form-row mb-3">
				<label class="col-lg-6 col-form-label">{{ lang['category.info'] }} <small class="form-text text-muted">{{ lang['category.info#desc'] }}</small></label>
				<div class="col-lg-6">
					<textarea id="info" name="info" cols="80" class="form-control"></textarea>
				</div>
			</div>

			<table class="table table-sm">
				<tbody>
					{{ extend }}
				</tbody>
			</table>
		</div>

		<div class="card-footer">
			<div class="form-group my-3 text-center">
				<button type="submit" class="btn btn-outline-success">{{ lang['addnew'] }}</button>
			</div>
		</div>
	</div>
</form>
