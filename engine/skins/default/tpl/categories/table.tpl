<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
<script type="text/javascript" src="{{ scriptLibrary }}/admin.js"></script>
<script type="text/javascript">
	// Process RPC requests for categories
	var categoryUToken = '{{ token }}';

	function categoryModifyRequest(cmd, cid) {
		var rpcCommand = '';
		var rpcParams = [];
		switch (cmd) {
			case 'up':
			case 'down':
			case 'del':
				rpcCommand = 'admin.categories.modify';
				rpcParams = {'mode': cmd, 'id': cid, 'token': categoryUToken};
				break;
		}
		if (rpcCommand == '') {
			alert('No RPC command');
			return false;

		}

		var linkTX = new sack();
		linkTX.requestFile = 'rpc.php';
		linkTX.setVar('json', '1');
		linkTX.setVar('methodName', rpcCommand);
		linkTX.setVar('params', json_encode(rpcParams));
		linkTX.method = 'POST';
		linkTX.onComplete = function () {
			if (linkTX.responseStatus[0] == 200) {
				try {
					resTX = eval('(' + linkTX.response + ')');
				} catch (err) {
					alert('{{ lang['fmsg.save.json_parse_error'] }} ' + linkTX.response);
					return false;
				}

				// First - check error state
				if (!resTX['status']) {
					// ERROR. Display it
					ngNotifyWindow(resTX['errorCode'] + ': ' + resTX['errorText'], 'Error');
					//alert('Error ('+resTX['errorCode']+'): '+resTX['errorText']);
				} else {
					if (resTX['content'] !== 'undefined') {
						if (resTX['infoText']) {
							ngNotifySticker(resTX['infoText'], {className: resTX['infoCode'] ? 'ngStickerClassClassic' : 'ngStickerClassError'});
						}
						document.getElementById('admCatList').innerHTML = resTX['content'];
					} else {
						alert('Template error: no content received from server for update, server response: ' + linkTX.response);
					}
				}
			} else {
				alert('{{ lang['fmsg.save.httperror'] }} ' + linkTX.responseStatus[0]);
			}
		}
		linkTX.runAJAX();

		return false;
	}
</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=categories">{{ lang['categories_title'] }}</a>
		</td>
	</tr>
</table>
<div id="list">
	<table width="97%" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<thead>
		<tr align="left" class="contHead">
			<td width="5%">#</td>
			<td>{{ lang['title'] }}</td>
			<td>ID</td>
			<td>{{ lang['alt_name'] }}</td>
			<td>{{ lang['category.header.menushow'] }}</td>
			<td>{{ lang['category.header.template'] }}</td>
			<td>{{ lang['news'] }}</td>
			<td width="160">{{ lang['action'] }}</td>
		</tr>
		</thead>
		<tbody id="admCatList">
		{% include localPath(0)~"entries.tpl" %}
		</tbody>
		<tfoot>
		<tr>
			<td colspan="8" class="contentEdit" align="right">&nbsp; {% if (flags.canModify) %}
					<form method="get" action="">
					<input type="hidden" name="mod" value="categories"/><input type="hidden" name="action" value="add"/><input type="submit" value="{{ lang['addnew'] }}" class="button"/>
					</form>{% endif %}</td>
		</tr>
		</tfoot>
	</table>

	</form>
</div>

