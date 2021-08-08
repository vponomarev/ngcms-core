# Шаблон `static/default.print.tpl`

Шаблон отвечает за вывод страницы печати для статической страницы (статьи).

Указываемый здесь шаблон – шаблон по умолчанию для отображения статических страниц.

Для каждого шаблона `static/NAME.tpl` необходимо создавать свою страницу для печати с именем `static/NAME.print.tpl`.

## Доступные блоки / переменные

Набор переменных и блоков одинаков с шаблоном [static/default.tpl](templates/static/default.tpl.md).

# Пример заполнения шаблона

```html
<html>
<head>
	<meta charset="{{ lang['encoding'] }}" />
	<style>
		body, td {
			font-family: verdana, arial, sans-serif;
			color: #666;
			font-size: 80%;
		}

		h1, h2, h3, h4 {
			font-family: verdana, arial, sans-serif;
			color: #666;
			font-size: 100%;
			margin: 0px;
		}
	</style>
	<title>{{ lang['print_version'] }} > {{ title }}</title>
</head>
<body bgcolor="#ffffff" text="#000000">
<table border="0" width="100%" cellspacing="1" cellpadding="3">
	<tr>
		<td width="100%"><a href="/">{{ lang['mainpage'] }}</a> > {{ title }}
			<hr>
		</td>
	</tr>
	<tr>
		<td width="100%">{{ content }}
			<hr>
			<a href="javascript:history.go(-1)">{{ lang['go_back'] }}</a></td>
	</tr>
</table>
</body>
</html>
```
