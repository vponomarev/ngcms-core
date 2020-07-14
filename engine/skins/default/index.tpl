<!DOCTYPE html>
<html lang="{{ lang['langcode'] }}">

<head>
	<meta charset="{{ lang['encoding'] }}" />
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
	<title>{{ home_title }} - {{ lang['admin_panel'] }}</title>

	<link href="{{ skins_url }}/public/css/app.css" rel="stylesheet" />

	<script src="{{ skins_url }}/public/js/manifest.js" type="text/javascript"></script>
	<script src="{{ skins_url }}/public/js/vendor.js" type="text/javascript"></script>
	<script src="{{ skins_url }}/public/js/app.js" type="text/javascript"></script>
</head>

<body>
	<div id="loading-layer" class="col-md-3 alert alert-dark" role="alert">
		<i class="fa fa-spinner fa-pulse mr-2"></i> {{ lang['loading'] }}
	</div>

	<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
		<a href="{{ home }}" title="{{ lang['mainpage_t'] }}" class="navbar-brand col-md-3 col-lg-2 mr-0 px-3">{{ home_title }}</a>
		<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<ul class="navbar-nav px-3">
			<li class="nav-item text-nowrap">
				<a href="{{ php_self }}?action=logout" title="{{ lang['logout_t'] }}" class="nav-link">{{ lang['logout'] }}</a>
			</li>
		</ul>
	</nav>

	<div class="container-fluid">
		<div class="row">
			<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
				<div class="sidebar-sticky pt-3">
					<ul class="nav flex-column">
						<li class="nav-item"><a href="{{ php_self }}" class="nav-link"><i class="fa fa-home"></i> {{ lang['admin_panel'] }}</a></li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=options" title="{{ lang['options_t'] }}" class="{{ h_active_options ? 'active' : '' }} nav-link">
								<i class="fa fa-cogs"></i> {{ lang['options'] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=extras" title="{{ lang['extras_t'] }}" class="{{ h_active_extras ? 'active' : '' }} nav-link">
								<i class="fa fa-puzzle-piece"></i> {{ lang['extras'] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=news&action=add" title="{{ lang['addnews_t'] }}" class="{{ h_active_addnews ? 'active' : '' }} nav-link">
								<i class="fa fa-newspaper-o"></i> {{ lang['addnews'] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=news" title="{{ lang['editnews_t'] }}" class="{{ h_active_editnews ? 'active' : '' }} nav-link">
								<i class="fa fa-pencil-square-o"></i> {{ lang['editnews'] }}
							</a>
							<!-- {{ unapproved }} -->
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=images" title="{{ lang['images_t'] }}" class="{{ h_active_images ? 'active' : '' }} nav-link">
								<i class="fa fa-image"></i> {{ lang['images'] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=files" title="{{ lang['files_t'] }}" class="{{ h_active_files ? 'active' : '' }} nav-link">
								<i class="fa fa-files-o"></i> {{ lang['files'] }}
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ php_self }}?mod=pm" title="{{ lang['pm_t'] }}" class="{{ h_active_pm ? 'active' : '' }} nav-link">
								<i class="fa fa-envelope"></i> {{ lang['pm'] }} <span class="badge badge-dark">{{ newpm }}</span>
							</a>
						</li>
					</ul>

					<hr>

					<ul class="nav flex-column mb-2">
						<li class="nav-item">
							<a href="http://ngcms.ru/forum/" target="_blank" class="nav-link">
								<i class="fa fa-leanpub"></i>{{ lang['forum'] }}
							</a>
						</li>
					</ul>
				</div>
			</nav>

			<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
				{{ notify }}
				{{ main_admin }}
			</main>
		</div>
	</div>

	<script type="text/javascript">
		window.NGCMS = {
			admin_url: '{{ admin_url }}',
			home: '{{ home }}',
			lang: {{ lang | json_encode(constant('JSON_PRETTY_PRINT') b-or constant('JSON_UNESCAPED_UNICODE')) }},
			langcode: '{{ lang['langcode'] }}',
			php_self: '{{ php_self }}',
			skins_url: '{{ skins_url }}'
		};
	</script>
</body>
