<tr>
<form name="activation" action="{php_self}" method="post">
<input type="hidden" name="action" value="activation" />
<input type="hidden" name="subaction" value="activate" />
<input type="hidden" name="userid" value="{userid}" />
<input type="hidden" name="code" value="{code}" />
<td colspan="2" style="border-top: 1px solid #eeeeef; border-bottom: 1px solid #eeeeef; padding: 5px; background-color: #fafafa;">{l_activation}</td>
</tr>
<tr>
<td width="30%" style="padding: 5px;">
{l_name}</td>
<td width="70%" style="padding: 5px;"><input tabindex="1" type="text" name="name" maxlength="60" size="30" /></td>
</tr>
<td style="padding: 5px;">{l_email}</td>
<td style="padding: 5px;"><input tabindex="2" type="text" name="email" maxlength="80" size="30" /></td>
</tr>
[captcha]
<tr>
<td style="padding: 5px;"><img src="{admin_url}/captcha.php"></td>
<td style="padding: 5px;"><input class="important" tabindex="3" type="text" name="vcode" maxlength="5" size="30" /></td>
</tr>
[/captcha]
<tr>
<td style="padding: 5px;" colspan="2"><input type="submit" class="button" value="{l_activate}" /></td>
</form>
</tr>