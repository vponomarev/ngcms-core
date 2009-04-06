
<script type="text/javascript">
function ChangeOption(optn) {
	document.getElementById('maincontent').style.display = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display  = (optn == 'additional')?"block":"none";
}

function preview(){

 var form = document.getElementById("postForm");
 if (form.content.value == '' || form.title.value == '') {
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
<br/>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td valign="top" >
 <!-- Left edit column -->

<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td style="background: #F0F0F0; padding: 3px;">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="navbutton" />
</td>
</tr>
<tr><td>

<!-- MAIN CONTENT -->
<div id="maincontent" style="display: block;">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
   <td width="10"><img src="{skins_url}/images/nav.png" hspace="8" alt="" /></td>
   <td width="100">{l_title}:</td>
   <td><input type="text" class="important" size="79" name="title" value="" tabindex="1" /></td>
  </tr>
  <tr>
   <td valign="top" colspan=3>{quicktags}<br /> {smilies}<br />
   <textarea style="margin-left: 0px; margin-right: 0px; margin-top: 1px; width: 99%;" name="content" rows="16" tabindex="2"></textarea></td>
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
<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.png" hspace="8" alt="" /><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
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
</td></tr>
</table>

</td>
<td id="rightBar" width="300" valign="top" style="background: #F0F0F0; padding-left: 3px; padding-top: 3px;">
 <!-- Right edit column -->
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
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
  <tr><td></td><td><label><input type="checkbox" name="approve" value="1" class="check" id="approve" {flag_approve} /> {l_approve}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {flag_mainpage} /> {l_mainpage}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="pinned" value="1" class="check" id="pinned" {flag_pinned} /> {l_add_pinned}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="favorite" value="1" class="check" id="favorite" {flag_favorite} /> {l_add_favorite}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="allow_com" value="1" class="check" id="allow_com" {flag_allow_com} /> {l_com_approve}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_HTML" value="1" class="check" id="flag_HTML" {disable_flag_html} /> {l_flag_html}</label></td></tr>
  <tr><td></td><td><label><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {disable_flag_raw} /> {l_flag_raw}</label></td></tr>
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