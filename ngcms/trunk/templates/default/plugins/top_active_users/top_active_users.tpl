<div class="block popular-authors-block">
	<div class="block-title">Популярные авторы</div>
	<ul>
		{% for entry in entries %}<li>{{ entry.name }} <a href="{{ entry.link }}" class="pull-right">{{ entry.news }}</a></li>{% endfor %}
	</ul>
</div>