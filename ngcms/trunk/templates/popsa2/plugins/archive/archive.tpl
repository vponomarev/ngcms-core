<ul class="plugin">
	{% for entry in entries %}<li><a href="{{entry.link}}">{{entry.title}} {% if (entry.counter) %}({{entry.cnt}}){% endif %}</a></li>{% endfor %}
</ul>