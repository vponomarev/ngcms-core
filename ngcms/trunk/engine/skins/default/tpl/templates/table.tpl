
<script type="text/javascript">
function ChangeOption(selectedOption) {
	document.getElementById('templates').style.display = "none";
	document.getElementById('plugins').style.display    = "none";

	if(selectedOption == 'plugins')    { document.getElementById('plugins').style.display = "";    }
	if(selectedOption == 'templates') { document.getElementById('templates').style.display = ""; }
}
</script>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr><td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"><a href="admin.php?mod=templates">{l_title}</a></td></tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr align="center">
<td width="100%" class="contentNav" align="left" valign="top">
<input type="button" onmousedown="javascript:ChangeOption('templates')" value="{l_tplsite}" class="navbutton" />
<input type="button" onmousedown="javascript:ChangeOption('plugins')" value="{l_tplmodules}" class="navbutton" />
<!-- <input type="button" onmousedown="javascript:ChangeOption('tplthemes')" value="{l_tplthemes}" class="navbutton" /> -->
</td>
</tr>
</table>

<!-- BLOCK: List of available templates -->
<div id="templates" style="width: 100%; height: 120px; overflow: auto;">
<form id="template.select" method="get" action="">
<input type="hidden" name="mod" value="templates"/>
<input type="hidden" name="theme" id="template.select.theme" value=""/>
<table width="100%">
<tr class="contHead">
	<td>{l_tpl.table.name}</td>
	<td>{l_tpl.table.title}</td>
	<td>{l_tpl.table.author}</td>
	<td>{l_tpl.table.version}</td>
	<td>{l_tpl.table.reldate}</td>
	<td>&nbsp;</td>
</tr>
{template_select}
</table>
</form>
</div>

<div style="width: 100%; height: 350px; overflow: auto;">
<!-- BLOCK PLUGINS -->
<table id="plugins" style="{show_ext}" class="content" border="0" cellspacing="0" cellpadding="0" align="center">
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
<tr align="left" class="contHead">
<td width="50%">{l_filename}</td>
<td width="50%">{l_action}</td>
</tr>
{entries_site}
</table>

</td>
</tr>
</table>
</div>

<div style="width: 100%; height: 350px; overflow: auto;">
--edit area--
</div>

<!--
</td>
</tr>
</table>
</div>
-->

