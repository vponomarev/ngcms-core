<tr>
	<td colspan="2">
		<fieldset class="admGroup">
			<legend class="title">
				{title}
				[toggle]
					[<a href="#" data-toggle="admin-group">{l_group.toggle}</a>]
				[/toggle]
			</legend>
			<div class="admin-group-content" [toggle] style="display:{toggle_mode};" [/toggle]>
				<table class="table table-sm">
					<tbody>
						{entries}
					</tbody>
				</table>
			</div>
		</fieldset>
	</td>
</tr>
