
<script type="text/javascript">

function ChangeOption(selectedOption) {
document.getElementById('admin').style.display     = "none";
document.getElementById('extras').style.display    = "none";
<!-- document.getElementById('site').style.display      = "none"; -->
<!-- document.getElementById('tplthemes').style.display = "none"; -->

document.getElementById('templates').style.display = "none";

if(selectedOption == 'admin')     { document.getElementById('admin').style.display = "";     }
if(selectedOption == 'extras')    { document.getElementById('extras').style.display = "";    }
<!-- if(selectedOption == 'site')      { document.getElementById('site').style.display = "";      } -->
<!-- if(selectedOption == 'tplthemes') { document.getElementById('tplthemes').style.display = ""; } -->

if(selectedOption == 'templates') { document.getElementById('templates').style.display = ""; }
}
</script>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="center" valign="top">
<!-- <input type="button" onmousedown="javascript:ChangeOption('admin')" value="{l_tpladmin}" class="navbutton" /> -->
<input type="button" onmousedown="javascript:ChangeOption('templates')" value="{l_tplsite}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('extras')" value="{l_tplmodules}" class="navbutton" />
<!-- <input type="button" onmousedown="javascript:ChangeOption('tplthemes')" value="{l_tplthemes}" class="navbutton" /> -->
</td>
</tr>
</table>

<!-- BLOCK: ADMIN -->
<table id="admin" style="{show_adm}" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_tplthemeselect}</td>
</tr>
<tr>
<td class="contentEntry1">
<form action="" method="post">
<input type="hidden" name="where" value="actions" />
<select name="skin">
{skins_list}
</select>
<input type="submit" value="{l_select}" class="button" />
</form>
</td>
</tr>
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_tpladmin}</td>
</tr>
<tr>
<td class="contentEntry1">

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="50%" class="contentHead">{l_filename}</td>
<td width="50%" class="contentHead">{l_action}</td>
</tr>
{entries_actions}
</table>

</td>
</tr>
</table>

<!-- BLOCK PLUGINS -->
<table id="extras" style="{show_ext}" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_tplmodules}</td>
</tr>
<tr>
<td class="contentEntry1">

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="50%" class="contentHead">{l_filename}</td>
<td width="50%" class="contentHead">{l_action}</td>
</tr>
{entries_extras}
</table>
</td>
</tr>
</table>

<!-- BLOCK TEMPLATES -->
<div id="templates" style="float: left; width: 100%;">
<form id="template.select" method="get" action="">
<input type="hidden" name="mod" value="templates"/>
<input type="hidden" name="theme" id="template.select.theme" value=""/>
<table width="100%" style="padding-top:10px;">
<tr class="h-l-tpl">
	<td>Name</td>
	<td>Title</td>
	<td>Author</td>
	<td>Version</td>
	<td>Reldate</td>
	<td>&nbsp;</td>
</tr>
{template_select}
</table>
</form>

<table width="100%" id="site" style="{show_site}" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_tplsite}</td>
</tr>
<tr>
<td class="contentEntry1">

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="50%" class="contentHead">{l_filename}</td>
<td width="50%" class="contentHead">{l_action}</td>
</tr>
{entries_site}
</table>

</td>
</tr>
</table>
</div>

<!--
<table id="tplthemes" style="display:none;" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_actions}</td>
<td class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt="" />{l_site}</td>
</tr>
<tr>
<td class="contentEntry1">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="50%" class="contentHead">{l_dirname}</td>
<td width="50%" class="contentHead">{l_action}</td>
</tr>
{themes_entries_actions}
</table>
</td>
<td class="contentEntry1">

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="50%" class="contentHead">{l_dirname}</td>
<td width="50%" class="contentHead">{l_action}</td>
</tr>
{themes_entries_site}
</table>
-->
</td>
</tr>
</table>