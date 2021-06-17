Шаблон lock.tpl
================

В случае, если сайт заблокирован (настройки => настройки системы => основные настройки => заблокировать сайт), и шаблон <b>sitelock.tpl</b> <u>не существует</u>, </b> то в блоке <b>[sitelock] ... [/sitelock]</b> шаблона <a href="templates/main.tpl.md"><b>main.tpl</b></a> отображается содержимое этого (<b>lock.tpl</b>) шаблона (см. также шаблон <a href="templates/sitelock.tpl.md"><b>sitelock.tpl</b></a>).

Доступные блоки/переменные
==========================

Переменные:

<b>{lock_reason}</b> - причина блокировки сайта, задаётся панели администрирования (настройки => настройки системы => основные настройки => причина блокировки сайта).


Пример заполнения шаблона
=========================

<pre >&lt;center>&lt;br />{lock_reason}&lt;/center></pre>