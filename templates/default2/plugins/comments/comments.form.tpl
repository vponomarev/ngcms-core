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
		cajax.setVar("password", form.password.value);
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

<div id="new_comments"></div>
<form id="comment" method="post" action="{post_url}" name="form" [ajax]onsubmit="add_comment(); return false;" [/ajax]>
<input type="hidden" name="newsid" value="{newsid}"/>
<input type="hidden" name="referer" value="{request_uri}"/>
<div class="title">{l_addcomment}</div>
<div class="bb">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		[not-logged]
		<tr>
			<td style="color: #696969;">{l_name}
				<small>{l_necessary}</small>
			</td>
			<td><input class="input" type="text" name="name" value="{savedname}"/></td>
		</tr>
		<tr>
			<td style="color: #696969;">{l_password}
				<small>{l_ifreg}</small>
			</td>
			<td><input class="input" type="password" name="password" value=""/></td>
		</tr>
		<tr>
			<td style="color: #696969;">{l_email}
				<small>{l_necessary}</small>
			</td>
			<td><input class="input" type="text" name="mail" value="{savedmail}"/></td>
		</tr>
		[/not-logged]
		<tr>
			<td colspan="2" style="padding-top: 15px;">
				{bbcodes}{smilies}
				<textarea class="textarea" name="content" id="content" style="width: 98%;" rows="8"></textarea>
			</td>
		</tr>
		[captcha]
		<tr>
			<td><img id="img_captcha" onclick="reload_captcha();" src="{captcha_url}?rand={rand}" alt="captcha"/></td>
			<td><input class="input" type="text" name="vcode" style="width:80px"/></td>
		</tr>
		[/captcha]
	</table>
</div>
<input type="submit" value="{l_add}" class="btn">
</form>