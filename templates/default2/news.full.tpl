[TWIG]
<div class="post">
	<div class="post-header">
		<div class="post-title">{{ news.title }}</div>
		<div class="post-info"><span class="post-views">{{ news.views }}</span> {% if pluginIsActive('comments') %}
				<span class="post-comments">{comments-num}</span>{% endif %}</div>
	</div>
	<div class="post-meta">
		{{ lang.published }}: {% if pluginIsActive('uprofile') %}
		<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}
		, {{ news.date }} <span class="separator"></span> {{ lang.category }}: {{ category }}
	</div>
	<div class="post-text">
		<p>{{ news.short }}{{ news.full }}</p>
		{% if (news.flags.hasPagination) %}
			<div class="paginator" style="margin-bottom: 25px;">
				<ul>
					{{ news.pagination }}
				</ul>
			</div>
		{% endif %}
	</div>
	<div class="post-footer">
		{% if (news.flags.canEdit) %}<a class="btn" href="{{ news.url.edit }}">{{ lang.editnews }}</a>{% endif %}
		{% if pluginIsActive('rating') %}{{ plugin_rating }}{% endif %}
		{% if pluginIsActive('tags') %}{% if (p.tags.flags.haveTags) %}
			<div class="tags">{{ lang.tags }}: {{ tags }}</div>{% endif %}{% endif %}
	</div>
</div>
{% if pluginIsActive('comments') %}{{ plugin_comments }}{% endif %}
[/TWIG]