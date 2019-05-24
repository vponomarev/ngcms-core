<form method="POST" action="{{ php_self }}?action=delete&pmid={{ pmid }}&location={{ location }}" style="display: inline;">
	<div class="full">
		<h1>{{ subject }} {% if (ifinbox) %}от{% endif %} {% if not (ifinbox) %}для{% endif %} {{ author }} {{ pmdate|date('Y-m-d H:i') }}</h1>
		<div class="pad20_f">
			<div class="btn-group">
				<a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
				<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
				<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a>
			</div>
			<div class="clear20"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%">
						<blockquote>{{ content }}</blockquote>
					</td>
				</tr>
				<tr>
					<td>
						<input class="btn btn-warning" type="submit" value="{{ lang['pm:delete_one'] }}">
</form>
{% if (ifinbox == 1) %}
	<form name="pm" method="POST" action="{{ php_self }}?action=reply&pmid={{ pmid }}" style="display: inline;">
		<input class="btn" type="submit" value="{{ lang['pm:reply'] }}">
	</form>
{% endif %}
<div class="clear20"></div>
</td>
</tr>
</table>
</div>
</div>