��������� �������������!
��� ������ ��� ���� ���������� ��������� ����� ����� �������� �����.

��������� �����:
* ID: {{ form.id }}
* ��������: {{ form.title }}
* ��������: {{ form.description }}
{% if (flags.link_news) %}
������ �� �������:
* ID: {{ news.id }}
* ������: {{ news.url }}
* ���������:{{ news.title }}

{%endif %}

������������ �������� ��������� ����:
{% for entry in entries %}
* ({{ entry.id }})[{{ entry.title }}]: {{ entry.value }}
{% endfor %}

---
� ���������,
�������� ����� ������ ����� (�������� �� ���� Next Generation CMS - http://ngcms.ru/)
