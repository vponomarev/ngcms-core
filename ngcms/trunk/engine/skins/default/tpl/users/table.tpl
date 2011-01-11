<script type="text/javascript">
function ChangeOption(selectedOption) {
	document.getElementById('list').style.display		= "none";
	document.getElementById('adduser').style.display	= "none";
	document.getElementById('addbutton').style.display	= "none";

	if (selectedOption == 'list') 	 {
		document.getElementById('list').style.display		= "";
		document.getElementById('addbutton').style.display	= "none";
	}
	if (selectedOption == 'adduser') {
		document.getElementById('adduser').style.display	= "";
		document.getElementById('addbutton').style.display	= "";
	}
}

var fInitStatus = false;

function updateAction() {
	mode = document.forms['form_users'].action.value;

	if (mode == 'massSetStatus') {
		if (!fInitStatus) {
			document.forms['form_users'].newstatus.value = '4';
			fInitStatus = true;
		}
		document.forms['form_users'].newstatus.disabled = false;
	} else {
		document.forms['form_users'].newstatus.disabled = true;
	}
}

function validateAction() {
	mode = document.forms['form_users'].action.value;

	if (mode == '') {
		alert('Необходимо выбрать действие!');
		return;
	}

	if ((mode == 'massSetStatus')&&(document.forms['form_users'].newstatus.value < 1)) {
		alert('{l_msge_setstatus}');
		return;
	}

	document.forms['form_users'].submit();
}

</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=users">{l_users_title}</a></td>
</tr>
</table>
[perm.modify]<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('list')" value="{l_users}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('adduser')" value="{l_adduser}" class="navbutton" />
</td>
</tr>
</table>
<br />[/perm.modify]
<table id="list" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="content">
<tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="100%" align="left">
<!-- Filter form: BEGIN -->
<form method="GET" action="{php_self}">
<input type="hidden" name="mod" value="users" />
<input type="hidden" name="action" value="list" />
{l_namefilter} <input type="text" name="name" value="{name}"/> &nbsp; {l_sort} <select name="sort">{sort_options}</select>
<select name="how">{how_options}</select>
<input style="text-align: center;" size=3 name="per_page" value="{per_page}"/>
<input type="submit" value="{l_sortit}" class="button" />
</form>
<!-- Filter form: END -->
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="100%" valign="top">
<!-- Mass actions form: BEGIN -->
<form method="GET" name="form_users" id="form_users" action="{php_self}">
<input type="hidden" name="mod" value="users" />
<input type="hidden" name="token" value="{token}"/>
<input type="hidden" name="name" value="{name}" />
<input type="hidden" name="how" value="{how_value}" />
<input type="hidden" name="sort" value="{sort_value}" />
<input type="hidden" name="page" value="{page_value}" />
<input type="hidden" name="per_page" value="{per_page_value}" />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
<tr align="left" class="contHead">
<td width="5%">#</td>
<td width="20%">{l_name}</td>
<td width="20%">{l_regdate}</td>
<td width="20%">{l_last_login}</td>
<td width="10%">{l_all_news2}</td>
[comments]<td width="10%">{l_listhead.comments}</td>[/comments]
<td width="15%">{l_status}</td>
<td width="5%">&nbsp;</td>
<td width="5%">[perm.modify]<input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form_users)" />[/perm.modify]</td>
</tr>
{entries}
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
<tr align="center">
<td colspan="9" class="contentEdit" align="right" valign="top">
[perm.modify]
<div style="text-align: left;">
{l_action}: <select name="action" style="font: 12px Verdana, Courier, Arial; width: 230px;" onchange="updateAction();" onclick="updateAction();">
 <option value="" style="background-color: #E0E0E0;">-- {l_action} --</option>
 <option value="massActivate">{l_activate}</option>
 <option value="massLock">{l_lock}</option>
 <option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
 <option value="massDel">{l_delete}</option>
 <option value="massDelInactive">{l_delete_unact}</option>
 <option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
 <option value="massSetStatus">{l_setstatus} &raquo;</option>
</select>
<select name="newstatus" disabled="disabled" style="font: 12px Verdana, Courier, Arial; width: 150px;"><option value="0"></option><option value="2">2 ({l_st_2})</option><option value="3">3 ({l_st_3})</option><option value="4">4 ({l_st_4})</option></select>
<input type="button" class="button" value="{l_submit}" onclick="validateAction();" />
<br/>
</div>
[/perm.modify]
</td>
</tr>
<tr>
<td width="100%" colspan="9">&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="9" class="contentHead">{pagesss}</td>
</tr>
</table>
</form>
<!-- Mass actions form: END -->
</td>
</tr>
</table>


[perm.modify]<form method="post" action="{php_self}?mod=users">
<input type="hidden" name="action" value="add" />
<input type="hidden" name="token" value="{token}"/>
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
[/perm.modify]