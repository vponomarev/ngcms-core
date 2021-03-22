Используемые в работе JavaScript'ы
-----------------------------------
За внешний вид вашего сайта отвечают шаблоны. Но некоторые элементы управления, предоставляемые ядром CMS, требуют для своей также определённого набора JavaScript'ов. В этом разделе будет дано краткое описание используемых JScript'ов.
Все скрипты, необходимые для работы ядра CMS, находятся в папке lib, вот их краткое описание:
admin.js - JScript необходимый для работы админ-панели
ajax.js - библиотека для работы AJAX-based функционала (немного модифицированная `Simple AJAX Code-Kit (SACK) v1.6.1`)
functions.js - набор функций общего пользования
Библиотека admin.js
-------------------
Содержит функцию json_encode(), которая позволяет генерировать JSON код и передавать его в ядро CMS:
<pre >
//
// Function from PHP to Javascript Project: php.js
// URL: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_json_encode/
function json_encode(mixed_val) {
...
</pre>
Библиотека ajax.js
------------------
Представляет собой модифицированную библиотеку `Simple AJAX Code-Kit (SACK) v1.6.1` и используется для любых AJAX вызовов в ядре CMS и во всех плагинах.
Пример использования данной библиотеки можно найти а плагине comments, в шаблоне по умолчанию:
/templates/default/plugins/comments/comments.form.tpl
Библиотека functions.js
-----------------------
Содержит набор функций общего пользования, отвечающих за работу различных элементов.
Используемые в работе HTML блоки / ID и STYLE элементов
-------------------------------------------------------
Следует учитывать, что для своей работы ядро CMS (и некоторые плагины) используют фиксированные идентификаторы блоков или заданный заранее набор CSS стилей.
Стили CSS:
.spoiler - набор стилей для спойлера (BB-тег [spoiler] .. [/spoiler]):
<pre >
/* SPOILERs */
.spoiler {
border:solid 1px #adbac6;
background:#ebeef7;
margin:5px 0px 5px 0px;
padding: 0px;
clear:both;
}
.spoiler .sp-head {
padding:4px 0 4px 2px;
cursor: pointer;
}
.spoiler .sp-head b {
background:url(../images/spoiler-plus.gif) no-repeat;
float:left;
width:9px;
height:9px;
margin:2px 4px 0 2px
}
.spoiler .sp-head b.expanded {
background:url(../images/spoiler-minus.gif) no-repeat;
float:left;
width:9px;
height:9px;
}
.spoiler .sp-body {
border-top:solid 1px #adbac6;
background: #f7f8fc;
display: none;
}
</pre>
При вставке BB-тега он вставляется в виде блока кода:
<pre >
&lt;div class-"spoiler">
&lt;div class-"sp-head" onclick-"toggleSpoiler(this.parentNode, this);">&lt;b>&lt;/b>Раскрыть&lt;/div>
&lt;div class-"sp-body">_тут текст спойлера_&lt;br />&lt;/div>
&lt;/div>
</pre>
.answer - используется при отображении содержимого BB-тега [quote] .. [/quote]
Блоки с собственным ID:
tags - используется при отображении списка BB кодов
save_area - используется при отображении списка BB кодов
