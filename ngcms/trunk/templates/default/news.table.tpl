{% if (handler == 'by.category') %}
<h3>{{ category.name }}</h3>
{% include template_from_string(category.info) %}
{% if category.icon.purl %}<img src="{{ category.icon.purl }}"/><br/>{% endif %}
{% endif %}
{% for entry in data %}
{{ entry }}
{% else %}
{{ engineMSG('commin', lang['msgi_no_news']) }}
{% endfor %}
{{ pagination }}
