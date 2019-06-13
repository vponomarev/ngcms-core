<div class="body">
	<form action="" method="post" name="db" id="db">
		<input type="hidden" name="action" value="config" id="action"/>
		<input type="hidden" name="stage" value="3" id="stage"/>
		{hinput}
		<p style="width: 99%;">{l_plugins.textblock}</p>
		<table class="plugTable" cellspacing="1" cellpadding="2">
			<thead>
			<tr>
				<td style="background-color: #dbe4ed; color:#3c9c08;">{l_plugins.activate}</td>
				<td style="background-color: #dbe4ed; color:#3c9c08;">ID</td>
				<td width="25%" style="background-color: #dbe4ed; color:#3c9c08;">{l_plugins.title}</td>
				<td style="background-color: #dbe4ed; color:#3c9c08;">{l_plugins.description}</td>
			</tr>
			</thead>
			{plugins}
		</table>
		<div style="float: left; width: 99%;">
			<table width="100%">
				<tr>
					<td width="33%">
						<input type="button" value="&laquo;&laquo; {l_button.back}" onclick="document.getElementById('stage').value='1'; document.getElementById('db').submit();" class="filterbutton"/>
					</td>
					<td></td>
					<td width="33%" style="text-align: right;">
						<input type="submit" value="{l_button.next} &raquo;&raquo;"/ class="filterbutton">
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>