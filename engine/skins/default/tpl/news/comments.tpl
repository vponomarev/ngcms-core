<tr>
	<td nowrap>[userlink]<a href="{com_url}">{com_author}</a>[/userlink]</td>
	<td nowrap>{com_time}</td>
	<td style="min-width:200px">{com_part}</td>
	<td><a href="{php_self}?mod=editcomments&newsid={com_post}&comid={com_id}">{l_edit_comm}</a></td>
	<td><a href="{php_self}?mod=ipban&iplock={com_ip}" target="_blank" title="{l_block_ip}">{com_ip}</a></td>
	<td>
		<input type="checkbox" name="delcomid[]" value="{com_id}-{com_author}-{com_ip}-{com_post}" />
	</td>
</tr>
