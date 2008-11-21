<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" id="table2" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36"></td>
				<td background="{tpl_url}/images/2z_41.gif" width="100%">&nbsp;<b><font color="#FFFFFF">{l_registration}</font></b></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table4" cellspacing="0" cellpadding="0">
			<tr>
				<td background="{tpl_url}/images/2z_54.gif" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
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
				<td background="{tpl_url}/images/2z_59.gif" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" id="table6" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4"></td>
				<td background="{tpl_url}/images/2z_69.gif" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>