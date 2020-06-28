<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=perm">{{ lang['permissions'] }}</a></td>
	</tr>
</table>
{{ lang['list_changes_performed'] }}:
<div class="pconf">
	<table class="content">
		<tr>
			<td>{{ lang['group'] }}</td>
			<td>ID</td>
			<td>{{ lang['name'] }}</td>
			<td>{{ lang['old_value'] }}</td>
			<td>{{ lang['new_value'] }}</td>
		</tr>
		{% for entry in updateList %}
			<tr>
				<td>{{ GRP[entry.group]['title'] }}</td>
				<td>{{ entry.id }}</td>
				<td>{{ entry.title }}</td>
				<td>{{ entry.displayOld }}</td>
				<td>{{ entry.displayNew }}</td>
			</tr>
		{% endfor %}
	</table>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width=100% colspan="5" class="contentHead">&nbsp;</td>
		</tr>
	</table>
	<div style="background-color: yellow; padding: 10px;">{{ lang['result'] }}: {% if (execResult) %}
			<b>{{ lang['success'] }}</b>{% else %}<font color="red"><b>{{ lang['error'] }}</b></font>{% endif %}</div>
</div>

