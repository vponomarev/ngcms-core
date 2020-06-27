<!-- Navigation bar -->
<ul class="breadcrumb">
	<li><a href="admin.php">{{ lang['home'] }}</a></li>
	<li><a href="admin.php?mod=options">{{ lang['options'] }}</a></li>
	<li class="active">{{ lang['rewrite'] }}</li>
</ul>

<!-- Info content -->
<div class="page-main">
	<div class="panel panel-default panel-table">
		<form method="post" action="admin.php?mod=rewrite" name="rewriteForm" id="rewriteForm">
			<span id="temp.data" style="position: absolute; display: none;"></span>
			<span id="DEBUG"></span>

			<div class="panel-body table-responsive">
				<table class="table table-condensed table-hover">
					<thead>
						<tr>
							
							<th width="20">ID</th>
							<th width="60">{{ lang['hdr.plugin'] }}</th>
							<th width="80">{{ lang['hdr.action'] }}</th>
							<th>{{ lang['hdr.description'] }}</th>
							<th>URL</th>
							<th>{{ lang['hdr.flags'] }}</th>
							<th class="text-right">{{ lang['action'] }}</th>
						</tr>
					</thead>
				<tbody id="cfg.body">
					
				</tbody>
				<!-- ROW FOR EDITING / ADDING -->

				<tfoot>
				<tr id="row.editRow" valign="top" class="bg-info">
					
					<td id="row.id" width="24px" >*</td>
					<td id="row.pluginName">*&nbsp;</td>
					<td id="row.cmd">&nbsp;</td>
					<td id="row.description">&nbsp;</td>
					<td id="row.url">
						<input type="text" id="ed.regex" class="form-control input-sm">
						{{ lang['tbl.available_vars'] }}:<br/>
						<span id="ed.varlist"></span>
					</td>
					<td id="row.flags" class="text-center">
						<input id="ed.flagPrimary" type="checkbox">&nbsp;
						<input id="ed.flagFailContinue" type="checkbox">&nbsp;
						<input id="ed.flagDisabled" type="checkbox">
					</td>
					<td nowrap class="text-right">
						<button id="ed.button" type="button" class="btn btn-default btn-sm" onclick="reSubmitEdit();"><i class="fa fa-plus"></i> Add</button>
						<button id="ed.bcancel" type="button" class="btn btn-warning btn-sm" onclick="reCancelEdit();"><i class="fa fa-ban"></i> Cancel</button>
					</td>
				</tr>
				<tr id="row.editRow2" valign="top">
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

			<div class="panel-footer text-center">
				<input type="button" value="{{ lang['save'] }}" onclick="reServerSubmit();" class="btn btn-success">
			</div>
		</form>
	</div>
</div>

<script>
<!--
// Connect to configuration data
var dConfig		= {{ json.config }};
var dData		= {{ json.data }};
var dTemplate	= {{ json.template }};

//
var currentEditRow = 0;

// Prepare data row
function populateTemplate(row) {
 var tpl = String(dTemplate);
 var flags = '<b><span style="color: '+(row['flagPrimary']?'blue':'#E0E0E0')+';">Pri</span> '+
				'<span style="color: '+(row['flagFailContinue']?'red':'#E0E0E0')+';">FFC</span> '+
				'<span style="color: '+(row['flagDisabled']?'red':'#E0E0E0')+';">'+(row['flagDisabled']?'OFF':'On')+'</span></b>';

 return tpl.replace(/{id}/g, row['id']).replace(/{pluginName}/g, row['pluginName']).replace(/{handlerName}/g, row['handlerName']).replace(/{description}/g, row['description']).replace(/{regex}/g, row['regex']).replace(/{flags}/g, flags);
}

// Load rows from config
function populateCfg() {
 var cbody = document.getElementById('cfg.body');

 var tmp = '';
 var dID;
 for (dID in dData)
 tmp = tmp + populateTemplate(dData[dID]);

 var tStorage = document.getElementById('temp.data');
 tStorage.innerHTML = '<table><tbody>'+tmp+'</tbody></table>';

 var cParent = cbody.parentNode;
 cbody.parentNode.replaceChild(tStorage.firstChild.firstChild, cbody);
 cParent.tBodies[0].id = 'cfg.body';
}


// ================================================================
// Editors functions
// ================================================================

//
// Fill field "PLUGIN"
//
function reFillCmd(plugin) {
 var tmp, cmd;

 tmp = '<select name="ed.cmd" class="form-control input-sm" id="ed.cmd" onchange="reUpdateDescr(document.getElementById(\'ed.pluginName\').value, this.value);">';
 if (dConfig[plugin] != null) {
 for (cmd in dConfig[plugin]) {
 tmp = tmp + '<option value="'+cmd+'">'+cmd+'</option>';
 }
 } else {
 tmp = tmp + '<option value="">--NO--</option>';
 }
 tmp = tmp + '</select>';
 document.getElementById('row.cmd').innerHTML = tmp;
 reUpdateDescr(document.getElementById('ed.pluginName').value, document.getElementById('ed.cmd').value);
}

//
function reServerSubmit() {
    var params = {'token':'{{ token }}'};
    // Merge dData into params // add token to params
    $.extend( params, dData );
    $.reqJSON('rpc.php', 'admin.rewrite.submit', params, function(json) {
        if (!json.status) {
            // Mark a row if recID is set
            if (json.recID)
                $('#re\\.row\\.'+json.recID).css('backgroundColor', '#AAAAAA');
            // ERROR. Display it
            $.notify({message: 'Error ('+json.errorCode+'): '+json.errorText},{type: 'danger'});
        } else {
            $.notify({message: '{{ lang['fmsg.save.done'] }}'},{type: 'success'});
        }
    });
}

//
// Show correct description
//
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
 tmp = tmp + '<b>'+vName+'</b> - '+vRec[vName]+'<br/>';
 }
 }
 document.getElementById('ed.varlist').innerHTML = tmp;
 } else {
 rd.innerHTML = 'N/A';
 }
}

//
// Set edit data
//
function reSetData(id, plugin, cmd, regex, flagPrimary, flagFailContinue, flagDisabled) {
 reFillCmd(plugin);
 reUpdateDescr(plugin, cmd);

 document.getElementById('row.id').innerHTML = id;
 document.getElementById('ed.pluginName').value = plugin;
 document.getElementById('ed.cmd').value = cmd;
 document.getElementById('ed.regex').value = regex;
 document.getElementById('ed.flagPrimary').checked = flagPrimary;
 document.getElementById('ed.flagFailContinue').checked = flagFailContinue;
 document.getElementById('ed.flagDisabled').checked = flagDisabled;

 if (id=='*') {
 document.getElementById('ed.button').innerHTML = '<i class="fa fa-plus"></i> Add';
 } else {
 document.getElementById('ed.button').innerHTML = '<i class="fa fa-save"></i> Save';
 }
}

// ====================================================
// Action on "EDIT" button click
//
function reEditRow(id) {
 var tmp;

 if (currentEditRow > 0) {
 // Reject previous edit mode
 document.getElementById('re.row.'+currentEditRow).style.background = 'white';
 }

 // Get values from this row
 reSetData(id, dData[id].pluginName, dData[id].handlerName, dData[id].regex, dData[id].flagPrimary, dData[id].flagFailContinue, dData[id].flagDisabled);

 currentEditRow = id;
 document.getElementById('re.row.'+currentEditRow).style.background = '#ecf3f7';
	
	$('#ed\\.regex').focus();
}

// Action on "DELETE" button click
function reDeleteRow(id) {
 if (currentEditRow > 0) {
	$.notify({message: '{{ lang['fmsg.edit.shouldleave'] }}'},{type: 'danger'});
 return false;
 }
 if (confirm('{{ lang['fmsg.edit.rowdel_confirm'] }} '+id)) {
 // Delete with renumbering
 var dCounter = document.getElementById('cfg.body').rows.length-1;

 for (var i = id; i < dCounter; i++) {
 dData[i] = dData[i+1];
 dData[i]['id'] = i;
 }
 delete(dData[dCounter]);
 populateCfg();
 }
 return true;
}

// Move record UP
function reMoveUp(id) {
 if (currentEditRow > 0) {
	$.notify({message: '{{ lang['fmsg.edit.shouldleave'] }}'},{type: 'danger'});
 return false;
 }
 // Самую первую строчку некуда двигать
 if (id == 0) {
 return false;
 }

 // Меняем местами строки
 var tmp = dData[id-1];
 dData[id-1] = dData[id];
 dData[id] = tmp;

 // Обновляем счетчики
 dData[id]['id'] = id;
 dData[id-1]['id'] = id-1;

 populateCfg();
 return true;
}

// Move record DOWN
function reMoveDown(id) {
 if (currentEditRow > 0) {
	$.notify({message: '{{ lang['fmsg.edit.shouldleave'] }}'},{type: 'danger'});
 return false;
 }
 var dCounter = document.getElementById('cfg.body').rows.length;

 // Самую последнюю строчку некуда двигать
 if ((id+1) >= dCounter) {
 return false;
 }

 // Вызываем обработчик "move UP"
 reMoveUp(id+1);
 return true;
}

// Button click :: CANCEL
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
 document.getElementById('re.row.'+currentEditRow).style.background = 'white';
 }
 currentEditRow = 0;

 // Set default values
 reSetData('*', pN, pC, '');
}

// Button click :: ADD / EDIT
function reSubmitEdit() {
 // Check mode: add or edit
 var recNo = document.getElementById('row.id').innerHTML;

 // Populate control object
 var rd = Object();
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
 rd['id'] = document.getElementById('row.id').innerHTML;

 // Save info into data array
 dData[rd['id']] = rd;
 }
 populateCfg();
 reCancelEdit();
}


// ================================================================
// INITIAL RUN (init editing params)
// ================================================================

// Init editing params
{

 populateCfg();

 var pluginName;
 var tmp;

 tmp = '<select name="ed.pluginName" class="form-control input-sm" id="ed.pluginName" onchange="reFillCmd(this.value);">';
 for (pluginName in dConfig) {
 tmp = tmp + '<option value="'+pluginName+'">'+pluginName+'</option>';
 }
 tmp = tmp + '</select>';
 document.getElementById('row.pluginName').innerHTML = tmp;
 reFillCmd(document.getElementById('ed.pluginName').value);
}

-->
</script>
