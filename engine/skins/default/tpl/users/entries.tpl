<tr align="left">
<td class="contentEntry1">{id}</td>
<td class="contentEntry1">[perm.details]<a href="{php_self}?mod=users&amp;action=editForm&amp;id={id}">[/perm.details]{name}[perm.details]</a>[/perm.details]</td>
<td class="contentEntry1">{regdate}</td>
<td class="contentEntry1">{last_login}</td>
<td class="contentEntry1">[link_news]<a href="{php_self}?mod=editnews&amp;action=list&amp;aid={id}">[/link_news]{news}[link_news]</a>[/link_news]</td>
[comments]<td width="10%" class="contentEntry1">{comments}</td>[/comments]
<td class="contentEntry1">{status}</td>
<td class="contentEntry1">{active}</td>
<td class="contentEntry1">[perm.modify][mass]<input name="selected_users[]" value="{id}" class="check" type="checkbox" />[/mass][/perm.modify]</td>
</tr>