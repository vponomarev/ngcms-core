<form name="register" action="/plugin/auth_loginza/register/" method="post">
<input type="hidden" name="type" value="doregister" />
<div class="full">
	<header><h1>�������� �������� ��������� ������</h1></header>
	<div class="telo">

		<div class="input"><label>��� ������������:</label><input name="login" type="text" title="��� ������������" value="{login}"/><br /><small>�� ������ �������� �� ���� ����� �����/������</small></div>
		<div class="input"><label>������:</label><input name="password" type="text" title="������" value="{password}"/><br /><small>���������� ��� ������ ����� �����/������</small></div>
		<div class="input"><label>E-mail �����:</label><input name="email" type="text" title="E-mail �����" value="{email}"/><br /><small>��� �������������� ������ ����� ������ ����� ���������� �� ���� ����� (�� ����������� ��� ����������)</small></div>
		<div><input class="btn btn-primary btn-large" type="submit" value="������������������!"/></div>
	</div>
</div>
</form>