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
			<a href="{{ php_self }}" class="navbar-brand col-md-3 col-lg-2 mr-0 px-3"><i class="fa fa-cogs"></i>  {{ lang['admin_panel'] }}</a>
			<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#menu-content" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<a href="{{ php_self }}?action=logout" title="{{ lang['logout_t'] }}"
						class="nav-link">{{ lang['logout'] }}</a>
				</li>
			</ul>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="nav-side-menu">

					<div class="menu-list">
						<ul id="menu-content" class="menu-content collapse out">
							<li><a href="{{ home }}" target="_blank"><i class="fa fa-external-link"></i>
									{{ lang['mainpage'] }}</a></li>

							{%
								set showContent = global.mod == 'news'
									or global.mod == 'categories'
									or global.mod == 'static'
									or global.mod == 'images'
									or global.mod == 'files'
							%}

							<li data-toggle="collapse" data-target="#content"
								class="collapsed {{ h_active_options ? 'active' : '' }} ">
								<a href="#"><i class="fa fa-newspaper-o"></i>{{ lang['news_a'] }} <span
										class="arrow"></span></a>
							</li>
							<ul class="sub-menu collapse {{ showContent ? 'show' : ''}}" id="content">
								{% if (perm.editnews) %}<li><a
										href="{{ php_self }}?mod=news">{{ lang['news.edit'] }}</a></li>{% endif %}
								{% if (perm.categories) %}<li><a
										href="{{ php_self }}?mod=categories">{{ lang['news.categories'] }}</a></li>
								{% endif %}
								{% if (perm.static) %}<li><a href="{{ php_self }}?mod=static">{{ lang['static'] }}</a>
								</li>{% endif %}
								{% if (perm.addnews) %}<li><a
										href="{{ php_self }}?mod=news&action=add">{{ lang['news.add'] }}</a></li>
								{% endif %}
								<li><a href="{{ php_self }}?mod=images">{{ lang['images'] }}</a></li>
								<li><a href="{{ php_self }}?mod=files">{{ lang['files'] }}</a></li>
							</ul>

							{%
								set showUsers = global.mod == 'users'
									or global.mod == 'ipban'
									or global.mod == 'ugroup'
									or global.mod == 'perm'
							%}

							<li data-toggle="collapse" data-target="#users"
								class="collapsed {{ h_active_userman ? 'active' : '' }}">
								<a href="#"><i class="fa fa-users"></i> {{ lang['userman'] }} <span
										class="arrow"></span></a>
							</li>
							<ul class="sub-menu collapse {{ showUsers ? 'show' : '' }}" id="users">
								{% if (perm.users) %}<li><a href="{{ php_self }}?mod=users">{{ lang['users'] }}</a></li>
								{% endif %}
								{% if (perm.ipban) %}<li><a href="{{ php_self }}?mod=ipban">{{ lang['ipban_m'] }}</a></li>
								{% endif %}
								<li><a href="{{ php_self }}?mod=ugroup">{{ lang['ugroup'] }}</a></li>
								<li><a href="{{ php_self }}?mod=perm">{{ lang['uperm'] }}</a></li>
							</ul>

							{%
								set showService = global.mod == 'configuration'
									or global.mod == 'dbo'
									or global.mod == 'rewrite'
									or global.mod == 'cron'
									or global.mod == 'statistics'
							%}

							<li data-toggle="collapse" data-target="#service"
								class="collapsed {{ h_active_system ? 'active' : '' }}">
								<a href="#"><i class="fa fa-cog"></i> {{ lang['system'] }} <span
										class="arrow"></span></a>
							</li>
							<ul class="sub-menu collapse {{ showService ? 'show' : '' }}" id="service">
								{% if (perm.configuration) %}<li><a
										href="{{ php_self }}?mod=configuration">{{ lang['configuration'] }}</a></li>
								{% endif %}
								{% if (perm.dbo) %}<li><a href="{{ php_self }}?mod=dbo">{{ lang['options_database'] }}</a></li>
								{% endif %}
								{% if (perm.rewrite) %}<li><a
										href="{{ php_self }}?mod=rewrite">{{ lang['rewrite'] }}</a></li>{% endif %}
								{% if (perm.cron) %}<li><a href="{{ php_self }}?mod=cron">{{ lang['cron_m'] }}</a></li>
								{% endif %}
								<li><a href="{{ php_self }}?mod=statistics">{{ lang['statistics'] }} </a></li>
							</ul>


							<li class="{{ h_active_extras ? 'active' : '' }} "><a href="{{ php_self }}?mod=extras"><i
										class="fa fa-puzzle-piece"></i>{{ lang['extras'] }}</a></li>
							{% if (perm.templates) %}<li class="{{ h_active_templates ? 'active' : '' }} "><a href="{{ php_self }}?mod=templates"><i
										class="fa fa-th-large"></i>{{ lang['templates_m'] }}</a></li>{% endif %}
							<li class="{{ h_active_pm ? 'active' : '' }} "><a href="{{ php_self }}?mod=pm"
									title="{{ lang['pm_t'] }}">
									<i class="fa fa-envelope"></i> {{ lang['pm'] }} <span
										class="badge badge-dark">{{ newpm }}</span></a>
							</li>
							<hr>
							<li><a href="{{ php_self }}?mod=docs"><i class="fa fa-book"
										aria-hidden="true"></i> Документация</a></li>
							<li><a href="https://ngcms.ru/forum/" target="_blank"><i class="fa fa-comments-o"
										aria-hidden="true"></i> Форум поддержки</a></li>
							<li><a href="https://ngcms.ru/" target="_blank"><i class="fa fa-globe fa-lg"></i>
									Официальный сайт</a></li>
							<li><a href="https://github.com/vponomarev/ngcms-core" target="_blank"><i
										class="fa fa-github"></i> Github</a></li>
							</ul>
					</div>
				</div>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
					{{ notify }}
					{{ main_admin }}
				</main>
			</div>
			<footer class="border-top mt-5">
				<p class="text-right text-muted py-4 my-0">2008-{{ year }} © <a href="http://ngcms.ru"
						target="_blank">Next Generation CMS</a></p>
			</footer>
		</div>
		<script type="text/javascript">
			{# Устанавливаем временную переменную, чтобы отловить ошибки JSON - декодирования.#}
			{% set encode_lang = lang | json_encode(constant('JSON_PRETTY_PRINT') b-or constant('JSON_UNESCAPED_UNICODE')) %}
			window.NGCMS = {
				admin_url: '{{ admin_url }}',
				home: '{{ home }}',
				lang: {{ encode_lang ?: '{}' }},
			langcode: '{{ lang['langcode'] }}',
				php_self: '{{ php_self }}',
					skins_url: '{{ skins_url }}'
		};
		$('#menu-content .sub-menu').on('show.bs.collapse', function () {
                    $('#menu-content .sub-menu.show').not(this).removeClass('show');
                });
		</script>
	</body>
</html>
