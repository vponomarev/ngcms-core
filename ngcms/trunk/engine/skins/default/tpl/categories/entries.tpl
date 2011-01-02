<tr align="center" class="contRow1">
<td><a href="#" onclick="categoryModifyRequest('up', {rid});"><img src="/engine/skins/default/images/up.gif"/></a><a href="#" onclick="categoryModifyRequest('down', {rid});"><img src="/engine/skins/default/images/down.gif"/></a></td>
<td><div style="float: left; margin-right: 5px;">{cutter}</div> <div style="float: left;"><a href="admin.php?mod=categories&amp;action=edit&amp;catid={rid}">{name}</a><br/><small><a href="{showcat}" title="{l_site.view}" target="_blank">{showcat}</a></small></div></td>
<td>{alt}</td>
<td>{show_main}</td>
<td>{template}</td>
<td><a href="admin.php?mod=editnews&amp;category={rid}">{news}</a></td>
<td><a href="#" onclick="categoryModifyRequest('del', {rid});"><img title="{l_delete}" alt="{l_delete}" src="/engine/skins/default/images/delete.gif" /></a></td>
</tr>