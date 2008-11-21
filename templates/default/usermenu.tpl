[login]
<script language="javascript">
var set_login = 0;
var set_pass  = 0;
</script>
<form name="login" method="post" action="" id="login">
<input type="hidden" name="referer" value="{request_uri}" />
<input type="hidden" name="action" value="dologin" />
<table border="0" cellspacing="0" cellpadding="0" width="300">
<tr>
<td align="left"><input onfocus="if (!set_login){set_login=1;this.value='';}" value="{l_name}" class="mw_login_form" type="text" name="username" maxlength="60" size="25" /></td>
<td align="left" colspan="2"><input onfocus="if(!set_pass){set_pass=1;this.value='';}" value="{l_password}" class="mw_login_form" type="password" name="password" maxlength="20" size="25" /></td>
</tr>
<tr>
<td><img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{reg_link}">{l_registration}</a><br /><img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{lost_link}">{l_lostpassword}</a></td>
<td><input type="image" value="{l_login}" src="{tpl_url}/images/2z_23.gif" style="width:37px; height:22px; border:0" name="Login" /></td>
<td valign="middle"><br />[login-err]<div style="color : #fff; padding : 5px;">{l_msge_login}</div>[/login-err]</td>
</tr>
</table>
</form>
[/login]
[is-logged]
<div class="mw_login">{l_hello}, {name}<br /> 
[if-have-perm]<img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{admin_url}">{l_adminpanel}</a> <img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{addnews_link}">{l_addnews}</a><br />[/if-have-perm]<img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{profile_link}">{l_myprofile}</a> <img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<a href="{home_url}/?action=logout">{l_logout}</a></div>
[/is-logged]