<form method=post name=form action="{php_self}?action=send">
<input type="hidden" name="title" value="{title}">
<input type="hidden" name="sendto" value="{sendto}">
<div class="post">
	<div class="post-header">
		<div class="post-title">Личные сообщения - Текст</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td colspan="2"><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="40%">Текст сообщения:</td>
					<td width="60%"><textarea onkeypress="if(event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) {add_comment();}" name="content" id="content" class="textarea" style="width:98%; height: 80px;"></textarea><br /><input name="saveoutbox" type="checkbox"/> {l_pm:saveoutbox}</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{l_pm:send}" accesskey="s" />
					</td>
				</tr>
			</table>
		</p>
	</div>
</div>
</form>