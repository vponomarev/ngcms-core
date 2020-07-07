[TWIG]
{% extends 'main.tpl' %}
{% block body %}
{% set mainblock %}
{{ short }}
{{ full }}
{% endset %}
{{ parent() }}
{% endblock %}
[/TWIG]
