<form method="post" action="{{ php_self }}?mod=categories" enctype="multipart/form-data">
	<input type="hidden" name="token" value="{{ token }}"/>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%" colspan="2" class="contentHead">
				<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/><a href="?mod=categories">{{ lang['categories_title'] }}</a>
				&#8594; {{ lang['addnew'] }}</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1"><label for="cat_show">{{ lang['show_main'] }}</label></td>
			<td width="50%" class="contentEntry2">
				<input type="checkbox" id="cat_show" name="cat_show" value="1" class="check" checked="checked"/></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['parent'] }}</td>
			<td width="50%" class="contentEntry2">{{ parent }}</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['title'] }}</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="name"/></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['alt_name'] }}</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="alt"/></td>
		</tr>
		{% if (flags.haveMeta) %}
			<tr>
				<td width="50%" class="contentEntry1">{{ lang['cat_desc'] }}</td>
				<td width="50%" class="contentEntry2"><input type="text" size="40" name="description"/></td>
			</tr>
			<tr>
				<td width="50%" class="contentEntry1">{{ lang['cat_keys'] }}</td>
				<td width="50%" class="contentEntry2"><input type="text" size="40" name="keywords"/></td>
			</tr>
		{% endif %}
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['cat_number'] }}</td>
			<td width="50%" class="contentEntry2"><input type="text" size="4" name="number"/></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['show.link'] }}</td>
			<td width="50%" class="contentEntry2">
				<select name="show_link">
					<option value="0">{{ lang['link.always'] }}</option>
					<option value="1">{{ lang['link.ifnews'] }}</option>
					<option value="2">{{ lang['link.never'] }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['cat_tpl'] }}</td>
			<td width="50%" class="contentEntry2"><select name="tpl">{{ tpl_list }}</select></td>
		</tr>
		<tr>
			<td width="70%" class="contentEntry1">{{ lang['template_mode'] }}</td>
			<td width="30%" class="contentEntry2"><select name="template_mode">{{ template_mode }}</select></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['icon'] }}<br/>
				<small>{{ lang['icon#desc'] }}</small>
			</td>
			<td width="50%" class="contentEntry2"><input type="text" size="40" name="icon" maxlength="255"/></td>
		</tr>
		<tr>
			<td width="70%" class="contentEntry1">{{ lang['attached_icon'] }}<br/>
				<small>{{ lang['attached_icon#desc'] }}</small>
			</td>
			<td width="30%" class="contentEntry2">
				<input type="file" size="40" name="image"/>
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['alt_url'] }}</td>
			<td width="50%" class="contentEntry2"><input type=text size="40" name="alt_url"/></td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{{ lang['orderby'] }}</td>
			<td width="50%" class="contentEntry2">{{ orderlist }}</td>
		</tr>
		<tr>
			<td width="70%" class="contentEntry1" valign="top">{{ lang['category.info'] }}<br/>
				<small>{{ lang['category.info#desc'] }}</small>
			</td>
			<td width="30%" class="contentEntry2"><textarea id="info" name="info" cols="70" rows="5"></textarea></td>
		</tr>
		{{ extend }}
		<tr>
			<td width="100%" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="100%" colspan="2" class="contentEdit" align="center">
				<input type="submit" value="{{ lang['addnew'] }}" class="button"/>
				<input type="hidden" name="action" value="doadd"/>
			</td>
		</tr>
	</table>
</form>
