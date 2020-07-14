<div class="page-title">
	<h2>{{ lang.options['options_title'] }}</h2>
</div>

<div class="row">
	<div class="col-6">
		<h3 class="font-weight-light">{{ lang.options['news'] }}</h3>
		<ul class="list-unstyled">
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
	<div class="col-6">
		<h3 class="font-weight-light">{{ lang.options['system'] }}</h3>
		<ul class="list-unstyled">
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

<div class="row">
	<div class="col-6">
		<h3 class="font-weight-light">{{ lang.options['userman'] }}</h3>
		<ul class="list-unstyled">
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
	<div class="col-6">
		<h3 class="font-weight-light">{{ lang.options['other'] }}</h3>
		<ul class="list-unstyled">
			<li><a href="{{ php_self }}?mod=extras">{{ lang.options['extras'] }}</a></li>
			<li><a href="{{ php_self }}?mod=images">{{ lang.options['images'] }}</a></li>
			<li><a href="{{ php_self }}?mod=files">{{ lang.options['files'] }}</a></li>
			{% if (perm.templates) %}
				<li><a href="{{ php_self }}?mod=templates">{{ lang.options['templates'] }}</a></li>
			{% endif %}
		</ul>
	</div>
</div>
