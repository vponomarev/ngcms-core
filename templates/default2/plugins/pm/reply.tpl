<form method=post name=form action="{{ php_self }}?action=send">
	<input type="hidden" name="title" value="{{ title }}">
	<input type="hidden" name="to_username" value="{{ to_username }}">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['pm:textmessage'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="5"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
						<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
						<a href="{{ php_self }}?action=set">{{ lang['pm:set'] }}</a></th>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="padding-top: 15px;">
						{{ quicktags }}{{ smilies }}
						<br/><textarea name="content" id="pm_content" style="width: 98%;" rows="8"/></textarea>
						<br/><br/><input name="saveoutbox" type="checkbox"/> {{ lang['pm:saveoutbox'] }}
					</td>
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