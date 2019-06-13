<tr class="contentEntry1" id="re.row.{id}">
	<td width="40px;"><a href="#" onclick="reMoveUp({id}); return false;"><img src="{{ skins_url }}/images/up.gif"/></a><a href="#" onclick="reMoveDown({id}); return false;"><img src="{{ skins_url }}/images/down.gif"/></a>
	</td>
	<td class="contentEntry1" id="re.{id}.id">{id}</td>
	<td class="contentEntry1" id="re.{id}.pluginName" width="70px">{pluginName}</td>
	<td class="contentEntry1" id="re.{id}.handlerName" width="80px">{handlerName}</td>
	<td class="contentEntry1" id="re.{id}.description">{description}</td>
	<td class="contentEntry1" id="re.{id}.regex">{regex}</td>
	<td class="contentEntry1" id="re.{id}.flags">{flags}</td>
	<td class="contentEntry1" align="right" width="80px;">
		<input id="btn.{id}" type="button" class="navbutton" style="width: 30px;" value="Edit" onclick="reEditRow({id});"/>
		<input id="btn.del.{id}" type="button" class="navbutton" style="width: 30px;" value="Del" onclick="reDeleteRow({id});"/>
	</td>
</tr>
