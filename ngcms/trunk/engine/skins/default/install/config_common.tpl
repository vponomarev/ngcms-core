<div class="body">
<form action="" method="post" name="db" id="db">
<input type="hidden" name="action" value="install" id="action"/>
<input type="hidden" name="stage" value="3" id="stage"/>
{hinput}
<p>
Для начала установки Вам необходимо ответить ещё на несколько несложных вопросов:
</p>
<fieldset>
<legend><b>Общие параметры</b></legend>
<table>
<tr><td>Адрес сайта:</td><td><input type="text" name="home_url" value="{home_url}" size="60"/></td></tr>
<tr><td>Заголовок сайта:</td><td><input type="text" name="home_title" value="{home_title}" size="60"/></td></tr>
</table>
</fieldset>
<br/>
<fieldset>
<legend><b>Данные администратора</b></legend>
<table>
<tr><td>Логин:</td><td><input type="text" name="admin_login" value="{admin_login}"/></td></tr>
<tr><td>Пароль:</td><td><input type="text" name="admin_password" value="{admin_password}"/></td></tr>
<tr><td>Email адрес:</td><td><input type="text" name="admin_email" value="{admin_email}"/></td></tr>
</table>
</fieldset>
<br/>
<fieldset>
<legend><b>Автозаполнение новостей</b></legend>
<table>
<tr><td>Включить:</td><td><input type="checkbox" value="1" disabled="disabled" name="autodata"{autodata_checked}/></td></tr>
</table>
<small>Режим автозаполнения создаёт предопределённый набор новостей, которые позволят Вам сразу же
после установки оценить возможности движка.</small>
</fieldset>

<div style="float: left; width: 99%;">
<br/>
<table width="100%">
<tr><td width="33%"><input type="button" value="&laquo;&laquo; Назад" onclick="document.getElementById('stage').value='3'; document.getElementById('action').value='config'; form.submit();"/></td><td></td><td width="33%" style="text-align: right;"><input style="font-weight: bold;" type="submit" value="Начать установку"/></td></tr>
</table>
</div>
</form>
</div>