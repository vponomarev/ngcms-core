# Шаблон news.print.tpl

Шаблон отвечает за вывод версии для печати полной новости.

Данный шаблон функционально абсолютно идентичен шаблону <a href="news.full.tpl.html">news.full.tpl</a>, для генерации версии для печати всегда используется тот же набор функций, которые используются и для генерации полной страницы новости.

Единственное отличие - при генерации версии для печати отключаются обработчики новостей `onBeforeShow()` и `onAfterShow()`. К примеру, эти обработчики испльзуются плагином `comments` для генерации формы добавления нового комментария и для показа списка комментариев к новости.

## Пример заполнения шаблона

```html
[TWIG]
<html>
<head>
    <title>{{ news.title }}</title>
    <meta charset="{{ lang['encoding'] }}" />

    <style>
        body,
        td {
            font-family: verdana, arial, sans-serif;
            color: #666;
            font-size: 80%;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: verdana, arial, sans-serif;
            color: #666;
            font-size: 100%;
            margin: 0px;
        }
    </style>
</head>

<body bgcolor="#ffffff" text="#000000">
    <table border="0" width="100%" cellspacing="1" cellpadding="3">
        <tbody>
            <tr>
                <td width="100%">
                    {{ news.categories.masterText }} &raquo; {{ news.title }}
                    <br />
                    <br />
                    <div style="border-top: 1px solid #ccc; width: 100%;">
                        <br />
                        <small>
							{{ news.date }}
							{{ lang['by'] }}
							{% if pluginIsActive('uprofile') %}<a href="{{ news.author.url }}">{% endif %}
								{{ news.author.name }}
							{% if pluginIsActive('uprofile') %}</a>{% endif %}
						</small>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    {{ news.short }}
                    {{ news.full }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
[/TWIG]
```
