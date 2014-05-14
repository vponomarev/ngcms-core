<form name="form" method="POST" action="{{ php_self }}?action=delete">
<div class="block-title">{{ lang['pm:outbox'] }}</div>
<table class="table table-striped table-bordered">
	<tr>
		<th colspan="4"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> | <a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> | <a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
	</tr>
	<tr align="center">
		<td width="25%">{{ lang['pm:date'] }}</td>
		<td width="40%">{{ lang['pm:subject'] }}</td>
		<td width="30%">{{ lang['pm:too'] }}</td>
		<td width="5%"><input type="checkbox" name="master_box" title="{{ lang['pm:checkall'] }}" onclick="javascript:check_uncheck_all(form)"></td>
	</tr>
	{% for entry in entries %}
	<tr align="center">
		<td>{{ entry.pmdate|date('Y-m-d H:i') }}</td>
		<td><a href="{{ php_self }}?action=read&pmid={{ entry.pmid }}&location=outbox">{{ entry.subject }}</a></td>
		<td>{{ entry.link }}</td>
		<td><input name="selected_pm[]" value="{{ entry.pmid }}" type="checkbox"/></td>
	</tr>
	{% endfor %}
</table>
<div class="pagination" style="margin-top: 10px;">
	<ul>
		{{ pagination }}
	</ul>
</div>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input type="submit" class="button" value="{{ lang['pm:delete'] }}" /></form>
</div>