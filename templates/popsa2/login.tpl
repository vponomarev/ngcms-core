<form name="login" method="post" action="{{ form_action }}">
	<input type="hidden" name="redirect" value="{{ redirect }}"/>
	<div class="full">
		<header><h1>{{ lang['login.title'] }}</h1></header>
		<div class="telo">
			{% if (flags.error) %}
			<div class="msge">{{ lang['login.error'] }}</div>
			{% endif %}
			{% if (flags.banned) %}
			<div class="msge">{{ lang['login.banned'] }}</div>
			{% endif %}
			{% if (flags.need_activate) %}
			<div class="msgi">{{ lang['login.need_activate'] }}</div>
			{% endif %}
			<div class="input"><label>{{ lang['login.name'] }}:</label><input type="text" name="username"/></div>
			<div class="input"><label>{{ lang['login.password'] }}:</label><input type="password" name="password"/></div>
			<div><input class="btn btn-primary btn-large" type="submit" value="{{ lang['login.submit'] }}"/></div>
		</div>
	</div>
</form>
