<script type="text/javascript">
function ChangeOption(selectedOption) {
document.getElementById('news').style.display = "none";
document.getElementById('rest').style.display = "none";

if(selectedOption == 'news') {document.getElementById('news').style.display = "";}
if(selectedOption == 'rest') {document.getElementById('rest').style.display = "";}
}
</script>
<form method="post" action="{php_self}?mod=rewrite" name="rewrite">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('news')" value="{l_news}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('rest')" value="{l_rest}" class="navbutton" />
</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr id="news">
<td width="100%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_news}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_category}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category]' value='{lnk_category}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_category_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category_page]' value='{lnk_category_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_full}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_by_cat]' value='{lnk_full_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_by_date]' value='{lnk_full_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_full_page}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_page_by_cat]' value='{lnk_full_page_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_page_by_date]' value='{lnk_full_page_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_date}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[date]' value='{lnk_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_date_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[date_page]' value='{lnk_date_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_year}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[year]' value='{lnk_year}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_year_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[year_page]' value='{lnk_year_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_month}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[month]' value='{lnk_month}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_month_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[month_page]' value='{lnk_month_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_user}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[user]' value='{lnk_user}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_print}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[print_by_cat]' value='{lnk_print_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[print_by_date]' value='{lnk_print_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_firstpage}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[firstpage]' value='{lnk_firstpage}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[page]' value='{lnk_page}' size="100" /></td>
</tr>
</table>
</td>
</tr>
<tr id="rest" style="display: none;">
<td width="100%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_rest}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_addnews}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[addnews]' value='{lnk_addnews}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_profile}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[profile]' value='{lnk_profile}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_registration}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[registration]' value='{lnk_registration}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_activation}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[activation]' value='{lnk_activation}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_activation_do}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[activation_do]' value='{lnk_activation_do}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_lostpassword}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[lostpassword]' value='{lnk_lostpassword}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_rss}<br /><small>{l_rss_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[rss]' value='{lnk_rss}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_category_rss}<br /><small>{l_category_rss_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category_rss]' value='{lnk_category_rss}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_static}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[static]' value='{lnk_static}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_plugins}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[plugins]' value='{lnk_plugins}' size="100" /></td>
</tr>
</table>
</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="save" />
<input type="submit" value="{l_save}" class="button" />
<input type="submit" value="{l_htaccess}" class="button" onclick="document.forms['rewrite'].subaction.value = 'htaccess';" />
</td>
</tr>
</table>
</form>