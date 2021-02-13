Шаблон news.print.tpl
---------------------

Шаблон отвечает за вывод версии для печати полной новости.

Данный шаблонфункционально абсолютно идентичен шаблону <a href="news.full.tpl.html">news.full.tpl</a>, для генерации версии для печати всегда используется тот же набор функций, которые используются и для генерации полной страницы новости.
Единственное отличие - при генерации версии для печати отключаются обработчики новостей onBeforeShow() и onAfterShow(). К примеру, эти обработчики испльзуются плагином comments для генерации формы добавления нового комментария и для показа списка комментариев к новости.


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
&lt;title>{title}&lt;/title>
&lt;/head>
&lt;body bgcolor-"#ffffff" text-"#000000">
&lt;table border-"0" width-"100%" cellspacing-"1" cellpadding-"3">
&lt;tr>
&lt;td width-"100%">
{category} &raquo; {title}&lt;br />&lt;br />
&lt;div style-"border-top: 1px solid #ccc; width: 100%;">&lt;br />&lt;small>{date} {l_by} {author}&lt;/small>&lt;/div>
&lt;/td>
&lt;/tr>
&lt;tr>
&lt;td width-"100%">
{short-story}
{full-story}
&lt;/td>
&lt;/tr>
&lt;/table>
&lt;/body>
&lt;/html>
</pre>
