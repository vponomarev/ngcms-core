<div class="body">
<form action="" method="post" name="db" id="db">
<input type="hidden" name="action" value="config" id="action"/>
<input type="hidden" name="stage" value="2" id="stage"/>
{hinput}


<table border="0">
<tr>
<td valign="top" width="450">
<div class="permBlock">
<div class="permHead">����������� ���������� �������</div>
<div class="permData">
<table width="100%" cellspacing="0" cellpadding="1">
<tr><td>������ PHP 4.3.2 � ����</td><td>{php_version}</td></tr>
<tr><td>������ mySQL 4.x/5.x</td><td>{sql_version}</td></tr>
<tr><td>��������� ������ ZLib</td><td>{gzip}</td></tr>
<tr><td>��������� XML</td><td>{xml}</td></tr>
<tr><td>���������� GD</td><td>{gdlib}</td></tr>
</table>
</div>
</div>

<br/>
<div class="permBlock">
<div class="permHead">��������� PHP</div>
<div class="permData">
<table width="100%">
<thead><tr><td>��������</td><td>�������������</td><td>�����������</td></thead>
<tr><td>Register Globals</td><td>���������</td><td>{flag:register_globals}</td></tr>
<tr><td>Magic Quotes GPC</td><td>���������</td><td>{flag:magic_quotes_gpc}</td></tr>
<tr><td>Magic Quotes Runtime</td><td>���������</td><td>{flag:magic_quotes_runtime}</td></tr>
<tr><td>Magic Quotes Sybase</td><td>���������</td><td>{flag:magic_quotes_sybase}</td></tr>
<tr><td><small>Allow call time pass reference</small></td><td>��������</td><td>{flag:allow_call_time_pass_reference}</td></tr>
</table>
</div>
</div>

</td>
<td width="10">&nbsp;</td>
<td valign="top" width="500">
<div class="permBlock">
<div class="permHead">������� � ������ � ������</div>
<div class="permData">
<table width="100%">
<thead><tr><td>����/�����</td><td>CHMOD</td><td>������</td></thead>
{chmod}
</table>
</div>
</div>
</td>
<td></td>
</tr>
</table>
<br/>
{error_message}
<br/>
<table width="100%">
<tr><td width="33%"><input type="button" value="&laquo;&laquo; �����" onclick="document.getElementById('stage').value='0'; form.submit();"/></td><td align="center">[error_button]<input style="background-color: red; color: white; font-weight: bold;" type="button" value="��������� ��������" onclick="document.getElementById('stage').value='1'; form.submit();"/>[/error_button]</td><td width="33%" style="text-align: right;"><input type="submit" value="����� &raquo;&raquo;"/></td></tr>
</table>
</form>
</div>