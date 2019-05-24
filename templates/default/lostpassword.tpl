[TWIG]
<div class="block-title">{{ lang['lostpassword_title'] }}</div>
<form name="lostpassword" action="{{ form_action }}" method="post">
	<input type="hidden" name="type" value="send"/>
	{{ entries }}
	[captcha]
	<div class="label label-table captcha pull-left">
		<label>{{ lang['captcha'] }}:</label>
		<input type="text" name="vcode" class="input">
		<img src="{{ admin_url }}/captcha.php" onclick="reload_captcha();" id="img_captcha" style="cursor: pointer;" alt="{{ lang['captcha'] }}"/>
		<div class="label-desc">{{ lang['captcha_desc'] }}</div>
	</div>
	<div class="clearfix"></div>
	<div class="label">
		<input type="submit" value="{{ lang['send_pass'] }}" class="button">
	</div>
	[/captcha]
</form>
<script type="text/javascript">
	function reload_captcha() {
		var captc = document.getElementById('img_captcha');
		if (captc != null) {
			captc.src = "{{ admin_url }}/captcha.php?rand=" + Math.random();
		}
	}
</script>
[/TWIG]