{% if (flags.error) %}
	<div class="alert alert-error">{{ lang['login.error'] }}</div>
{% endif %}

{% if (flags.banned) %}
	<div class="alert alert-info">{{ lang['login.banned'] }}</div>
{% endif %}

{% if (flags.need_activate) %}
	<div class="alert alert-info">{{ lang['login.need_activate'] }}</div>
{% endif %}

<div class="block-title">{{ lang['login.title'] }}</div>

<form name="login" method="post" action="{{ form_action }}">
	<input type="hidden" name="redirect" value="{{ redirect }}" />

	<div class="label pull-left">
		<label for="logn">{{ lang['login.username'] }}:</label>
		<input type="text" type="text" name="username" class="input" />
	</div>

	<div class="label pull-right">
		<label for="pass">{{ lang['login.password'] }}:</label>
		<input type="password" type="password" name="password" class="input" />
	</div>

	<div class="clearfix"></div>

	<div class="label">
		<input type="submit" value="{{ lang['login.submit'] }}" class="button" />
	</div>
</form>
