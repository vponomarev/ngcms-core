<div class="body">
<form action="" method="post" name="form" id="form">
<input type="hidden" name="action" value="config" id="action" />
<input type="hidden" name="stage" value="1" />
{hinput}

<p>
�� ������ �������� ��� ���������� ������ ��������� ����������� � ��<br/>
����� ����������� ��� ����� ������, ��� ����� �����!</p>

{error_message}
<table width="100%" align="center" class="content" cellspacing="0" cellpadding="0">
<tr>
<td width="70%" class="contentEntry1">������ �� <span class="req">*</span>: {err:reg_dbhost}<br/><small>������� ������, �� ������� ����� ��������� ���� ������</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbhost" value="{reg_dbhost}"/></td>
</tr>
<tr>
<td width="70%" class="contentEntry1">��� ������������ �� <span class="req">*</span>: {err:reg_dbuser}<br/><small>��� ������������, ������� ����� �������������� ��� ����������� � ��</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbuser" value="{reg_dbuser}"/></td>
</tr>
<tr>
<td width="70%" class="contentEntry1">������ ��:<br/><small>������ ������������</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbpass" value="{reg_dbpass}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">�������� �� <span class="req">*</span>: {err:reg_dbname}<br/><small>�������� ��, � ������� ����� ��������� ������. �� ������ �������������� ������� ������ �� ���� ������������ ����� `������������ ������������`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbname" value="{reg_dbname}"/></td>
</tr>
<tr class="even">
<td width="70%" class="contentEntry1">������� ����� ������:<br/><small>�������, ������� ����� ����������� �� ���� ������ ����������� ������ (������ '<b>_</b>' ����� ������������� �������� ����� ���������� ���� ��������)</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbprefix" value="{reg_dbprefix}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">������������ ������������ � �� � mySQL<br/><small>��� ������������� ������� ������ ��� ���������� ������� ����� � ������ ������������ � �������� ���� ����� �� �������� ��� ������ � ���������� ���� �������. ������ ��� ������������ <b>root</b></small></td>
<td width="30%" class="contentEntry2"><input type=checkbox name="reg_autocreate" value="1" {reg_autocreate}/></td>
</tr>
<tr class="even">
<td width="70%" class="contentEntry1">���������������� ����� ��� ������� ��: {err:reg_dbadminuser}<br/><small>������ ��� ������ `������������ ������������...`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminuser" value="{reg_dbadminuser}"/></td>
</tr>
<tr class="odd">
<td width="70%" class="contentEntry1">���������������� ������ ��� ������� ��:<br/><small>������ ��� ������ `������������ ������������...`</small></td>
<td width="30%" class="contentEntry2"><input type="text" size="40" name="reg_dbadminpass" value="{reg_dbadminpass}"/></td>
</tr>
</table>
<br/><br/>
<table width="100%">
<tr><td><input type="button" value="&laquo;&laquo; �����" onclick="document.getElementById('action').value=''; form.submit();"/></td><td style="text-align: right;"><input type="submit" value="����� &raquo;&raquo;"/></td></tr>
</table>
</form>
</div>