<div class="body">
<form action="" method="post" name="form" id="form">
<input type="hidden" name="action" value="config" id="action" />
<input type="hidden" name="stage" value="1" />
{hinput}

<p>
На данной странице Вам необходимо ввести параметры подключения к БД<br/>
Будте внимательны при вводе данных, они очень важны!</p>

{error_message}
<table width="100%" align="center" class="content" cellspacing="0" cellpadding="0">
<tr>
<td width="70%" class="contentEntry1">Сервер БД <span class="req">*</span>: {err:reg_dbhost}<br/><small>Укажите сервер, на котором будет храниться база данных</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbhost" value="{reg_dbhost}"/></td>
</tr>
<tr>
<td width="70%" class="contentEntry1">Имя пользователя БД <span class="req">*</span>: {err:reg_dbuser}<br/><small>Имя пользователя, которое будет использоваться для подключения к БД</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbuser" value="{reg_dbuser}"/></td>
</tr>
<tr>
<td width="70%" class="contentEntry1">Пароль БД:<br/><small>Пароль пользователя</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbpass" value="{reg_dbpass}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">Название БД <span class="req">*</span>: {err:reg_dbname}<br/><small>Название БД, в которой будут храниться данные. Вы должны предварительно создать данную БД либо использовать режим `автосоздание пользователя`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbname" value="{reg_dbname}"/></td>
</tr>
<tr class="even">
<td width="70%" class="contentEntry1">Префикс имени таблиц:<br/><small>Префикс, который будет добавляться ко всем именам создаваемых таблиц (символ '<b>_</b>' будет автоматически добавлен после указанного вами префикса)</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbprefix" value="{reg_dbprefix}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">Автосоздание пользователя и БД в mySQL<br/><small>Для использования данного режима Вам необходимо указать логин и пароль пользователя у которого есть права на создание баз данных и назначение прав доступа. Обычно это пользователь <b>root</b></small></td>
<td width="30%" class="contentEntry2"><input type=checkbox name="reg_autocreate" value="1" {reg_autocreate}/></td>
</tr>
<tr class="even">
<td width="70%" class="contentEntry1">Административный логин для сервера БД: {err:reg_dbadminuser}<br/><small>Только для режима `автосоздание пользователя...`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminuser" value="{reg_dbadminuser}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">Административный пароль для сервера БД:<br/><small>Только для режима `автосоздание пользователя...`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminpass" value="{reg_dbadminpass}"/></td>
</tr>
</table>
<br/><br/>
<table width="100%">
<tr><td><input type="button" value="&laquo;&laquo; Назад" onclick="document.getElementById('action').value=''; form.submit();"/></td><td style="text-align: right;"><input type="submit" value="Далее &raquo;&raquo;"/></td></tr>
</table>
</form>
</div>