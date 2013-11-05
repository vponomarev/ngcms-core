<article>
	<div class="full">
		<header><h1>���� �������</h1></header>
		<div class="telo">
			{% if (recs > 0) %}
			<table class="table">
				<thead>
					<tr valign="top">
						<td>#</td>
						<td>������������</td>
						<td>������</td>
						<td>����</td>
						<td>���-��</td>
						<td>���������</td>
					</tr>
				</thead>
				<tbody>
					{% for entry in entries %}
					<tr>
						<td>{{ loop.index }}</td>
						<td>{{ entry.title }}</td>
						<td>{{ entry.xfields.news.size }}</td>
						<td align="right">{{ entry.price }}</td>
						<td align="right">{{ entry.count }}</td>
						<td align="right">{{ entry.sum }}</td>
					</tr>
					{% endfor %}
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">�����:</td>
						<td align="right"><strong>{{ total }}</strong></td>
					</tr>
				</tfoot>
			</table>
			{% else %}
				<div class="msge">���� ������� �����! 111</div>
			{% endif %}
		</div>
	</div>
</article>