{% if (flags.error) %}
	<div class="ng-message">
		{{ errorText }}
	</div>
{% endif %}
<div class="post">
	<div class="post-header">
		<div class="post-title">{{ title }}</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>{% block content %}{% endblock %}</p>
	</div>
</div>