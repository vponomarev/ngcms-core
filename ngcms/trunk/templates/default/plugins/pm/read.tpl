<style>.pm form { display: inline; }</style>
<div class="pm">
<form method="POST" action="{php_self}?action=delete&pmid={pmid}&location={location}">
<input type="hidden" name="title" value="{subject}">
<input type="hidden" name="from" value="{from}">
<div class="block-title">������ ��������� - {subject}</div>
<table class="table table-striped table-bordered">
	<tr align="center">
		<td><a href="/plugin/pm/?action=write">�������� ���������</a> | <a href="/plugin/pm/">�������� ���������</a> | <a href="/plugin/pm/?action=outbox">������������ ���������</a></td>
	</tr>
	<tr>
		<td width="100%"><blockquote>{content}</blockquote></td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input type="submit" class="button" value="�������" /></form>
	[if-inbox]<form name="pm" method="POST" action="{php_self}?action=reply&pmid={pmid}">
		<input class="button" type="submit" value="{l_pm:reply}">
	</form>[/if-inbox]
</div>
</form>
</div>