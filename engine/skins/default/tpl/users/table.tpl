<script type="text/javascript">
function ChangeOption(selectedOption) {
document.getElementById('list').style.display = "none";
document.getElementById('adduser').style.display = "none";
document.getElementById('addbutton').style.display = "none";

if(selectedOption == 'list') {document.getElementById('list').style.display = ""; document.getElementById('addbutton').style.display = "none"; }
if(selectedOption == 'adduser') {document.getElementById('adduser').style.display = ""; document.getElementById('addbutton').style.display = ""; }
}
</script>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('list')" value="{l_users}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('adduser')" value="{l_adduser}" class="navbutton" />
</td>
</tr>
</table>
<br />
<table id="list" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="content">
<tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="100%" align="left">
<form method="post" action="{php_self}?mod=users&amp;action=list">
{l_namefilter} <input type="text" name="name" value="{name}"/> &nbsp; {l_sort} <select name="sort">{sort_options}</select>
<select name="how">{how_options}</select>
<input style="text-align: center;" size=3 name="per_page" value="{per_page}"/>
<input type="submit" value="{l_sortit}" class="button" />
</form>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="100%" valign="top">
<form method="post" name="users" action="{php_self}?mod=users">
<input type="hidden" name="action" value="" />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
<tr align="left">
<td width="5%" class="contentHead">#</td>
<td width="20%" class="contentHead">{l_name}</td>
<td width="20%" class="contentHead">{l_regdate}</td>
<td width="20%" class="contentHead">{l_last_login}</td>
<td width="10%" class="contentHead">{l_all_news2}</td>
<td width="15%" class="contentHead">{l_status}</td>
<td width="5%" class="contentHead">&nbsp;</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(users)" /></td>
</tr>
{entries}
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
<tr align="center">
<td colspan="8" class="contentEdit" align="center" valign="top">
<input class="button" type="submit" value="{l_activate}" onclick="if (confirm('{l_c_activate}')){document.forms['users'].action.value = 'massactivate';}else{return false;}" />&nbsp;
<input class="button" type="submit" value="{l_delete}" onclick="if (confirm('{l_c_delete}')){document.forms['users'].action.value = 'massdelete';}else{return false;}" />
<input class="button" type="submit" value="{l_delete_unact}" onclick="if (confirm('{l_c_delete_unact}')){document.forms['users'].action.value = 'massdelunact';}else{return false;}" />
</td>
</tr>
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="8" class="contentHead">{pagesss}</td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<form method="post" action="{php_self}?mod=users">
<input type="hidden" name="action" value="adduser" />
<table id="adduser" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentEntry1">{l_name}</td>
<td width="50%" class="contentEntry2"><input size="40" type="text" name="regusername" />
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_password}</td>
<td width="50%" class="contentEntry2"><input size="40" class="password" type="text" name="regpassword" />
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_email}</td>
<td width="50%" class="contentEntry2"><input size="40" class="email" type="text" name="regemail" />
</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_status}</td>
<td width="50%" class="contentEntry2">
<select name="reglevel">
<option value="4">4 ({l_st_4})</option>
<option selected value="3">3 ({l_st_3})</option>
<option value="2">2 ({l_st_2})</option>
<option value="1">1 ({l_st_1})</option>
</select>
</tr>
</table>
<br />
<table id="addbutton" style="display: none;"  width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value="{l_adduser}" class="button" />
</td>
</tr>
</table>
</form>