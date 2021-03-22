Шаблон static/default.print.tpl
-------------------------------

Шаблон отвечает за вывод страницу печати для статической страницы (статьи).
Указываемый здесь шаблон - шаблон по умолчанию для отображения статических страниц.

Для каждого шаблона static/NAME.tpl необходимо создавать свою страницу для печати, её имя - static/NAME.print.tpl
Набор переменных и блоков идентичен набору, доступному в шаблоне static/default.tpl

Пример заполнения шаблона
-------------------------

<pre >
&lt;html>
&lt;head>
&lt;meta content-"text/html; charset-utf-8" http-equiv-Content-Type>
&lt;style>
body,td { font-family: verdana, arial, sans-serif; color: #666; font-size: 80%; }
h1,h2,h3,h4 { font-family: verdana, arial, sans-serif; color: #666; font-size: 100%; margin: 0px; }
&lt;/style>
&lt;title>Next Generation > Версия для печати > {title}&lt;/title>
&lt;/head>
&lt;body bgcolor-"#ffffff" text-"#000000">
&lt;table border-"0" width-"100%" cellspacing-"1" cellpadding-"3">
&lt;tr>
&lt;td width-"100%">&lt;a href-"/">Главная&lt;/a> > {title}&lt;hr>&lt;/td>
&lt;/tr>
&lt;tr>
&lt;td width-"100%">{content}&lt;hr>&lt;a href-"javascript:history.go(-1)">Вернуться назад&lt;/a>&lt;/td>
&lt;/tr>
&lt;/table>
&lt;/body>
&lt;/html>
</pre>
