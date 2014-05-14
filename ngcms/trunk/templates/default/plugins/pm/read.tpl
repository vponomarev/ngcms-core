<form method="POST" action="{{ php_self }}?action=delete&pmid={{ pmid }}&location={{ location }}">
<div class="block-title">{{ subject }} {% if (ifinbox) %}от{% endif %} {% if not (ifinbox) %}для{% endif %} {{ author }} {{ pmdate|date('Y-m-d H:i') }}</div>
<table class="table table-striped table-bordered">
	<tr>
		<th><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> | <a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> | <a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
	</tr>
	<tr>
		<td width="100%"><blockquote>{{ content }}</blockquote></td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input class="button" type="submit" value="{{ lang['pm:delete_one'] }}"></form>
	{% if (ifinbox == 1) %}
	<form name="pm" method="POST" action="{{ php_self }}?action=reply&pmid={{ pmid }}" style="display: inline;">
		<input class="button" type="submit" value="{{ lang['pm:reply'] }}">
	</form>
	{% endif %}
</div>