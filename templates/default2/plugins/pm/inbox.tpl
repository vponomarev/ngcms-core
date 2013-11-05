<form name="form" method="POST" action="{php_self}?action=delete">
<div class="post">
	<div class="post-header">
		<div class="post-title">Личные сообщения - Входящие</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td colspan="5"><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table class="pm" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td class="pm_head" width="25%">{l_pm:date}</td>
					<td class="pm_head" width="35%">{l_pm:subject}</td>
					<td class="pm_head" width="20%">{l_pm:from}</td>
					<td class="pm_head" width="15%">{l_pm:state}</td>
					<td class="pm_head" width="5%"><input type="checkbox" name="master_box" title="{l_pm:checkall}" onclick="javascript:check_uncheck_all(form)"></td>
				</tr>
				{entries}
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="Удалить" />
					</td>
				</tr>
			</table>
		</p>
	</div>
</div>
</form>