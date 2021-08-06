<form name="login" method="post" action="{form_action}">
	<input type="hidden" name="redirect" value="{redirect}"/>
	<div class="full">
		<header><h1>{{ lang['login.title'] }}</h1></header>
		<div class="telo">
			[error]
			<div class="msge">{{ lang['login.error'] }}</div>
			[/error]
			[banned]
			<div class="msge">{{ lang['login.banned'] }}</div>
			[/banned]
			[need.activate]
			<div class="msgi">{{ lang['login.need.activate'] }}</div>
			[/need.activate]
			<div class="input"><label>{{ lang['login.name'] }}:</label><input type="text" name="username"/></div>
			<div class="input"><label>{{ lang['login.password'] }}:</label><input type="password" name="password"/></div>
			<div><input class="btn btn-primary btn-large" type="submit" value="{{ lang['login.submit'] }}"/></div>
		</div>
	</div>
</form>
