<script type="text/javascript">
function ChangeOption(selectedOption) {
	document.getElementById('maincontent').style.display = "none";
	document.getElementById('additional').style.display = "none";

	if(selectedOption == 'maincontent') {
		document.getElementById('maincontent').style.display = "";
	}
	
	if(selectedOption == 'additional') {
		document.getElementById('additional').style.display = "";
	}
}

function validate_form() {
 var f = document.getElementById('profileForm');

 // ICQ
 var icq = f.editicq.value;
 if ((icq.length > 0)&&(! icq.match(/^\d{4,10}$/))) { 
 	alert("{l_uprofile:wrong_icq}"); 
 	return false; 
 }

 // Email
 var email = f.editmail.value;
 if ((email.length > 0) && (! emailCheck(email))) {
 	alert("{l_uprofile:wrong_email}");
 	return false;
 }

 // About
 var about = f.editabout.value;
 if (({about_sizelimit} > 0) && (about.length > {about_sizelimit})) {
 	alert("{about_sizelimit_text}");
 	return false;	
 }
 return true;
}
</script>
<form id="profileForm" method="post" action="{form_action}" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36"></td>
				<td background="{tpl_url}/images/2z_41.gif" width="100%">&nbsp;<b><font color="#FFFFFF">{l_uprofile:profile_of} - {name}</font></b></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td background="{tpl_url}/images/2z_54.gif" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_uprofile:maincontent}" class="button" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_uprofile:additional}" class="button" />
</td>
</tr>
</table>
<br />
<table id="maincontent" class="content" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:status}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{status}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:regdate}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{regdate}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:last}</td>
<td style="padding: 5px;" class="entry">{last}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:all_news}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{news}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:all_comments}</td>
<td style="padding: 5px;" class="entry">{comments}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:new_pass}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input class="password" name="editpassword" size="40" maxlength="16" /><br /><small>{l_uprofile:pass_left}</small></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:email}</td>
<td style="padding: 5px;" class="entry"><input type="text" class="email" name="editmail" value="{email}" size="40" /></td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:site}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input type="text" name="editsite" value="{site}" size="40" /></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:icq}</td>
<td style="padding: 5px;" class="entry"><input type="text" name="editicq" value="{icq}" size="40" maxlength="10" /></td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:from}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input type="text" name="editfrom" value="{from}" size="40" maxlength="60" /></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:about} {about_sizelimit_text}</td>
<td style="padding: 5px;" class="entry"><textarea name="editabout" rows="7" cols="55">{about}</textarea></td>
</tr>
</table>

<table id="additional" style="display: none;" class="content" border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_uprofile:avatar}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{avatar}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_uprofile:photo}</td>
<td style="padding: 5px;" class="entry">{photo}</td>
</tr>
</table>
<br />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value="{l_uprofile:save}" class="button" onclick="return validate_form();"/>
<input type="hidden" name="plugin_cmd" value="apply" />
</td>
</tr>
</table>
				</td>
				<td background="{tpl_url}/images/2z_59.gif" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4"></td>
				<td background="{tpl_url}/images/2z_69.gif" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>