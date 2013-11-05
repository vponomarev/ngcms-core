[found]<div class="alert alert-success">{l_search.found}: <b>{count}</b></div>[/found]
[notfound]<div class="alert alert-info">{l_search.notfound}</div>[/notfound]
[error]<div class="alert alert-error"><b>{l_search.error}</b></div>[/error]
<form method="GET" action="{form_url}">
<div class="block-title">Поиск по сайту</div>
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0 0 0;">
	<tr align="center">
		<td>{l_search.filter.author} <input type="text" name="author" class="input" value="{author}" style="width:130px" /></td>
		<td>{l_search.filter.category} {catlist}</td>
		<td>{l_search.filter.date} <select name="postdate"><option value=""></option>{datelist}</select></td>
	</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px 0;">
	<tr>
		<td align="center"><br /><input type=text name="search" value="{search}" style="width:400px" class="input" /> <input class="button" type="submit" value="{l_search.submit}" /></td>
	</tr>
</table>
</form>
<div class="articles full">
	{entries}
</div>