<article>
	<a href="{{ news.url.full }}">{{ news.title|truncateHTML(70,'...') }}</a>
	<span>{{ news.author.name }}, {{ category }}</span>
</article>