<script type="text/javascript">
var cajax = new sack();
function reload_captcha() {
	var captc = document.getElementById('img_captcha');
	if (captc != null) {
		captc.src = "{captcha_url}?rand="+Math.random();
	}
}	

function add_comment(){
	// First - delete previous error message
	var perr;
	if (perr=document.getElementById('error_message')) {
		perr.parentNode.removeChild(perr);
	}

	// Now let's call AJAX comments add
	var form = document.getElementById('comment');
	//cajax.whattodo = 'append';
	cajax.onShow("");[not-logged]
	cajax.setVar("name", form.name.value);
	cajax.setVar("mail", form.mail.value);[captcha]
	cajax.setVar("vcode", form.vcode.value); [/captcha][/not-logged]
	cajax.setVar("content", form.content.value);
	cajax.setVar("newsid", form.newsid.value);
	cajax.setVar("ajax", "1");
	cajax.setVar("json", "1");
	cajax.requestFile = "{post_url}"; //+Math.random();
	cajax.method = 'POST';
	//cajax.element = 'new_comments';
	cajax.onComplete = function() { 
		if (cajax.responseStatus[0] == 200) {
			try {
				var resRX = eval('('+cajax.response+')');
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
				alert('Error parsing JSON output. Result: '+cajax.response); 
			}
		} else {
			alert('TX.fail: HTTP code '+cajax.responseStatus[0]);
		}	
		[captcha] 
		reload_captcha();[/captcha]
	}
	cajax.runAJAX();
}
</script>
<div class="title">Добавить комментарий</div>
<div class="respond">
	<form id="comment" method="post" action="{post_url}" name="form" [ajax]onsubmit="add_comment(); return false;"[/ajax]>
	<input type="hidden" name="newsid" value="{newsid}" />
	<input type="hidden" name="referer" value="{request_uri}" />
		[not-logged]
		<div class="label pull-left">
			<label for="name">Введите имя:</label>
			<input type="text" name="name" value="{savedname}" class="input">
		</div>
		<div class="label pull-right">
			<label for="email">Введите E-mail:</label>
			<input type="text" name="mail" value="{savedmail}" class="input">
		</div>
		[/not-logged]
		<div class="clearfix"></div>
		{bbcodes}{smilies}
		<div class="clearfix"></div>
		<div class="label">
			<label></label>
			<textarea onkeypress="if(event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) {add_comment();}" name="content" id="content" class="textarea"></textarea>
		</div>
		[captcha]
		<div class="label captcha pull-left">
			<label for="captcha">Введите код безопасности:</label>
			<input type="text" name="vcode" id="captcha" class="input">
			<img id="img_captcha" onclick="reload_captcha();" src="{captcha_url}?rand={rand}" alt="captcha" />
		</div>
		[/captcha]
		<div class="label pull-right">
			<label for="sendComment" class="default">&nbsp;</label>
			<input type="submit" id="sendComment" value="Добавить комментарий" class="button">
		</div>
	</form>
</div>