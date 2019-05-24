<form method="POST" action="{{ php_self }}?action=set">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['pm:set'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="5"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
						<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
						<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table class="pm" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="checkbox" name="email" id="email" {{ checked }} /> {{ lang['pm:email_set'] }}</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{{ lang['pm:send'] }}">
</form>

</td>
</tr>
</table>
</p>
</div>
</div>