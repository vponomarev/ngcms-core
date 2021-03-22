Шаблон main.tpl
---------------

![](templates/template_structure_small.gif)

Файл является ядром шаблонизатора и отвечает за генерацию структуры всего HTML документа.

В нём вы определяете все HTML заголовки и другие обязательные элементы разметки для XHTML 1.0 совместимого документа.
Если вы создаёте шаблон для NGCMS на основе уже существующего HTML-форматирования (HTML-шаблона), то удобнее всего взяв за основу исходный index.html шаблон, переименовать его в main.tpl и начать переделывать, заменяя логические блоки шаблона-исходника на переменные, предоставляемые движком.

Считается <u>правилом хорошего тона</u> указывать ссылку на сайт NGCMS внутри вашего нового шаблона. Обычно её размещают внизу страницы.
Прошу обратить внимание, что лицензионное соглашение, используемое при распространении NGCMS не заставляет вас оставлять какие-либо ссылки на ваших страницах, но оставляя ссылку на страницу NGCMS вы способствуете развитию самой системы, а это выгодно всем, включая вас.

Пример ссылки:
<pre >&lt;a href="http://ngcms.ru/" target-"_blank">Powered by &lt;b>NGCMS&lt;/b>&lt;/a></pre>

Доступные блоки/переменные
--------------------------

Блоки:

[sitelock] ... [/sitelock] - блокировка контента сайтаПри активации режима "заблокировать сайт" (настройки -> настройки системы -> основные настройки) всё содержимое этого блока будет прятаться, а на его место - выводиться содержимое шаблона <a href="lock.tpl.html">lock.tpl</a>
[debug] ... [/debug] - содержимое блока будет отображаться при включении режима "генерация отладочной информации" (настройки -> настройки системы -> безопасность) Содержимое блока показывается <u>только</u> администратору сайта
[is-logged] ... [/is-logged] - содержимое блока выводится в случае, если страница показывается залогиненному посетителю
[isnt-logged] ... [/isnt-logged] - содержимое блока выводится в случае, если страница показывается <u>не</u>залогиненному посетителю

Переменные:

{mainblock} - основной блок информации, именно в этом блоке фактически отображается смысловое содержание страницы
{home} - ссылка (относительная) на домашнюю страницу сайта
{titles} - заголовок страницы (помещается в тег &lt;title> блока &lt;head>)
{htmlvars} - данную переменную <u>необходимо</u> разместить внутри HTML блока &lt;head>, она содержит вызовы CSS/JS скриптов, необходимых для работы самого движка или плагинов
{queries} - информационно-диагностическая переменная, показывает количество SQL запросов использованных для генерации страницы
{exectime} - информационно-диагностическая переменная, показывает потраченное на генерацию страницы время (с точностью до 1/100 секунды)
{search_form} - содержит форму краткого поиска (шаблон: search.form.tpl)
{personal_menu} - содержит блок приветствия/авторизации пользователя (шаблон: usermenu.tpl)
{personal_menu:logged} - если пользователь залогинен, то содержит блок приветствия пользователя (шаблон: usermenu.tpl); иначе - пустоту
{personal_menu:not.logged} - если пользователь <u>не</u> залогинен, то содержит блок авторизации пользователя (шаблон: usermenu.tpl); иначе - пустоту
{categories} - содержит древовидное меню категорий новостей (см. также шаблон: <a href-"categories.tpl.html">categories.tpl</a>)
{what} - идентификатор CMS ("Next Generation CMS")

{version} - установленная версия CMS<u>Желательно</u>, но <u>не обязательно</u> внутри HTML блока &lt;head> указывать переменную generator. Вам этот тег не принесёт никакого вреда, но такая запись будет полезна для развития NGCMS.
<pre >&lt;meta name="generator" content="{what} {version}" /></pre>

{debug_queries} - отладочная переменная (видимая только администратору), содержит HTML-список всех SQL запросов с указанием времени их исполнения
{debug_profiler} - отладочная переменная (видимая только администратору), содержит HTML-список наиболее значимых действий системы с указанием времени их исполнения


Необходимые для работы элементы
-------------------------------

Для корректной работы всех элементов ядра CMS вам необходимо подключить несколько JavaScript'ов, обеспечивающих работу части функций ядра, а также некоторые другие элементы:

Загрузка необходимых JavaScript'ов, добавляется в секцию &lt;head&gt; шаблона:

<pre >
&lt;script type="text/javascript" src="{scriptLibrary}/functions.js">&lt;/script>
&lt;script type="text/javascript" src="{scriptLibrary}/ajax.js">&lt;/script>
</pre>


Подключение невидимого блока, в секцию &lt;body&gt; шаблона, блок должен иметь id-"loading-layer".
Возможны различные варианты реализации такого подхода. Например так:

<pre >
&lt;div id="loading-layer" style="display:none; width:180px; height:40px; background:#fff; text-align:center; border:1px solid #eeeeef;">&lt;img src="{tpl_url}/images/loading.gif" alt-"" />&lt;/div>
</pre>

Указанный блок необходим для корректной работы AJAX библиотеки и отображается в момент обращения к серверу для получения необходимой дополнительной информации.

Пример заполнения шаблона
-------------------------

Ниже приведён пример заполнения шаблона из поставки "по умолчанию":

<pre >
&lt;!DOCTYPE html PUBLIC "=//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
&lt;html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{l_langcode}" lang="{l_langcode}" dir="ltr">
&lt;head>
&lt;meta http-equiv="content-type" content="text/html; charset={l_encoding}" />
&lt;meta http-equiv="content-language" content="{l_langcode}" />
&lt;meta name-"generator" content="{what} {version}" />
&lt;meta name="document-state" content="dynamic" />
{htmlvars}
&lt;link href="{tpl_url}/style.css" rel="stylesheet" type="text/css" media="screen" />
&lt;link href="{home}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />
&lt;script type="text/javascript" src="{scriptLibrary}/functions.js">&lt;/script>
&lt;script type="text/javascript" src="{scriptLibrary}/ajax.js">&lt;/script>
&lt;title>{titles}&lt;/title>
&lt;/head>
&lt;body>
[sitelock]
&lt;div id="loading-layer"><img src="{tpl_url}/images/loading.gif" alt="" />&lt;/div>
...
[/sitelock]
&lt;/body>
&lt;/html>
</pre>
