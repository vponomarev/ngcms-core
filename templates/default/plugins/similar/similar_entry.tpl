[TWIG]
<li class="article">
	<span class="article-img">
		{% if (news.embed.imgCount > 0) %}
			<img src="{{ news.embed.images[0] }}" width="315" height="161" />
		{% else %}
			<img src="{{ tpl_url }}/img/img-none.jpg" width="315" height="161" />
		{% endif %}
		<div class="article-cat"><a href="#">Категория</a></div>
	</span>
	<span class="article-title"><a href="{url}">{title}</a></span>
	<span class="article-meta"><span>{date}</span> | <span>{author}</span></span>
	<!--span class="article-text">
		<p>{text}</p>
	</span-->
</li>
[/TWIG]