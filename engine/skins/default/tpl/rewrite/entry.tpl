<tr id="re.row.{id}">
	<td id="re.{id}.id">{id}</td>
	<td id="re.{id}.pluginName">{pluginName}</td>
	<td id="re.{id}.handlerName">{handlerName}</td>
	<td id="re.{id}.description" nowrap>{description}</td>
	<td id="re.{id}.regex" nowrap>{regex}</td>
	<td id="re.{id}.flags" nowrap>{flags}</td>
	<td class="text-right">
		<div class="btn-group btn-group-sm" role="group">
			<button type="button" onclick="reMoveUp({id});" class="btn btn-outline-primary"><i class="fa fa-arrow-up"></i></button>
			<button type="button" onclick="reMoveDown({id});" class="btn btn-outline-primary"><i class="fa fa-arrow-down"></i></button>

			<button id="btn.{id}" type="button" onclick="reEditRow({id});" class="btn btn-outline-primary"><i class="fa fa-pencil"></i></button>
			<button id="btn.del.{id}" type="button" onclick="reDeleteRow({id});" class="btn btn-outline-danger"><i class="fa fa-trash"></i></button>
		</div>
	</td>
</tr>
