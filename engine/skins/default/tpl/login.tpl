<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{l_langcode}" lang="{l_langcode}" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={l_encoding}" />
<title>{home_title} - {l_adminpanel}</title>
<link href="{skins_url}/login_style.css" rel="stylesheet" type="text/css" media="screen" />

</head>
<body>

<!--Login block-->

<div id="login_wrap">
  <div class="l_block">

<div class="in">
    
    <img src="{skins_url}/images/login/logo.png" width="283" height="129" />
    <form name="login" method="post" action="{php_self}">
    <label>{l_name}</label>
    <input tabindex="1" type="text" name="username" value="" maxlength="60" />
    <label>{l_password}</label>
    <input class="password" tabindex="2" type="password" name="password" maxlength="20" />
	<br />
    <input type="submit" class="filterbutton" value="{l_login}" />
<input type="hidden" name="redirect" value="{redirect}" />
<input type="hidden" name="action" value="login" />
    </form>
    </div>
  </div>
  <p class="log_copyright">2008-2012 © <a href="http://ngcms.ru" target="_blank">Next Generation CMS</a></p>
</div>
<!--/Login block-->

</body>
</html>
