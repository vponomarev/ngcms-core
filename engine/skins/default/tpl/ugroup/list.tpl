<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=ugroup">{{ lang['user_groups'] }}</a>
		</td>
	</tr>
</table>


<table width="97%" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
	<thead>
	<tr align="left" class="contHead">
		<td width="5%">#</td>
		<td>{{ lang['identifier'] }}</td>
		<td>{{ lang['name'] }}</td>
		<td>{{ lang['users_in_group'] }}</td>
		<td>{{ lang['action'] }}</td>
	</tr>
	</thead>
	<tbody id="admCatList">

	{% for entry in entries %}
		<tr align="center" class="contRow1">
			<td>{{ entry.id }}</td>
			<td>{{ entry.identity }}</td>
			<td>{{ entry.name }}</td>
			<td>{{ entry.count }}</td>
			<td>{% if (entry.flags.canEdit) %}[
					<a href="?mod=ugroup&action=editForm&id={{ entry.id }}">Edit</a> ]{% endif %}{% if (entry.flags.canDelete) %}[
					<a href="?mod=ugroup&action=delete&id={{ entry.id }}&token={{ token }}">Delete</a> ]{% endif %}</td>
		</tr>
	{% endfor %}


	</tbody>
	<tfoot>
	<tr>
		<td colspan="8" class="contentEdit" align="left">&nbsp; {% if (flags.canAdd) %}
				<form method="get" action="">
				<input type="hidden" name="mod" value="ugroup"/><input type="hidden" name="action" value="addForm"/><input type="submit" value="{{ lang['add_group'] }}" class="button"/>
				</form>{% endif %}</td>
	</tr>
	</tfoot>
</table>
