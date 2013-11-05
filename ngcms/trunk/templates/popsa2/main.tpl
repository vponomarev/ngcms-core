[TWIG]
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{{ lang['langcode'] }}" lang="{{ lang['langcode'] }}" dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset={{ lang['encoding'] }}" />
		<meta http-equiv="content-language" content="{{ lang['langcode'] }}" />
		<meta name="generator" content="{{ what }} {{ version }}" />
		<meta name="document-state" content="dynamic" />
		{{ htmlvars }}
		<link href="{{ tpl_url }}/css/setka.css" rel="stylesheet" />
		<link href="{{ tpl_url }}/css/style.css" rel="stylesheet" />
		<link href="{{ tpl_url }}/css/btn/btn.css" rel="stylesheet" />
		<link rel="shortcut icon" href="{{ tpl_url }}/favicon.ico">
		{% if pluginIsActive('rss_export') %}<link href="{{ home }}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />{% endif %}
		<script type="text/javascript" src="{{ scriptLibrary }}/jquery-1.8.2.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/functions.js"></script>
		<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
		<title>{{ titles }}</title>
	</head>
	<body>
		<div id="loading-layer"><img src="{{ tpl_url }}/images/ajax-loader.gif" alt="�������" /></div>
		<!--������-->
		<div id="userpanel" class="hidden">
			{{ personal_menu }}
		</div>
		<div class="panel_line"></div>
		<!--/������-->
		<header>
			<!--����+����-+�����-->
			<div id="logo">
				<div class="in960">
					<!--�������-->
					<div class="k320" name="topper">
						<div class="pad_logo" id="logo_top"><a href="{{ home }}"><img src="{{ tpl_url }}/images/logo.png" ></a></div>
					</div>
					<!--/�������-->
					<!-- �������-->
					<div class="k400">
						<div class="pad_logo_f">
							<ul class="menu-h">
								<li><a href="{{ home }}">�������</a></li>
								<li><a href="#">� �����</a></li>
								<li><a href="#">��������</a></li>
							</ul>
						</div>
					</div>
					<!-- /�������-->
					<div class="k240">
						<a href="#userpanel" id="nolink" class="popup tooltip" rel="width:auto; height:auto;" title="{% if (global.flags.isLogged) %}������ �������{% else %}�����������{% endif %}"><div class="user_btn"></div></a>
						<a href="{{ home }}/rss.xml"><div class="rss"></div></a>
						<a href="http://twitter.com" target="_blank"><div class="twitter"></div></a>
						<div class="pad_logo_f">
							<!--�����-->
							{{ search_form }}
							<!--/�����-->
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<!--/����+����-+�����-->
			<!--����-->
			<div id="top">
				<div id="main_nav">
					<ul class="menu-h-d">
						<li><a href="#">� �����</a>
							<!--���������� ����-->
							<ul>
								<li><a href="#">������ �������</a>
									<!--���������� ���� 2 �������-->
									<ul>
										<li><a href="#">������ �������</a></li>
										<li><a href="#">������ �������</a></li>
										<li><a href="#">������ �������</a></li>
										<li><a href="#">������ �������</a></li>
									</ul>
									<!--/���������� ����  2 �������-->
								</li>
								<li><a href="#">������ �������</a></li>
								<li><a href="#">������ �������</a></li>
								<li><a href="#">������ �������</a></li>
							</ul>
							<!--/���������� ����-->
						</li>
						<li><a href="#">� ����</a></li>
						<li><a href="#">���������</a></li>
						<li><a href="#">�������</a></li>
						<li><a href="#">��������</a></li>
						<li><a href="#">�����</a>
							<!--���������� ����-->
							<ul>
								<li><a href="#">������</a></li>
								<li><a href="#">��������� 2012</a></li>
								<li><a href="#">������</a></li>
								<li><a href="#">������ �����</a></li>
							</ul>
							<!--/���������� ����-->
						</li>
						<li><a href="#">��������</a></li>
						<li><a href="#">���������</a></li>
					</ul>
				</div>
				<!--����� �� �������-->
				{% if isHandler('news:main') %}
				<div class="home_banner">
					<div class="pad_banner">
						<h1>h1 ��������� ��������� ������ �� ������� ��������</h1>
						<p class="big">� ���� ����� ����� ���������� ������ ��� ���������� ��� �� ������������. ����� �� ����!</p>
						<p>�������� ��� ��������� �� ������ � main.tpl</p>
					</div>
				</div> 
				{% endif %}
				<!--/����� �� �������-->
			</div>
			<!--/����-->
		</header>
		<!--�������-->
		<div id="content_i">
			<div class="in960">
				<div class="clear20"></div>
				<!--������ �������-->
				<sidebar>
					<div class="k320">
						<div class="pad_left_col">
							<!--Category-->
							<div class="green_b">
								<h3>���������</h3>
								<div class="l300_white"></div>
								<ul class="cat_b">
									<li><a href="{{ home }}">�������</a></li>
									{{ categories }}
									{% if pluginIsActive('voting') %}<li {% if isHandler('voting') %}class="active"{% endif %}><a href="/plugin/voting/">������</a></li>{% endif %}
									{% if pluginIsActive('sitemap') %}<li {% if isHandler('sitemap') %}class="active"{% endif %}><a href="/plugin/sitemap/">����� �����</a></li>{% endif %}
								</ul>
							</div>
							<div class="clear20"></div>
							<!--/Category-->
							<!--������ Basket-->
							{% if pluginIsActive('basket') %}
								{{ plugin_basket }}
							{% endif %}
							<!--/������ Basket-->
							<!--�������������� �������-->
							<div class="white_b">
								<h3>�������������� �������</h3>
								<div class="l300_green_blue"></div>
								<ul class="list_b">
									<li><a href="#">� �����</a></li>
									<li><a href="#">� ����</a></li>
									<li><a href="#">���������</a></li>
								</ul>
							</div>
							<div class="clear20"></div>
							<!--/�������������� �������-->
							<!--������ xnews-->
							<div class="white_b">
								<h3>����������</h3>
								<div class="l300_green_blue"></div>
								<ul class="plugin">
									{{ callPlugin('xnews.show', {'order' : 'views', 'count': '6', 'template' : 'xnews1'}) }}
								</ul>
							</div>
							<div class="clear20"></div>
							<div class="white_b">
								<h3>��������� �������</h3>
								<div class="l300_green_blue"></div>
								<ul class="plugin">
									{{ callPlugin('xnews.show', {'order' : 'last', 'count': '6', 'template' : 'xnews1'}) }}
								</ul>
							</div>
							<div class="clear20"></div>
							<!--/������ xnews-->
							<!--��������� + ����� + �����������-->
							<div class="white_b">
								<!-- TABS START -->
								<ul class="tabs fixed">
									{% if pluginIsActive('calendar') %}<li><a href="#tab1">���������</a></li>{% endif %}
									{% if pluginIsActive('archive') %}<li><a href="#tab2">�����</a></li>{% endif %}
									{% if pluginIsActive('voting') %}<li><a href="#tab3">�����������</a></li>{% endif %}
								</ul>
								<div class="clear"></div>
								{% if pluginIsActive('calendar') %}
								<div class="tab" id="tab1">
									<h3>���������</h3>
									<div class="l300_green_blue"></div>
									<div class="telo">
										{{ callPlugin('calendar.show') }}
									</div>
								</div>
								{% endif %}
								{% if pluginIsActive('archive') %}
								<div class="tab" id="tab2">
									<h3>����� ��������</h3>
									<div class="l300_green_blue"></div>
									<!-- ���� � ������ ����� 6 ������� �� � �������� ������� -->
									{{ callPlugin('archive.show', {'maxnum' : 12, 'counter' : 1, 'template': 'archive', 'cacheExpire': 60}) }}
								</div>
								{% endif %}
								{% if pluginIsActive('voting') %}
								<div class="tab" id="tab3">
									<h3>�����������</h3>
									<div class="l300_green_blue"></div>
									<div class="pad20">
										{{ voting }}
									</div>
								</div>
								{% endif %}
								<!-- TABS END -->
							</div>
							<div class="clear20"></div>
							<!--/��������� + ����� + �����������-->
							<!--��������� �������� + ���-->
							<!-- TABS START -->
							<div class="white_b">
								<ul class="tabs fixed">
									{% if pluginIsActive('lastcomments') %}<li><a href="#tab4">��������� �����������</a></li>{% endif %}
									{% if pluginIsActive('jchat') %}<li><a href="#tab5">�����</a></li>{% endif %}
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
							<!--/��������� �������� + ���-->
							<!--�������-->
							<div class="white_b">
								<h3>�������</h3>
								<div class="l300_green_blue"></div>
								<div class="pad20">
									<img src="{{ tpl_url }}/images/blank_pix.jpg" class="align_center" />
								</div>
							</div>
							<div class="clear20"></div>
							<!--/�������-->
							<!--������������ �������� � ������-->
							{% if pluginIsActive('switcher') %}
							<div class="white_b">
								<h3>����� �������</h3>
								<div class="l300_green_blue"></div>
								<div class="pad20">
									{{ switcher }}
								</div>
							</div>
							<div class="clear20"></div>
							{% endif %}
							<!--/������������ �������� � ������-->
							<!--tags-->
							{% if pluginIsActive('tags') %}{{ plugin_tags }}{% endif %}
							<!--/tags-->
						</div>
					</div>
				</sidebar>
				<!--/������ �������-->
				<!--mainblock-->
				<div class="k640">
					<!--������� ������-->
					{% if pluginIsActive('breadcrumbs') %}{{ breadcrumbs }}{% endif %}
					<!--/������� ������-->
					<section>
						{{ mainblock }}
					</section>
				</div>
				<!--/mainblock-->
				<div class="clear20"></div>
				<div class="clear"></div>
			</div>
		</div>
		<!--/�������-->
		<!--������-->
		<footer>
			<div id="ftr">
				<div class="in960">
					<div class="ftr_mid"></div>
					<!--���������--> 
					<div class="k320">
						<div class="pad20_f">
							<p>&copy; <a title="{{ home_title }}" href="{{ home }}">{{ home_title }}</a></p>
						</div>
					</div>
					<!--/���������-->
					<div class="k640"><a href="#logo_top" class="jump"><div class="go_top"></div></a>
					<div class="pad20_f">
						<p>���������, �� �������� ���� ���� ���������� ������������ �������, ���������� ���������� ��������� �������, � � �� �� ����� ��������������� ���������� ����������� ��� ������� ���� �������� ������.</p>
					</div>
				</div>
				<div class="clear"></div>
				<div class="k320">&nbsp;</div>
				<!--������ ������ � �������� �����-->
				<div class="k320">
					<div class="pad20_f">
						<p class="f12">SQL ��������: <b>{{ queries }}</b> | ��������� ��������: <b>{{ exectime }}</b> ��� | <b>{{ memPeakUsage }}</b> Mb&nbsp;</p>
					</div>
				</div>
				<!--/������ ������ � �������� �����-->
				<!--������ �� �������!!!!-->
				<div class="k320"> 
					<a href="http://rocketvip.ru" target="_blank"><img src="{{ tpl_url }}/images/rocketvip.png" class="right_s" alt="��������, ���������� � ������������ ������" /><font class="graphic">��������, ���������� � ������������ ������</font></a>
					&nbsp;
					<a href="http://ngcms.ru" target="_blank"><img src="{{ tpl_url }}/images/ngcms.png" class="right_s" alt="NGCMS - ���������� ������� ���������� ������" /><font class="graphic">NGCMS - ���������� ������� ���������� ������</font></a>
				</div>
				<!--/������ �� �������!!!!-->
				<div class="clear20"></div>
			</div>
		</footer>
		<!--/������-->
		[debug]{debug_queries}<br/>{debug_profiler}[/debug]
	</body>
	<script src="{{ tpl_url }}/js/easy.js"></script>
	<script src="{{ tpl_url }}/js/main.js"></script>
</html>
[/TWIG]