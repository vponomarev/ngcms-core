<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=options">{{ lang.options['options_title'] }}</a>
		</td>
	</tr>
</table>

<br/>

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
	<tr>
		<td width="50%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" class="contentNav" style="padding-left : 0;">
						<img src="{{ skins_url }}/images/nav_opt.gif" hspace="5" alt=""/>{{ lang.options['news'] }}</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 5px;">
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/static.gif" hspace="8" alt=""/>{% if (perm.static) %}
						<a href="{{ php_self }}?mod=static" title="{l_static}">{% endif %}{{ lang.options['static'] }}{% if (perm.static) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/categories.gif" hspace="8" alt=""/>{% if (perm.categories) %}
						<a href="{{ php_self }}?mod=categories" title="{{ lang.options['news.categories'] }}">{% endif %}{{ lang.options['news.categories'] }}{% if (perm.categories) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/add_news.png" width="16" height="16" hspace="8" alt=""/>{% if (perm.addnews) %}
						<a href="{{ php_self }}?mod=news&action=add" title="{{ lang.options['news.add'] }}">{% endif %}{{ lang.options['news.add'] }}{% if (perm.addnews) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/edit_news.png" width="16" height="16" hspace="8" alt=""/>{% if (perm.editnews) %}
						<a href="{{ php_self }}?mod=news" title="{{ lang.options['news.edit'] }}">{% endif %}{{ lang.options['news.edit'] }}{% if (perm.editnews) %}</a>{% endif %}
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" style="padding-left:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" class="contentNav" style="padding-left : 0;">
						<img src="{{ skins_url }}/images/nav_opt.gif" hspace="5" alt=""/>{{ lang.options['system'] }}
					</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 5px;">
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/configuration.gif" hspace="8" alt=""/>{% if (perm.configuration) %}
						<a href="{{ php_self }}?mod=configuration" title="{{ lang.options['configuration'] }}">{% endif %}{{ lang.options['configuration'] }}{% if (perm.configuration) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/dbo.gif" hspace="8" alt=""/>{% if (perm.dbo) %}
						<a href="{{ php_self }}?mod=dbo" title="{{ lang.options['dbo'] }}">{% endif %}{{ lang.options['dbo'] }}{% if (perm.dbo) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/statistics.gif" hspace="8" alt=""/><a href="{{ php_self }}?mod=statistics" title="{{ lang.options['statistics'] }}">{{ lang.options['statistics'] }}</a>
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/rewrite.gif" hspace="8" alt=""/>{% if (perm.rewrite) %}
						<a href="{{ php_self }}?mod=rewrite" title="{{ lang.options['rewrite'] }}">{% endif %}{{ lang.options['rewrite'] }}{% if (perm.rewrite) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/cron.png" hspace="8" width="16" height="16" alt=""/>{% if (perm.cron) %}
						<a href="{{ php_self }}?mod=cron" title="{{ lang.options['cron'] }}">{% endif %}{{ lang.options['cron'] }}{% if (perm.cron) %}</a>{% endif %}
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="50%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" class="contentNav" style="padding-left : 0;">
						<img src="{{ skins_url }}/images/nav_opt.gif" hspace="5" alt=""/>{{ lang.options['userman'] }}
					</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 5px;">
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/users.gif" hspace="8" alt=""/>{% if (perm.users) %}
						<a href="{{ php_self }}?mod=users" title="{{ lang.options['users'] }}">{% endif %}{{ lang.options['users'] }}{% if (perm.users) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/ipban.gif" hspace="8" alt=""/>{% if (perm.ipban) %}
						<a href="{{ php_self }}?mod=ipban" title="{{ lang.options['ipban'] }}">{% endif %}{{ lang.options['ipban'] }}{% if (perm.ipban) %}</a>{% endif %}
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/ipban.gif" hspace="8" alt=""/><a href="{{ php_self }}?mod=ugroup" title="{{ lang.options['ugroup'] }}">{{ lang.options['ugroup'] }}</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/uperm.png" hspace="8" alt=""/><a href="{{ php_self }}?mod=perm" title="{{ lang.options['uperm'] }}">{{ lang.options['uperm'] }}</a>
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" style="padding-left:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" class="contentNav" style="padding-left : 0;">
						<img src="{{ skins_url }}/images/nav_opt.gif" hspace="5" alt=""/>{{ lang.options['other'] }}
					</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 5px;">
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/extras.gif" hspace="8" alt=""/><a href="{{ php_self }}?mod=extras" title="{{ lang.options['extras'] }}">{{ lang.options['extras'] }}</a>
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/images.gif" hspace="8" alt=""/><a href="{{ php_self }}?mod=images" title="{{ lang.options['images'] }}">{{ lang.options['images'] }}</a>
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/files.gif" hspace="8" alt=""/><a href="{{ php_self }}?mod=files" title="{{ lang.options['files'] }}">{{ lang.options['files'] }}</a>
					</td>
				</tr>
				<tr>
					<td width="100%" class="contentEntry1">
						<img src="{{ skins_url }}/images/templates.gif" hspace="8" alt=""/>{% if (perm.templates) %}
						<a href="{{ php_self }}?mod=templates" title="{{ lang.options['templates'] }}">{% endif %}{{ lang.options['templates'] }}{% if (perm.templates) %}</a>{% endif %}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>