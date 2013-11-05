{% if (flags.error) %}
<div class="alert alert-error">
	{{ errorText }}
</div>
{% endif %}
<div class="block-title">{{ title }}</div>
{% block content %}{% endblock %}