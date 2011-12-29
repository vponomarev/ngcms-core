<div class="body">
<form action="" method="post" name="db" id="db">
<input type="hidden" name="action" value="config" id="action"/>
<input type="hidden" name="stage" value="3" id="stage"/>
{hinput}
<p style="width: 99%;">На данной странице отображается список <u>всех</u> плагинов (вместе с кратким описанием), входящих в поставку.<br/>
Некоторые плагины Вы можете прямо здесь активировать и настроить.<br/>Те плагины, для которых активация на этой странице недоступна, требуют более сложной настройки, которую Вы сможете провести сразу после установки.</p>
<table class="plugTable" cellspacing="1" cellpadding="2">
<thead><tr><td style="background-color: #dbe4ed; color:#3c9c08;">Включить</td><td style="background-color: #dbe4ed; color:#3c9c08;">ID</td><td width="25%" style="background-color: #dbe4ed; color:#3c9c08;">Название</td><td style="background-color: #dbe4ed; color:#3c9c08;">Описание</td></tr></thead>
{plugins}
</table>
<div style="float: left; width: 99%;">
<table width="100%">
<tr><td width="33%"><input type="button" value="&laquo;&laquo; Назад" onclick="document.getElementById('stage').value='1'; document.getElementById('db').submit();" class="filterbutton"/></td><td></td><td width="33%" style="text-align: right;"><input type="submit" value="Далее &raquo;&raquo;"/ class="filterbutton"></td></tr>
</table>
</div>
</form>
</div>