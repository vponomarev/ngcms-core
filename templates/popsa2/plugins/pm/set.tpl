<form method="POST" action="{{ php_self }}?action=set">
	<div class="full">
		<h1>{{ lang['pm:set'] }}</h1>
		<div class="pad20_f">
			<div class="btn-group">
				<a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
				<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
				<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a>
			</div>
			<div class="clear20"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width=100%>
						<input type="checkbox" name="email" id="email" {{ checked }} /> {{ lang['pm:email_set'] }}</td>
				</tr>
				<input type="hidden" name="check">
				<tr>
					<td style="padding-top: 20px;">
						<input class="btn" type="submit">
</form>
<div class="clear20"></div>
</td>
</tr>
</table>
</div>
</div>