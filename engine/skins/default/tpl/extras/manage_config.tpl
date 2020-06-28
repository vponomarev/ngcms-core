<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=configuration">{{ lang['extras'] }}</a>
			&#8594; {{ lang['manage_vars'] }}</td>
	</tr>
</table>

<form method="post" action="?mod=extras&manageConfig=1">
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="mod" value="extras"/>
	<input type="hidden" name="manageConfig" value="1"/>
	<input type="hidden" name="action" value="commit"/>

	<div id="configAreaX"></div>
	<textarea name="config" id="configArea" cols="120" rows="40" style="width: 99%; font: normal 11px/14px Courier,Tahoma,sans-serif;"></textarea>
	<!-- <input type="submit" value="Commit changes"/> --> &nbsp;
	<input type="button" value="Load data" onclick="loadData(); return false;"/> &nbsp;
	<input type="button" value="Show content" onclick="showContent(); return false;"/>
</form>

<script type="text/javascript" language="javascript">
	function loadData() {
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.extras.getPluginConfig',
			rndval: new Date().getTime(),
			params: json_encode({token: '{{ token }}'})
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				alert('Error parsing JSON output. Result: ' + linkTX.response);
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], 'ERROR');
			}
			var line = resTX['content'];
			var newline = line.replace(/\\u/g, "%u");
			//$('#configAreaX').html(newline);
			$('#configArea').val(unescape(newline));
			//$('#configArea').val("\u0420"+"\u0415\u041a\u041b\u0410\u041c\u0410_\u041d\u0410_\u041c\u041e\u0420\u0414\u0415");
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('HTTP error during request', 'ERROR');
		});
	}

	function showContent() {
		alert($('#configArea').val());
	}


</script>