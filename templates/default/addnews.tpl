<script type="text/javascript">
function ChangeOption(optn) {
	document.getElementById('maincontent').style.display = (optn == 'maincontent')?"block":"none";
	document.getElementById('additional').style.display  = (optn == 'additional')?"block":"none";
}
</script>
<form name="_tmp_storage" action="" id="_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form name="form" method="post" action="">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36"></td>
				<td background="{tpl_url}/images/2z_41.gif" width="100%">&nbsp;<font color="#FFFFFF"><b>{l_addnews_title}</b></font></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36"></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td background="{tpl_url}/images/2z_54.gif" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF">
				[if-have-perm]<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="button" />
<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="button" />
</td>
</tr>
</table>[/if-have-perm]
<br />
<div id="maincontent" style="display: block;">
<table border="0" cellspacing="0" cellpadding="0" style="padding-left: 10px;">
<tr>
<td width="100%" style="padding: 3px;">{l_newstitle}</td>
</tr>
<tr>
<td width="100%" style="padding: 3px;"><input type="text" class="mw_small_forms" size="40" name="title" /></td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">{l_category}&nbsp;{catlist}&nbsp;<input type="button" name="catbutton" id="catbutton" value="{l_add}" class="button" onClick="addcat(); return false;" /></td>
</tr>
<tr>
<td width="100%" style="padding: 3px;"><input class="mw_small_forms" type="text" name="categories" maxlength="255" id="categories" value="" size="40" /></td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">{l_contentshort}</td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">{quicktags_short}<br />{smilies_short}<br /><textarea name="contentshort" rows="10" cols="75"></textarea></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" style="padding-left: 10px;">
<tr>
<td width="100%" style="padding: 3px;">{l_contentfull} <a href="javascript:ShowOrHide('full');"><img src="{tpl_url}/images/show_hide.gif" /></a></td>
</tr>
<tr id="full" style="display: none;">
<td width="100%" valign="top"><br />{quicktags_full}<br />{smilies_full}<br /><textarea name="contentfull" rows="10" cols="75"></textarea></td>
</tr>
</table>
</div>
[if-have-perm]
<div id="additional" style="display: none;">
<table style="padding-left: 10px;" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" style="padding: 3px;">{l_alt_name}</td>
</tr>
<tr>
<td width="100%" style="padding: 3px;"><input type="text" name="alt_name" size="40" /></td>
</tr>
[meta]
<tr>
<td width="100%" style="padding: 3px;">{l_description}</td>
</tr>
<tr>
<td width="100%" style="padding: 3px;"><input type="text" name="description" value="" maxlength="255" size="40" /></td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">{l_keywords}</td>
</tr>
<tr>
<td width="100%"><input type="text" name="keywords" value="" maxlength="255" size="40" /></td>
</tr>
[/meta]
<tr>
<td width="100%" style="padding: 3px;"><input type="checkbox" name="customdate" id="customdate" value="customdate" class="check" /> <label for="customdate">{l_custom_date}</label></td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">{changedate}</td>
</tr>
{plugin_xfields}
[isplugin nsched]{nsched}[/isplugin]
[isplugin finance]{finance}[/isplugin]
{plugin_tags}
<tr>
<td width="100%" style="padding: 3px;">{l_additional}</td>
</tr>
<tr>
<td width="100%" style="padding: 3px;">
<input type="checkbox" name="mainpage" value="1" checked class="check" id="mainpage" /> <label for="mainpage"> {l_mainpage}</label>
<br /><input type="checkbox" name="allow_com" value="1" checked class="check" id="allow_com" /> <label for="allow_com"> {l_allow_com}</label>
<br /><input type="checkbox" name="approve" value="1" checked class="check" id="approve" /> <label for="approve">{l_approve}</label>
<br /><input type="checkbox" name="favorite" value="1" class="check" id="favorite" /> <label for="favorite">{l_add_favorite}</label>
<br /><input type="checkbox" name="pinned" value="1" class="check" id="pinned" /> <label for="pinned">{l_add_pinned}</label>
<br /><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {disable_flag_raw}> <label for="flag_RAW">{l_flag_raw}</label>
<br /><input type="checkbox" name="flag_HTML" value="1" class="check" id="flag_HTML" {disable_flag_html}> <label for="flag_HTML">{l_flag_html}</label>
</td>
</tr>
</table>
</div>
[/if-have-perm]
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center">
<td width="100%" class="contentEdit" align="center" valign="top">
<input type="hidden" name="subaction" value="add" />
<input type="hidden" name="action" value="addnews" />
<input type="hidden" name="save" value="" />
<input type="submit" value="{l_addnews}" class="button" />
</td>
</tr>
</table>
				</td>
				<td background="{tpl_url}/images/2z_59.gif" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4"></td>
				<td background="{tpl_url}/images/2z_69.gif" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4"></td>
			</tr>
		</table>
		</td>
	</tr>
</table></form>