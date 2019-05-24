{% for entry in data %}
	{{ entry }}
{% else %}
	<div class="ng-message">
		{{ lang['msgi_no_news'] }}
	</div>
{% endfor %}
{{ pagination }}