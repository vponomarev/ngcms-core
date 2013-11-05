<style>.pm form { display: inline; }</style>
<div class="pm">
<form method="POST" action="{php_self}?action=delete&pmid={pmid}&location={location}">
<input type="hidden" name="title" value="{subject}">
<input type="hidden" name="from" value="{from}">
<div class="post">
	<div class="post-header">
		<div class="post-title">Личные сообщения - {subject}</div>
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
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%"><blockquote>{content}</blockquote></td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input type="submit" class="btn" value="Удалить" /></form>
						[if-inbox]<form name="pm" method="POST" action="{php_self}?action=reply&pmid={pmid}">
							<input class="btn" type="submit" value="{l_pm:reply}">
						</form>[/if-inbox]
					</td>
				</tr>
			</table>
		</p>
	</div>
</div>
</form>
</div>