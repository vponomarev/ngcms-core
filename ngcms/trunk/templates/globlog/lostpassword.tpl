<div class="text_box">

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%">&nbsp;{l_lostpassword}</td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
				<table border="0" width="100%">
<form name="lostpassword" action="{php_self}" method="post">
<input type="hidden" name="action" value="lostpassword" />
<input type="hidden" name="type" value="send" />
{entries}
[captcha]
<tr>
<td style="padding: 5px;"><img src="{admin_url}/captcha.php"></td>
<td style="padding: 5px;"><input class="important" tabindex="3" type="text" name="vcode" maxlength="5" size="30" /></td>
</tr>
[/captcha]
<tr>
<td style="padding: 5px;" colspan="2"><input type="submit" class="button" value="{l_send_pass}" /></td>
</form>
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

</div>