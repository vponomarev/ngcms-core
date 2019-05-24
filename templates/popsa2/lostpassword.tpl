<form name="lostpassword" action="{form_action}" method="post">
	<input type="hidden" name="type" value="send"/>
	<div class="full">
		<h1>{l_lostpassword}</h1>
		<div class="pad20_f">
			{entries}
			[captcha]
			<div class="input">
				<label>Проверочный код</label>
				<img src="{admin_url}/captcha.php" class="left" onclick="reload_captcha();" id="img_captcha" style="cursor: pointer;" alt="Security code"/>
				<input type="text" name="vcode" style="width:80px"/>
				<div class="clear10"></div>
			</div>
			[/captcha]
			<div><input type="submit" class="btn btn-primary btn-large" value="{l_send_pass}"/></div>
		</div>
	</div>
</form>
<script type="text/javascript">
	function reload_captcha() {
		var captc = document.getElementById('img_captcha');
		if (captc != null) {
			captc.src = "{admin_url}/captcha.php?rand=" + Math.random();
		}
	}
</script>