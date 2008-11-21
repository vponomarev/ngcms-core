<form action="{php_self}?mod=users" method="post">
<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
<tr>
<td width=100% colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" />{l_profile_of} - {name}</td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_status}</td>
<td width=50% class=contentEntry2 valign=middle><select name="editlevel">{status}</select></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_regdate}</td>
<td width=50% class=contentEntry2 valign=middle>{regdate}</td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_last_login}</td>
<td width=50% class=contentEntry2 valign=middle>{last}</td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_last_ip}</td>
<td width=50% class=contentEntry2 valign=middle>{ip} <a href="http://www.nic.ru/whois/?ip={ip}" title="{l_whois}">{l_whois}</a></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_all_news}</td>
<td width=50% class=contentEntry2 valign=middle>{news}</td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_all_comments}</td>
<td width=50% class=contentEntry2 valign=middle>{com}</td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_new_pass}</td>
<td width=50% class=contentEntry2 valign=middle><input class=password name=editpassword size=40 maxlength=16 /><br /><small>{l_pass_left}</small></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_email}</td>
<td width=50% class=contentEntry2 valign=middle><input class=email type=text name=editmail value="{mail}" size=40 /></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_site}</td>
<td width=50% class=contentEntry2 valign=middle><input type="text" name="editsite" value="{site}" size=40 /></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_icq}</td>
<td width=50% class=contentEntry2 valign=middle><input type="text" name="editicq" value="{icq}" size=40 maxlength=10 /></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_from}</td>
<td width=50% class=contentEntry2 valign=middle><input type="text" name="editfrom" value="{where_from}" size=40 maxlength=60 /></td>
</tr>
<tr>
<td width=50% class=contentEntry1>{l_about}</td>
<td width=50% class=contentEntry2 valign=middle><textarea name="editabout" rows="7" cols="60">{info}</textarea></td>
</tr>
</table>
</td>
</tr>
<tr align="center">
<td width=100% class=contentEntry1 colspan="2">
<input type="submit" value="{l_save}" class="button" />
<input type="button" value="{l_cancel}" onClick="history.back();" class="button" />
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="action" value="doedituser" />
<input type="hidden" name="oldpass" value="{pass}" />
</td>
</tr>
</table>
</form>