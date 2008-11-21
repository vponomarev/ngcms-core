<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$lang[langcode]" lang="$lang[langcode]" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={l_encoding}" />
<title>{home_title} - {l_adminpanel}</title>
<link rel="stylesheet" href="{skins_url}/style.css" type="text/css" media="screen" />
</head>
<body>
<form name="login" method="post" action="{php_self}">
<table width="350" align="center" cellspacing="3" cellpadding="0" style="padding-top: 50px;">
<tr><td colspan=2 align="center" style="color: red; font-weight: bold; background: #EEEEEE;">{error}</td></tr>
<tr>
<td>{l_name}</td>
<td><input tabindex="1" type="text" name="username" value="" maxlength="60" size="30" /></td>
</tr>
<tr>
<td>{l_password}</td>
<td><input class="password" tabindex="2" type="password" name="password" maxlength="20" size="30" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" class="button" value="{l_login}" />
<input type="hidden" name="referer" value="{request_uri}" />
<input type="hidden" name="action" value="dologin" /></td>
</tr>
</table>
</form>
</body>
</html>
