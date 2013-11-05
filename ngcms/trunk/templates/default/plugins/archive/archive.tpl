<div class="block archive-block">
	<div class="block-title">Архив новостей</div>
	<ul>
		{% for entry in entries %}
			{% if (loop.index <= 6) %}
				<li {% if (loop.first) %}class="active"{% endif %}>
					<a href="{{entry.link}}">{{entry.title}} {% if (entry.counter) %}({{entry.cnt}}){% endif %}</a>
				</li>
			{% elseif (loop.index > 6)  %}
				<li class="showhide">
					<a href="{{entry.link}}">{{entry.title}} {% if (entry.counter) %}({{entry.cnt}}){% endif %}</a>
				</li>
			{% endif %}
		{% endfor %}
	</ul>
	{% if (entries|length >=7) %}<a style="cursor: pointer;" class="pull-right" id="show_all_archive">Показать весь архив</a>{% endif %}
</div>