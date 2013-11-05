{% if (not ajaxUpdate) %}
<div id="basketTotalDisplay">
	{% endif %}
	<div class="white_b">
		<h3>�������</h3>
		<div class="l300_green_blue"></div>
			<ul class="plugin">
				{% if (count > 0) %}
					<li>�������: <strong>{{ count }}</strong></li>
					<li>�����: <strong class="green_t">{{ price }}</strong> <em>���.</em></li>
					<li><a href="/plugin/basket/" class="btn btn-primary btn-large btn-block">�������� �����</a></li>
				{% else %}
					<li>������� �����</li>
				{% endif %}
			</ul>
		</div>
		<div class="clear20"></div>
		{% if (not ajaxUpdate) %}
	</div>
</div>
{% endif %}