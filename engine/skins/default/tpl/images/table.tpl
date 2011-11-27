<!-- Preload uploadify engine -->
<script type="text/javascript" src="{admin_url}/includes/js/swfobject.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/jquery.uploadify.v2.1.4.min.js"></script>

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
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_images_title}</td>
</tr>
</table>
<form action="{php_self}" method="get" name="options_bar">
<input type="hidden" name="mod" value="images" />
<input type="hidden" name="action" value="list" />
<input type="hidden" name="area" value="{area}" />
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
<table id="list" width="1000" border="0" cellspacing="0" cellpadding="0" class="imagesfilter">
  <tr>
    <td>
    <table  border="0" cellspacing="0" cellpadding="0" class="filterblock3">
  <tr>
    <td><label><input type="checkbox" onclick="setCookie('img_preview',this.checked?1:0); document.location=document.location;" {box_preview}/> {l_show_preview}</label></td>
    <td><label>{l_month}</label> <select name="postdate"><option selected value="">- {l_all} -</option>{dateslist}</select></td>
    <td><label>{l_category}</label> {dirlistcat}</td>
    <td>[status]<label>{l_author}</label> <select name="author"><option value="">- {l_all} -</option>{authorlist}</select>[/status]</td>
    <td class="right"><label>{l_per_page}</label> <input style="text-align: center" name="npp" value="{npp}" type=text size=3 />  <input type=submit value="{l_show}" class="images_filterbutton" /></td>
  </tr>
</table>

    </td>
  </tr>
</table>
</form>
<br />
<form action="{php_self}?mod=images" method="post" name="imagedelete" id="delform">
<input type="hidden" name="subaction" value="" />
<input type="hidden" name="area" value="{area}" />
<table id="entries" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr class="contHead">
<td colspan="3" width="80">{l_header.insert}</td>
[preview]<td>{l_show_preview}</td>[/preview]
<td>{l_name}</td>
<td colspan="2">{l_header.view}</td>
<!-- <td>{l_action}</td> -->
<td colspan="2">{l_size}</td>
<td>{l_category}</td>
<td>{l_author}</td>
<td>{l_action}</td>
<td><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(imagedelete)" /></td>
</tr>
{entries}

<tr>

<td colspan="5" class="contentEdit">{pagesss}</td>
<td colspan="5" class="contentEdit">[status]<div><input type=submit class="button" onclick="setStatus('delete');" value="{l_delete}" /></div>[/status]</td><td colspan="5" class="contentEdit" >[status]<div>{l_move}: {dirlist} <input type=submit class=button onclick="setStatus('move');" value="OK" /></div>[/status]</td>
</tr>
<tr>
<td colspan="7">&nbsp;</td>
</tr>

</table>

</form>

[status]
<table id="categories" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_addnewcat}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_delcat}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">
<form action="{php_self}?mod=images" method="post" name="newcat">
<input type="hidden" name="subaction" value="newcat" />
<input type="hidden" name="area" value="{area}" />
<input type="text" name="newfolder" size="30" />&nbsp; <input type="submit" value="OK" class="button" />
</form>
</td>
<td width="50%" class="contentEntry1">
<form action='{php_self}?mod=images' method='post' name='delcat'>
<input type="hidden" name="subaction" value="delcat" />
<input type="hidden" name="area" value="{area}" />
{dirlist}&nbsp; <input type="submit" value="OK" class="button" />
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
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_upload_img}</td>
</tr>
<tr>
<td>
<form action="{php_self}?mod=images" method="post" enctype="multipart/form-data" name="sn">
<input type="hidden" name="subaction" value="upload" />
<input type="hidden" name="area" value="{area}" />
<br />{dirlistS}&nbsp;
<span id="showRemoveAddButtoms">
<input type="button" class="button" value='{l_delone}' onClick="RemoveImages();return false;" />&nbsp;
<input type="button" class="button" value='{l_onemore}' onClick="AddImages();return false;" /><br /><br />
</span>
<script language="javascript" type="text/javascript">
function AddImages() {
	var tbl = document.getElementById('imageup');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span>'+iteration+': <'+'/'+'span>';
	cellRight = row.insertCell(1);

	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'userfile[' + iteration + ']');
	el.setAttribute('size', '60');
	el.setAttribute('value', iteration);
	cellRight.appendChild(el);
}
function RemoveImages() {
	var tbl = document.getElementById('imageup');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}
</script>
<table id="imageup" class="upload">
<tr id="row">
<td>1: </td><td><input type="file" size="60" id="fileUploadInput" name="userfile[0]" /></td>
</tr>
</table>

<div class="list">
  <input type="checkbox" name="replace" value="1" id="flagReplace" class='check' />									<label for="flagReplace">{l_do_replace}</label><br />
  <input type="checkbox" name="rand" value="1" id="flagRand" class='check' />										<label for="flagRand">{l_do_rand}</label><br />
  <input type="checkbox" name="thumb" value="1" id="flagThumb" class='check' {thumb_mode}{thumb_checked}/>			<label for="flagThumb">{l_do_preview}</label><br />
  <input type="checkbox" name="shadow" value="1" id="flagShadow" class='check' {shadow_mode}{shadow_checked} />		<label for="flagShadow">{l_do_shadow}</label><br />
  <input type="checkbox" name="stamp" value="1" id="flagStamp" class='check' {stamp_mode}{stamp_checked} />			<label for="flagStamp">{l_do_wmimage}</label>
</div></td>
</tr>
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value='{l_upload}' class="button" onclick="uploadifyDoUpload(); return false;" />
</td>
</tr>
</table>
</form>

<!-- BEGIN: Init UPLOADIFY engine -->
<script type="text/javascript">
$(document).ready(function() {
	$('#fileUploadInput').uploadify({
        'uploader'  : '{admin_url}/includes/js/uploadify.swf',
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
alert(res);
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
	});
});

function uploadifyDoUpload() {
	// Prepare script data

	var scriptData = new Array();
	scriptData['ngAuthCookie']	= getCookie('zz_auth');
	scriptData['uploadType']	= 'image';
	scriptData['category']		= document.getElementById('categorySelect').value;
	scriptData['rand']			= document.getElementById('flagRand').checked?1:0;
	scriptData['replace']		= document.getElementById('flagReplace').checked?1:0;
	scriptData['thumb']			= document.getElementById('flagThumb').checked?1:0;
	scriptData['stamp']			= document.getElementById('flagStamp').checked?1:0;
	scriptData['shadow']		= document.getElementById('flagShadow').checked?1:0;

  	$('#fileUploadInput').uploadifySettings('scriptData',scriptData,true);
	$('#fileUploadInput').uploadifyUpload();
}
</script>
<!-- END: Init UPLOADIFY engine -->
</td>

<td width="50%" class="contentEntry1" valign="top">
<form action="{php_self}?mod=images" method="post" name="snup">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_upload_img_url}</td>
</tr>
<tr>
<td>
<input type="hidden" name="subaction" value="uploadurl" />
<input type="hidden" name="area" value="{area}" />
<br />{dirlist}&nbsp;
<input type="button" class="button" value='{l_delone}' onClick="RemoveImages2();return false;" />&nbsp;
<input type="button" class="button" value='{l_onemore}' onClick="AddImages2();return false;" /><br /><br />
<script language="javascript" type="text/javascript">
function AddImages2() {
	var tbl = document.getElementById('imageup2');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);

	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span">'+iteration+': <'+'/'+'span>';

	cellRight = row.insertCell(1);

	var el = document.createElement('input');
	el.setAttribute('type', 'text');
	el.setAttribute('name', 'userurl[' + iteration + ']');
	el.setAttribute('size', '60');
	cellRight.appendChild(el);
}
function RemoveImages2() {
	var tbl = document.getElementById('imageup2');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}
</script>
<table id="imageup2" class="upload">
<tr id="row">
<td>1: </td><td><input type="text" size="60" name="userurl[0]" /></td>
</tr>
</table>

<div class="list">
  <input type="checkbox" name="replace" value='replace' id=replace2 class='check' />
  <label for=replace2>{l_do_replace}</label><br />
  <input type="checkbox" name="rand" value='rand' id=rand2 class='check' />
  <label for=rand2>{l_do_rand}</label><br />
  <input type="checkbox" name="thumb" value='thumb' id=thumb2 class='check'  {thumb_mode}{thumb_checked} />
  <label for=thumb2>{l_do_preview}</label><br />
  <input type="checkbox" name="shadow" value='shadow' id=shadow2 class='check' {shadow_mode}{shadow_checked} /><label for=shadow2>{l_do_shadow}</label><br />
  <input type="checkbox" name="stamp" value='stamp' id=stamp2 class='check' {stamp_mode}{stamp_checked} /><label for=stamp2>{l_do_wmimage}</label>
</div></td>
</tr>
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value='{l_upload}' class="button" />
</td>
</tr>
</table>
</form>
</td>
</tr>
</table>