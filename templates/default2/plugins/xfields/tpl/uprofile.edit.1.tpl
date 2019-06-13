{% for entry in entries %}
	<tr id="xfl_{{ entry.id }}">
		<td width="40%">{{ entry.title }}:</td>
		<td width="60%" class="xprofile">{{ entry.input }}</td>
	</tr>
{% endfor %}