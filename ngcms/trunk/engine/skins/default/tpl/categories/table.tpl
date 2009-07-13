<script type="text/javascript">
function ChangeOption(selectedOption) {
document.getElementById('list').style.display = "none";
document.getElementById('addnew').style.display = "none";
document.getElementById('delete').style.display = "none";

if (selectedOption == 'list') {document.getElementById('list').style.display = ""; document.getElementById('delete').style.display = ""; }
if(selectedOption == 'addnew') {document.getElementById('addnew').style.display = ""; document.getElementById('delete').style.display = "none";}
}
</script>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('list')" value="{l_list}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('addnew')" value="{l_addnew}" class="navbutton" />
</td>
</tr>
</table>
<br />
<form method="post" name="categories" action="{php_self}?mod=categories&amp;action=sort">
<table id="list" width="97%" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td width="100%" colspan="7">&nbsp;</td>
</tr>
<tr align="center">
<td width="5%" class="contentHead">#</td>
<td width="10%" class="contentHead">{l_position}</td>
<td width="15%" class="contentHead">{l_title}</td>
<td width="15%" class="contentHead">{l_alt_name}</td>
<td width="10%" class="contentHead">{l_show_main}</td>
<td width="10%" class="contentHead">{l_news}</td>
<td width="10%" class="contentHead">{l_action}</td>
</tr>
{cat_tree}
</table>

<table id="delete" border="0" width="97%" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr align="center">
<td width="100%" class="contentEdit"><input type="submit" value="{l_sort}" class="button" /></td>
</tr>
</table>
</form>

<form method="post" action="{php_self}?mod=categories">
<table id="addnew" style="display: none;"  border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_addnew}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><label for="cat_show">{l_show_main}</label></td>
<td width="50%" class="contentEntry2"><input type="checkbox" id="cat_show" name="cat_show" value="1" class="check" checked="checked" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_title}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="name" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_show.link}</td>
<td width="50%" class="contentEntry2"><select name="show_link"><option value="0">{l_link.always}</option><option value="1">{l_link.ifnews}</option><option value="2">{l_link.never}</option></select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_alt_name}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="alt" /></td>
</tr>
[meta]
<tr>
<td width="50%" class="contentEntry1">{l_cat_desc}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="description" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_cat_keys}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="keywords" /></td>
</tr>
[/meta]
<tr>
<td width="50%" class="contentEntry1">{l_cat_number}</td>
<td width="50%" class="contentEntry2"><input type="text" size="4" name="number" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_cat_tpl}</td>
<td width="50%" class="contentEntry2"><select name="tpl">{tpl_list}</select></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_icon}</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="icon" maxlength="255" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_alt_url}</td>
<td width="50%" class="contentEntry2"><input type=text size="40" name="alt_url" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_orderby}</td>
<td width="50%" class="contentEntry2">{orderlist}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_parent}</td>
<td width="50%" class="contentEntry2">{parent}</td>
</tr>
{extend}
<tr><td width="100%" colspan="2">&nbsp;</td></tr>
<tr>
<td width="100%" colspan="2" class="contentEdit" align="center">
<input type="submit" value="{l_addnew}" class="button" />
<input type="hidden" name="action" value="add" />
</td>
</tr>
</table>
</form>