<form name="form" method="POST" action="{php_self}?action=delete">
<div class="block-title">Личные сообщения - Отправленные</div>
<table class="table table-striped table-bordered">
	<tr align="center">
		<td colspan="4"><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
	</tr>
	<tr align="center">
		<td width="25%">{l_pm:date}</td>
		<td width="40%">{l_pm:subject}</td>
		<td width="30%">{l_pm:too}</td>
		<td width="5%"><input type="checkbox" name="master_box" title="{l_pm:checkall}" onclick="javascript:check_uncheck_all(form)"></td>
	</tr>
	{entries}
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input type="submit" class="button" value="Удалить" />
</div>
</form>