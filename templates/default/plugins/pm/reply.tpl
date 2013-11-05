<form method=post name=form action="{php_self}?action=send">
<input type="hidden" name="title" value="{title}">
<input type="hidden" name="sendto" value="{sendto}">
<div class="block-title">Личные сообщения - Текст</div>
<table class="table table-striped table-bordered">
	<tr align="center">
		<td><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
	</tr>
	<tr>
		<td>{quicktags}{smilies}</td>
	</tr>
	<tr>
		<td><textarea onkeypress="if(event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) {add_comment();}" name="content" id="content" class="textarea" style="height:120px; width: 98%;"></textarea></td>
	</tr>
	<tr>
		<td><input name="saveoutbox" type="checkbox"/> {l_pm:saveoutbox}</td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input class="button" type="submit" value="{l_pm:send}"  accesskey="s" />
</div>
</form>