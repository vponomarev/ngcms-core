<script language="javascript">
	function sw_update() {
		var x = document.getElementById('switcher_selector');
		document.cookie = 'sw_template=' + x.value + '; expires=Mon,31-Jan-2017';
		document.location = document.location;
	}
</script>
<select id="switcher_selector" style="width: 150px;">{list}</select>
<input type=button onclick="sw_update();" class="btn btn-primary btn-large" value="Выбрать">