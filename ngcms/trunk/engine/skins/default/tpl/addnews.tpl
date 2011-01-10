<script type="text/javascript">
//
// Global variable: ID of current active input area
[edit.split]var currentInputAreaID = 'content.short';[/edit.split][edit.nosplit]var currentInputAreaID = 'content';[/edit.nosplit]

function ChangeOption(optn) {
	document.getElementById('maincontent').style.display = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display  = (optn == 'additional')?"block":"none";
	document.getElementById('attaches').style.display    = (optn == 'attaches')?"block":"none";
}

function preview(){

 var form = document.getElementById("postForm");
 if (form.content[edit.split]_short[/edit.split].value == '' || form.title.value == '') {
  alert('{l_msge_preview}');
  return false;
 }

 form.mod.value = "preview";
 form.target = "_blank";
 form.submit();

 form.mod.value = "addnews";
 form.target = "_self";
 return true;
}

function changeActive(name) {
 if (name == 'full') {
	document.getElementById('container.content.full').className  = 'contentActive';
	document.getElementById('container.content.short').className = 'contentInactive';
	currentInputAreaID = 'content.full';
 } else {
	document.getElementById('container.content.short').className = 'contentActive';
	document.getElementById('container.content.full').className  = 'contentInactive';
	currentInputAreaID = 'content.short';
 }
}
</script>
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type="hidden" name="area" value="" />
</form>
<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="post" action="{php_self}" target="_self">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=editnews">{l_news_title}</a> &#8594; {l_addnews_title}</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td valign="top" >
 <!-- Left edit column -->

<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td class="contentNav" align="center">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_bar.maincontent}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_bar.additional}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('attaches')" value="{l_bar.attaches}" class="navbutton" />
</td>
</tr>
<tr><td>

<!-- MAIN CONTENT -->
<div id="maincontent" style="display: block;">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
   <td width="10"><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td width="100"><span class="f15">{l_title}</span></td>
   <td><input type="text" class="important" size="79" name="title" value="" tabindex="1" /></td>
  </tr>
  <tr>
   <td valign="top" colspan=3>{quicktags}<br /> {smilies}<br />
[edit.split]
    <div id="container.content.short" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="content_short" id="content.short" rows="10" tabindex="2"></textarea></div>
[extended.more]    <table cellspacing="2" cellpadding="0" width="100%"><tr><td nowrap>{l_editor.divider}: &nbsp;</td><td style="width: 90%"><input tabindex="2" type="text" name="content_delimiter" style="width: 99%;" value=""/></td></tr></table>[/extended.more]
    <div id="container.content.full" class="contentInactive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="content_full" id="content.full" rows="10" tabindex="2"></textarea></div>
[/edit.split]
[edit.nosplit]
    <div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="content" id="content" rows="10" tabindex="2"></textarea></div>
[/edit.nosplit]

   </tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_alt_name}:</td>
   <td><input type="text" name="alt_name" value="" size="60" tabindex="3" /></td>
  </tr>
[meta]
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_description}:</td>
   <td><input type="text" name="description" value="" size="60" tabindex="4" /></td>
  </tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_keywords}:</td>
   <td><input type="text" name="keywords" value="" size="60" tabindex="5" /></td>
  </tr>
[/meta]
</table>
</div>


<!-- ADDITIONAL -->
<div id="additional" style="display: none;">
<table border="0" cellspacing="1" cellpadding="0" width="100%">
<tr>
<td class="contentHead"><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
</tr>
<tr>
<td class="contentEntry1">{changedate}</td>
</tr>
[isplugin xfields]{plugin_xfields}[/isplugin]
[isplugin nsched]{nsched}[/isplugin]
[isplugin finance]{finance}[/isplugin]
[isplugin tags]{plugin_tags}[/isplugin]
</table>
</div>

<!-- ATTACHES -->
<div id="attaches" style="display: none;">
<br/>
<span class="f15">{l_attach.list}</span>
<table width="100%" cellspacing="1" cellpadding="2" border="0" id="attachFilelist">
<thead>
<tr class="contHead"><td>#</td><td width="80">Date</td><td>FileName</td><td width="90">Size</td><td width="40">DEL</td></tr>
</thead>
<tbody>
<!-- <tr><td>*</td><td>New file</td><td colspan="2"><input type="file"/></td><td><input type="button" size="40" value="-"/></td></tr> -->
<tr><td colspan="3">&nbsp;</td><td colspan="2"><input type="button" value="Добавить поле" class="button" style="width: 100%;" onclick="attachAddRow();" /></td></tr>
</table>
</div>

<script language="javascript" type="text/javascript">
<!--
function attachAddRow() {
	var tbl = document.getElementById('attachFilelist');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow - 1);

	// Add cells
	row.insertCell(0).innerHTML = '*';
	row.insertCell(1).innerHTML = 'New file';
	
	// Add file input
	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'userfile[' + (++attachAbsoluteRowID) + ']');
	el.setAttribute('size', '80');

	var xCell = row.insertCell(2);
	xCell.colSpan = 2;
	xCell.appendChild(el);


	el = document.createElement('input');
	el.setAttribute('type', 'button');
	el.setAttribute('onclick', 'document.getElementById("attachFilelist").deleteRow(this.parentNode.parentNode.rowIndex);');
	el.setAttribute('value', '-');
	row.insertCell(3).appendChild(el);
}
// Add first row 
var attachAbsoluteRowID = 0;
attachAddRow();
-->
</script>


</td></tr>
</table>

</td>
<td id="rightBar" width="300" valign="top" >
 <!-- Right edit column -->
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
   <td width="20"></td>
   <td><span class="f15">{l_category}</span></td>
  </tr>
  <tr>
   <td></td><td><div class="list">{mastercat}</div></td>
  </tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr>
   <td></td>
   <td><span class="f15">{l_editor.extcat}</span></td>
  </tr>
  <tr>
   <td></td><td>
   <div style="overflow: auto; height: 150px;" class="list">{extcat}</div>
   
   </td>
  </tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr>
   <td></td>
   <td><span class="f15">{l_editor.configuration}</span></td>
  </tr>
  <tr>
  
  
  <td></td><td>
  <div class="list">
  <label><input type="checkbox" name="approve" value="1" class="check" id="approve" {flag_approve} /> {l_approve}</label><br />
  <label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {flag_mainpage} /> {l_mainpage}</label><br />
  <label><input type="checkbox" name="pinned" value="1" class="check" id="pinned" {flag_pinned} /> {l_add_pinned}</label><br />
  <label><input type="checkbox" name="favorite" value="1" class="check" id="favorite" {flag_favorite} /> {l_add_favorite}</label><br />
  
  <label><input name="flag_HTML" type="checkbox" class="check" id="flag_HTML" value="1" checked="checked" {disable_flag_html} /> {l_flag_html}</label><br />
  <label><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {disable_flag_raw} /> {l_flag_raw}</label><br />
   [comments]<hr/>{l_comments:mode.header}: <select name="allow_com"><option value="0"{acom:0}>{l_comments:mode.disallow}<option value="1"{acom:1}>{l_comments:mode.allow}<option value="2"{acom:2}>{l_comments:mode.default}</select>[/comments]<br />
  </div>
  
  
  </tr>
 </table>

</td>
</tr>
</table>


<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="add" />
<input type="hidden" name="mod" value="addnews" />
<input type="hidden" name="save" value="" />
<input type="button" value="{l_preview}" class="button" onclick="return preview();" />
<input type="submit" value="{l_addnews}" class="button" />
</td>
</tr>
</table>
</form>

<script language="javascript" type="text/javascript">
// Restore variables if needed
var jev = {JEV};
var form = document.getElementById('postForm');
for (i in jev) {
 //try { alert(i+' ('+form[i].type+')'); } catch (err) {;}
 if (typeof(jev[i]) == 'object') {
 	for (j in jev[i]) {
 		//alert(i+'['+j+'] = '+ jev[i][j]);
 		try { form[i+'['+j+']'].value = jev[i][j]; } catch (err) {;}
 	}	
 } else {
  try {
   if ((form[i].type == 'text')||(form[i].type == 'textarea')||(form[i].type == 'select-one')) {
    form[i].value = jev[i];
   } else if (form[i].type == 'checkbox') {
    form[i].checked = (jev[i]?true:false);
   }
  } catch(err) {;}
 }
}
</script>