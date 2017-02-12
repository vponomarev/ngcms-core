[TWIG]
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
<head>
	<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}"/>
	<meta http-equiv="content-language" content="{{ lang['langcode'] }}"/>
	<meta name="generator" content="{{ what }} {{ version }}"/>
	<meta name="document-state" content="dynamic"/>
	{{ htmlvars }}
	<link href="{{ tpl_url }}/css/setka.css" rel="stylesheet"/>
	<link href="{{ tpl_url }}/css/style.css" rel="stylesheet"/>
	<link href="{{ tpl_url }}/css/btn/btn.css" rel="stylesheet"/>
	<link rel="shortcut icon" href="{{ tpl_url }}/favicon.ico">
	{% if pluginIsActive('rss_export') %}
		<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
	<script type="text/javascript" src="{{ scriptLibrary }}/jq/jquery.js"></script>
	<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
	<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
	<title>{{ titles }}</title>
</head>
<body>
<div id="loading-layer"><img src="{{ tpl_url }}/images/ajax-loader.gif" alt="Гружусь"/></div>
<!--Панель-->
<div id="userpanel" class="hidden">
	{{ personal_menu }}
</div>
<div class="panel_line"></div>
<!--/Панель-->
<header>
	<!--Лого+меню-+поиск-->
	<div id="logo">
		<div class="in960">
			<!--Логотип-->
			<div class="k320" name="topper">
				<div class="pad_logo" id="logo_top"><a href="{{ home }}"><img src="{{ tpl_url }}/images/logo.png"></a>
				</div>
			</div>
			<!--/Логотип-->
			<!-- Менюшка-->
			<div class="k400">
				<div class="pad_logo_f">
					<ul class="menu-h">
						<li><a href="{{ home }}">Главная</a></li>
						<li><a href="#">О сайте</a></li>
						<li><a href="#">Контакты</a></li>
					</ul>
				</div>
			</div>
			<!-- /Менюшка-->
			<div class="k240">
				<a href="#userpanel" id="nolink" class="popup tooltip" rel="width:auto; height:auto;" title="{% if (global.flags.isLogged) %}Личный кабинет{% else %}Авторизация{% endif %}">
					<div class="user_btn"></div>
				</a>
				<a href="{{ home }}/rss.xml">
					<div class="rss"></div>
				</a>
				<a href="http://twitter.com" target="_blank">
					<div class="twitter"></div>
				</a>
				<div class="pad_logo_f">
					<!--Поиск-->
					{{ search_form }}
					<!--/Поиск-->
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<!--/Лого+меню-+поиск-->
	<!--Меню-->
	<div id="top">
		<div id="main_nav">
			<ul class="menu-h-d">
				<li><a href="#">О САЙТЕ</a>
					<!--Выпадающее меню-->
					<ul>
						<li><a href="#">Первый уровень</a>
							<!--Выпадающее меню 2 уровень-->
							<ul>
								<li><a href="#">Второй уровень</a></li>
								<li><a href="#">Второй уровень</a></li>
								<li><a href="#">Второй уровень</a></li>
								<li><a href="#">Второй уровень</a></li>
							</ul>
							<!--/Выпадающее меню  2 уровень-->
						</li>
						<li><a href="#">Первый уровень</a></li>
						<li><a href="#">Первый уровень</a></li>
						<li><a href="#">Первый уровень</a></li>
					</ul>
					<!--/Выпадающее меню-->
				</li>
				<li><a href="#">В МИРЕ</a></li>
				<li><a href="#">ЭКОНОМИКА</a></li>
				<li><a href="#">РЕЛИГИЯ</a></li>
				<li><a href="#">КРИМИНАЛ</a></li>
				<li><a href="#">СПОРТ</a>
					<!--Выпадающее меню-->
					<ul>
						<li><a href="#">Футбол</a></li>
						<li><a href="#">Олимпиада 2012</a></li>
						<li><a href="#">Хоккей</a></li>
						<li><a href="#">Бузидо шуидо</a></li>
					</ul>
					<!--/Выпадающее меню-->
				</li>
				<li><a href="#">КУЛЬТУРА</a></li>
				<li><a href="#">ИНОПРЕССА</a></li>
			</ul>
		</div>
		<!--Шапка на главной-->
		{% if isHandler('news:main') %}
			<div class="home_banner">
				<div class="pad_banner">
					<h1>Наша DEMO страница</h1>
				</div>
			</div>
		{% endif %}
		<!--/Шапка на главной-->
	</div>
	<!--/Меню-->
</header>
<!--Контент-->
<div id="content_i">
	<div class="in960">
		<div class="clear20"></div>
		<!--правая колонка-->
		<sidebar>
			<div class="k320">
				<div class="pad_left_col">
					<!--Category-->
					<div class="green_b">
						<h3>Навигация</h3>
						<div class="l300_white"></div>
						<ul class="cat_b">
							<li><a href="{{ home }}">Главная</a></li>
							{{ categories }}
							{% if pluginIsActive('voting') %}
								<li {% if isHandler('voting') %}class="active"{% endif %}><a href="/plugin/voting/">Опросы</a>
								</li>{% endif %}
							{% if pluginIsActive('sitemap') %}
								<li {% if isHandler('sitemap') %}class="active"{% endif %}><a href="/plugin/sitemap/">Карта
									сайта</a></li>{% endif %}
						</ul>
					</div>
					<div class="clear20"></div>
					<!--/Category-->
					<!--Плагин Basket-->
					{% if pluginIsActive('basket') %}
						{{ plugin_basket }}
					{% endif %}
					<!--/Плагин Basket-->
					<!--Второстепенная менюшка-->
					<div class="white_b">
						<h3>Второстепенная менюшка</h3>
						<div class="l300_green_blue"></div>
						<ul class="list_b">
							<li><a href="#">О сайте</a></li>
							<li><a href="#">В мире</a></li>
							<li><a href="#">Экономика</a></li>
						</ul>
					</div>
					<div class="clear20"></div>
					<!--/Второстепенная менюшка-->
					<!--Плагин xnews-->
					<div class="white_b">
						<h3>Популярные</h3>
						<div class="l300_green_blue"></div>
						<ul class="plugin">
							{{ callPlugin('xnews.show', {'order' : 'views', 'count': '6', 'template' : 'xnews1'}) }}
						</ul>
					</div>
					<div class="clear20"></div>
					<div class="white_b">
						<h3>Последние новости</h3>
						<div class="l300_green_blue"></div>
						<ul class="plugin">
							{{ callPlugin('xnews.show', {'order' : 'last', 'count': '6', 'template' : 'xnews1'}) }}
						</ul>
					</div>
					<div class="clear20"></div>
					<!--/Плагин xnews-->
					<!--Календарь + Архив + Голосовалка-->
					<div class="white_b">
						<!-- TABS START -->
						<ul class="tabs fixed">
							{% if pluginIsActive('calendar') %}
								<li><a href="#tab1">Календарь</a></li>{% endif %}
							{% if pluginIsActive('archive') %}
								<li><a href="#tab2">Архив</a></li>{% endif %}
							{% if pluginIsActive('voting') %}
								<li><a href="#tab3">Голосование</a></li>{% endif %}
						</ul>
						<div class="clear"></div>
						{% if pluginIsActive('calendar') %}
							<div class="tab" id="tab1">
								<h3>Календарь</h3>
								<div class="l300_green_blue"></div>
								<div class="telo">
									{{ callPlugin('calendar.show', {}) }}
								</div>
							</div>
						{% endif %}
						{% if pluginIsActive('archive') %}
							<div class="tab" id="tab2">
								<h3>Архив новостей</h3>
								<div class="l300_green_blue"></div>
								<!-- Если в архиве более 6 месяцев то с работает спойлер -->
								{{ callPlugin('archive.show', {'maxnum' : 12, 'counter' : 1, 'template': 'archive', 'cacheExpire': 60}) }}
							</div>
						{% endif %}
						{% if pluginIsActive('voting') %}
							<div class="tab" id="tab3">
								<h3>Голосование</h3>
								<div class="l300_green_blue"></div>
								<div class="pad20">
									{{ voting }}
								</div>
							</div>
						{% endif %}
						<!-- TABS END -->
					</div>
					<div class="clear20"></div>
					<!--/Календарь + Архив + Голосовалка-->
					<!--Последние комменты + Чат-->
					<!-- TABS START -->
					<div class="white_b">
						<ul class="tabs fixed">
							{% if pluginIsActive('lastcomments') %}
								<li><a href="#tab4">Последние комментарии</a></li>{% endif %}
							{% if pluginIsActive('jchat') %}
								<li><a href="#tab5">Чатик</a></li>{% endif %}
						</ul>
						<div class="clear"></div>
						{% if pluginIsActive('lastcomments') %}
							<div class="tab" id="tab4">
								{{ plugin_lastcomments }}
							</div>
						{% endif %}
						{% if pluginIsActive('jchat') %}
							<div class="tab" id="tab5">
								{{ plugin_jchat }}
							</div>
						{% endif %}
						<!-- TABS END -->
					</div>
					<div class="clear20"></div>
					<!--/Последние комменты + Чат-->
					<!--Реклама-->
					<div class="white_b">
						<h3>Реклама</h3>
						<div class="l300_green_blue"></div>
						<div class="pad20">
							<img src="{{ tpl_url }}/images/blank_pix.jpg" class="align_center"/>
						</div>
					</div>
					<div class="clear20"></div>
					<!--/Реклама-->
					<!--Переключение шаблонов и языков-->
					{% if pluginIsActive('switcher') %}
						<div class="white_b">
							<h3>Выбор профиля</h3>
							<div class="l300_green_blue"></div>
							<div class="pad20">
								{{ switcher }}
							</div>
						</div>
						<div class="clear20"></div>
					{% endif %}
					<!--/Переключение шаблонов и языков-->
					<!--tags-->
					{% if pluginIsActive('tags') %}{{ plugin_tags }}{% endif %}
					<!--/tags-->
				</div>
			</div>
		</sidebar>
		<!--/правая колонка-->
		<!--mainblock-->
		<div class="k640">
			<!--Хлебные крошки-->
			{% if pluginIsActive('breadcrumbs') %}{{ breadcrumbs }}{% endif %}
			<!--/Хлебные крошки-->
			<section>
				{{ mainblock }}
			</section>
		</div>
		<!--/mainblock-->
		<div class="clear20"></div>
		<div class="clear"></div>
	</div>
</div>
<!--/Контент-->
<!--Подвал-->
<footer>
	<div id="ftr">
		<div class="in960">
			<div class="ftr_mid"></div>
			<!--Копирайты-->
			<div class="k320">
				<div class="pad20_f">
					<p>&copy; <a title="{{ home_title }}" href="{{ home }}">{{ home_title }}</a></p>
				</div>
			</div>
			<!--/Копирайты-->
			<div class="k640"><a href="#logo_top" class="jump">
					<div class="go_top"></div>
				</a>
				<div class="pad20_f">
					<p>Эвтектика, по которому один блок опускается относительно другого, интенсивно прекращает цокольный
						генезис, и в то же время устанавливается достаточно приподнятый над уровнем моря коренной
						цоколь.</p>
				</div>
			</div>
			<div class="clear"></div>
			<div class="k320">&nbsp;</div>
			<!--Всякая нужная и ненужная хрень-->
			<div class="k320">
				<div class="pad20_f">
					<p class="f12">SQL запросов: <b>{{ queries }}</b> | Генерация страницы: <b>{{ exectime }}</b> сек |
						<b>{{ memPeakUsage }}</b> Mb&nbsp;</p>
				</div>
			</div>
			<!--/Всякая нужная и ненужная хрень-->
			<!--Ссылки не убирать!!!!-->
			<div class="k320">
				<a href="http://rocketvip.ru" target="_blank"><img src="{{ tpl_url }}/images/rocketvip.png" class="right_s" alt="Создание, разработка и изготовление сайтов"/><font class="graphic">Создание,
						разработка и изготовление сайтов</font></a>
				&nbsp;
				<a href="http://ngcms.ru" target="_blank"><img src="{{ tpl_url }}/images/ngcms.png" class="right_s" alt="NGCMS - бесплатная система управления сайтом"/><font class="graphic">NGCMS
						- бесплатная система управления сайтом</font></a>
			</div>
			<!--/Ссылки не убирать!!!!-->
			<div class="clear20"></div>
		</div>
</footer>
<!--/Подвал-->
[debug]{debug_queries}<br/>{debug_profiler}[/debug]
</body>
<script src="{{ tpl_url }}/js/easy.js"></script>
<script src="{{ tpl_url }}/js/main.js"></script>
</html>
[/TWIG]