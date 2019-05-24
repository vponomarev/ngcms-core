<div class="body">
	<form action="" method="post">
		<input type="hidden" name="action" value="config"/>
		<input type="hidden" name="stage" value="0"/>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top"><br/>
					<blockquote>
						{l_welcome.textblock1}<br/>
						{l_welcome.textblock2}
					</blockquote>
				</td>
			</tr>
		</table>
		<p style="margin-bottom: 5px;"><b>{l_welcome.choose_lang}</b></p>
		{lang_select}
		<p style="margin-bottom: 5px;"><b>{l_welcome.licence}</b></p>
		<div style="height: 300px; border: 1px solid #76774C; background-color: #FDFDD3; margin-bottom: 10px; padding: 5px; overflow: auto;">
			{license}
		</div>
		<label for="agree"><input type="checkbox" name="agree" id="agree" value="1" {ad}/> {l_welcome.licence.accept}
		</label><br/><br/>
		<input type="submit" value="{l_welcome.continue} &raquo;&raquo;" class="filterbutton"/>
	</form>
</div>
