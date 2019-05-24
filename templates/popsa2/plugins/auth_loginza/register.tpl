<form name="register" action="/plugin/auth_loginza/register/" method="post">
	<input type="hidden" name="type" value="doregister"/>
	<div class="full">
		<header><h1>Осталось уточнить некоторые данные</h1></header>
		<div class="telo">

			<div class="input"><label>Имя
					пользователя:</label><input name="login" type="text" title="Имя пользователя" value="{login}"/><br/>
				<small>Вы можете заходить на сайт через логин/пароль</small>
			</div>
			<div class="input">
				<label>Пароль:</label><input name="password" type="text" title="Пароль" value="{password}"/><br/>
				<small>Обязателен для захода через логин/пароль</small>
			</div>
			<div class="input"><label>E-mail
					адрес:</label><input name="email" type="text" title="E-mail адрес" value="{email}"/><br/>
				<small>при восстановлении пароля новый пароль будет высылаться на этот адрес (не обязательно для
					заполнения)
				</small>
			</div>
			<div><input class="btn btn-primary btn-large" type="submit" value="Зарегистрироваться!"/></div>
		</div>
	</div>
</form>