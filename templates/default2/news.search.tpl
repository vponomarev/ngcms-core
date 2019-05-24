[TWIG]
<div class="post">
	<div class="post-header">
		<div class="post-title"><a href="{{ news.url.full }}">{{ news.title }}</a></div>
		<div class="post-info"><span class="post-views">{{ news.views }}</span> {% if pluginIsActive('comments') %}
				<span class="post-comments">{comments-num}</span>{% endif %}</div>
	</div>
	<div class="post-meta">
		{{ lang.published }}: {% if pluginIsActive('uprofile') %}
		<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}
		, {{ news.date }} <span class="separator"></span> {{ lang.category }}: {{ category }}
	</div>
	<div class="post-text">
		<p>{{ news.short }}</p>
	</div>
	<div class="post-footer">
		<a class="btn" href="{{ news.url.full }}">{{ lang.more }}</a>
		{% if pluginIsActive('rating') %}{{ plugin_rating }}{% endif %}
	</div>
</div>
[/TWIG]