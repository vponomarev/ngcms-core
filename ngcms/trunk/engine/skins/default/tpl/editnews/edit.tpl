<script type="text/javascript">

//
// Global variable: ID of current active input area
[edit.split]var currentInputAreaID = 'content.short';[/edit.split][edit.nosplit]var currentInputAreaID = 'content';[/edit.nosplit]

function ChangeOption(optn) {
	document.getElementById('maincontent').style.display 	= (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display 	= (optn == 'additional')?"block":"none";
	document.getElementById('attaches').style.display		= (optn == 'attaches')?"block":"none";
[isplugin comments]	document.getElementById('comments').style.display	= (optn == 'comments')?"block":"none";
	document.getElementById('showEditNews').style.display	= (optn == 'comments')?"none":"block";
	document.getElementById('rightBar').style.display	= (optn == 'comments')?"none":"";[/isplugin]
}
function preview(){
 var form = document.getElementById("form");
 if (form.content == '' || form.title.value == '') {
  alert('{l_msge_preview}');
  return false;
 }

 form['mod'].value = "preview";
 form.target = "_blank";
 form.submit();

 form['mod'].value = "editnews";
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
<input type=hidden name="area" value="" />
</form>
<form name="form" ENCTYPE="multipart/form-data" method="post" action="{php_self}?" id="form">
<input type="hidden" name="mod" value="editnews"/>
<input type="hidden" name="action" value="editnews"/>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td valign="top" >
 <!-- Left edit column -->

<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td style="background: #F0F0F0; padding: 3px;">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_bar.maincontent}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_bar.additional}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('attaches')" value="{l_bar.attaches} {attach_count}" class="navbutton" />
[isplugin comments]<input type="button" onmousedown="javascript:ChangeOption('comments')" value="{l_bar.comments} ({comnum})" class="navbutton" />[/isplugin]
</td>
</tr>
<tr><td>

<!-- MAIN CONTENT -->
<div id="maincontent" style="display: block;">
<table width="100%" cellspacing="1" cellpadding="0" border="0">
  <tr>
   <td width="10"><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td width="100">{l_title}:</td>
   <td><input type="text" class="important" size="79" name="title" value="{title}" tabindex="1" /></td>
  </tr>
  <tr>
   <td valign="top" colspan=3>{quicktags}<br /> {smilies}<br />
[edit.split]
    <div id="container.content.short" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" name="content_short" id="content.short" rows="10" tabindex="2">{content.short}</textarea></div>
    <div id="container.content.full" class="contentInactive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" name="content_full" id="content.full" rows="10" tabindex="2">{content.full}</textarea></div>
[/edit.split]
[edit.nosplit]
    <div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="content" id="content" rows="10" tabindex="2">{content}</textarea></div>
[/edit.nosplit]
	</td>
  </tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_alt_name}:</td>
   <td><input type="text" name="alt_name" value="{alt_name}" size="60" tabindex="3" /></td>
  </tr>
[meta]
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_description}:</td>
   <td><input type="text" name="description" value="{description}" size="60" tabindex="4" /></td>
  </tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_keywords}:</td>
   <td><input type="text" name="keywords" value="{keywords}" size="60" tabindex="5" /></td>
  </tr>
[/meta]
 </table>
</td></tr>
</table>
</div>

<!-- ADDITIONAL -->
<div id="additional" style="display: none;">
<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.png" hspace="8" alt="" />{l_date.manage}</td>
</tr>
<tr>
<td class="contentEntry1">

<table cellspacing=1 cellpadding=1 style="font: 11px verdana, sans-serif;">
<tr><td><input type="checkbox" name="setdate_custom" id="setdate_custom" value="1" class="check" onclick="document.getElementById('setdate_current').checked=false;" /></td><td><label for="setdate_custom">{l_date.setdate}</label></td><td><span id="cdate">{changedate}</span></td></tr>
<tr><td><input type="checkbox" name="setdate_current" id="setdate_current" value="1" class="check" onclick="document.getElementById('setdate_custom').checked=false;" /></td><td><label for="setdate_current">{l_date.setcurrent}</label> &nbsp;</td><td>&nbsp;</td>
</table>

</td>
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
<b><u>{l_attach.list}</u></b>
<table width="98%" cellspacing="1" cellpadding="2" border="0" id="attachFilelist">
<thead>
<tr><td>ID</td><td width="80">{l_attach.date}</td><td>{l_attach.filename}</td><td width="90">{l_attach.size}</td><td width="40">DEL</td></tr>
</thead>
<tbody>
<!-- <tr><td colspan="5">No data</td></tr> -->
{attach_entries}
<!-- <tr><td>*</td><td>New file</td><td colspan="2"><input type="file"/></td><td><input type="button" size="40" value="-"/></td></tr> -->
<tr><td colspan="3">&nbsp;</td><td colspan="2"><input type="button" value="{l_attach.more_rows}" style="width: 100%;" onclick="attachAddRow();" /></td></tr>
</table>
</div>

<script language="javascript">
<!--
function attachAddRow() {
	var tbl = document.getElementById('attachFilelist');
	var lastRow = tbl.rows.length;
	var row = tbl.insertRow(lastRow - 1);

	// Add cells
	row.insertCell(0).innerHTML = '*';
	row.insertCell(1).innerHTML = '{l_attach.new_file}';
	
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


</td>
<td id="rightBar" width="300" valign="top" style="background: #F0F0F0; padding-left: 3px; padding-top: 3px;">
 <!-- Right edit column -->
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr><td><img src="{skins_url}/images/nav.png" hspace="0" alt="" /></td><td>Общая информация</td></tr>
  <tr><td></td><td>Автор: <a style="font-family: Tahoma, Sans-serif;" href="{php_self}?mod=users&amp;action=editForm&amp;id={authorid}"><b>{author}</b></a> [isplugin uprofile] <a href="{author_page}" target="_blank" title="{l_site.viewuser}"><img src="{skins_url}/images/open_new.png" alt="{l_newpage}"/></a>[/isplugin]</td></tr>
  <tr><td></td><td>Создано: <b>{createdate}</b></td></tr>
  <tr><td></td><td>Отредактировано: <b>{editdate}</b></td></tr>
  <tr>
   <td width="20"><img src="{skins_url}/images/nav.png" hspace="0" alt="" /></td>
   <td>{l_category}</td>
  </tr>
  <tr>
   <td></td><td>{mastercat}</td>
  </tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr>
   <td></td>
   <td>Дополнительные категории</td>
  </tr>
  <tr>
   <td></td><td>
   <div style="width: 95%; margin-top: 5px; overflow: auto; height: 102px; margin-left: 5px; padding: 3px; border: 1px solid #AABBCC;">{extcat}</div></td>
  </tr>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="0" alt="" /></td>
   <td>Настройки</td>
  </tr>
  <tr><td></td><td><label><input type="checkbox" name="approve" value="1" {ifapp} class="check" id="approve" /> {l_approve}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="mainpage" value="1" {ifmp} class="check" id="mainpage" /> {l_mainpage}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="pinned" value="1" {ifpin} class="check" id="pinned" /> {l_add_pinned}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="favorite" value="1" {iffav} class="check" id="favorite" /> {l_add_favorite}</label></td></tr>
[comments]  <tr><td></td><td><label><input type="checkbox" name="allow_com" value="1" {ifch} class="check" id="allow_com" /> {l_com_approve}</label></td></tr>[/comments]
  <tr><td></td><td><label><input type="checkbox" name="setViews" value="1" class="check" id="setViews" /> {l_set_views}:</label> <input type="text" size="4" name="views" value="{views}" /></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_HTML" value="1" {ifhtml} class="check" id="flag_HTML" {disable_flag_html} /> {l_flag_html}</label> {flags_lost}</td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_RAW" value="1" {ifraw} class="check" id="flag_RAW" {disable_flag_raw} /> {l_flag_raw}</label> {flags_lost}</td></tr>
 </table>

</td>
</tr>
</table>

<br />

<div id="showEditNews" style="display: block;">
<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="subaction" value="doeditnews" />
<input type="button" value="{l_preview}" class="button" onClick="preview()" />
<input type="submit" value="{l_do_editnews}" accesskey="s" class="button" />&nbsp;
<input type="button" value="{l_delete}" onClick="confirmit('{php_self}?mod=editnews&amp;subaction=do_mass_delete&amp;selected_news[]={id}', '{l_sure_del}')" class="button" />
</td>
</tr>
</table>
</div>
</form>

<form method="post" name="commentsForm" id="commentsForm" action="{php_self}?mod=editnews">
<!-- COMMENTS -->
<div id="comments" style="display: none;">
<table border="0" cellspacing="0" cellpadding="0" width="98%">
<tr align="center">
<td class="contentHead">{l_author}</td>
<td class="contentHead">{l_date}</td>
<td class="contentHead">{l_comment}</td>
<td class="contentHead">{l_edit_comm}</td>
<td class="contentHead">{l_block_ip}</td>
<td class="contentHead"><input type="checkbox" name="master_box" value="all" onclick="javascript:check_uncheck_all(commentsForm)" class="check" /></td>
</tr>
{comments}
<tr>
<td colspan="5">&nbsp;</td>
</tr>
<tr align="center">
<td width="100%" colspan="6" class="contentEdit" align="center" valign="top">
<input type="hidden" name="action" value="do_mass_com_delete" />
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="mod" value="editnews" />
<input type="submit" value="{l_comdelete}" onClick="if (!confirm('{l_sure_del_com}')) {return false;}" class="button" />
</td>
</tr>
</table>
</div>
</form>
