<form method=post name=form action="{php_self}?mod=pm&action=send">
	<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td width="50%" style="padding-right:10px;" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					<tr>
						<td width="100%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8" alt=""/>{l_content}
						</td>
					</tr>
					<tr>
						<td width="100%">
							{quicktags}<br/>{smilies}<br/><textarea name="content" id="content" rows="10" cols="60" tabindex="1" maxlength="3000"/></textarea>
						</td>
					</tr>
				</table>
			</td>
			<td width="50%" style="padding-left:10px;" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width=100% colspan="2" class="contentHead">
							<img src="{skins_url}/images/nav.gif" hspace="8" align="absmiddle">{l_additional}
						</td>
					</tr>
					<tr>
						<td width=50% class="contentEntry1">{l_title}</td>
						<td width=50% class="contentEntry2">
							<input type="text" class=important size="40" name="title" tabindex="2" maxlength="50"/></td>
					</tr>
					<tr>
						<td width="50%" class="contentEntry1">{l_receiver}<br/>
							<small>{l_receiver_desc}</small>
						</td>
						<td width="50%" class="contentEntry2">
							<input type="text" name="sendto" size="40" tabindex="3" maxlength="70"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td width="100%" colspan="2" class="contentEdit" valign="top">
				<input type="submit" value="{l_send}" accesskey="s" class="button"/>
			</td>
		</tr>
	</table>
</form>