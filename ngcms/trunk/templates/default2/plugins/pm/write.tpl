<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>
<style>
.suggestWindow { background:#f6f8fb; border: 1px solid #aaaaaa; color: #232323; width: 316px; position: absolute; display: block; visibility: hidden; padding: 0px; font: normal 12px tahoma, sans-serif; top: 0px; margin: 0; left: 80px; }
#suggestBlock { padding-top: 2px; padding-bottom: 2px;  width: 100%; border: 0px; }
#suggestBlock td { padding-left: 2px; }
#suggestBlock tr { padding: 3px; padding-left: 8px; background: white; }
/* #suggestBlock tr:hover, */
#suggestBlock .suggestRowHighlight { background: #59a6ec; color: white; cursor: default; }
#suggestBlock .cleft { padding-left: 5px; }
#suggestBlock .cright { text-align: right; padding-right: 5px; }
.suggestClose { display: block; text-align: right; font: normal 10px verdana, tahoma, sans-serif; background:#3c9c08; color: white; padding:3px; cursor: pointer; }
</style>
<form method=post name=form action="{php_self}?action=send">
<div class="post">
	<div class="post-header">
		<div class="post-title">Личные сообщения - Новое</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td colspan="2"><a href="/plugin/pm/?action=write">Написать сообщение</a> | <a href="/plugin/pm/">Входящие сообщения</a> | <a href="/plugin/pm/?action=outbox">Отправленные сообщения</a></td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="40%">{l_pm:subject}:</td>
					<td width="60%"><input class="input" type="text" name="title" /></td>
				</tr>
				<tr>
					<td width="40%">{l_pm:too}:<br><span class="impot"><small>{l_pm:to}</small></span></td>
					<td width="60%"><input class="input" type="text" name="sendto" id="sendto" autocomplete="off" value="{username}" /><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{skins_url}/images/loading.gif"/></span></td>
				</tr>
				<tr>
					<td width="40%">Текст сообщения:</td>
					<td width="60%"><textarea onkeypress="if(event.keyCode==10 || (event.ctrlKey && event.keyCode==13)) {add_comment();}" name="content" id="content" class="textarea" style="width:98%; height: 80px;"></textarea><br /><input name="saveoutbox" type="checkbox"/> {l_pm:saveoutbox}</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{l_pm:send}" accesskey="s" />
					</td>
				</tr>
			</table>
		</p>
	</div>
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