<form action="{php_self}?mod=editnews" method="post" name="options_bar">
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td style="padding-left : 10px;">{l_sort}<br /><select name="sort">{sortlist}</select></td>
<td>{l_month}<br /><select name="postdate"><option selected value="">- {l_show_all} -</option>{selectdate}</select></td>
<td>{l_category}<br />{category_select}</td>
<td>{l_author}<br /><select name="authorid"><option value="">- {l_show_all} -</option>{authorlist}</select></td>
<td>{l_status_mode}<br /><select name="status_mode"><option value="">{l_show_all}</option>{statuslist}</select></td>
<td>{l_news_per_page} <input style="text-align: center" name="news_per_page" value="{news_per_page}" type="text" size="3" /> <input type="submit" value="{l_do_show}" class="button" /></td>
</tr>
</table>
</form>
<br />
<form action="{php_self}?mod=editnews" method="post" name="editnews">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="5%" class="contentHead">{l_postid_short}</td>
<td width="10%" class="contentHead">{l_date}</td>
<td width="45%" class="contentHead">{l_title}</td>
[comments]<td width="10%" class="contentHead">{l_listhead.comments}</td>[/comments]
<td width="25%" class="contentHead">{l_category}</td>
<td width="10%" class="contentHead">{l_author}</td>
<td width="5%" class="contentHead">&nbsp;</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(editnews)" /></td>
</tr>
[no-news]<tr><td colspan="6"><p>- {l_not_found} -</p></td></tr>[/no-news]
{entries}
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>

[actions]
<tr align="center">
<td colspan="8" class="contentEdit" align="right" valign="top">
<div style="text-align: left;">
��������: <select name="subaction" style="font: 12px Verdana, Courier, Arial; width: 230px;">
<option value="">-- {l_action} --</option>
<option value="do_mass_approve">{l_approve}</option>
<option value="do_mass_forbidden">{l_forbidden}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
<option value="do_mass_mainpage">{l_massmainpage}</option>
<option value="do_mass_unmainpage">{l_massunmainpage}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
[comments]<option value="do_mass_com_approve">{l_com_approve}</option>
<option value="do_mass_com_forbidden">{l_com_forbidden}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>[/comments]
<option value="do_mass_delete">{l_delete}</option>
</select>
<input type="submit" value="���������.." class="button" />
<input type="hidden" name="mod" value="editnews" />
<br/>
</div>
</td>
</tr>
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
[/actions]
<tr>
<td align="center" colspan="8" class="contentHead">{pagesss}</td>
</tr>
</table>
</form>
