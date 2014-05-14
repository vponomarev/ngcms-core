[TWIG]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}" />
		<meta http-equiv="content-language" content="{{ lang['langcode'] }}" />
		<meta name="generator" content="{{ what }} {{ version }}" />
		<meta name="document-state" content="dynamic" />
		{{ htmlvars }}
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="{{ tpl_url }}/css/normalize.css">
		<link rel="stylesheet" href="{{ tpl_url }}/css/main.css">
		<link rel="stylesheet" href="{{ tpl_url }}/css/style.css">
		<link rel="stylesheet" href="{{ tpl_url }}/css/slider.css">
		<!--[if lt IE 9]>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="{{ tpl_url }}/css/ie7.css">
			<script src="{{ tpl_url }}/js/ie7.js"></script>
		<![endif]-->
		<!--[if lte IE 6]>
			<link rel="stylesheet" href="{{ tpl_url }}/css/ie6.css">
		<![endif]-->
		{% if pluginIsActive('rss_export') %}<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
		<script src="{{ tpl_url }}/js/jquery.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
		<title>{{ titles }}</title>
	</head>
	<body>
		<div id="loading-layer"><img src="{{ tpl_url }}/img/loading.gif" alt="" /></div>
		<div id="wrapper">
			<header id="header">
				<div id="logo">
					<a href="{{ home }}"><img src="{{ tpl_url }}/img/logo.jpg" alt=""></a>
				</div>
				{% if (global.flags.isLogged) %}
					{{ personal_menu }}
				{% else %}
					<div id="auth">
						<a href="/register/" class="auth-registration">������������������</a>
						<a href="#auth-modal" rel="modal" class="auth-login">�����</a>
					</div>
				{% endif %}
				<div class="clearfix"></div>
				<div id="menu" class="clearfix">
					<nav class="menu">
						<ul>
							<li><a href="{{ home }}">�������</a></li>
							<li><a href="#">�������</a></li>
							<li><a href="#">�����</a></li>
							<li><a href="#">������</a>
								<ul>
									<li><a href="#">���������� ������</a></li>
									<li><a href="#">�������</a></li>
									<li><a href="#">������</a></li>
								</ul>
							</li>
							<li><a href="#">����</a></li>
							<li><a href="#">�������</a></li>
							<li><a href="#">������</a></li>
							<li><a href="#">� ���</a></li>
						</ul>
					</nav>
					<div id="search">
						{{ search_form }}
					</div>
				</div>
			</header>
			<section id="container" class="clearfix">
				<div id="content-main">
					{% include "slider.tpl" %}
					{% if isHandler('news:main|news:by.category|news:by.month|news:by.day') %}
						<div class="articles full">
							<div class="articles-switch">
								<a href="#" id="articles-switch-1" class="articles-switch-1" onclick="javascript:save_articles_switch_one();"></a>
								<a href="#" id="articles-switch-2" class="articles-switch-2" onclick="javascript:save_articles_switch_two();"></a>
							</div>
							<div class="block-title">�������</div>
							<div class="clearfix"></div>
							{{ mainblock }}
						</div>
					{% else %}
						{{ mainblock }}
					{% endif %}
				</div>
				<aside id="sidebar">
					<div class="block popular-block">
						<div class="block-title">���������� ������</div>
						<ul class="tabs tabs-full" id="popular-news">
							<li class="active"><a href="#tab-1" data-transitional="fade">�� ����</a></li>
							<li><a href="#tab-2" data-transitional="fade">�� ������</a></li>
							<li><a href="#tab-3" data-transitional="fade">�� ��� �����</a></li>
						</ul>
						<div class="tab-content tab-bordered">
							<div class="tab-pane active" id="tab-1">
								{{ callPlugin('xnews.show', {'order' : 'last', 'count': '6', 'template' : 'xnews1'}) }}
							</div>
							<div class="tab-pane" id="tab-2">
								{{ callPlugin('xnews.show', {'order' : 'last', 'count': '6', 'template' : 'xnews1'}) }}
							</div>
							<div class="tab-pane" id="tab-3">
								{{ callPlugin('xnews.show', {'order' : 'last', 'count': '6', 'template' : 'xnews1'}) }}
							</div>
						</div>
					</div>
					{% if pluginIsActive('archive') %}
						<!-- ���� � ������ ����� 6 ������� �� � �������� ������� -->
						{{ callPlugin('archive.show', {'maxnum' : 12, 'counter' : 1, 'template': 'archive', 'cacheExpire': 60}) }}
					{% endif %}
					{% if pluginIsActive('calendar') %}
						{{ callPlugin('calendar.show') }}
					{% endif %}
					{% if pluginIsActive('voting') %}
						{{ voting }}
					{% endif %}
					{% if pluginIsActive('tags') %}
						{{ plugin_tags }}
					{% endif %}
					{% if pluginIsActive('switcher') %}
						{{ switcher }}
					{% endif %}
					{% if pluginIsActive('top_active_users') %}
						{{ callPlugin('top_active_users.show', {'number' : 12, 'mode' : 'news', 'template': 'top_active_users', 'cacheExpire': 60}) }}
					{% endif %}
				</aside>
			</section>
			<footer id="footer">
				<div class="copyright">
					<p>&copy; <a title="{{ home_title }}" href="{{ home }}">{{ home_title }}</a> Powered by <a title="Next Generation CMS" target="_blank" href="http://ngcms.ru/">NG CMS</a> 2007 � {{ now|date("Y") }}. <br />SQL ��������: <b>{{ queries }}</b> | ��������� ��������: <b>{{ exectime }}</b> ��� | <b>{{ memPeakUsage }}</b> Mb&nbsp;</p>
					<p>��� ����� ��������. <br /> ����������� ���������� ��� ������������ �<br /> �������������� ������ ���������!</p>
				</div>
				<div class="poweredby">
					<p>������ ����� - <a href="http://cargocollective.com/Qesoart">Qesoart</a></p>
					<p>��� ���� ���������� ������� �������<br /> �������� �� <a href="#">examplesite@m.ru</a></p>
				</div>
			</footer>
		</div>
		{% if not (global.flags.isLogged) %}{{ personal_menu }}{% endif %}
		{% if isHandler('news:news') %}<script src="{{ tpl_url }}/js/jquery.custom-scrollbar.min.js"></script>{% endif %}
		<script src="{{ tpl_url }}/js/slider.js"></script>
		<script src="{{ tpl_url }}/js/script.js"></script>
	</body>
</html>
[debug]
{debug_queries}<br/>{debug_profiler}
[/debug]
[/TWIG]