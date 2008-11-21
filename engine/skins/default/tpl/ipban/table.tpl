<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="70%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="4" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_list}</td>
</tr>
<tr>
<td width=100% colspan="4">&nbsp;</td>
</tr>
<tr align="center">
<td width="15%" class="contentHead">{l_ip}</td>
<td width="45%" class="contentHead">{l_reason}</td>
<td width="20%" class="contentHead">{l_counter}</td>
<td width="20%" class="contentHead">{l_unblock}</td>
</tr>
{entries}
</table>
</td>
<td width="30%" style="padding-left:10px;" valign="top">
<form name="form" method="post" action="{php_self}?mod=ipban">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width=100% colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_block}</td>
</tr>
<tr>
<td width=100% class=contentEntry2>
{l_ip}&nbsp;<input type="text" name="add_ip" size="30" tabindex="1" />
</td>
</tr>
<tr>
<td width=100% class=contentEntry2 valign=middle>{l_reason}&nbsp;<input type="text" name="desc" size="30" tabindex="2" /></td>
</tr>
<tr>
<td width=100% class=contentEntry2 valign=middle>
<input type="submit" value="{l_block}" class="button" />
<input type="hidden" name="action" value="add" />
</td>
</tr>
</table>
</form>
</td>
</tr>
</table>