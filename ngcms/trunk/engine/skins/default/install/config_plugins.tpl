<div class="body">
<form action="" method="post" name="db" id="db">
<input type="hidden" name="action" value="config" id="action"/>
<input type="hidden" name="stage" value="3" id="stage"/>
{hinput}
<p style="width: 99%;">�� ������ �������� ������������ ������ <u>����</u> �������� (������ � ������� ���������), �������� � ��������.<br/>
��������� ������� �� ������ ����� ����� ������������ � ���������.<br/>�� �������, ��� ������� ��������� �� ���� �������� ����������, ������� ����� ������� ���������, ������� �� ������� �������� ����� ����� ���������.</p>
<table class="plugTable" cellspacing="1" cellpadding="2">
<thead><tr><td>��������</td><td>ID</td><td width="25%">��������</td><td>��������</td></tr></thead>
{plugins}
</table>
<div style="float: left; width: 99%;">
<table width="100%">
<tr><td width="33%"><input type="button" value="&laquo;&laquo; �����" onclick="document.getElementById('stage').value='1'; document.getElementById('db').submit();"/></td><td></td><td width="33%" style="text-align: right;"><input type="submit" value="����� &raquo;&raquo;"/></td></tr>
</table>
</div>
</form>
</div>