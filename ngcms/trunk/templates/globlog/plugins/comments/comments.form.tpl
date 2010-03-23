 <div class="comment_box">
 
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
	cajax.setVar("password", form.password.value);
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
				var nc = document.getElementById('new_comments');
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

<div id="new_comments"></div>
<form id="comment" method="post" action="{post_url}" name="form" [ajax]onsubmit="add_comment(); return false;"[/ajax]>
<input type="hidden" name="newsid" value="{newsid}" />
<input type="hidden" name="referer" value="{request_uri}" />


<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
[not-logged]
<tr>
<td width="200" style="padding-left: 15px;">{l_name}</td>
<td style="padding: 5px;"><input type="text" size="30" name="name" value="{savedname}"  /></td>
</tr>
<tr>
<td style="padding-left: 15px;">{l_password} <small>{l_ifreg}</small></td>
<td style="padding: 5px;"><input class="password" type="password" maxlength="16" size="30" name="password" value="" /></td>
</tr>
<tr>
<td style="padding-left: 15px;">{l_email}  <small>{l_necessary}</small></td>
<td style="padding: 5px;"><input class="email" type="text" size="30" maxlength="70" name="mail" value="{savedmail}"  /></label></td>
</tr>
[captcha]
<tr>
<td style="padding-left: 15px;"><img id="img_captcha" onclick="reload_captcha();" src="{captcha_url}?rand={rand}" alt="captcha" /></td>
<td style="padding: 5px;"><input class="important" type="text" name="vcode" maxlength="5" size="30" /></td>
</tr>
[/captcha]
[/not-logged]
<tr>
<td width="200" valign="top" style="padding-left: 5px;"><br />
<a href="javascript:ShowOrHide('bbcodes');"><img src="{tpl_url}/images/arr_bot.gif" border="0" />{l_bbcodes}</a><br />
<div id="bbcodes" style="display : none;"><br />{bbcodes}</div></td>

<td valign="top"><br />
<a href="javascript:ShowOrHide('smilies');"><img src="{tpl_url}/images/arr_bot.gif" border="0" />{l_smilies}</a><br />
<div id="smilies" style="display : none;"><br />{smilies}</div></td>
</tr>
<tr>
<td colspan="2" style="padding: 15px;">
<textarea name="content" id="content" style="width: 95%;" rows="8"></textarea>
</td>
</tr>
<tr>
<td style="padding: 15px;" align="left" colspan="2"><input type="submit" class="button" value="{l_add}"/>&nbsp; <input type="reset" class="button" value="{l_clear}" /></td>
</tr>
</table>
				</td>
				<td width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%"></td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>

</div>