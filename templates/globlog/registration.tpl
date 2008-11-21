 <div class="text_box">
 
<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%">&nbsp;{l_registration}</td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table4" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
				<table border="0" width="100%">
<tr>
<form name="register" action="{php_self}" method="post">
<input type="hidden" name="action" value="registration" />
<input type="hidden" name="type" value="doregister" />
{entries}
[captcha]
<tr>
<td style="padding: 5px;"><img src="{admin_url}/captcha.php"></td>
<td style="padding: 5px;"><input class="important" type="text" name="vcode" maxlength="5" size="30" /></td>
</tr>
[/captcha]
<tr>
<td style="padding: 5px;" colspan="2"><input type="submit" class="button" value="{l_register}" /></td>
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
		<table border="0" width="100%" id="table6" cellspacing="0" cellpadding="0">
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