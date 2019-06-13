<script language="javascript">
	function markNameEdit() {
		var e = document.getElementById('bk_editImageName');
		var d = e.innerHTML;
		e.innerHTML = '<input type="text" name="newname" value="' + escape(d) + '"/>';
	}

</script>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{skins_url}/images/nav.gif" hspace="8"><a href="?mod=images">{l_images_title}</a> &#8594;
			{l_edit_title} "{orig_name}"
		</td>
	</tr>
</table>
<br/>
<a href="{link_back}"><strong>{l_back_to_list}</strong></a>
<br/>
<form method="post" action="admin.php">
	<input type="hidden" name="mod" value="images"/>
	<input type="hidden" name="subaction" value="editApply"/>
	<input type="hidden" name="id" value="{id}"/>

	<table class="contentNav" border="0" cellspacing="0" cellpadding="2" width="100%">
		<tr class="contRow2">
			<td width="300">{l_original_name}:</td>
			<td>{orig_name}</td>
		</tr>
		<tr class="contRow1">
			<td>{l_name}:</td>
			<td><span id="bk_editImageName">{name}</span>
				<a href="#" onclick="markNameEdit(); this.style.display='none';">{l_rename}</a></td>
		</tr>
		<tr class="contRow1">
			<td>{l_url}:</td>
			<td><a target="_blank" href="{fileurl}">{fileurl}</a></td>
		</tr>
		<tr class="contRow2">
			<td>{l_date}:</td>
			<td>{date}</td>
		</tr>
		<tr class="contRow2">
			<td>{l_author}:</td>
			<td>{author}</td>
		</tr>
		<tr class="contRow2">
			<td>{l_img_size}:</td>
			<td>{width} x {height} ( {size} )</td>
		</tr>
		<tr class="contRow2">
			<td>{l_category}:</td>
			<td>{category}</td>
		</tr>
		<tr class="contRow1">
			<td>{l_wmimage}:</td>
			<td>[have_stamp]{l_added}[/have_stamp][no_stamp]<label><input type="checkbox" name="createStamp" value="1"/>
					{l_add}[/no_stamp]</td>
		</tr>
		<tr class="contRow1">
			<td>{l_preview}:</td>
			<td>
				{l_status}: {preview_status}
				[preview]<br/>{l_s_size}: {preview_width} x {preview_height} ( {preview_size} )[/preview]<br/><br/>
				<a href="#" onclick="document.getElementById('bk_createPreview').style.display='block'; this.style.display='none';">{l_create_edit}</a>
				<div id="bk_createPreview" style="display: none;">
					<fieldset>
						<legend>{l_preview}</legend>
						<table>
							<tr>
								<td colspan="2"><label><input type="checkbox" name="flagPreview" value="1"/>
										{l_create_edit_preview}</label></td>
							</tr>
							<tr>
								<td>{l_size_max}:</td>
								<td><input size="4" name="thumbSizeX" value="{thumb_size_x}"/> x
									<input size="4" name="thumbSizeY" value="{thumb_size_y}"/> {l_pixels}
								</td>
							</tr>
							<tr>
								<td>{l_quality_jpeg}:</td>
								<td><input size="2" name="thumbQuality" value="{thumb_quality}"/> %</td>
							</tr>
							<tr>
								<td>{l_s_wmimage}:</td>
								<td><label><input type="checkbox" name="flagStamp" value="1"/> {l_set_up}</label></td>
							</tr>
							<tr>
								<td>{l_s_shadow}:</td>
								<td><label><input type="checkbox" name="flagShadow" value="1"/> {l_add}</label></td>
							</tr>
						</table>
					</fieldset>
				</div>
			</td>
		</tr>
		[preview]
		<tr class="contRow1">
			<td>{l_url_preview}:</td>
			<td><a target=_blank" href="{thumburl}">{thumburl}</a></td>
		</tr>
		<tr class="contRow1">
			<td>&nbsp;</td>
			<td><img src="{thumburl}" border="0"/></td>
		</tr>
		[/preview]
		<tr class="contRow1">
			<td>{l_description}:</td>
			<td><textarea name="description" cols="80" rows="2">{description}</textarea></td>
		</tr>
		<tr class="contRow1">
			<td colspan="2"><input type="submit" style="width: 300px;" value="{l_save}" class="button"/></td>
		</tr>
	</table>
</form>
