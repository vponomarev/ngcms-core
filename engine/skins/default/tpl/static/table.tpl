<!-- Navigation bar -->
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=static">{{ lang['static_title'] }}</a>
		</td>
	</tr>
</table>

<!-- Info content -->
<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
	<tr>
		<td width="100%" align="right">{{ lang['per_page'] }}
			<form action="{{ php_self }}" method="get" name="options_bar">
				<input type="hidden" name="mod" value="static"/><input style="text-align: center" name="per_page" value="{{ per_page }}" type="text" size="3"/>
				<input type="submit" value="{{ lang['do_show'] }}" class="button"/></form>
			&nbsp;</td>
	</tr>
</table>

<form action="{{ php_self }}?mod=static" method="post" name="static">
	<input type="hidden" name="token" value="{{ token }}"/>
	<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr align="left" class="contHead">
			<td width="20">{% if (perm.modify) %}
					<input class="check" type="checkbox" name="master_box" title="{{ lang['select_all'] }}" onclick="javascript:check_uncheck_all(static)" />{% endif %}
			</td>
			<td width="50">{{ lang['state'] }}</td>
			<td width="45%">{{ lang['title'] }}</td>
			<td>{{ lang['list.altname'] }}</td>
			<td>{{ lang['list.template'] }}</td>
			<td width="100">{{ lang['list.date'] }}</td>
		</tr>
		{% for entry in entries %}
			<tr align="left">
				<td class="contentEntry1">{% if (perm.modify) %}
						<input name="selected[]" value="{{ entry.id }}" class="check" type="checkbox" />{% endif %}</td>
				<td class="contentEntry1">
					<div style="margin-right: 5px;">{{ entry.status }}</div>
				</td>
				<td class="contentEntry1">
					<div style="float: left; margin: 0px;">
						{% if (perm.details) %}
						<a title="ID: {{ entry.id }}" href="{{ php_self }}?mod=static&amp;action=editForm&amp;id={{ entry.id }}">{% endif %}{{ entry.title }}{% if (perm.details) %}</a>{% endif %}
						<br/>
						<small>{{ entry.url }}</small>
						&nbsp;
					</div>
				</td>
				<td class="contentEntry1">{{ entry.alt_name }}</td>
				<td class="contentEntry1">{{ entry.template }}</td>
				<td class="contentEntry1">{{ entry.date }}</td>
			</tr>
		{% else %}
			<tr>
				<td colspan="6"><p>- {{ lang['not_found'] }} -</p></td>
			</tr>
		{% endfor %}
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
	</table>
	<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td width="47%" align="left" class="contentEdit">
				<div id="submit">
					{% if (perm.modify) %}
						<select name="action">
							<option value="">-- {{ lang['action'] }} --</option>
							<option value="do_mass_delete">{{ lang['delete'] }}</option>
							<option value="do_mass_approve">{{ lang['approve'] }}</option>
							<option value="do_mass_forbidden">{{ lang['forbidden'] }}</option>
						</select>
						<input type="submit" value="OK" class="button"/>
					{% endif %}
				</div>
			</td>
			<td width="50%" class="contentEdit" align="right">{% if (perm.modify) %}
					<input type="button" value="{{ lang['addstatic'] }}" onclick="document.location='?mod=static&action=addForm'; return false;" class="button" />{% endif %}
				&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">{{ pagesss }}</td>
	</table>
</form>