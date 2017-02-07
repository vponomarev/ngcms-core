<ul>
	{% for entry in entries %}
		{% if (loop.index <= 6) %}
			<li>
				<a href="{{ entry.link }}">{{ entry.title }} {% if (entry.counter) %}({{ entry.cnt }}){% endif %}</a>
			</li>
		{% elseif (loop.index > 6) %}
			<li class="showhide">
				<a href="{{ entry.link }}">{{ entry.title }} {% if (entry.counter) %}({{ entry.cnt }}){% endif %}</a>
			</li>
		{% endif %}
	{% endfor %}
</ul>
{% if (entries|length >=7) %}<a style="cursor: pointer;" id="show_all_archive" class="more">Полный архив</a>{% endif %}