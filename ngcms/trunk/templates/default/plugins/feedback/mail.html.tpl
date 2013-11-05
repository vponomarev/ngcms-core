<html>
<body>
<div style="font: normal 11px verdana, sans-serif;">
<h3>Уважаемый администратор!</h3>
Вам только что было отправлено сообщение через форму обратной связи.<br/>
<br/>
<h4>Параметры формы:</h4>
<table width="100%" cellspacing="1" cellpadding="1">
<tr><td style="background: #E0E0E0;"><b>ID:</b></td><td style="background: #EFEFEF;">{{ form.id }}</td></tr>
<tr><td style="background: #E0E0E0;"><b>Название:</b></td><td style="background: #EFEFEF;">{{ form.title }}</td></tr>
<tr><td style="background: #E0E0E0;"><b>Описание:</b></td><td style="background: #EFEFEF;">{{ form.description }}</td></tr>
</table>
<br/>

{% if (flags.link_news) %}
<h4>Запрос по новости:</h4>
<table width="100%" cellspacing="1" cellpadding="1">
<tr><td style="background: #E0E0E0;"><b>ID:</b></td><td style="background: #EFEFEF;">{{ news.id }}</td></tr>
<tr><td style="background: #E0E0E0;"><b>Ссылка:</b></td><td style="background: #EFEFEF;">{{ news.url }}</td></tr>
<tr><td style="background: #E0E0E0;"><b>Заголовок:</b></td><td style="background: #EFEFEF;">{{ news.title }}</td></tr>
</table>
<br/>
{%endif %}

<h4>Пользователь заполнил следующие поля:</h4>
<table width="100%" cellspacing="1" cellpadding="1">
<thead>
<tr><td width="15%" style="background: #E0E0E0; font-weight: bold;">ID</td><td width="35%" style="background: #E0E0E0; font-weight: bold;">Заголовок</td><td style="background: #E0E0E0; font-weight: bold;">Содержимое</td></tr>
</thead>
<tbody>
{% for entry in entries %}
<tr style="background: #FFFFFF;" valign="top">
 <td style="background: #EFEFEF;">{{ entry.id }}</td>
 <td style="background: #EFEFEF;">{{ entry.title }}</td>
 <td style="background: #EFEFEF;">{{ entry.value }}</td>
</tr>
{% endfor %}
</tbody>
</table>

<br/>
<br/>
---<br/>
С уважением,<br/>
почтовый робот Вашего сайта (работает на базе <b><font color="#90b500">N</font><font color="#5a5047">ext</font> <font color="#90b500">G</font><font color="#5a5047">eneration</font> CMS</b> - http://ngcms.ru/)
</div>

</body>
</html>

