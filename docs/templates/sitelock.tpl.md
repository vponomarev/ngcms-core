Шаблон sitelock.tpl
-------------------

В случае, если сайт заблокирован (настройки -> настройки системы -> основные настройки -> заблокировать сайт), и данный файл шаблона существует, то его содержимое будет выведено вместо любой страницы сайта (см. также шаблон <a href="lock.tpl.md">lock.tpl</a>).

Доступные блоки/переменные
--------------------------

Переменные:

{{ lock_reason }} - причина блокировки сайта, задаётся панели администрирования (настройки -> настройки системы -> основные настройки -> причина блокировки сайта).


Пример заполнения Дефолтного шаблона
-------------------------


<pre >
&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
&lt;html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
&lt;head>
	&lt;meta http-equiv="content-type" content="text/html; charset={{ lang.encoding }}"/>
	&lt;meta http-equiv="content-language" content="{{ lang.langcode }}"/>
	&lt;meta name="generator" content="{{ what }} {{ version }}"/>
	&lt;meta name="document-state" content="dynamic"/>
	&lt;style type="text/css">
		body {
			font: 12px/16px Arial, Helvetica, Tahoma, sans-serif;
			margin: 0;
			padding: 0;
			color: #1f282c;
			word-wrap: break-word;
		}

		.errorwrap {
			margin: 0 auto;
			width: 600px;
			margin-top: 26%;
			text-align: center;
		}

		.errorwrap p {
			margin: 0 0 15px 0;
		}
	&lt;/style>
	&lt;title>{{ lang.site_temporarily_disabled }}&lt;/title>
&lt;/head>
&lt;body>
&lt;div align="center" class="errorwrap">
	&lt;p>{{ lock_reason }}&lt;/p>
&lt;/div>
&lt;/body>
&lt;/html>

</pre>
