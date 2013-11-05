Уважаемый администратор!
Вам только что было отправлено сообщение через форму обратной связи.

Параметры формы:
* ID: {{ form.id }}
* Название: {{ form.title }}
* Описание: {{ form.description }}
{% if (flags.link_news) %}
Запрос по новости:
* ID: {{ news.id }}
* Ссылка: {{ news.url }}
* Заголовок:{{ news.title }}

{%endif %}

Пользователь заполнил следующие поля:
{% for entry in entries %}
* ({{ entry.id }})[{{ entry.title }}]: {{ entry.value }}
{% endfor %}

---
С уважением,
почтовый робот Вашего сайта (работает на базе Next Generation CMS - http://ngcms.ru/)
