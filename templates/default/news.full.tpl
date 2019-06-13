[TWIG]
<article class="full-post">
	<h1 class="title">{{ news.title }}</h1>
	<span class="meta">{{ news.date }} | {% if pluginIsActive('uprofile') %}
		<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}</span>
	<p>{{ news.short }}{{ news.full }}</p>
	{% if (news.flags.hasPagination) %}
		<div class="pagination">
			<ul>
				{{ news.pagination }}
			</ul>
		</div>
	{% endif %}
	<div class="post-full-footer">
		{% if pluginIsActive('tags') %}{% if (p.tags.flags.haveTags) %}
			<div class="post-full-tags">{{ lang.tags }}: {{ tags }}</div>{% endif %}{% endif %}
		<div class="post-full-meta">{{ lang.views }}
			: {{ news.views }} {% if pluginIsActive('comments') %}| {{ lang.com }}: {comments-num}{% endif %}</div>
		{% if pluginIsActive('rating') %}
			<div class="post-rating">{{ lang.rating }}: <span class="post-rating-inner">{{ plugin_rating }}</span>
			</div>{% endif %}
	</div>
</article>
{% if pluginIsActive('similar') %}{{ plugin_similar_tags }}{% endif %}
{% if pluginIsActive('comments') %}
	<div class="title">{{ lang.comments }} ({comments-num})</div>
	{{ plugin_comments }}
{% endif %}
[/TWIG]