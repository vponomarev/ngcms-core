<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>
<style>
.suggestWindow { background: #f9f9f9; border: 1px solid #e2e2e2; width: 316px; position: absolute; display: block; visibility: hidden; padding: 0px; font: normal 12px  tahoma, sans-serif; top: 0px; margin: 0; left: 80px; }
#suggestBlock { padding-top: 2px; padding-bottom: 2px; width: 100%; border: 0px; }
#suggestBlock td { padding-left: 2px; }
#suggestBlock tr { padding: 3px; padding-left: 8px; background: white; }
/* #suggestBlock tr:hover, */
#suggestBlock .suggestRowHighlight { background: #59a6ec; color: white; cursor: default; }
#suggestBlock .cleft { padding-left: 5px; }
#suggestBlock .cright { text-align: right; padding-right: 5px; }
.suggestClose { display: block; text-align: right; font: normal 10px verdana, tahoma, sans-serif; background:#ffeeee; padding:3px; cursor: pointer; }
</style>
<form method=post name=form action="{php_self}?action=send">
<div class="block-title">Личные сообщения - Новое</div>
<table class="table table-striped table-bordered">
	<tr align="center">
		<td colspan="2"><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
	</tr>
	<tr>
		<td class="label">{l_pm:subject}:</td>
		<td><input class="input" type="text" name="title" /></td>
	</tr>
	<tr>
		<td class="label">{l_pm:too}:<br><span class="impot"><small>{l_pm:to}</small></span></td>
		<td><input class="input" type="text" name="sendto" id="sendto" autocomplete="off" value="{username}" /><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{skins_url}/images/loading.gif"/></span></td>
	</tr>
	<tr>
		<td colspan="2">{quicktags}{smilies}</td>
	</tr>
	<tr>
		<td colspan="2"><textarea onkeypress="if(event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) {add_comment();}" name="content" id="content" class="textarea" style="height:120px; width: 98%;"></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><input name="saveoutbox" type="checkbox"/> {l_pm:saveoutbox}</td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input class="button" type="submit" value="{l_pm:send}" accesskey="s" />
</div>
</form>
<script language="javascript" type="text/javascript">
	function systemInit() {
		new ngSuggest('sendto',
			{ 
				'iMinLen' : 1,
				'stCols' : 1,
				'stColsClass': ['cleft'],
				'lId' : 'suggestLoader',
				'hlr' : 'true',
				'stColsHLR'	: [ true ],
				'reqMethodName' : 'pm_get_username',
			}
		);
	}
	if (document.body.attachEvent) {
		document.body.onload = systemInit;
	} else {
		systemInit();
	}
</script>