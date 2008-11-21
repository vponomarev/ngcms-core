<script type="text/javascript">
function ChangeOption(optn) {
	document.getElementById('maincontent').style.display  = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display   = (optn == 'additional')?"block":"none";
	document.getElementById('comments').style.display     = (optn == 'comments')?"block":"none";
	document.getElementById('showEditNews').style.display = (optn == 'comments')?"none":"block";
}
function preview(){

 var form = document.getElementById("postForm");
 if (form.contentshort.value == '' || form.title.value == '') {
  alert('{l_msge_preview}');
  return false;
 }

 form.mod.value = "preview";
 form.target = "_blank";
 form.submit();

 form.mod.value = "editnews";
 form.target = "_self";
 return true;
}
</script>
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form name="form" method="post" action="{php_self}?mod=editnews&amp;action=editnews" id="postForm">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('comments')" value="{l_comments}" class="navbutton" />
</td>
</tr>
</table>
<br />

<!-- MAIN CONTENT -->
<div id="maincontent" style="display: block;">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_title}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_category}&nbsp;{catlist}&nbsp;<input type="button" id="catbutton" name="catbutton" value="{l_add}" class="button" onClick="addcat(); return false;" /></td>
</tr>
<tr>
<td width=50% class="contentEntry1"><input type="text" class="important" size="40" name="title" value="{title}" tabindex="1" /></td>
<td width=50% class="contentEntry1"><input type="text" name="categories" maxlength="255" id="categories" value="{allcats}" size="40" /></td>
</tr>
<tr>
<td width=50% class="contentHead" colspan="2"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_contentshort}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags_short}<br />{smilies_short}<br />
<textarea name="contentshort" rows="15" cols="100">{short}</textarea></td>
</tr>
<tr>
<td width=50% class="contentHead" colspan="2"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_contentfull}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags_full}<br />{smilies_full}<br />
<textarea name="contentfull" rows="15" cols="100">{full}</textarea></td>
</tr>
</table>
</div>

<!-- ADDITIONAL -->
<div id="additional" style="display: none;">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_alt_name}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" /><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="alt_name" value="{alt_name}" size="40" tabindex="2" /></td>
<td width="50%" class="contentEntry1">{changedate}</td>
</tr>
[meta]
<tr>
<td width=50% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_description}</td>
<td width=50% class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_keywords}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="description" value="{description}" maxlength="255" size="40" /></td>
<td width="50%" class="contentEntry1"><input type="text" name="keywords" value="{keywords}" maxlength="255" size="40" /></td>
</tr>
[/meta]
[isplugin xfields]{plugin_xfields}[/isplugin]
[isplugin nsched]{nsched}[/isplugin]
[isplugin finance]{finance}[/isplugin]
[isplugin tags]{plugin_tags}[/isplugin]
[options]
<tr>
<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_additional}</td>
</tr>
<tr>
<td width="100%" colspan="2" class="contentEntry1">
<table width="600">
<tr>
<td><input type="checkbox" name="approve" value="1" {ifapp} class="check" id="approve" /> <label for="approve">{l_approve}</label></td>
<td width="30"></td>
<td style="background: #F0F0F0;"><input type="checkbox" name="flag_HTML" value="1" {ifhtml} class="check" id="flag_HTML" {disable_flag_html} /> <label for="flag_HTML">{l_flag_html}</label> {flags_lost}</td>
</tr>
<tr>
<td><input type="checkbox" name="mainpage" value="1" {ifmp} class="check" id="mainpage" /> <label for="mainpage"> {l_mainpage}</label></td>
<td width="30"></td>
<td style="background: #F0F0F0;"><input type="checkbox" name="flag_RAW" value="1" {ifraw} class="check" id="flag_RAW" {disable_flag_raw} /> <label for="flag_RAW">{l_flag_raw}</label> {flags_lost}</td>
</tr>
<tr>
<td><input type="checkbox" name="pinned" value="1" {ifpin} class="check" id="pinned" /> <label for="pinned">{l_add_pinned}</label></td>
<td width="30"></td>
<td><input type="checkbox" name="allow_com" value="1" {ifch} class="check" id="allow_com" /> <label for="allow_com"> {l_com_approve}</label></td>
</tr>
<tr>
<td><input type="checkbox" name="favorite" value="1" {iffav} class="check" id="favorite" /> <label for="favorite">{l_add_favorite}</label></td>
<td width="30"></td>
<td><input type="checkbox" name="setViews" value="1" class="check" id="setViews" /> <label for="setViews">{l_set_views}:</label> <input type="text" size="4" name="views" value="{views}" /></td>
</tr>
</table>
</td>
[/options]
</tr>
</table>
</div>
<br />

<div id="showEditNews" style="display: block;">
<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="subaction" value="doeditnews" />
<input type="hidden" name="mod" value="editnews" />
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
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="center">
<td width="20%" class="contentHead">{l_author}</td>
<td width="17%" class="contentHead">{l_date}</td>
<td width="40%" class="contentHead">{l_comment}</td>
<td width="10%" class="contentHead">{l_edit_comm}</td>
<td width="15%" class="contentHead">{l_block_ip}</td>
<td width="5%" class="contentHead"><input type="checkbox" name="master_box" value="all" onclick="javascript:check_uncheck_all(commentsForm)" class="check" /></td>
</tr>
{comments}
<tr>
<td colspan="5">&nbsp;</td>
</tr>
<tr align="center">
<td width="100%" colspan="5" class="contentEdit" align="center" valign="top">
<input type="hidden" name="action" value="do_mass_com_delete" />
<input type="hidden" name="id" value="{id}" />
<input type="hidden" name="mod" value="editnews" />
<input type="submit" value="{l_comdelete}" onClick="if (!confirm('{l_sure_del_com}')) {return false;}" class="button" />
</td>
</tr>
</table>
</div>
</form>