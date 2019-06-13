{% for entry in entries %}
	<tr id="xfl_{{ entry.id }}">
		<td>{{ entry.title }}:</td>
		<td class="xprofile">{{ entry.input }}</td>
	</tr>
{% endfor %}