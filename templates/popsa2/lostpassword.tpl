<form name="lostpassword" action="{{ form_action }}" method="post">
	<input type="hidden" name="type" value="send" />

	<div class="full">
		<h1>{{ lang['lostpassword'] }}</h1>

		<div class="pad20_f">
			{{ entries }}

			{% if flags.hasCaptcha %}
				<div class="input">
					<label>{{ lang['captcha'] }}</label>
					<img
						id="img_captcha"
						onclick="reload_captcha();"
						src="{{ captcha_source_url }}"
						alt="{{ lang['captcha'] }}"
						style="cursor: pointer;" />

					<input type="text" name="vcode" style="width:80px" />
					<div class="clear10"></div>
				</div>
			{% endif %}

			<div>
				<input type="submit" value="{{ lang['send_pass'] }}" class="btn btn-primary btn-large" />
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	function reload_captcha() {
		var captcha = document.getElementById('img_captcha');

		captcha && (captcha.src = "{{ captcha_source_url }}?rand=" + Math.random());
	}
</script>
