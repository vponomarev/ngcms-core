<div class="block-title">�������� �������� ��������� ������</div>
<form name="register" action="/plugin/auth_loginza/register/" method="post">
<input type="hidden" name="type" value="doregister" />
	<div class="label label-table">
		<label>��� ������������:</label>
		<span class="input2"><input name="login" type="text" title="��� ������������" value="{login}"/></span>
		<div class="label-desc">�� ������ �������� �� ���� ����� �����/������</div>
	</div>
	<div class="label label-table">
		<label>������:</label>
		<span class="input2"><input name="password" type="text" title="������" value="{password}"/></span>
		<div class="label-desc">���������� ��� ������ ����� �����/������</div>
	</div>
	<div class="label label-table">
		<label>E-mail �����:</label>
		<span class="input2"><input name="email" type="text" title="E-mail �����" value="{email}"/></span>
		<div class="label-desc">��� �������������� ������ ����� ������ ����� ���������� �� ���� ����� (�� ����������� ��� ����������)</div>
	</div>
	<div class="clearfix"></div>
	<div class="label">
		<label class="pull-left"><input type="checkbox" name="agree">
		� ����������� � <a href="#">��������</a> � <a href="#">���������</a> � �������� ��.</label>
		<input type="submit" value="������������������" class="button pull-right">
	</div>
</form>
<script type="text/javascript">
	function validate() {
		if (document.register.agree.checked == false) {
			window.alert('������������ � ��������� � ���������.');
			return false;
		}
		return true;
	}
</script>