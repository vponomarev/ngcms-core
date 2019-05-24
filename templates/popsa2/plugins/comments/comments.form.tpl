<script type="text/javascript">
	var cajax = new sack();
	function reload_captcha() {
		var captc = document.getElementById('img_captcha');
		if (captc != null) {
			captc.src = "{captcha_url}?rand=" + Math.random();
		}
	}

	function add_comment() {
		// First - delete previous error message
		var perr;
		if (perr = document.getElementById('error_message')) {
			perr.parentNode.removeChild(perr);
		}

		// Now let's call AJAX comments add
		var form = document.getElementById('comment');
		//cajax.whattodo = 'append';
		cajax.onShow("");
		[not - logged]
		cajax.setVar("name", form.name.value);
		cajax.setVar("mail", form.mail.value);
		[captcha]
		cajax.setVar("vcode", form.vcode.value);
		[/captcha]
		[/not-logged]
		cajax.setVar("content", form.content.value);
		cajax.setVar("newsid", form.newsid.value);
		cajax.setVar("ajax", "1");
		cajax.setVar("json", "1");
		cajax.requestFile = "{post_url}"; //+Math.random();
		cajax.method = 'POST';
		//cajax.element = 'new_comments';
		cajax.onComplete = function () {
			if (cajax.responseStatus[0] == 200) {
				try {
					var resRX = eval('(' + cajax.response + ')');
					var nc;
					if (resRX['rev'] && document.getElementById('new_comments_rev')) {
						nc = document.getElementById('new_comments_rev');
					} else {
						nc = document.getElementById('new_comments');
					}
					nc.innerHTML += resRX['data'];
					if (resRX['status']) {
						// Added successfully!
						form.content.value = '';
					}
				} catch (err) {
					alert('Error parsing JSON output. Result: ' + cajax.response);
				}
			} else {
				alert('TX.fail: HTTP code ' + cajax.responseStatus[0]);
			}
			[captcha]
			reload_captcha();
			[/captcha]
		}
		cajax.runAJAX();
	}
</script>
<form id="comment" method="post" action="{post_url}" name="form" [ajax]onsubmit="add_comment(); return false;" [/ajax]>
<input type="hidden" name="newsid" value="{newsid}"/>
<input type="hidden" name="referer" value="{request_uri}"/>
<fieldset>
	<legend>{l_addcomment}</legend>
	[not-logged]
	<div class="input"><label>{l_name} <sup>*</sup></label><input type="text" name="name" value="{savedname}"/></div>
	<div class="input"><label>{l_email} <sup>*</sup></label><input type="text" name="mail" value="{savedmail}"/></div>
	[/not-logged]
	[captcha]
	<div class="input">
		<label>Проверочный код <sup>*</sup></label>
		<img id="img_captcha" onclick="reload_captcha();" src="{captcha_url}?rand={rand}" alt="captcha" style="cursor: pointer;" class="left;"/>
		<input type="text" name="vcode" style="width:80px"/>
		<div class="clear"></div>
	</div>
	[/captcha]
	<div>
		{bbcodes}<br>{smilies}<br><textarea name="content" id="content" class="wy" rows="8" style="width:98%"></textarea>
	</div>
	<input type="submit" class="btn btn-large btn-primary" value="{l_add}"/>
</fieldset>
</form>