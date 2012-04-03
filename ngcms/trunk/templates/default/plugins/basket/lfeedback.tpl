{% if (recs > 0) %}
<h3>Ваша корзина</h3>
<div class="table">
<table class="basket_tb">
<thead>
<tr valign="top">
 <td>#</td><td>Наименование</td><td>Цена</td><td>Кол-во</td><td>Стоимость</td>
</tr>
</thead>
<tbody>
{% for entry in entries %}
<tr>
 <td>{{ loop.index }}</td><td>{{ entry.title }}</td><td align="right">{{ entry.price }}</td><td align="right">{{ entry.count }}</td><td align="right">{{ entry.sum }}</td>
</tr>
{% endfor %}
</tbody>
<tfoot>
<tr>
<td colspan="4">Итого:</td>
<td align="right">{{ total }}</td>
</tr>
</tfoot>
</table>
</div>
{% else %}
Ваша корзина пуста!
{% endif %}
