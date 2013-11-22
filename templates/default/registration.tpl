<script src="{{ tpl_url }}/js/registration.js"></script>
<div class="block-title">Регистрация нового пользователя</div>
<form name="register" action="{{ form_action }}" method="post" onsubmit="return validate();">
<input type="hidden" name="type" value="doregister" />
	{% for entry in entries %}
		<div class="label label-table">
			<label for="{{entry.id}}">{{ entry.title }}:</label>
			<span class="input2">{{ entry.input }}</span>
			<div class="label-desc" id="{{entry.id}}">{{ entry.descr }}</div>
		</div>
	{% endfor %}
	{% if flags.hasCaptcha %}
	<div class="label label-table captcha pull-left">
		<label for="reg_capcha">Введите код безопасности:</label>
		<input id="reg_capcha" type="text" name="vcode" class="input">
		<img src="{{ admin_url }}/captcha.php" onclick="reload_captcha();" id="img_captcha" style="cursor: pointer;" alt="Security code"/>
		<div class="label-desc">Вам предстоит специальный код для подтверждения вашего действия.</div>
	</div>
	{% endif %}
	<div class="clearfix"></div>
	<div class="label">
		<label class="pull-left"><input type="checkbox" name="agree">
		Я ознакомился с <a href="#">правилам</a> и <a href="#">условиями</a> и принимаю их.</label>
		<input type="submit" value="Зарегистрироваться" class="button pull-right">
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
	function reload_captcha() {
		var captc = document.getElementById('img_captcha');
		if (captc != null) {
			captc.src = "{{ admin_url }}/captcha.php?rand="+Math.random();
		}
	}   
</script>