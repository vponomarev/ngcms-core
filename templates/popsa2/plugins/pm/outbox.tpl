<form name="form" method="POST" action="{{ php_self }}?action=delete">
	<div class="full">
		<h1>{{ lang['pm:outbox'] }}</h1>
		<div class="pad20_f">
			<div class="btn-group">
				<a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
				<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
				<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a>
			</div>
			<div class="clear20"></div>
			<table class="table">
				<tr align="center">
					<td width="25%">{{ lang['pm:date'] }}</td>
					<td width="40%">{{ lang['pm:subject'] }}</td>
					<td width="30%">{{ lang['pm:too'] }}</td>
					<td width="5%">
						<input type="checkbox" name="master_box" title="{{ lang['pm:checkall'] }}" onclick="javascript:check_uncheck_all(form)">
					</td>
				</tr>
				{% for entry in entries %}
					<tr align="center">
						<td>{{ entry.pmdate|date('Y-m-d H:i') }}</td>
						<td>
							<a href="{{ php_self }}?action=read&pmid={{ entry.pmid }}&location=outbox">{{ entry.subject }}</a>
						</td>
						<td>{{ entry.link }}</td>
						<td><input name="selected_pm[]" value="{{ entry.pmid }}" type="checkbox"/></td>
					</tr>
				{% endfor %}
				<tr>
					<td width=100% colspan="4">
						<div style="padding: 10px; text-align:center;">{{ pagination }}</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<input class="btn btn-warning" type="submit" value="{{ lang['pm:delete'] }}">
</form>
<div class="clear20"></div>
</td>
</tr>
</table>
</div>
</div>