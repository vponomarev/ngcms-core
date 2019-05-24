{% for entry in data %}
	{{ entry }}
{% else %}
	<div class="alert alert-info">
		<strong>{{ lang.notifyWindowInfo }}</strong>
		{{ lang['msgi_no_news'] }}
	</div>
{% endfor %}
{{ pagination }}