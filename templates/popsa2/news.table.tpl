{% for entry in data %}
	{{ entry }}
{% else %}
	<div class="full">
		<div class="pad20_f">
			<div class="msge">{{ lang['msgi_no_news'] }}</div>
		</div>
	</div>
{% endfor %}
{{ pagination }}