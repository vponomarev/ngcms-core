<div class="block-title">Осталось уточнить некоторые данные</div>
<form name="register" action="/plugin/auth_loginza/register/" method="post">
<input type="hidden" name="type" value="doregister" />
	<div class="label label-table">
		<label>Имя пользователя:</label>
		<span class="input2"><input name="login" type="text" title="Имя пользователя" value="{login}"/></span>
		<div class="label-desc">Вы можете заходить на сайт через логин/пароль</div>
	</div>
	<div class="label label-table">
		<label>Пароль:</label>
		<span class="input2"><input name="password" type="text" title="Пароль" value="{password}"/></span>
		<div class="label-desc">Обязателен для захода через логин/пароль</div>
	</div>
	<div class="label label-table">
		<label>E-mail адрес:</label>
		<span class="input2"><input name="email" type="text" title="E-mail адрес" value="{email}"/></span>
		<div class="label-desc">При восстановлении пароля новый пароль будет высылаться на этот адрес (не обязательно для заполнения)</div>
	</div>
	<div class="clearfix"></div>
	<div class="label">
		<label class="pull-left"><input type="checkbox" name="agree">
		Я ознакомился с <a href="#">правилам</a> и <a href="#">условиями</a> и принимаю их.</label>
		<input type="submit" value="Зарегестрироваться" class="button pull-right">
	</div>
</form>
<script type="text/javascript">
	function validate() {
		if (document.register.agree.checked == false) {
			window.alert('Ознакомьтесь с правилами и условиями.');
			return false;
		}
		return true;
	}
</script>