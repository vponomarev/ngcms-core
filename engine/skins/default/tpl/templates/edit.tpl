<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr class="contHead">
<td width=100% colspan="8"><img src="{skins_url}/images/nav.gif" hspace="8" />{location}</td>
</tr>
</table>
<form action="{php_self}?mod=templates" method="post">
<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td class="contentEntry1">
<input type="hidden" name="theme" value="{theme}">
<input type="hidden" name="skin" value="{skin}">
<input type="hidden" name="new" value="{new}">
<input type="hidden" name="filename" value="{filename}">
<input type="hidden" name="where" value="{where}">
<input type="hidden" name="action" value="save">
<textarea cols=130 rows=25 name="filebody" style="width: 99%; font: 9pt consolas, verdana, sans-serif;">{filebody}</textarea><br />
<input type="submit" value="{l_save}" class="button">
</td>
</tr>
</table>
</form>