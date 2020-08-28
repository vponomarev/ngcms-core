{% if (global.flags.isLogged) %}
	<h3>{{ lang['hello'] }}, {{ global.user.name }}</h3>
	<ul class="u_panel">
		[if-have-perm]
		<li class="admin"><a href="{{ admin_url }}" target="_blank">{{ lang['adminpanel'] }}</a></li>
		<li class="addnew"><a href="{{ addnews_link }}">{{ lang['addnews'] }}</a></li>
		[/if-have-perm]
		{% if pluginIsActive('pm') %}
			<li class="pm_n"><a href="{{ p.pm.link }}"><span class="bg">ЛС ({{ p.pm.pm_unread }} / {{ p.pm.pm_all }}
					)</span></a></li>{% endif %}
		{% if pluginIsActive('uprofile') %}
			<li class="profile"><a href="{{ profile_link }}">{{ lang['myprofile'] }}</a></li>{% endif %}
		{% if pluginIsActive('bookmarks') %}
			<li class="book_n"><a href="/plugin/bookmarks/">Мои закладки</a></li>{% endif %}
		<li class="logout"><a href="{{ logout_link }}">{{ lang['logout'] }}</a></li>
	</ul>
{% else %}
	<script language="javascript">
		var set_login = 0;
		var set_pass = 0;
	</script>
	{% if pluginIsActive('auth_loginza') %}
		<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>{% endif %}
	<h3>Войти на сайт</h3>
	<form name="login" method="post" action="{{ form_action }}" id="login">
		<input type="hidden" name="redirect" value="{{ redirect }}"/>
		<div class="input">
			<input onfocus="if (!set_login){set_login=1;this.value='';}" value="{{ lang['name'] }}" class="mw_login_form" type="text" name="username" maxlength="60"/>
		</div>
		<div class="input">
			<input onfocus="if(!set_pass){set_pass=1;this.value='';}" value="{{ lang['password'] }}" class="mw_login_form" type="password" name="password" maxlength="20"/>
		</div>
		<input type="hidden" name="redirect" value="{{ redirect }}"/>
		<input type="submit" value="{{ lang['login'] }}" name="Login" class="btn btn-primary btn-large"/>
		<div class="clear10"></div>
		<ul class="meta">
			<li class="register_n"><a href="{{ reg_link }}" title="Регистрация на сайте!">Регистрация</a></li>
			<li class="lost_n"><a href="{{ lost_link }}" title="Забыли пароль?">Забыли пароль?</a></li>
		</ul>
		<div class="clear10"></div>
	</form>
	{% if pluginIsActive('auth_loginza') %}
		<a href="http://loginza.ru/api/widget?token_url={home}" class="loginza">
			<img src="/engine/plugins/auth_loginza/tpl/img/sign_in_button_gray.gif" alt="Войти через loginza"/>
		</a>
	{% endif %}
{% endif %}