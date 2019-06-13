<form method=post name=form action="{{ php_self }}?action=send">
	<input type="hidden" name="title" value="{{ title }}">
	<input type="hidden" name="to_username" value="{{ to_username }}">
	<div class="full">
		<h1>{{ lang['pm:textmessage'] }}</h1>
		<div class="pad20_f">
			<div class="btn-group">
				<a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
				<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
				<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a>
			</div>
			<div class="clear20"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						{{ quicktags }}<br/>{{ smilies }}
						<div class="clear20"></div>
						<div>
							<textarea name="content" id="content" tabindex="1" class="textarea"/></textarea>
						</div>
						<div>
							<label>{{ lang['pm:saveoutbox'] }}
								&nbsp;&nbsp;<input name="saveoutbox" type="checkbox"/></label>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<input class="btn" type="submit" value="{{ lang['pm:send'] }}">
</form>
<div class="clear20"></div>
</td>
</tr>
</table>
</div>
</div>
