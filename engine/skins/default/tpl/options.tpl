<div class="page-title">
	<h2>{{ lang.options['options_title'] }}</h2>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card mb-4">
			<h5 class="card-header font-weight-light">{{ lang.options['news'] }}</h5>
			<div class="card-body">
				<ul class="list-unstyled mb-0">
					{% if (perm.static) %}
						<li><a href="{{ php_self }}?mod=static">{{ lang.options['static'] }}</a></li>
					{% endif %}
					{% if (perm.categories) %}
						<li><a href="{{ php_self }}?mod=categories">{{ lang.options['news.categories'] }}</a></li>
					{% endif %}
					{% if (perm.addnews) %}
						<li><a href="{{ php_self }}?mod=news&action=add">{{ lang.options['news.add'] }}</a></li>
					{% endif %}
					{% if (perm.editnews) %}
						<li><a href="{{ php_self }}?mod=news">{{ lang.options['news.edit'] }}</a></li>
					{% endif %}
				</ul>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card mb-4">
			<h5 class="card-header font-weight-light">{{ lang.options['system'] }}</h5>
			<div class="card-body">
				<ul class="list-unstyled mb-0">
					{% if (perm.configuration) %}
						<li><a href="{{ php_self }}?mod=configuration">{{ lang.options['configuration'] }}</a></li>
					{% endif %}
					{% if (perm.dbo) %}
						<li><a href="{{ php_self }}?mod=dbo">{{ lang.options['dbo'] }}</a></li>
					{% endif %}
					{% if (perm.rewrite) %}
						<li><a href="{{ php_self }}?mod=rewrite">{{ lang.options['rewrite'] }}</a></li>
					{% endif %}
					{% if (perm.cron) %}
						<li><a href="{{ php_self }}?mod=cron">{{ lang.options['cron'] }}</a></li>
					{% endif %}
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card mb-4">
			<h5 class="card-header font-weight-light">{{ lang.options['userman'] }}</h5>
			<div class="card-body">
				<ul class="list-unstyled mb-0">
					{% if (perm.users) %}
						<li><a href="{{ php_self }}?mod=users">{{ lang.options['users'] }}</a></li>
					{% endif %}
					{% if (perm.ipban) %}
						<li><a href="{{ php_self }}?mod=ipban">{{ lang.options['ipban'] }}</a></li>
					{% endif %}
					<li><a href="{{ php_self }}?mod=ugroup">{{ lang.options['ugroup'] }}</a></li>
					<li><a href="{{ php_self }}?mod=perm">{{ lang.options['uperm'] }}</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="card mb-4">
			<h5 class="card-header font-weight-light">{{ lang.options['other'] }}</h5>
			<div class="card-body">
				<ul class="list-unstyled mb-0">
					<li><a href="{{ php_self }}?mod=extras">{{ lang.options['extras'] }}</a></li>
					<li><a href="{{ php_self }}?mod=images">{{ lang.options['images'] }}</a></li>
					<li><a href="{{ php_self }}?mod=files">{{ lang.options['files'] }}</a></li>
					{% if (perm.templates) %}
						<li><a href="{{ php_self }}?mod=templates">{{ lang.options['templates'] }}</a></li>
					{% endif %}
				</ul>
			</div>
		</div>
	</div>
</div>
