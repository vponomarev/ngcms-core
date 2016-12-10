<!-- Preload uploadify engine -->
<script type="text/javascript" src="{scriptLibrary}/jq/plugins/uploadify/swfobject.js"></script>
<script type="text/javascript" src="{scriptLibrary}/jq/plugins/uploadify/jquery.uploadify.v2.1.4.min.js"></script>

<!-- Main scripts -->
<script type="text/javascript">
var flagRequireReload = 0;
function ChangeOption(selectedOption) {
	document.getElementById('list').style.display = "none";
	[status]document.getElementById('categories').style.display = "none";[/status]
	document.getElementById('uploadnew').style.display = "none";

	if(selectedOption == 'list') {
		if (flagRequireReload) {
			document.location.href = document.location.href;
		}
		document.getElementById('list').style.display = "";
		document.getElementById('entries').style.display = "";
	}
	[status]if(selectedOption == 'categories') {
		document.getElementById('categories').style.display = "";
		document.getElementById('entries').style.display = "none";
	}[/status]

	if(selectedOption == 'uploadnew') {
		document.getElementById('uploadnew').style.display = "";
		document.getElementById('entries').style.display = "none";
	}
}

function setStatus(mode) {
 var st = document.getElementById('delform');
 st.subaction.value = mode;
}
</script>
<form action="{php_self}?mod=files&amp;action=list" method="post" name="options_bar">
<input type="hidden" name="area" value="{area}" />
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_files_title}</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('list')" value="{l_list}" class="navbutton" />
[status]<input type="button" onmousedown="javascript:ChangeOption('categories')" value="{l_categories}" class="navbutton" />[/status]
<input type="button" onmousedown="javascript:ChangeOption('uploadnew')" value="{l_uploadnew}" class="navbutton" />
</td>
</tr>
</table>
<br />
<table id="list" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="20%" style="padding-left : 10px;">{l_sort}</td>
<td width="20%">{l_month} <select name="postdate"><option selected value="">- {l_all} -</option>{dateslist}</select></td>
<td width="20%">{l_category} {dirlistcat}</td>
<td width="20%">[status]{l_author} <select name="author"><option value="">- {l_all} -</option>{authorlist}</select>[/status]</td>
<td width="20%">{l_per_page} <input style="text-align: center" name="npp" value="{npp}" type=text size=3 /> <input type=submit value="{l_show}" class="button" /></td>
</tr>
</table>
</form>
<br />
<form action="{php_self}?mod=files" method="post" name="delform" id="delform">
<input type="hidden" name="area" value="{area}" />
<input type="hidden" name="subaction" value="" />
<table id="entries" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left" class="contHead">
<td width="5%">#</td>
<td width="25%">{l_name}</td>
<td width="20%">{l_action}</td>
<td width="20%">{l_size}</td>
<td width="15%">{l_category}</td>
<td width="10%" >{l_author}</td>
<td width="5%"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(delform)" /></td>
</tr>
{entries}
<tr>


<td colspan="3" class="contentEdit">{pagesss}</td>
<td colspan="1" align="left" class="contentEdit">[status]<br /><div><input type=submit class="button" onclick="setStatus('delete');" value="{l_delete}" /></div>[/status]</td><td colspan="3" align="right" class="contentEdit">[status]<div>{l_move}: {dirlist} <input type=submit class=button onclick="setStatus('move');" value="OK" /></div>[/status]
</td>
</tr>
<tr>
<td colspan="7">&nbsp;</td>
</tr>
</table>
</form>
[status]
<table id="categories" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" />{l_addnewcat}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" />{l_delcat}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">
<form action="{php_self}?mod=files" method="post" name="newcat">
<input type="hidden" name="area" value="{area}" />
<input type="hidden" name="subaction" value="newcat" />
{l_name} <input type="text" name="newfolder" size="30" />
&nbsp; <input type="submit" value="OK" class="button" />
</form>
</td>
<td width="50%" class="contentEntry1">
<form action="{php_self}?mod=files" method="post" name="delcat">
<input type="hidden" name="area" value="{area}" />
<input type="hidden" name="subaction" value="delcat" />
{l_name} {dirlist}&nbsp; <input type="submit" value="OK" class="button" />
</form>
</td>
</tr>
</table>
[/status]

<table id="uploadnew" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content">
<tr>
<td width="50%" valign="top" class="contentEntry1">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_upload_file}
</td>
</tr>
<tr>
<td>
<br />

<form action="{php_self}?mod=files" method="post" enctype="multipart/form-data" name="sn">
<input type="hidden" name="area" value="{area}" />
<input type="hidden" name="subaction" value="upload" />
{dirlistS}&nbsp;
<span id="showRemoveAddButtoms">
<input type="button" class="button" value='{l_delone}' onClick="RemoveFiles();return false;" />&nbsp;
<input type="button" class="button" value='{l_onemore}' onClick="AddFiles();return false;" /><br /><br />
</span>
<script language="javascript" type="text/javascript">
function AddFiles() {
	var tbl = document.getElementById('fileup');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span style="font-size: 12px;">'+iteration+': </span>';

	cellRight = row.insertCell(1);

	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'userfile[' + iteration + ']');
	el.setAttribute('size', '30');
	el.setAttribute('value', iteration);
	cellRight.appendChild(el);
}
function RemoveFiles() {
	var tbl = document.getElementById('fileup');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}
</script>
<table id="fileup" class="upload">
<tr id="row">
<td>1: </td><td><input type="file" id="fileUploadInput" size="30" name="userfile[0]" /></td>
</tr>
</table>
<br /><br />
<div class="list">
  <input type="checkbox" name="replace" value="replace" id="flagReplace" class="check" value="1"/>
  <label for="flagReplace">{l_do_replace}</label><br />
  <input type="checkbox" name="rand" value='rand' id="flagRand" class="check" value="1"/> <label for="flagRand">{l_do_rand}</label><br />
</div>
<br /><input type="submit" value='{l_upload}' class="button" onclick="uploadifyDoUpload(); return false;"/>
</form>

<!-- BEGIN: Init UPLOADIFY engine -->
<script type="text/javascript">
$(document).ready(function() {
	$('#fileUploadInput').uploadify({
        'uploader'  : '{scriptLibrary}/jq/plugins/uploadify/uploadify.swf',
		'script'    : '{admin_url}/rpc.php?methodName=admin.files.upload',
		'cancelImg' : '{skins_url}/images/up_cancel.png',
		'folder'    : '',
		'fileExt'   : '{listExt}',
		'fileDesc'  : '{descExt}',
		'sizeLimit'	: {maxSize},
		'auto'      : false,
		'multi'     : true,
		'buttonText'  : 'Select files ...',
		'width'		: 200,
		'removeCompleted' : true,
		'onInit' : function() { document.getElementById('showRemoveAddButtoms').style.display= 'none'; },
		'onComplete' : function(ev, ID, fileObj, res, data) {
			// Response should be in JSON format
			var resData;
			var resStatus = 0;
			try {
				resData = eval('('+res+')');
				if (typeof(resData['status']))
					resStatus = 1;
			} catch (err) { alert('Error parsing JSON output. Result: '+res); }

			if (!resStatus) {
				alert('Upload resp: '+res);
				return false;
			}

			flagRequireReload = 1;

			// If upload fails
			if (resData['status'] < 1) {
				$('#' + $(ev.target).attr('id') + ID).append('<div class="msg">('+resData['errorCode']+') '+resData['errorText']+'</div>');
				if (typeof(resData['errorDescription']) !== 'undefined') {
					$('#' + $(ev.target).attr('id') + ID).append('<div class="msgInfo">'+resData['errorDescription']+'</div>');
				}
				$('#' + $(ev.target).attr('id') + ID).css('border', '2px solid red');
				return false;
			} else {
				$('#' + $(ev.target).attr('id') + ID).append('<div>'+resData['errorText']+'</div>');
				$('#' + $(ev.target).attr('id') + ID).fadeOut(5000);
			}
			return true;
		},
		//'onSelect' : function(event, ID, fileObj) { processEvent('onSelect ('+event+', '+ID+', '+fileObj.name+' ['+fileObj.size+'])'); }

	});
});

function uploadifyDoUpload() {
	// Prepare script data
	var scriptData = new Array();
	scriptData['ngAuthCookie']	= '{authcookie}';
	scriptData['uploadType']	= 'file';
	scriptData['category']		= document.getElementById('categorySelect').value;
	scriptData['rand']			= document.getElementById('flagRand').checked?1:0;
	scriptData['replace']		= document.getElementById('flagReplace').checked?1:0;

  	$('#fileUploadInput').uploadifySettings('scriptData',scriptData,true);
	$('#fileUploadInput').uploadifyUpload();
}
</script>
<!-- END: Init UPLOADIFY engine -->
</td>
</tr>
</table>
</td>

<td width="50%" class="contentEntry1" valign="top">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_upload_file_url}</td>
</tr>
<tr>
<td><br />

<form action="{php_self}?mod=files" method="post" name="snup">
<input type="hidden" name="subaction" value="uploadurl" />
<input type="hidden" name="area" value="{area}" />
<br />{dirlist}&nbsp;
<input type="button" class="button" value='{l_delone}' onClick="RemoveFiles2();return false;" />&nbsp;
<input type="button" class="button" value='{l_onemore}' onClick="AddFiles2();return false;" /><br /><br />
<script language="javascript" type="text/javascript">
function AddFiles2() {
	var tbl = document.getElementById('fileup2');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span style="font-size: 12px;">'+iteration+': </span>';

	cellRight = row.insertCell(1);

	var el = document.createElement('input');
	el.setAttribute('type', 'text');
	el.setAttribute('name', 'userurl[' + iteration + ']');
	el.setAttribute('size', '30');
	cellRight.appendChild(el);
//	document.getElementById('files_number').value = iteration;
}
function RemoveFiles2() {
	var tbl = document.getElementById('fileup2');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
//		document.getElementById('files_number').value =  document.getElementById('files_number').value - 1;
	}
}
</script>
<table id="fileup2" class="upload">
<tr id="row">
<td>1: </td><td><input type="text" size="30" name="userurl[0]" /></td>
</tr>
</table>
<br />
<div class="list">
  <input type=checkbox name="replace" value='replace' id=replace class='check' />
  <label for=replace>{l_do_replace}</label><br />
  <input type=checkbox name="rand" value='rand' id=rand class='check' /> <label for=rand>{l_do_rand}</label><br />
</div>
<br />
<input type="submit" value='{l_upload}' class="button" />
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>