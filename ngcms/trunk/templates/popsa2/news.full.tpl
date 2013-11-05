[TWIG]
<!--������ �������-->
<article>
	<div class="full">
		<header><h1>{{ news.title }} {% if pluginIsActive('bookmarks') %}&nbsp; {{ plugin_bookmarks_news }}{% endif %}</h1></header>
		<div class="telo">
			{% if (news.flags.isUpdated) %}<small class="muted">{{ lang['updated'] }} {{ news.update }}</small>{% endif %}
			<p>{{ news.short }}{{ news.full }}</p>
			{% if (news.flags.hasPagination) %}<div class="center">{{ news.pagination }}</div>{% endif %}
			<div class="clear20"></div>
			<!--Basket-->
			{% if pluginIsActive('basket') %}
				<div class="clear20"></div>
				<div class="btn-group">
					[xfield_price]<span class="btn"><strong class="green_t">[xvalue_price]</strong> <em>������</em></span>[/xfield_price]
					[basket]<a href="#" class="btn btn-success" onclick="rpcBasketRequest('plugin.basket.manage', {'action': 'add', 'ds':1,'id':{news-id},'count':1}); return false;">������!</a>[/basket]
				</div>
				<div class="clear20"></div>
			{% endif %}
			<!--/Basket-->
			{% if pluginIsActive('tags') %}{% if (p.tags.flags.haveTags) %}<b>�����:</b> {{ tags }}{% endif %}{% endif %}
			{% if pluginIsActive('complain') %}{{ plugin_complain }}{% endif %}
			<div class="clear20"></div>
			{% if not (global.flags.isLogged) %}
			<div class="msgi"><p>��������� ����������, �� ����� �� ���� ��� �������������������� ������������. �� ����������� ��� <a href="/register/"><b>������������������</b></a> <a href="/login/"><b>���� �����</b></a> �� ���� ��� ����� ������.</p>
				{% if pluginIsActive('auth_loginza') %}
					<a href="http://loginza.ru/api/widget?token_url={{ home }}" class="loginza">
						<img src="/engine/plugins/auth_loginza/tpl/img/sign_in_button_gray.gif" alt="����� ����� loginza"/>
					</a>
				{% endif %}
			</div>
			{% endif %}
		</div>
		<!--����������-->
		<div class="pad20_f">
			<div class="meta_b">
				<ul class="meta">
					<li class="date">{{ news.date }}</li>
					<li class="author">{% if pluginIsActive('uprofile') %}<a href="{{ news.author.url }}">{% endif %}{{ news.author.name }}{% if pluginIsActive('uprofile') %}</a>{% endif %}</li>
					<li class="view">{{ news.views }}</li>
					{% if (news.flags.canEdit) %}<a href="{{ news.url.edit }}"><li class="edit_z"></li></a>{% endif %}
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<!--/����������-->
	</div>
</article>
<!--/������ �������-->
{% if pluginIsActive('neighboring_news') %}{{ neighboring_news }}{% endif %}
<!--�������-->
{% if pluginIsActive('comments') %}{{ plugin_comments }}{% endif %}
<!--/�������-->
[/TWIG]