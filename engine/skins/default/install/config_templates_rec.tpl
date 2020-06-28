<div class="col-sm-6">
	<label class="head"><input type="radio" name="template" value="{name}"{checked}> {title}</label>
	<table class=" table-condensed table-">
		<tr class="row">
			<td class="col-sm-7">
				<a href="{templateURL}/{name}/{image}" target="_blank">
					<img src="{templateURL}/{name}/{imagepreview}" class="img-responsive">
				</a>
			</td>
			<td class="col-sm-5" style="vertical-align: middle;">
				<b>{l_template.author}:</b>&nbsp; &nbsp; <u>{author}</u><br/>
				<b>ID:</b>&nbsp; &nbsp; <u>{id}</u><br/>
				<b>{l_template.version}:</b>&nbsp; &nbsp;{version}<br/>
				<b>{l_template.reldate}:</b>&nbsp;  {reldate}
			</td>
		</tr>
	</table>
</div>