<div class="body">
	<form action="" method="post" name="db" id="db">
		<input type="hidden" name="action" value="config" id="action"/>
		<input type="hidden" name="stage" value="4" id="stage"/>
		{hinput}
		<p style="width: 99%;">
			{l_templates.textblock}
		</p>
		{templates}
		<div style="float: left; width: 99%;">
			<br/>
			<table width="100%">
				<tr>
					<td width="33%">
						<input type="button" value="&laquo;&laquo; {l_button.back}" onclick="document.getElementById('stage').value='2'; form.submit();" class="filterbutton"/>
					</td>
					<td></td>
					<td width="33%" style="text-align: right;">
						<input type="submit" value="{l_button.next} &raquo;&raquo;" class="filterbutton"/></td>
				</tr>
			</table>
		</div>
	</form>
</div>