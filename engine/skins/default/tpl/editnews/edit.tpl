<script type="text/javascript">
function ChangeOption(optn) {
	document.getElementById('maincontent').style.display  = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display   = (optn == 'additional')?"block":"none";
	document.getElementById('comments').style.display     = (optn == 'comments')?"block":"none";
	document.getElementById('showEditNews').style.display = (optn == 'comments')?"none":"block";
}
function preview(){

 var form = document.getElementById("form");
 if (form.content == '' || form.title.value == '') {
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
<form name="form" method="post" action="{php_self}?mod=editnews&amp;action=editnews" id="form">
<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td valign="top" >
 <!-- Left edit column -->

<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td style="background: #F0F0F0; padding: 3px;">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('comments')" value="{l_comments}" class="navbutton" />
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
   <td valign="top" colspan=3>{quicktags}<!--<br /> {smilies_short}<br /> -->
   <textarea style="margin-left: 0px; margin-right: 0px; margin-top: 1px; width: 99%;" name="content" rows="16" tabindex="2">{content}</textarea></td>
  </tr>
  <tr>
   <td><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td>{l_alt_name}:</td>
   <td><input type="text" name="alt_name" value="{alt_name}" size="60" tabindex="3" /></td>
  </tr>
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
 </table>
</td></tr>
</table>
</div>

<!-- ADDITIONAL -->
<div id="additional" style="display: none;">
<table border="1" cellspacing="0" cellpadding="0" class="content" align="center">

<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.png" hspace="8" alt="" /><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.png" hspace="8" alt="" />{l_alt_name}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1">{changedate}</td>
<td width="50%" class="contentEntry1"><input type="text" name="alt_name" value="{alt_name}" size="40" tabindex="2" /></td>
</tr>
[isplugin xfields]{plugin_xfields}[/isplugin]
[isplugin nsched]{nsched}[/isplugin]
[isplugin finance]{finance}[/isplugin]
[isplugin tags]{plugin_tags}[/isplugin]
[options]
</table>
</div>


</td>
<td width="300" valign="top" style="background: #F0F0F0; padding-left: 3px; padding-top: 3px;">
 <!-- Right edit column -->
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr><td><img src="{skins_url}/images/nav.png" hspace="0" alt="" /></td><td>Общая информация</td></tr>
  <tr><td></td><td>Автор: <b>{author}</b></td></tr>
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
  <tr><td></td><td><label><input type="checkbox" name="allow_com" value="1" {ifch} class="check" id="allow_com" /> {l_com_approve}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="setViews" value="1" class="check" id="setViews" /> {l_set_views}:</label> <input type="text" size="4" name="views" value="{views}" /></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_HTML" value="1" {ifhtml} class="check" id="flag_HTML" {disable_flag_html} /> {l_flag_html}</label> {flags_lost}</td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_RAW" value="1" {ifraw} class="check" id="flag_RAW" {disable_flag_raw} /> {l_flag_raw}</label> {flags_lost}</td></tr>
 </table>

</td>
</tr>
</table>

<!--
<tr>
<td width=50% class="contentHead" colspan="2"><img src="{skins_url}/images/nav.png" hspace="8" alt="" />{l_contentshort}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags_short}<br />{smilies_short}<br />
<textarea name="contentshort" rows="15" cols="90">{short}</textarea></td>
</tr>
<tr>
<td width=50% class="contentHead" colspan="2"><img src="{skins_url}/images/nav.png" hspace="8" alt="" />{l_contentfull}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags_full}<br />{smilies_full}<br />
<textarea name="contentfull" rows="15" cols="100">{full}</textarea></td>
</tr>
</table>
-->

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