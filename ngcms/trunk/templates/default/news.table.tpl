{% for entry in data %}
{{ entry }}
{% else %}
<div class="alert alert-info">
	<strong>����������</strong>
	{{ lang['msgi_no_news'] }}
</div>
{% endfor %}
{{ pagination }}