<tr id="re.row.{id}">
 <td><a href="#" onclick="reMoveUp({id}); return false;"><img src="{skins_url}/images/up.gif"/></a><a href="#" onclick="reMoveDown({id}); return false;"><img src="{skins_url}/images/down.gif"/></a></td>
 <td id="re.{id}.id">{id}</td>
 <td id="re.{id}.pluginName">{pluginName}</td>
 <td id="re.{id}.handlerName">{handlerName}</td>
 <td id="re.{id}.description">{description}</td>
 <td id="re.{id}.regex">{regex}</td>
 <td id="re.{id}.flags">{flags}</td>
 <td>
  <input id="btn.{id}" type="button" value="Edit" onclick="reEditRow({id});"/>
  <input id="btn.del.{id}" type="button" value="Delete" onclick="reDeleteRow({id});"/>
 </td>
</tr>
