
<script type="text/javascript">
function ChangeOption(optn) {
	document.getElementById('maincontent').style.display = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display  = (optn == 'additional')?"block":"none";
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

 form.mod.value = "addnews";
 form.target = "_self";
 return true;
}
</script>
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form id="postForm" name="form" method="post" action="{php_self}" target="_self">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<input type="button" onclick="ChangeOption('maincontent');" value="{l_maincontent}" class="navbutton" />
<input type="button" onclick="ChangeOption('additional');" value="{l_additional}" class="navbutton" />
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
<td width="50%" class="contentEntry1"><input type="text" class="important" size="40" name="title" tabindex="1" /></td>
<td width="50%" class="contentEntry1"><input type="text" name="categories" maxlength="255" id="categories" value="" size="40" /></td>
</tr>
<tr>
<td width="50%" class="contentHead" colspan="2"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_contentshort}</td>
</tr>
<tr>
<td width="100%" colspan="2" valign="top" class="contentEntry1">{quicktags_short}<br />{smilies_short}<br /><textarea name="contentshort" rows="15" cols="100"></textarea></td>
</tr>
</table>
<table id="maincontent2" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td width="100%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_contentfull} <a href="javascript:ShowOrHide('full');"><img src="{skins_url}/images/show_hide.gif" /></a></td>
</tr>
<tr id="full" style="display: none;">
<td width="100%" valign="top" class="contentEntry1">{quicktags_full}<br />{smilies_full}<br /><textarea name="contentfull" rows="15" cols="100"></textarea></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
</div>

<!-- ADDITIONAL -->
<div id="additional" style="display: none;">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td colspan="2" width="100%" style="padding-left:10px;" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_alt_name}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" /><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="alt_name" size="40" tabindex="2" /></td>
<td width="50%" class="contentEntry1">{changedate}</td>
</tr>
[meta]
<tr>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_description}</td>
<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_keywords}</td>
</tr>
<tr>
<td width="50%" class="contentEntry1"><input type="text" name="description" value="" maxlength="255" size="40" /></td>
<td width="50%" class="contentEntry1"><input type="text" name="keywords" value="" maxlength="255" size="40" /></td>
</tr>
[/meta]
{plugin_xfields}
[isplugin nsched]{nsched}[/isplugin]
[isplugin finance]{finance}[/isplugin]
{plugin_tags}
<tr>
<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_additional}</td>
</tr>
<tr>
<td width="100%" colspan="2" class="contentEntry1">
[options]
<input type="checkbox" name="approve" value="1" checked class="check" id="approve" /> <label for="approve">{l_approve}</label> 
<br /><input type="checkbox" name="mainpage" value="1" checked class="check" id="mainpage" /> <label for="mainpage"> {l_mainpage}</label>
<br /><input type="checkbox" name="allow_com" value="1" checked class="check" id="allow_com" /> <label for="allow_com"> {l_allow_com}</label>
<br /><input type="checkbox" name="favorite" value="1" class="check" id="favorite" /> <label for="favorite">{l_add_favorite}</label>
<br /><input type="checkbox" name="pinned" value="1" class="check" id="pinned" /> <label for="pinned">{l_add_pinned}</label>
<br /><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {disable_flag_raw}/> <label for="flag_RAW">{l_flag_raw}</label>
<br /><input type="checkbox" name="flag_HTML" value="1" class="check" id="flag_HTML" {disable_flag_html}/> <label for="flag_HTML">{l_flag_html}</label>
[/options]
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="add" />
<input type="hidden" name="mod" value="addnews" />
<input type="hidden" name="save" value="" />
<input type="button" value="{l_preview}" class="button" onClick="return preview();" />
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
// alert(i+' ('+typeof(jev[i])+')');
 if (typeof(jev[i]) == 'object') {
 	//alert('OBJ'); 
 	for (j in jev[i]) {
 		//alert(i+'['+j+'] = '+ jev[i][j]);
 		try { form[i+'['+j+']'].value = jev[i][j]; } catch (err) {;}
 	}	
 } else {
  try {
   form[i].value = jev[i];
  } catch(err) {;}
 }
}
</script>