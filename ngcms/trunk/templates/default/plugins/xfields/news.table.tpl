<div class="table">
<table border="1">
	<tr>
		<th width="45">���.</th>
		<th width="80">��������</th>
		<th width="125">�����</th>
		<th width="80">����, ���</th>
		<th width="140">���-��, ��.</th>
	</tr>
[entries]
        <tr>
		<td>{{ entry.field_identity }}</td>
		<td>{{ entry.field_package }}</td>
		<td>{{ entry.field_class }}</td>
		<td class="price_orange">{{ entry.field_price }}</td>
		<td class="detail">{% if (entry.flags.basket_allow) %}<input name="basket_{{ entry.id }}" id="basket_{{ entry.id }}" type="text" class="kolvo" value="1" /> &nbsp; <a href="#" onclick="rpcBasketRequest('plugin.basket.manage', { 'action' : 'add', 'ds' : 51, 'id' : {{ entry.id }}, count : document.getElementById('basket_{{ entry.id }}').value }); return false;" >� �������</a>{% endif %}</td>
	</tr>
[/entries]
</table>
</div>
