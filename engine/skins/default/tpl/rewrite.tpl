<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['rewrite'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['rewrite'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form id="rewriteForm" name="rewriteForm" method="post" action="{{ php_self }}?mod=rewrite">
	<input type="hidden" name="token" value="{{ token }}" />

	<span id="temp.data" style="position: absolute; display: none;"></span>
	<span id="DEBUG"></span>

	<div class="card">
		<div class="table-responsive">
			<table class="table table-sm">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ lang['hdr.plugin'] }}</th>
						<th>{{ lang['hdr.action'] }}</th>
						<th>{{ lang['hdr.description'] }}</th>
						<th>URL</th>
						<th>{{ lang['hdr.flags'] }}</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody id="cfg.body">

				</tbody>

				<!-- ROW FOR EDITING / ADDING -->
				<tfoot>
					<tr id="row.editRow">
						<td id="row.id">*</td>
						<td id="row.pluginName">*&nbsp;</td>
						<td id="row.cmd">&nbsp;</td>
						<td id="row.description">&nbsp;</td>
						<td id="row.url">
							<input id="ed.regex" type="text" class="form-control "/>
							{{ lang['tbl.available_vars'] }}:<br/>
							<span id="ed.varlist"></span>
						</td>
						<td id="row.flags">
							<input id="ed.flagPrimary" type="checkbox"/>
							<input id="ed.flagFailContinue" type="checkbox"/>
							<input id="ed.flagDisabled" type="checkbox"/>
						</td>
						<td>
							<div class="btn-group btn-group-sm" role="group">
								<button id="ed.button" type="button" onclick="reSubmitEdit();" class="btn btn-outline-success">Add</button>
								<button id="ed.bcancel" type="button" onclick="reCancelEdit();" class="btn btn-outline-dark">Cancel</button>
							</div>
						</td>
					</tr>

					<tr id="row.editRow2">
						<td colspan="4">&nbsp;</td>
						<td colspan="2">
							<!--
							Переопределение значений переменных:
							<table width="100%">
							<tr><td>altname</td><td width="20"><input type="checkbox"></td><td><input type="text"/></td></tr>
							</table>
							-->
						</td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div class="card-footer">
			<button type="button" onclick="reServerSubmit();" class="btn btn-outline-success">SAVE</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	// Connect to configuration data.
	var dConfig = {{ json.config }};
	var dData = {{ json.data }};
	var dTemplate = {{ json.template }};

	//
	var currentEditRow = 0;

	/**
	 * Prepare data row.
	 *
	 * @param  {object} row
	 * @returns {string}
	 */
	function populateTemplate(row) {
		var tpl = String(dTemplate);
		var flags = '<b><span style="color: ' + (row['flagPrimary'] ? 'blue' : '#E0E0E0') + ';">Pri</span> ' +
			'<span style="color: ' + (row['flagFailContinue'] ? 'red' : '#E0E0E0') + ';">FFC</span> ' +
			'<span style="color: ' + (row['flagDisabled'] ? 'red' : '#E0E0E0') + ';">' + (row['flagDisabled'] ? 'OFF' : 'On') + '</span></b>';

		return tpl.replace(/{id}/g, row['id'])
			.replace(/{pluginName}/g, row['pluginName'])
			.replace(/{handlerName}/g, row['handlerName'])
			.replace(/{description}/g, row['description'])
			.replace(/{regex}/g, row['regex'])
			.replace(/{flags}/g, flags);
	}

	/**
	 * Load rows from config.
	 *
	 * @returns {void}
	 */
	function populateCfg() {
		var cbody = document.getElementById('cfg.body');

		var tmp = '';
		var dID;
		for (dID in dData)
			tmp = tmp + populateTemplate(dData[dID]);

		var tStorage = document.getElementById('temp.data');
		tStorage.innerHTML = '<table><tbody>' + tmp + '</tbody></table>';

		var cParent = cbody.parentNode;
		cbody.parentNode.replaceChild(tStorage.firstChild.firstChild, cbody);
		cParent.tBodies[0].id = 'cfg.body';
	}

	// ================================================================
	// Editors functions
	// ================================================================

	/**
	 * Fill field "PLUGIN".
	 *
	 * @param  {string} plugin
	 * @returns {void}
	 */
	function reFillCmd(plugin) {
		var tmp, cmd;

		tmp = '<select id="ed.cmd" name="ed.cmd" onchange="reUpdateDescr(document.getElementById(\'ed.pluginName\').value, this.value);" class="custom-select">';
		if (dConfig[plugin] != null) {
			for (cmd in dConfig[plugin]) {
				tmp = tmp + '<option value="' + cmd + '">' + cmd + '</option>';
			}
		} else {
			tmp = tmp + '<option value="">--NO--</option>';
		}
		tmp = tmp + '</select>';
		document.getElementById('row.cmd').innerHTML = tmp;
		reUpdateDescr(document.getElementById('ed.pluginName').value, document.getElementById('ed.cmd').value);
	}

	/**
	 * [reServerSubmit description]
	 *
	 * @returns {void}
	 */
	function reServerSubmit() {
		post('admin.rewrite.submit', dData, false)
			.then(function(response) {
				ngNotifySticker(NGCMS.lang['fmsg.save.done'], {
					closeBTN: true
				});
			})
			.catch(function(error) {
				if (error.response) {
					const response = error.response;

					// Mark a row if recID is set
					if (! response.status && 'recID' in response) {
						document.getElementById('re.row.' + response.recID).style.background = '#AAAAAA';
					}
				}
			});
	}

	/**
	 * Show correct description.
	 *
	 * @param  {string} plugin
	 * @param  {string} cmd
	 * @returns {void}
	 */
	function reUpdateDescr(plugin, cmd) {
		var tmp, vName;
		var rd = document.getElementById('row.description');
		// alert('reUpdateDescr('+plugin+', '+cmd+') :'+rd.innerHTML);

		if ((dConfig[plugin] != null) && (dConfig[plugin][cmd] != null) && (dConfig[plugin][cmd]['descr'] != null)) {
			// Description
			rd.innerHTML = dConfig[plugin][cmd]['descr'];

			// Variables
			tmp = '';

			var vRec = dConfig[plugin][cmd]['vars'];
			if (vRec != null) {
				for (vName in vRec) {
					tmp = tmp + '<b>' + vName + '</b> - ' + vRec[vName] + '<br/>';
				}
			}
			document.getElementById('ed.varlist').innerHTML = tmp;
		} else {
			rd.innerHTML = 'N/A';
		}
	}

	/**
	 * Set edit data.
	 *
	 * @param  {number} id
	 * @param  {string} plugin
	 * @param  {string} cmd
	 * @param  {string} regex
	 * @param  {boolean} flagPrimary
	 * @param  {boolean} flagFailContinue
	 * @param  {boolean} flagDisabled
	 * @returns {void}
	 */
	function reSetData(id, plugin, cmd, regex, flagPrimary, flagFailContinue, flagDisabled) {
		reFillCmd(plugin);
		reUpdateDescr(plugin, cmd);

		document.getElementById('row.id').textContent = id;
		document.getElementById('ed.pluginName').value = plugin;
		document.getElementById('ed.cmd').value = cmd;
		document.getElementById('ed.regex').value = regex;
		document.getElementById('ed.flagPrimary').checked = flagPrimary;
		document.getElementById('ed.flagFailContinue').checked = flagFailContinue;
		document.getElementById('ed.flagDisabled').checked = flagDisabled;

		document.getElementById('ed.button').textContent = id == '*' ? 'Add' : 'Save';
	}

	/**
	 * Action on "EDIT" button click.
	 *
	 * @param  {number} id
	 * @returns {void}
	 */
	function reEditRow(id) {
		var tmp;

		if (currentEditRow > 0) {
			// Reject previous edit mode
			document.getElementById('re.row.' + currentEditRow).style.background = 'white';
		}

		// Get values from this row
		reSetData(id, dData[id].pluginName, dData[id].handlerName, dData[id].regex, dData[id].flagPrimary, dData[id].flagFailContinue, dData[id].flagDisabled);

		currentEditRow = id;

		document.getElementById('re.row.' + currentEditRow).style.background = '#ecf3f7';
	}

	/**
	 * Action on "DELETE" button click.
	 *
	 * @param  {number} id
	 * @returns {boolean}
	 */
	function reDeleteRow(id) {
		if (currentEditRow > 0) {
			alert('{{ lang['fmsg.edit.shouldleave'] }}');
			return false;
		}
		if (confirm('{{ lang['fmsg.edit.rowdel_confirm'] }} ' + id)) {
			// Delete with renumbering
			var dCounter = document.getElementById('cfg.body').rows.length - 1;

			for (var i = id; i < dCounter; i++) {
				dData[i] = dData[i + 1];
				dData[i]['id'] = i;
			}
			delete(dData[dCounter]);
			populateCfg();
		}
		return true;
	}

	/**
	 * Move record UP.
	 *
	 * @param  {number} id
	 * @returns {boolean}
	 */
	function reMoveUp(id) {
		if (currentEditRow > 0) {
			alert('{{ lang['fmsg.edit.shouldleave'] }}');
			return false;
		}
		// Самую первую строчку некуда двигать
		if (id == 0) {
			return false;
		}

		// Меняем местами строки
		var tmp = dData[id - 1];
		dData[id - 1] = dData[id];
		dData[id] = tmp;

		// Обновляем счетчики
		dData[id]['id'] = id;
		dData[id - 1]['id'] = id - 1;

		populateCfg();
		return true;
	}

	/**
	 * Move record DOWN.
	 *
	 * @param  {number} id
	 * @returns {boolean}
	 */
	function reMoveDown(id) {
		if (currentEditRow > 0) {
			alert('{{ lang['fmsg.edit.shouldleave'] }}');
			return false;
		}
		var dCounter = document.getElementById('cfg.body').rows.length;

		// Самую последнюю строчку некуда двигать
		if ((id + 1) >= dCounter) {
			return false;
		}

		// Вызываем обработчик "move UP"
		reMoveUp(id + 1);
		return true;
	}

	/**
	 * Button click :: CANCEL.
	 *
	 * @returns {void}
	 */
	function reCancelEdit() {
		var pN = '';
		var pC = '';

		// Find first configuration record
		for (pN in dConfig) {
			for (pC in dConfig[pN]) {
				break;
			}
			break;
		}

		if (currentEditRow > 0) {
			// Reject previous edit mode
			document.getElementById('re.row.' + currentEditRow).style.background = 'white';
		}
		currentEditRow = 0;

		// Set default values
		reSetData('*', pN, pC, '');
	}

	/**
	 * Button click :: ADD / EDIT.
	 *
	 * @returns {void}
	 */
	function reSubmitEdit() {
		// Check mode: add or edit
		var recNo = document.getElementById('row.id').textContent;

		// Populate control object
		var rd = {};
		rd['pluginName'] = document.getElementById('ed.pluginName').value;
		rd['handlerName'] = document.getElementById('ed.cmd').value;
		rd['regex'] = document.getElementById('ed.regex').value;
		rd['flagPrimary'] = document.getElementById('ed.flagPrimary').checked;
		rd['flagFailContinue'] = document.getElementById('ed.flagFailContinue').checked;
		rd['flagDisabled'] = document.getElementById('ed.flagDisabled').checked;
		rd['description'] = dConfig[rd['pluginName']][rd['handlerName']]['descr'];

		if (recNo == '*') {
			// Add
			var cbody = document.getElementById('cfg.body');
			rd['id'] = cbody.rows.length;
			// Save info into data array
			dData[rd['id']] = rd;
		} else {
			// Edit
			rd['id'] = document.getElementById('row.id').textContent;

			// Save info into data array
			dData[rd['id']] = rd;
		}
		populateCfg();
		reCancelEdit();
	}


	// ================================================================
	// INITIAL RUN (init editing params)
	// ================================================================
	{

		populateCfg();

		var pluginName;
		var tmp;

		tmp = '<select id="ed.pluginName" name="ed.pluginName" onchange="reFillCmd(this.value);" class="custom-select">';
		for (pluginName in dConfig) {
			tmp = tmp + '<option value="' + pluginName + '">' + pluginName + '</option>';
		}
		tmp = tmp + '</select>';
		document.getElementById('row.pluginName').innerHTML = tmp;
		reFillCmd(document.getElementById('ed.pluginName').value);
	}
</script>
