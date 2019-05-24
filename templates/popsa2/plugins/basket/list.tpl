<article>
	<div class="full">
		<header><h1>Ваша корзина</h1></header>
		<div class="telo">
			{% if (recs > 0) %}
				<form method="post" action="/plugin/basket/update/"/>
				<table class="table">
					<tr>
						<th>#</th>
						<th>Наименование</th>
						<th>Размер</th>
						<th>Цена</th>
						<th>Кол-во</th>
						<th align="right">Стоимость</th>
					</tr>
					{% for entry in entries %}
						<tr>
							<td class="f11">{{ loop.index }}</td>
							<td>{{ entry.title }}</td>
							<td>{{ entry.xfields.news.size }}</td>
							<td align="right"><strong>{{ entry.price }}</strong></td>
							<td>
								<input name="count_{{ entry.id }}" type="text" maxlength="5" style="width: 35px;" value="{{ entry.count }}"/>
							</td>
							<td align="right" class="blue_t">{{ entry.sum }}</td>
						</tr>
					{% endfor %}
					<tr class="muted">
						<td colspan="5">Итого:</td>
						<td align="right"><strong class="green_t">{{ total }}</strong></td>
					</tr>
				</table>
				<div class="btn-group">
					<input type="submit" class="btn" value="Пересчитать"/>
					<input type="button" value="Оформить заказ" class="btn btn-primary" onclick="document.location='{{ form_url }}';"/>
				</div>
				</form>
				<div class="clear20"></div>
			{% else %}
				<div class="msge">Ваша корзина пуста!</div>
			{% endif %}
		</div>
	</div>
</article>