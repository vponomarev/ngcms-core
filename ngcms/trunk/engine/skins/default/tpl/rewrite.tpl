<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<form method="post" action="{php_self}?mod=rewrite" name="rewriteForm" id="rewriteForm">
<span id="temp.data" style="position: absolute; display: none;"></span>
<span id="DEBUG"></span>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<thead>
<tr class="contentNav" style="font-weight: bold;"><td>&nbsp;</td><td width="25">#</td><td width="100">Плагин</td><td width="130">Действие</td><td>Описание</td><td>URL</td><td>Флаги</td><td>&nbsp;</td></tr>
</thead>
<tbody id="cfg.body">
</tbody>

<!-- ROW FOR EDITING / ADDING -->
<tr><td colspan="8" style="background: #EEEEEE; height: 5px;">&nbsp;</td></tr>
<tr id="row.editRow" valign="top">
 <td>&nbsp;</td>
 <td id="row.id">*</td>
 <td id="row.pluginName">*&nbsp;</td>
 <td id="row.cmd">&nbsp;</td>
 <td id="row.description">&nbsp;</td>
 <td id="row.url"><input type="text" style="width: 90%;" id="ed.regex"/><br/>
 	Доступные переменные:<br/><span id="ed.varlist"></span>
 </td>
 <td id="row.flags"><input id="ed.flagPrimary" type="checkbox"/> &nbsp; <input id="ed.flagFailContinue" type="checkbox"/></td>
 <td><input style="width: 60px;" type="button" onclick="reSubmitEdit();" id="ed.button" value="Add"/> <input type="button" id="ed.bcancel" onclick="reCancelEdit();" value="Cancel"/></td>
</tr>
</table>
<input type="button" value="SAVE" onclick="reServerSubmit();"/>

<script type="text/javascript" language="javascript">
<!--
// Connect to configuration data
var dConfig	= {json.config};
var dData	= {json.data};
var dTemplate	= {json.template};

//
var currentEditRow = 0;

// Prepare data row
function populateTemplate(row) {
 var tpl = String(dTemplate);
 var flags = '<b><span style="color: '+(row['flagPrimary']?'blue':'#E0E0E0')+';">Pri</span> '+
             '<span style="color: '+(row['flagFailContinue']?'red':'#E0E0E0')+';">FFC</span></b>';

 return tpl.replace(/{id}/g, row['id']).replace(/{pluginName}/g, row['pluginName']).replace(/{handlerName}/g, row['handlerName']).replace(/{description}/g, row['description']).replace(/{regex}/g, row['regex']).replace(/{flags}/g, flags);
}

// Load rows from config
function populateCfg() {
 var cbody = document.getElementById('cfg.body');

 var tmp = '';
 for (dID in dData)
  tmp = tmp + populateTemplate(dData[dID]);

 var tStorage = document.getElementById('temp.data');
 tStorage.innerHTML = '<table>'+tmp+'</table>';

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
 var tmp;

 tmp = '<select name="ed.cmd" style="width: 120px;" id="ed.cmd" onchange="reUpdateDescr(document.getElementById(\'ed.pluginName\').value, this.value);">';
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
 var dOut = json_encode(dData);
 var linkTX = new sack();
 linkTX.requestFile = 'rpc.php';
 linkTX.setVar('json', '1');
 linkTX.setVar('methodName', 'rewrite.submit');
 linkTX.setVar('params', dOut);
 linkTX.method='POST';
 linkTX.onComplete = function() {
  if (linkTX.responseStatus[0] == 200) {
        try {
  	 resTX = eval('('+linkTX.response+')');
  	} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }

  	// First - check error state
  	if (!resTX['status']) {
  		// Mark a row if recID is set
  		if (resTX['recID'])
  			document.getElementById('re.row.'+resTX['recID']).style.background = '#AAAAAA';
  		// ERROR. Display it
  		alert('Error ('+resTX['errorCode']+'): '+resTX['errorText']);
  	} else {
  		alert('Request complete');
  	}	
  } else {
  	alert('TX.fail: HTTP code '+linkTX.responseStatus[0]);
  }	
 }
 linkTX.runAJAX();
}

//
// Show correct description
//
function reUpdateDescr(plugin, cmd) {
 var tmp;
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
function reSetData(id, plugin, cmd, regex, flagPrimary, flagFailContinue) {
 reFillCmd(plugin);
 reUpdateDescr(plugin, cmd);

 document.getElementById('row.id').innerHTML = id;
 document.getElementById('ed.pluginName').value = plugin;
 document.getElementById('ed.cmd').value = cmd;
 document.getElementById('ed.regex').value = regex;
 document.getElementById('ed.flagPrimary').checked = flagPrimary;
 document.getElementById('ed.flagFailContinue').checked = flagFailContinue;

 if (id=='*') {
  document.getElementById('ed.button').value = 'Add';
 } else {
  document.getElementById('ed.button').value = 'Save';
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
 reSetData(id, dData[id].pluginName, dData[id].handlerName, dData[id].regex, dData[id].flagPrimary, dData[id].flagFailContinue);
 
 currentEditRow = id;
 document.getElementById('re.row.'+currentEditRow).style.background = 'red';

}

// Action on "DELETE" button click
function reDeleteRow(id) {
 if (currentEditRow > 0) {
  alert('Сначала необходимо выйти из режима редактирования!');
  return false;
 }
 if (confirm('Вы действительно хотите удалить строку # '+id)) {
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
  alert('Сначала необходимо выйти из режима редактирования!');
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
  alert('Сначала необходимо выйти из режима редактирования!');
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
 rd['pluginName']  = document.getElementById('ed.pluginName').value;
 rd['handlerName'] = document.getElementById('ed.cmd').value;
 rd['regex']       = document.getElementById('ed.regex').value;
 rd['flagPrimary'] = document.getElementById('ed.flagPrimary').checked;
 rd['flagFailContinue'] = document.getElementById('ed.flagFailContinue').checked;
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

 tmp = '<select name="ed.pluginName" style="width: 100px;" id="ed.pluginName" onchange="reFillCmd(this.value);">';
 for (pluginName in dConfig) {
  tmp = tmp + '<option value="'+pluginName+'">'+pluginName+'</option>';
 }
 tmp = tmp + '</select>';
 document.getElementById('row.pluginName').innerHTML = tmp;
 reFillCmd(document.getElementById('ed.pluginName').value);
}



-->
</script>


<!--
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr id="news">
<td width="100%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
 <td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_news}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_category}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category]' value='{lnk_category}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_category_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category_page]' value='{lnk_category_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_full}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_by_cat]' value='{lnk_full_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_by_date]' value='{lnk_full_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_full_page}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_page_by_cat]' value='{lnk_full_page_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[full_page_by_date]' value='{lnk_full_page_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_date}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[date]' value='{lnk_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_date_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[date_page]' value='{lnk_date_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_year}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[year]' value='{lnk_year}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_year_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[year_page]' value='{lnk_year_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_month}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[month]' value='{lnk_month}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_month_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[month_page]' value='{lnk_month_page}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_user}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[user]' value='{lnk_user}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><b>{l_print}</b><br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle">&nbsp;</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_cat}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[print_by_cat]' value='{lnk_print_by_cat}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">&raquo; {l_by_date}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[print_by_date]' value='{lnk_print_by_date}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_firstpage}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[firstpage]' value='{lnk_firstpage}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_page}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[page]' value='{lnk_page}' size="100" /></td>
</tr>
</table>
</td>
</tr>
<tr id="rest" style="display: none;">
<td width="100%" style="padding-right:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_rest}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_addnews}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[addnews]' value='{lnk_addnews}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_profile}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[profile]' value='{lnk_profile}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_registration}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[registration]' value='{lnk_registration}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_activation}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[activation]' value='{lnk_activation}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_activation_do}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[activation_do]' value='{lnk_activation_do}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_lostpassword}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[lostpassword]' value='{lnk_lostpassword}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_rss}<br /><small>{l_rss_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[rss]' value='{lnk_rss}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_category_rss}<br /><small>{l_category_rss_desc}</small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[category_rss]' value='{lnk_category_rss}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_static}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[static]' value='{lnk_static}' size="100" /></td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{l_plugins}<br /><small></small></td>
<td width="50%" class="contentEntry2" valign="middle"><input type="text" name='format[plugins]' value='{lnk_plugins}' size="100" /></td>
</tr>
</table>
</td>
</tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="save" />
<input type="submit" value="{l_save}" class="button" />
<input type="submit" value="{l_htaccess}" class="button" onclick="document.forms['rewrite'].subaction.value = 'htaccess';" />
</td>
</tr>
</table>
</form>
-->
