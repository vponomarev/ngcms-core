[TWIG]
<!--Короткая новость-->
<article>
	<div class="short">
		<header><a href="{{ news.url.full }}"><h2 class="green_l">{{ news.title }}</h2></a></header>
		<div class="telo">
			{% if (news.flags.isUpdated) %}
				<small class="muted">{{ lang['updated'] }} {{ news.update }}</small>{% endif %}
			<p>{{ news.short }}</p>
			<div class="clear"></div>
			<!--Basket-->
			{% if pluginIsActive('basket') %}
				<div class="clear20"></div>
				<div class="btn-group">
					[xfield_price]<span class="btn"><strong class="green_t">[xvalue_price]</strong> <em>рублей</em></span>[/xfield_price]
					[basket]<a href="#" class="btn btn-success" onclick="rpcBasketRequest('plugin.basket.manage', {'action': 'add', 'ds':1,'id':{news-id},'count':1}); return false;">Купить!</a>[/basket]
				</div>
				<div class="clear20"></div>
			{% endif %}
			<!--/Basket-->
		</div>
		<!--Информация-->
		<div class="meta_b">
			<ul class="meta">
				<li class="date">{{ news.date }}</li>
				<li class="author">{% if pluginIsActive('uprofile') %}
					<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}
				</li>
				<li class="view">{{ news.views }}</li>
				{% if pluginIsActive('comments') %}
					<li class="comm1"><a href="{{ news.url.full }}#comments">{comments-num}</a></li>{% endif %}
				{% if (news.flags.canEdit) %}<a href="{{ news.url.edit }}">
						<li class="edit_z"></li></a>{% endif %}
				<li style="float: right;">
					<div class="btn-group"><a href="{{ news.url.full }}" class="btn btn-primary">Подробнее &rarr;</a>
					</div>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		<!--/Информация-->
	</div>
</article>
<!--/Короткая новость-->
[/TWIG]