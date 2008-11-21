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
 	alert("{l_wrong_icq}"); 
 	return false; 
 }

 // Email
 var email = f.editmail.value;
 if ((email.length > 0) && (! emailCheck(email))) {
 	alert("{l_wrong_email}");
 	return false;
 }

 // About
 var about = f.editabout.value;
 if (({about_sizelimit} > 0) && (about.length > {about_sizelimit})) {
 	alert("{l_wrong_about}");
 	return false;	
 }
 return true;
}
</script>
 <div class="text_box">
<form id="profileForm" method="post" action="" enctype="multipart/form-data">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td  width="100%"><div style="padding-left:15px;">&nbsp;{l_profile_of} - {name}</div></td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="7">&nbsp;</td>
				<td>
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="button" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="button" />
</td>
</tr>
</table>
<br />
<table id="maincontent" class="content" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_status}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{status}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_regdate}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{regdate}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_last}</td>
<td style="padding: 5px;" class="entry">{last}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_all_news}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{news}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_all_comments}</td>
<td style="padding: 5px;" class="entry">{comments}</td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_new_pass}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input class="password" name="editpassword" size="40" maxlength="16" /><br /><small>{l_pass_left}</small></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_email}</td>
<td style="padding: 5px;" class="entry"><input type="text" class="email" name="editmail" value="{email}" size="40" /></td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_site}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input type="text" name="editsite" value="{site}" size="40" /></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_icq}</td>
<td style="padding: 5px;" class="entry"><input type="text" name="editicq" value="{icq}" size="40" maxlength="10" /></td>
</tr>
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_from}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry"><input type="text" name="editfrom" value="{from}" size="40" maxlength="60" /></td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_about} {about_sizelimit_text}</td>
<td style="padding: 5px;" class="entry"><textarea name="editabout" rows="7" cols="55">{about}</textarea></td>
</tr>
</table>

<table id="additional" style="display: none;" class="content" border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{l_avatar}</td>
<td style="padding: 5px; background-color: #f9fafb;" class="entry">{avatar}</td>
</tr>
<tr>
<td style="padding: 5px;" class="entry">{l_photo}</td>
<td style="padding: 5px;" class="entry">{photo}</td>
</tr>
</table>
<br />
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value="{l_save}" class="button" onclick="return validate_form();"/>
<input type="hidden" name="action" value="profile" />
<input type="hidden" name="subaction" value="save" />
<input type="hidden" name="save" value="" />
</td>
</tr>
</table>
				</td>
				<td width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				</td>
				<td width="100%"></td>
				<td>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
</div>