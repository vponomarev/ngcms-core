<form method="post" action="{php_self}?mod=categories">
<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100%" colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_editing}</td>
</tr>
<tr>
<td width="100%" colspan="5">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><label for="cat_show">{l_show_main}</label></td>
<td width="50%" class="contentEntry2"><input type="checkbox" id="cat_show" name="cat_show" value="1" class="check" {check} /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_title}</td>
<td width="50%" class="contentEntry2"><input value="{name}" type=text size="40" name="name" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_alt_name}</td>
<td width="50%" class="contentEntry2"><input value="{alt}" type=text size="40" name="alt" /></td>
</tr>
[meta]
<tr>
<td width="50%" class="contentEntry1">{l_cat_desc}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="description" value="{description}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_cat_keys}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="keywords" value="{keywords}" /></td>
</tr>
[/meta]
<tr>
<td width="50%" class="contentEntry1">{l_cat_number}</td>
<td width="50%" class="contentEntry2"><input type="text" size="4" name="number" value="{number}" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_cat_tpl}</td>
<td width="50%" class="contentEntry2"><select name="tpl">{tpl_list}</select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_parent}</td>
<td width="50%" class="contentEntry2">{parent}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_icon}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="icon" value="{icon}" maxlength="255" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_alt_url}</td>
<td width="50%" class="contentEntry2"><input value="{alt_url}" type=text size="40" name="alt_url" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_orderby}</td>
<td width="50%" class="contentEntry2">{orderlist}</td>	
</tr>
{extend}
<tr>
<td width="100%" colspan="2">&nbsp;</td>
</tr>
<tr align="center">
<td width="100%" colspan="2" class="contentEdit">
<input type="submit" value="{l_edit}" class="button" />
<input type="hidden" name="action" value="doedit" />
<input type="hidden" name="catid" value="{catid}" />
</td>
</tr>
</table>
</form>