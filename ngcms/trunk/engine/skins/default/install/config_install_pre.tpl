<script type="text/javascript">
var ajax = new sack();
function check_connection(){
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
<input type="hidden" name="agree" value="1" />
<input type="hidden" name="action" value="config" />
<input type="hidden" name="stage" value="2" />

<p style="padding: 5px 0px 5px 15px;">
����� ����������� ��� ����� ������, ��� ����� �����!<br />

$ERR[general_error]
$ERR[general_error_info]
<table width="650" align="center" class="content">
<tr>
<td width="50%" class="contentEntry1">������ ��: $ERR[reg_dbhost]</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbhost" value="{reg_dbhost}"></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">��� ������������ ��: $ERR[reg_dbuser]</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbuser" value="{reg_dbuser}"></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">������ ��:</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbpass" value="{reg_dbpass}"></td>
</tr>
<tr class="odd">
<td width="50%" class="contentEntry1">�������� ��: $ERR[reg_dbname]</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbname" value="{reg_dbname}"></td>
</tr>
<tr class="even">
<td width="50%" class="contentEntry1">������� ����� ��:</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbprefix" value="{reg_dbprefix}"></td>
</tr>
<tr class="odd">
<td width="50%" class="contentEntry1">������������ ������������ � �� � mySQL<br>��������� ���������������� ������ � ������� ��</td>
<td width="50%" class="contentEntry2"><input type=checkbox name="reg_autocreate" value="1" $DATA[reg_autocreate]></td>
</tr>
<tr class="even">
<td width="50%" class="contentEntry1">���������������� ����� ��� ������� ��: $ERR[reg_dbadminuser]</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminuser" value="$DATA[reg_dbadminuser]"></td>
</tr>
<tr class="odd">
<td width="50%" class="contentEntry1">���������������� ������ ��� ������� ��:</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminpass" value="$DATA[reg_dbadminpass]"></td>
</tr>
<tr class="even">
<td colspan="2"><input type=button value="��������� ����������� � ������� ��" class="button" onclick="check_connection();">&nbsp;<br>
</tr>
<tr class="odd">
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">����� �����: $ERR[home_url]</td>
<td width="50%" class="contentEntry2"><input type="text" name="home_url" value="$DATA[home_url]" size="40"><br /><small>������� � http:// � ��� ����� �� �����</small></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">��� ��������������: $ERR[reg_username]</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_username" value="$DATA[reg_username]"></tr>
<tr>
<td width="50%" class="contentEntry1">������ ��������������: $ERR[reg_password1]</td>
<td width="50%" class="contentEntry2"><input type="password" size="40" name="reg_password1"></tr>
<tr>
<td width="50%" class="contentEntry1">������ ��� ���:</td>
<td width="50%" class="contentEntry2"><input type="password" size="40" name="reg_password2"></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">Email ��������������:</td>
<td width="50%" class="contentEntry2"><input type="text" size="40" name="reg_email" value="$DATA[reg_email]"></td>
</tr>
<tr>
<td width="50%" class="contentEntry1" colspan="2"><input type="submit" value="���������� ���������!" class="button" /></td>
</tr>
</table>
</p>
</form>
