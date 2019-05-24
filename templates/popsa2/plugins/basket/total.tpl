{% if (not ajaxUpdate) %}
<div id="basketTotalDisplay">
	{% endif %}
	<div class="white_b">
		<h3>Корзина</h3>
		<div class="l300_green_blue"></div>
		<ul class="plugin">
			{% if (count > 0) %}
				<li>Товаров: <strong>{{ count }}</strong></li>
				<li>Всего: <strong class="green_t">{{ price }}</strong> <em>руб.</em></li>
				<li><a href="/plugin/basket/" class="btn btn-primary btn-large btn-block">Оформить заказ</a></li>
			{% else %}
				<li>корзина пуста</li>
			{% endif %}
		</ul>
	</div>
	<div class="clear20"></div>
	{% if (not ajaxUpdate) %}
</div>
	</div>
{% endif %}