[TWIG]
<article class="full-post">
	<h1 class="title">{{ news.title }}</h1>
	<span class="meta">{{ news.date }} | {% if pluginIsActive('uprofile') %}<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}</span>
	<p>{{ news.short }}{{ news.full }}</p>
	{% if (news.flags.hasPagination) %}
		<div class="pagination">
			<ul>
				{{ news.pagination }}
			</ul>
		</div>
	{% endif %}
	<div class="post-full-footer">
		{% if pluginIsActive('tags') %}{% if (p.tags.flags.haveTags) %}<div class="post-full-tags">Теги: {{ tags }}</div>{% endif %}{% endif %}
		<div class="post-full-meta">Просмотров: {{ news.views }} {% if pluginIsActive('comments') %}| Комментариев: {comments-num}{% endif %}</div>
		{% if pluginIsActive('rating') %}<div class="post-rating">Рейтинг: <span class="post-rating-inner">{{ plugin_rating }}</span></div>{% endif %}
	</div>
</article>
{% if pluginIsActive('similar') %}{{ plugin_similar_tags }}{% endif %}
{% if pluginIsActive('comments') %}
	<div class="title">Комментарии ({comments-num})</div>
	{{ plugin_comments }}
{% endif %}
[/TWIG]