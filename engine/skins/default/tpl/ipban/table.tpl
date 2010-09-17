
<table class="content" border="0" cellspacing="0" cellpadding="2" align="center">
<tr>
<td width="66%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_hdr.list}</td>
</tr>

<tr align="left" class="contHead">
<td>{l_hdr.ip}</td>
<td>{l_hdr.counter}</td>
<td>{l_hdr.type}</td>
<td>{l_hdr.reason}</td>
<td>&nbsp;</td>
</tr>
{entries}
</table>
</td>
<td width="33%" style="padding-left:5px;" valign="top">
<form name="form" method="post" action="{php_self}?mod=ipban">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width=100% colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_hdr.block}</td>
</tr>
<tr><td class="contentEntry2">{l_add.ip}:</td><td><input type="text" name="ip" value="{iplock}" size="31" /></td></tr>
<tr><td class="contentEntry2">{l_add.block.open}:</td><td><select disabled="disabled" name="lock:open"><option value="0">--</option><option value="1" style="color: blue;">{l_lock.block}</option><option value="2" style="color: red;">{l_lock.silent}</option></select></tr>
<tr><td class="contentEntry2">{l_add.block.reg}:</td><td><select name="lock:reg"><option value="0">--</option><option value="1" style="color: blue;">{l_lock.block}</option><option value="2" style="color: red;">{l_lock.silent}</option></select></tr>
<tr><td class="contentEntry2">{l_add.block.login}:</td><td><select name="lock:login"><option value="0">--</option><option value="1" style="color: blue;">{l_lock.block}</option><option value="2" style="color: red;">{l_lock.silent}</option></select></tr>
<tr><td class="contentEntry2">{l_add.block.comm}:</td><td><select name="lock:comm"><option value="0">--</option><option value="1" style="color: blue;">{l_lock.block}</option><option value="2" style="color: red;">{l_lock.silent}</option></select></tr>
<tr><td class="contentEntry2">{l_add.block.rsn}</td><td><input type="text" name="lock:rsn" size="30" /></td></tr>
<tr>
<td width=100% class="contentEntry" colspan="2" valign="middle" align="center">
<input type="submit" value="{l_add.submit}" class="button" />
<input type="hidden" name="action" value="add" />
</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<br/>
<br/>
{l_info.descr}
