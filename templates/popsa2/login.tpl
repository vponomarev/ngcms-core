<form name="login" method="post" action="{form_action}">
	<input type="hidden" name="redirect" value="{redirect}"/>
	<div class="full">
		<header><h1>{l_login.title}</h1></header>
		<div class="telo">
			[error]
			<div class="msge">{l_login.error}</div>
			[/error]
			[banned]
			<div class="msge">{l_login.banned}</div>
			[/banned]
			[need.activate]
			<div class="msgi">{l_login.need.activate}</div>
			[/need.activate]
			<div class="input"><label>{l_login.name}:</label><input type="text" name="username"/></div>
			<div class="input"><label>{l_login.password}:</label><input type="password" name="password"/></div>
			<div><input class="btn btn-primary btn-large" type="submit" value="{l_login.submit}"/></div>
		</div>
	</div>
</form>