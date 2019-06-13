<script type="text/javascript" language="javascript">

	//
	// First: Init CSS manage mechanism

	var sheetRules = new Array();
	var sIdx;
	for (sIdx = 0; sIdx < document.styleSheets.length; sIdx++) {
		if ((document.styleSheets[sIdx].href != null) && (document.styleSheets[sIdx].href.indexOf('/skins/default/style.css'))) {
			// Catched
			if (document.styleSheets[sIdx].cssRules) {
				sheetRules = document.styleSheets[sIdx].cssRules;
			} else if (document.styleSheets[sIdx].rules) {
				sheetRules = document.styleSheets[sIdx].rules;
			}
			break;
		}
	}

	var sIndexActive = -1;
	var sIndexInactive = -1;
	var sIndexUninstalled = -1;

	var qShowState = 0;
	/*
	 var qStateAll		= 1;
	 var qStateActive	= 0;
	 var qStateInactive	= 0;
	 var qStateUninstalled	= 0;
	 */

	for (i = 0; i < sheetRules.length; i++) {
		var sText = '' + sheetRules[i]['selectorText'];
		sText = sText.toLowerCase();
		if (sText == '.pluginentryactive td') sIndexActive = i;
		if (sText == '.pluginentryinactive td') sIndexInactive = i;
		if (sText == '.pluginentryuninstalled td') sIndexUninstalled = i;
	}

	// ===================================================================
	// Init pre-saved in cookies values
	var cookieStatus = getCookie('ngadm_pstatus');
	if ((cookieStatus !== null) && (typeof(cookieStatus) == "string") && (Number(cookieStatus) >= 0) && (Number(cookieStatus) <= 4)) {
		qShowState = Number(cookieStatus);
	}


	// ===================================================================
	// Init pre-display CSS groups
	if (sIndexActive >= 0)
		sheetRules[sIndexActive].style.display = ((qShowState == 0 ) || (qShowState == 1)) ? '' : 'none';

	if (sIndexInactive >= 0)
		sheetRules[sIndexInactive].style.display = ((qShowState == 0 ) || (qShowState == 2)) ? '' : 'none';

	if (sIndexUninstalled >= 0)
		sheetRules[sIndexUninstalled].style.display = ((qShowState == 0 ) || (qShowState == 3)) ? '' : 'none';


	// ===================================================================
	// Function to set display filter
	function setDisplayMode(mode) {
		qShowState = mode;
		setCookie('ngadm_pstatus', qShowState, 'Wed, 01-Jan-2020 00:00:00 GMT', 0, 0, 0);

		if (sIndexActive >= 0)
			sheetRules[sIndexActive].style.display = ((qShowState == 0 ) || (qShowState == 1)) ? '' : 'none';

		if (sIndexInactive >= 0)
			sheetRules[sIndexInactive].style.display = ((qShowState == 0 ) || (qShowState == 2)) ? '' : 'none';

		if (sIndexUninstalled >= 0)
			sheetRules[sIndexUninstalled].style.display = ((qShowState == 0 ) || (qShowState == 3)) ? '' : 'none';

		document.getElementById('pTypeAll').className = (qShowState == 0) ? 'pActive' : 'pInactive';
		document.getElementById('pTypeActive').className = (qShowState == 1) ? 'pActive' : 'pInactive';
		document.getElementById('pTypeInactive').className = (qShowState == 2) ? 'pActive' : 'pInactive';
		document.getElementById('pTypeUninstalled').className = (qShowState == 3) ? 'pActive' : 'pInactive';
	}

</script>

<div id="pluginMenu">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width=100% colspan="5" class="contentHead">
				<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="admin.php?mod=extras">{{ lang['extras'] }}</a>
			</td>
		</tr>
	</table>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<td width=100% colspan="8" class="contentNav">
				<div id="pluginTypeMenu">
					<span id="pTypeAll" class="pInactive" onclick="setDisplayMode(0);">{{ lang['list.all'] }}
						({{ cntAll }})</span><span class="pSeparator">&nbsp;</span>
					<span id="pTypeActive" class="pInactive" onclick="setDisplayMode(1);">{{ lang['list.active'] }}
						({{ cntActive }})</span><span class="pSeparator">&nbsp;</span>
					<span id="pTypeInactive" class="pInactive" onclick="setDisplayMode(2);">{{ lang['list.inactive'] }}
						({{ cntInactive }})</span><span class="pSeparator">&nbsp;</span>
					<span id="pTypeUninstalled" class="pInactive" onclick="setDisplayMode(3);">{{ lang['list.needinstall'] }}
						({{ cntUninstalled }})</span>
				</div>
				&nbsp;
			</td>
		</tr>
		<tr align="left" class="contHead">
			<td>{{ lang['id'] }}</td>
			<td>{{ lang['title'] }}</td>
			<td>{{ lang['type'] }}</td>
			<td>{{ lang['version'] }}</td>
			<td>&nbsp;</td>
			<td>{{ lang['description'] }}</td>
			<td>{{ lang['author'] }}</td>
			<td>{{ lang['action'] }}</td>
		</tr>
		</thead>
		<tbody id="entryList">
		{% for entry in entries %}
			<tr align="left" class="{{ entry.style }}" id="plugin_{{ entry.id }}">
				<td>{{ entry.id }} {{ entry.new }}</td>
				<td>{{ entry.url }}</td>
				<td>{{ entry.type }}</td>
				<td>{{ entry.version }}</td>
				<td nowrap>{{ entry.readme }} {{ entry.history }}</td>
				<td>{{ entry.description }}</td>
				<td>{{ entry.author_url }}</td>
				<td nowrap="nowrap">{{ entry.link }} {{ entry.install }}</td>
			</tr>
		{% endfor %}
		</tbody>
	</table>
</div>

<script language="javascript" type="text/javascript">

	// ===================================================================
	// Now let's init buttons
	document.getElementById('pTypeAll').className = (qShowState == 0) ? 'pActive' : 'pInactive';
	document.getElementById('pTypeActive').className = (qShowState == 1) ? 'pActive' : 'pInactive';
	document.getElementById('pTypeInactive').className = (qShowState == 2) ? 'pActive' : 'pInactive';
	document.getElementById('pTypeUninstalled').className = (qShowState == 3) ? 'pActive' : 'pInactive';


	function ngPluginSwitch(plugin, state) {
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.extras.switch',
			rndval: new Date().getTime(),
			params: json_encode(
				{
					'token': '{{ token }}',
					'plugin': plugin,
					'state': state,
				}
			)
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				ngNotifyWindow('{{ lang['rpc_jsonError'] }} ' + data, '{{ lang['notifyWindowError'] }}');
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('{{ lang['rpc_httpError'] }}', '{{ lang['notifyWindowError'] }}');
		});
	}


</script>
