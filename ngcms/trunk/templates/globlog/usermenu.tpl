[login]
<script language="javascript">
var set_login = 0;
var set_pass  = 0;
</script>
<form name="login" method="post" action="{form_action}" id="login">
<input type="hidden" name="redirect" value="{redirect}" />


<input onfocus="if (!set_login){set_login=1;this.value='';}" value="{l_name}" class="mw_login_form" type="text" name="username" maxlength="60" size="25" />
<input onfocus="if(!set_pass){set_pass=1;this.value='';}" value="{l_password}" class="mw_login_form" type="password" name="password" maxlength="20" size="25" />
<a href="{reg_link}">{l_registration}</a>&nbsp;
<a href="{lost_link}">{l_lostpassword}</a>
<input type="image" value="{l_login}" src="{tpl_url}/images/login.gif" style="border:0" name="Login" />
</form>
</div>
[/login]
[is-logged]
<div class="mw_login">{l_hello}, {name} ! 
[if-have-perm]
&nbsp;<a href="{admin_url}">{l_adminpanel}</a> 
&nbsp;<a href="{addnews_link}">{l_addnews}</a>
[/if-have-perm]
&nbsp;<a href="{profile_link}">{l_myprofile}</a> 
&nbsp;<a href="{logout_link}">{l_logout}</a></div>
[/is-logged]