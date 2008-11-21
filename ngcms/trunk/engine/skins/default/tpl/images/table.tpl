<script type="text/javascript">
function ChangeOption(selectedOption) {
	document.getElementById('list').style.display = "none";
	[status]document.getElementById('categories').style.display = "none";[/status]
	document.getElementById('uploadnew').style.display = "none";

	if(selectedOption == 'list') {
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
<form action="{php_self}?mod=images&amp;action=list" method="post" name="options_bar">
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
<table id="list" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
<tr>
<td width="20%" style="padding-left : 10px;"><label><input type="checkbox" onclick="setCookie('img_preview',this.checked?1:0); document.location=document.location;" {box_preview}/> {l_show_preview}</label></td>
<td width="20%">{l_month} <select name="postdate"><option selected value="">- {l_all} -</option>{dateslist}</select></td>
<td width="20%">{l_category} {dirlistcat}</td>
<td width="20%">[status]{l_author} <select name="author"><option value="">- {l_all} -</option>{authorlist}</select>[/status]</td>
<td width="20%">{l_per_page} <input style="text-align: center" name="news_per_page" value="{news_per_page}" type=text size=3 /> <input type=submit value="{l_show}" class="button" /></td>
</tr>
</table>
</form>
<br />
<form action="{php_self}?mod=images" method="post" name="imagedelete" id="delform">
<input type="hidden" name="subaction" value="" />
<input type="hidden" name="area" value="{area}" />
<table id="entries" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr class="contHead">
<td colspan="3" width="80">Вставка</td>
[preview]<td>{l_show_preview}</td>[/preview]
<td>{l_name}</td>
<td colspan="2">Просмотр</td>
<!-- <td>{l_action}</td> -->
<td colspan="2">{l_size}</td>
<td>{l_category}</td>
<td>{l_author}</td>
<td>{l_action}</td>
<td><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(imagedelete)" /></td>
</tr>
{entries}
<tr>
<td colspan="3">{pagesss}</td>
<td colspan="1" align="center">[status]<br /><div><input type=submit class="button" onclick="setStatus('delete');" value="{l_delete}" /></div>[/status]</td><td colspan="3" align="right">[status]<br /><div>{l_move}: {dirlist} <input type=submit class=button onclick="setStatus('move');" value="OK" /></div>[/status]</td>
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
<form action="{php_self}?mod=images" method="post" enctype="multipart/form-data" name="sn">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_upload_img}</td>
</tr>
<tr>
<td>
<input type="hidden" name="subaction" value="upload" />
<input type="hidden" name="area" value="{area}" />
<br />{dirlist}&nbsp;
<input type="button" class="button" value='{l_delone}' onClick="RemoveImages();return false;" />&nbsp;
<input type="button" class="button" value='{l_onemore}' onClick="AddImages();return false;" /><br /><br />
<script language="javascript" type="text/javascript">
function AddImages() {
	var tbl = document.getElementById('imageup');
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span style="font-size: 12px;">'+iteration+': <'+'/'+'span>';
	cellRight = row.insertCell(1);

	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'userfile[' + iteration + ']');
	el.setAttribute('size', '30');
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
<tr id="row" style="font-size: 12px;">
<td style="font-size: 12px;">1: </td><td><input type="file" size="30" name="userfile[0]" /></td>
</tr>
</table>
<br />
<input type="checkbox" name="replace" value='replace' id=replace class='check' /> <label for=replace>{l_do_replace}</label><br />
<input type="checkbox" name="rand" value='rand' id=rand class='check' /> <label for=rand>{l_do_rand}</label><br />
<input type="checkbox" name="thumb" value='thumb' id=thumb class='check' {thumb_mode}{thumb_checked}/> <label for=thumb>{l_do_preview}</label><br />
<input type="checkbox" name="shadow" value='shadow' id=shadow class='check' {shadow_mode}{shadow_checked} /><label for=shadow>{l_do_shadow}</label><br />
<input type="checkbox" name="stamp" value='stamp' id=stamp class='check' {stamp_mode}{stamp_checked} /><label for=stamp>{l_do_wmimage}</label><br />
</td>
</tr>
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="submit" value='{l_upload}' class="button" />
</td>
</tr>
</table>
</form>
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
	cellRight.innerHTML = '<span style="font-size: 12px;">'+iteration+': <'+'/'+'span>';
	
	cellRight = row.insertCell(1);
	
	var el = document.createElement('input');
	el.setAttribute('type', 'text');
	el.setAttribute('name', 'userurl[' + iteration + ']');
	el.setAttribute('size', '30');
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
<td style="font-size: 12px;">1: </td><td><input type="text" size="30" name="userurl[0]" /></td>
</tr>
</table>
<br />
<input type="checkbox" name="replace" value='replace' id=replace2 class='check' /> <label for=replace2>{l_do_replace}</label><br />
<input type="checkbox" name="rand" value='rand' id=rand2 class='check' /> <label for=rand2>{l_do_rand}</label><br />
<input type="checkbox" name="thumb" value='thumb' id=thumb2 class='check'  {thumb_mode}{thumb_checked} /> <label for=thumb2>{l_do_preview}</label><br />
<input type="checkbox" name="shadow" value='shadow' id=shadow2 class='check' {shadow_mode}{shadow_checked} /><label for=shadow2>{l_do_shadow}</label><br />
<input type="checkbox" name="stamp" value='stamp' id=stamp2 class='check' {stamp_mode}{stamp_checked} /><label for=stamp>{l_do_wmimage}</label><br />
</td>
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