<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{l_langcode}" lang="{l_langcode}" dir="ltr">
<head>
	<meta http-equiv="Content-type" content="text/html; charset={l_encoding}"/>
	<meta http-equiv="Content-language" content="{l_langcode}"/>
	<link href="{tpl_url}/style.css" rel="stylesheet" type="text/css" media="screen"/>
	<title>{l_preview}</title>
	<script type="text/javascript" src="{ scriptLibrary }/functions.js"></script>
	<script type="text/javascript" src="{ scriptLibrary }/ajax.js"></script>
	{htmlvars}
</head>
<body>
<fieldset style="border : 1px solid #333;">
	<legend><span style="font-size: 10px; font-family: Verdana">{l_short}</span></legend>
	{short}
</fieldset>
<fieldset style="border : 1px solid #333;">
	<legend><span style="font-size: 10px; font-family: Verdana">{l_full}</span></legend>
	{full}
</fieldset>
</body>
</html>