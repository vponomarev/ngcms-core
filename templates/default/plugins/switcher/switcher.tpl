<script language="javascript">
	function sw_update() {
		var x = document.getElementById('switcher_selector');
		document.cookie='sw_template='+x.value+'; expires=Mon,31-Jan-2017';
		document.location = document.location;
	}
</script>
<div class="block archive-block">
	<div class="block-title">Выбор профиля</div>
	<select id="switcher_selector" >{list}</select> <input type=button onclick="sw_update();" class="button" value="Выбрать">
</div>