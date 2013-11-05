[TWIG]
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}" />
		<meta http-equiv="content-language" content="{{ lang['langcode'] }}" />
		<meta name="generator" content="{{ what }} {{ version }}" />
		<meta name="document-state" content="dynamic" />
		{{ htmlvars }}
		<link href="{{ tpl_url }}/css/style.css" type="text/css" rel="stylesheet">
		<!--[if IE 7]><link href="{{ tpl_url }}/css/ie8.css" type="text/css" rel="stylesheet"><![endif]-->
		{% if pluginIsActive('rss_export') %}<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
		<script src="{{ tpl_url }}/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
		<title>{{ titles }}</title>
	</head>
	<body>
		<div id="loading-layer" style="display:none;"><img src="{{ tpl_url }}/img/loading.gif" alt="" /></div>
		<div id="wrapper">
			<div id="header">
				<div id="menu">
					<ul>
						<li><a href="{{ home }}">�������</a></li>
						<li><a href="#">�����</a></li>
						<li><a href="#">� �����</a></li>
						<li><a href="#">��������</a></li>
						<li><a href="#">����������</a></li>
						<li><a href="#">������</a>
							<ul>
								<li><a href="#">����������� ������</a></li>
								<li><a href="#">����������� ������</a></li>
								<li><a href="#">����������� ������</a></li>
							</ul>
						</li>
						<li><a href="#">������ 2</a>
							<ul>
								<li><a href="#">����������� ������</a></li>
								<li><a href="#">����������� ������</a></li>
								<li><a href="#">����������� ������</a></li>
							</ul>
						</li>
						{% if pluginIsActive('nsm') %}<li><a href="/plugin/nsm/" class="auth-add-news">�������� �������</a></li>{% endif %}
					</ul>
				</div>
				<div id="social">
					<a href="#" class="rss"></a>
					<a href="#" class="vk"></a>
					<a href="#" class="twitter"></a>
				</div>
				<div class="clr"></div>
				<div id="logo">
					<a href="{{ home }}"><img src="{{ tpl_url }}/img/logo.png" alt=""></a>
				</div>
				<div id="right-control">
					<div id="search">
						{{ search_form }}
					</div>
					{{ personal_menu }}
				</div>
			</div><!--#HEADER END-->
			<div class="clr"></div>
			<div id="container">
				<div class="bg"></div>
				<div id="mainblock">
					{{ mainblock }}
				</div>
				<div id="sidebar">
					{% if pluginIsActive('xnews') %}
					<div class="block">
						<div class="block-title last-news">
							���������� ������
							<span>�� ��������� �����</span>
							<div class="icon-last-news"></div>
						</div>
						<div class="block-menu">
							{{ callPlugin('xnews.show', {'order' : 'viewed', 'count': '6', 'template' : 'xnews1'}) }}
						</div>
					</div>
					{% endif %}
					{% if pluginIsActive('voting') %}
					<div class="block">
						<div class="block-title polls">
							������ �������������
							<span>��� ����� ���� ������!</span>
							<div class="icon-polls"></div>
						</div>
						<div class="block-polls">
							{{ voting }}
						</div>
					</div>
					{% endif %}
					{% if pluginIsActive('switcher') %}
					<div class="block">
						<div class="block-title">
							����� �������:
							<span>������������ �������� � ������</span>
						</div>
						<div class="block">
							{{ switcher }}
						</div>
					</div>
					{% endif %}
				</div>
				<div class="clr"></div>
				<div id="sidebar-2">
					{% if pluginIsActive('archive') %}
					<div class="block archiv">
						<div class="block-title">
							<span class="icon icon-archiv"></span> �����:
						</div>
						<div class="block-archiv">
							<!-- ���� � ������ ����� 6 ������� �� � �������� ������� -->
							{{ callPlugin('archive.show', {'maxnum' : 12, 'counter' : 1, 'template': 'archive', 'cacheExpire': 60}) }}
						</div>
					</div>
					{% endif %}
					{% if pluginIsActive('tags') %}
					<div class="block tags">
						<div class="block-title">
							<span class="icon icon-tags"></span> ������ �����:
						</div>
						<div class="block-tags">
							{{ plugin_tags }}
						</div>
					</div>
					{% endif %}
					{% if pluginIsActive('calendar') %}
					<div class="block calendar">
						<div class="block-title">
							<span class="icon icon-calendar"></span> ���������:
						</div>
						<div class="block-calendar">
							{{ callPlugin('calendar.show') }}
						</div>
					</div>
					{% endif %}
				</div>
				<div id="sidebar-3">
					<div class="block stats">
						<div class="block-title">
							<span class="icon-stats"></span> ����������:
						</div>
						<div class="block-stats">
							{{ k_online }}
						</div>
					</div>
				</div>
			</div><!--#CONTAINER END-->
			<div class="clr"></div>
			<div id="footer">
				<div class="copyright">
					<em>Powered by <a title="Next Generation CMS" target="_blank" href="http://ngcms.ru/">NG CMS</a> 2007 � {{ now|date("Y") }}. | SQL ��������: <b>{{ queries }}</b> | ��������� ��������: <b>{{ exectime }}</b> ��� | <b>{{ memPeakUsage }}</b> Mb&nbsp;</em><br>
					� <a href="{{ home }}">{{ home_title }}</a>  
				</div>
				<div class="by">
					<a href="http://bymel.ru/" target="_blank"><img src="{{ tpl_url }}/img/by.png" alt=""></a>
				</div>
			</div><!--#FOOTER END-->
		</div><!--#WRAPPER END-->
		<script src="{{ tpl_url }}/js/script.js"></script>
	</body>
</html>
[debug]
{debug_queries}<br/>{debug_profiler}
[/debug]
[/TWIG]