<form name="register" action="/plugin/auth_loginza/register/" method="post">
<input type="hidden" name="type" value="doregister" />
<div class="full">
	<header><h1>ќсталось уточнить некоторые данные</h1></header>
	<div class="telo">

		<div class="input"><label>»м€ пользовател€:</label><input name="login" type="text" title="»м€ пользовател€" value="{login}"/><br /><small>¬ы можете заходить на сайт через логин/пароль</small></div>
		<div class="input"><label>ѕароль:</label><input name="password" type="text" title="ѕароль" value="{password}"/><br /><small>ќб€зателен дл€ захода через логин/пароль</small></div>
		<div class="input"><label>E-mail адрес:</label><input name="email" type="text" title="E-mail адрес" value="{email}"/><br /><small>при восстановлении парол€ новый пароль будет высылатьс€ на этот адрес (не об€зательно дл€ заполнени€)</small></div>
		<div><input class="btn btn-primary btn-large" type="submit" value="«арегистрироватьс€!"/></div>
	</div>
</div>
</form>