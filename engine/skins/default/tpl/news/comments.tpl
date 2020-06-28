<tr align="center">
	<td class="contentEntry1">[userlink]<a href="{com_url}">{com_author}</a>[/userlink]</td>
	<td class="contentEntry1">{com_time}</td>
	<td class="contentEntry1" align="left">{com_part}</td>
	<td class="contentEntry1"><a href="{php_self}?mod=editcomments&newsid={com_post}&comid={com_id}">{l_edit_comm}</a>
	</td>
	<td class="contentEntry1"><a href="{php_self}?mod=ipban&iplock={com_ip}" target="_blank" title="{l_block_ip}">{com_ip}</a>
	</td>
	<td class="contentEntry1">
		<input type="checkbox" name="delcomid[]" value="{com_id}-{com_author}-{com_ip}-{com_post}" class="check"/></td>
</tr>