<form method="POST" action="{{ php_self }}?action=delete&pmid={{ pmid }}&location={{ location }}" style="display: inline;">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ subject }} {% if (ifinbox) %}от{% endif %} {% if not (ifinbox) %}для{% endif %} {{ author }} {{ pmdate|date('Y-m-d H:i') }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
						<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
						<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%">
						<blockquote>{{ content }}</blockquote>
					</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{{ lang['pm:delete_one'] }}">
</form>
{% if (ifinbox == 1) %}
	<form name="pm" method="POST" action="{{ php_self }}?action=reply&pmid={{ pmid }}" style="display: inline;">
		<input class="btn" type="submit" value="{{ lang['pm:reply'] }}">
	</form>
{% endif %}
</td>
</tr>
</table>
</p>
</div>
</div>