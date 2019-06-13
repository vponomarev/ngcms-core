<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="admin.php?mod=templates">{{ lang.templates['title'] }}</a>
		</td>
	</tr>
</table>

<form name="selectForm" id="selectForm">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="center">
			<td width="100%" class="contentNav" align="left" valign="top">
				<label><input id="selectTypeTemplate" type="radio" name="selectType" value="template" checked="checked"/> {{ lang.templates['tplsite'] }}
					: </label><select name="selectTemplate" id="selectTemplate">
					{% for st in siteTemplates %}
						<option value="{{ st.name }}">{{ st.name }} ({{ st.title }})</option>
					{% endfor %}
				</select><br/>
				<label><input id="selectTypePlugin" type="radio" name="selectType" value="plugin"/> {{ lang.templates['tplmodules'] }}
				</label><br/>
				<input type="button" value="{{ lang.templates['select'] }}" class="navbutton" onclick="submitTemplateSelector();"/>
			</td>
		</tr>
	</table>
</form>

<div style="width: 100%;">
	<!-- BLOCK TEMPLATES -->
	<table width="100%" border="0" cellaspacing="0" cellpadding="0">
		<tr>
			<td colspan="2" style="background-color: #E0E0A0; padding: 3px;">{{ lang.templates['tpl.edit'] }} [
				<b><span id="templateNameArea">default</span> </b>]
			</td>
		</tr>
		<tr>
			<td width="230" valign="top">
				<div id="fileTreeSelector" style="overflow: auto; width: 99%; height: 578px; background-color: #ABCDEF; float: left; ">
					TEST CONTENT
				</div>
				<div style="width: 100%; background-color: #E0E0E0; padding: 3px; ">
					<!-- <input style="width: 150px;" type="button" class="navbutton" value="Create template.."/> -->
				</div>
			</td>
			<td valign="top">
				<div id="fileEditorInfo" style="width: 100%; padding: 3px; background-color: #E0E0E0; height: 26px;">
					&nbsp;</div>
				<div id="fileEditorContainer" style="width: 100%; padding: 0px; margin: 0px; height: 540px;">
					<textarea id="fileEditorSelector" wrap="off" style="width: 100%; height: 100%; float: left; background-color: #EEEEEE; white-space: nowrap; font-family: monospace; font-size: 10pt;">*** EDITOR ***</textarea>
					<div id="imageViewContainer" style="display: none; height: 100%; width: 100%; vertical-align: middle;"></div>
				</div>
				<div id="fileEditorButtonLine" style="width: 100%; background-color: #E0E0E0; padding: 3px;">
					<input style="width: 150px;" type="button" class="navbutton" value="Save file" onclick="submitTemplateEdit();"/>&nbsp;
					&nbsp; &nbsp; <input style="width: 150px;" type="button" class="navbutton" value="Delete file"/>
				</div>
			</td>
		</tr>
	</table>
</div>

<link rel="stylesheet" href="{{ home }}/lib/codemirror/codemirror.css">
<script type="text/javascript" src="{{ home }}/lib/codemirror/codemirror.js"></script>

<script type="text/javascript" src="{{ home_url }}/lib/ngFileTree.js"></script>
<link rel="stylesheet" href="{{ home_url }}/lib/ngFileTree.css" type="text/css" media="screen"/>
<script type="text/javascript" language="javascript">
	var ngTemplateName = 'default';
	var ngFileName = '';
	var ngFileType = '';
	var ngFileContent = '';
	var ngFileTreeParams = {
		root: '/',
		script: '/engine/rpc.php',
		securityToken: '{{ token }}',
		templateName: ngTemplateName
	};

	var ngFileTreeFunc = function (file) {
		ngFileName = file;
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.templates.getFile',
			rndval: new Date().getTime(),
			params: json_encode({template: ngTemplateName, 'file': file, token: '{{ token }}'})
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				alert('Error parsing JSON output (mod=templates). Result: ' + resTX.response);
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], 'ERROR');
			}

			ngFileContent = '';
			ngFileType = resTX['type'];

			$('#fileEditorInfo').html(((ngFileType == 'image') ? 'Image' : 'File') + ' name: <b>' + ngFileName + '</b> (' + resTX['size'] + ' bytes)<br/>Last change time: ' + resTX['lastChange']);

			if (resTX['type'] == 'image') {
				document.getElementById('imageViewContainer').style.display = 'block';
				document.getElementById('fileEditorSelector').style.display = 'none';
				$('#imageViewContainer').html(resTX['content']);
			} else {
				document.getElementById('imageViewContainer').style.display = 'none';
				document.getElementById('fileEditorSelector').style.display = 'block';
				$('#fileEditorSelector').val(resTX['content']);

				// Remove previous codemirror (if installed)
				$(".CodeMirror").remove();

				// Install codemirror
				var edField = $('#fileEditorSelector');
				var eW = edField.width();
				var eH = edField.height();
				var cm = CodeMirror.fromTextArea(
					document.getElementById('fileEditorSelector'), {
						lineNumbers: true,
						//mode: i,
						//         lineWrapping: true,
						styleActiveLine: true,
						tabMode: "indent",
						extraKeys: {
							"F11": function (cm) {
								cm.setOption("fullScreen", !cm.getOption("fullScreen"));
							},
							"Esc": function (cm) {
								if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
							}
						}

					});
				cm.setSize(eW, eH);
				cm.on("change", function (cm) {
					$("#fileEditorSelector").val(cm.getValue());
				});

				ngFileContent = resTX['content'];
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('HTTP error during request', 'ERROR');
		});
	}

	function submitTemplateSelector() {
		var selectMode = $('input[name=selectType]:checked', '#selectForm').val();
		var selectTemplate = $('[name=selectTemplate]', '#selectForm').val();

		$('#fileEditorInfo').html('');
		$('#imageViewContainer').html('');
		$('#fileEditorSelector').val('');

		ngFileName = '';

		if (selectMode == 'template') {
			ngTemplateName = selectTemplate;
			ngFileTreeParams['templateName'] = ngTemplateName;

			$('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
			$('#templateNameArea').html(ngTemplateName);
		} else {
			ngTemplateName = '#plugins';
			ngFileTreeParams['templateName'] = ngTemplateName;
			$('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
			$('#templateNameArea').html(' PLUGIN TEMPLATES ');
		}
	}

	function submitTemplateEdit() {
		var editedContent = $('#fileEditorSelector').val();

		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.templates.updateFile',
			rndval: new Date().getTime(),
			params: json_encode({
				template: ngTemplateName,
				'file': ngFileName,
				token: '{{ token }}',
				'content': editedContent
			})
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
			} else {
				ngNotifyWindow(resTX['content'], 'RESULT');
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('HTTP error during request', 'ERROR');
		});


//	ngNotifyWindow('Test MSG', 'Test Title');
	}

	$(document).ready(function () {
		$('#fileTreeSelector').ngFileTree(ngFileTreeParams, ngFileTreeFunc);
	});

</script>