<form name="register" action="{{ form_action }}" method="POST">
	<input type="hidden" name="type" value="doregister"/>
	<div class="full">
		<h1>{{ lang['registration'] }}</h1>
		<div class="pad20_f">
			{% for entry in entries %}
				<div class="input">
					<label>{{ entry.title }}:</label>
					{{ entry.input }}<br/>
					<small>{{ entry.descr }}</small>
				</div>
			{% endfor %}
			{% if flags.hasCaptcha %}
				<div class="input">
					<label>Проверочный код</label>
					<img src="{{ admin_url }}/captcha.php" class="left" onclick="reload_captcha();" id="img_captcha" style="cursor: pointer;" alt="Security code"/>
					<input type="text" name="vcode" style="width:80px"/>
					<div class="clear10"></div>
				</div>
			{% endif %}
			<div><input type="submit" class="btn btn-primary btn-large" value="{{ lang['register'] }}"/></div>
		</div>
	</div>
</form>
<script type="text/javascript">
	function reload_captcha() {
		var captc = document.getElementById('img_captcha');
		if (captc != null) {
			captc.src = "{{ admin_url }}/captcha.php?rand=" + Math.random();
		}
	}
</script>