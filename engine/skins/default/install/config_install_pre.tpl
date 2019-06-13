<script type="text/javascript">
	var ajax = new sack();
	function check_connection() {
		//var res = document.getElementById('connection_result');
		var form = document.getElementById('db');
		ajax.execute = 1;
		ajax.setVar("action", "testdb");
		ajax.setVar("host", form.reg_dbhost.value);
		if (form.reg_autocreate.checked) {
			ajax.setVar("user", form.reg_dbadminuser.value);
			ajax.setVar("pass", form.reg_dbadminpass.value);
		} else {
			ajax.setVar("user", form.reg_dbuser.value);
			ajax.setVar("pass", form.reg_dbpass.value);
		}
		ajax.setVar("dbname", form.reg_dbname.value);
		ajax.requestFile = 'install.php';
		ajax.method = 'POST';
		ajax.runAJAX();
	}
</script>

<form action="" method="post" name="db" id="db">
	<input type="hidden" name="agree" value="1"/>
	<input type="hidden" name="action" value="config"/>
	<input type="hidden" name="stage" value="2"/>

	<p style="padding: 5px 0px 5px 15px;">
		Будте внимательны при вводе данных, они очень важны!<br/>

		$ERR[general_error]
		$ERR[general_error_info]
	<table width="650" align="center" class="content">
		<tr>
			<td width="50%" class="contentEntry1">Сервер БД: $ERR[reg_dbhost]</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbhost" value="{reg_dbhost}">
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Имя пользователя БД: $ERR[reg_dbuser]</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbuser" value="{reg_dbuser}">
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Пароль БД:</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbpass" value="{reg_dbpass}">
			</td>
		</tr>
		<tr class="odd">
			<td width="50%" class="contentEntry1">Название БД: $ERR[reg_dbname]</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbname" value="{reg_dbname}">
			</td>
		</tr>
		<tr class="even">
			<td width="50%" class="contentEntry1">Префикс имени БД:</td>
			<td width="50%" class="contentEntry2">
				<input type="text" size="40" name="reg_dbprefix" value="{reg_dbprefix}"></td>
		</tr>
		<tr class="odd">
			<td width="50%" class="contentEntry1">Автосоздание пользователя и БД в mySQL<br>Требуется административный
				доступ к серверу БД
			</td>
			<td width="50%" class="contentEntry2">
				<input type=checkbox name="reg_autocreate" value="1" $DATA[reg_autocreate]></td>
		</tr>
		<tr class="even">
			<td width="50%" class="contentEntry1">Административный логин для сервера БД: $ERR[reg_dbadminuser]</td>
			<td width="50%" class="contentEntry2">
				<input type="text" size="40" name="reg_dbadminuser" value="$DATA[reg_dbadminuser]"></td>
		</tr>
		<tr class="odd">
			<td width="50%" class="contentEntry1">Административный пароль для сервера БД:</td>
			<td width="50%" class="contentEntry2">
				<input type="text" size="40" name="reg_dbadminpass" value="$DATA[reg_dbadminpass]"></td>
		</tr>
		<tr class="even">
			<td colspan="2">
				<input type=button value="Проверить подключение к серверу БД" class="filterbutton" onclick="check_connection();">&nbsp;<br>
		</tr>
		<tr class="odd">
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Адрес сайта: $ERR[home_url]</td>
			<td width="50%" class="contentEntry2">
				<input type="text" name="home_url" value="$DATA[home_url]" size="40"><br/>
				<small>начиная с http:// и без слэша на конце</small>
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Имя администратора: $ERR[reg_username]</td>
			<td width="50%" class="contentEntry2">
				<input type="text" size="40" name="reg_username" value="$DATA[reg_username]">
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Пароль администратора: $ERR[reg_password1]</td>
			<td width="50%" class="contentEntry2"><input type="password" size="40" name="reg_password1">
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Пароль еще раз:</td>
			<td width="50%" class="contentEntry2"><input type="password" size="40" name="reg_password2"></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">Email администратора:</td>
			<td width="50%" class="contentEntry2">
				<input type="text" size="40" name="reg_email" value="$DATA[reg_email]"></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1" colspan="2">
				<input type="submit" value="Продолжить установку!" class="filterbutton"/></td>
		</tr>
	</table>
	</p>
</form>
