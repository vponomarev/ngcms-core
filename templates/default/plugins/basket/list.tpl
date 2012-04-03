{% if (recs > 0) %}
<form method="post" action="/plugin/basket/update/"/>
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
 <td>{{ loop.index }}</td><td>{{ entry.title }}</td><td align="right">{{ entry.price }}</td><td><input name="count_{{ entry.id }}" type="text" maxlength="5" style="width: 35px;" value="{{ entry.count }}"/></td><td align="right">{{ entry.sum }}</td>
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
<br/>
<input type="submit" style="width: 150px;" value="Пересчитать"/> <input type="button" style="width: 150px;" value="Оформить заказ" onclick="document.location='{{ form_url }}';"/>
</form>
{% else %}
Ваша корзина пуста!
{% endif %}
