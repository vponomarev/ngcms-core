<script type="text/javascript" src="{{ scriptLibrary }}/ajax.js"></script>
<script type="text/javascript" src="{{ scriptLibrary }}/admin.js"></script>
<script type="text/javascript" src="{{ scriptLibrary }}/libsuggest.js"></script>
<!-- DEBUG WINDOW <div id="debugWin" style="overflow: auto; position: absolute; top: 160px; left: 230px; width: 400px; height: 400px; background: white; 4px double black; padding: 2px; margin: 2px;">DEBUG WINDOW</div> -->

<!-- Preload JS/CSS for plugins -->
{{ preloadRAW }}
<!-- /end preload -->

<form action="{{ php_self }}?mod=news" method="post" name="options_bar">
	<table width="1000" border="0" cellspacing="0" cellpadding="0" class="editfilter">
		<tr>
			<td valign="top" width="100%">
				&nbsp; Поиск: <input name="sl" type="text" class="bfsearch" size="60" value="{{ sl }}"/>
				<input type="submit" value="{{ lang.editnews['do_show'] }}"/>
			</td>
		</tr>
	</table>
</form>
<!-- Конец блока фильтрации -->

<br/>
<form action="{{ php_self }}?mod=news" method="post" name="editnews">
	<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td valign="top">
				<table width="250" cellspacing="0" cellpadding="0" border="0" style="margin-right: 2px;">
					<thead>
					<tr class="contHead">
						<td>Категории</td>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td {% if (cat_active < 1) %}style="background-color: #EEEEEE;"{% endif %}>
							<img alt="+" height="18" width="18" src="{{ skins_url }}/images/catmenu/plus.png"/>
							<a href="?mod=news">Все категории</a></td>
					</tr>
					{% for cat in catmenu %}
						<tr>
							<td {% if (cat.flags.selected) %}style="background-color: #EEEEEE;"{% endif %}>
								<div style="float: left; margin-right: 5px;">{{ cat.cutter }}</div>
								<div style="float: left;">
									<a href="?mod=news&category={{ cat.id }}">{{ cat.name }}</a>{% if (cat.posts>0) %} [ {{ cat.posts }}]{% endif %}
								</div>
							</td>
						</tr>
					{% endfor %}
					</tbody>
				</table>
			</td>
			<td valign="top">
				<!-- List of news start here -->
				<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
					<thead>
					<tr align="left" class="contHead">
						<td width="16">&nbsp;</td>
						<td>{{ lang.editnews['title'] }}</td>
						<td width="5%">&nbsp;</td>
						<td width="5%">
							<input class="check" type="checkbox" name="master_box" title="{{ lang.editnews['select_all'] }}" onclick="javascript:check_uncheck_all(editnews)"/>
						</td>
					</tr>
					</thead>
					<tbody>
					{% for entry in entries %}
						<tr align="left">
							<td width="16" class="contentEntry1" cellspacing=0 cellpadding=0 style="padding:0; margin:0;">{% if entry.flags.mainpage %}
									<img src="{{ skins_url }}/images/mainpage.png" border="0" width="16" height="16" title="Main"/> {% endif %}
							</td>
							<td class="contentEntry1">
								<a href="{{ php_self }}?mod=news&amp;action=edit&amp;id={{ entry.newsid }}">{{ entry.title }}</a><br/>
								<small>{% if entry.flags.status %}
										<a href="{{ entry.link }}">{{ entry.link }}</a>{% else %}нет ссылки{% endif %}
								</small>
							</td>
							<td class="contentEntry1">{% if entry.flags.status %}
									<img src="{{ skins_url }}/images/yes.png" alt="{{ lang.approved }}" />{% else %}
									<img src="{{ skins_url }}/images/no.png" alt="{{ lang.unapproved }}"/>{% endif %}
							</td>
							<td class="contentEntry1">
								<input name="selected_news[]" value="{{ entry.newsid }}" class="check" type="checkbox"/>
							</td>
						</tr>
					{% else %}
						<tr>
							<td colspan="6"><p>- {{ lang.editnews['not_found'] }} -</p></td>
						</tr>
					{% endfor %}
					</tbody>
					<tfoot>
					<tr>
						<td width="100%" colspan="8">&nbsp;</td>
					</tr>

					{% if flags.allow_modify %}
						<tr align="center">
							<td colspan="8" class="contentEdit" align="right" valign="top">
								<div style="text-align: left;">
									{{ lang.editnews['action'] }}:
									<select name="subaction" style="font: 12px Verdana, Courier, Arial; width: 230px;">
										<option value="">-- {{ lang.editnews['action'] }} --</option>
										<option value="mass_approve">{{ lang.editnews['approve'] }}</option>
										<option value="mass_forbidden">{{ lang.editnews['forbidden'] }}</option>
										<option value="" class="bg-light" disabled>===================</option>
										<option value="mass_mainpage">{{ lang.editnews['massmainpage'] }}</option>
										<option value="mass_unmainpage">{{ lang.editnews['massunmainpage'] }}</option>
										<option value="" class="bg-light" disabled>===================</option>
										<option value="mass_currdate">{{ lang.editnews['modify.mass.currdate'] }}</option>
										<option value="" class="bg-light" disabled>===================</option>
										{% if flags.comments %}
											<option value="do_mass_com_approve">{{ lang.editnews['com_approve'] }}</option>
											<option value="mass_com_forbidden">{{ lang.editnews['com_forbidden'] }}</option>
											<option value="" class="bg-light" disabled>===================</option>
										{% endif %}
										<option value="mass_delete">{{ lang.editnews['delete'] }}</option>
									</select>
									<input type="submit" value="{{ lang.editnews['submit'] }}" class="button"/>
									<input type="hidden" name="mod" value="news"/>
									<input type="hidden" name="action" value="manage"/>
									<br/>
								</div>
							</td>
						</tr>
						<tr>
							<td width="100%" colspan="8">&nbsp;</td>
						</tr>
					{% endif %}
					<tr>
						<td align="center" colspan="8" class="contentHead">{{ pagesss }}</td>
					</tr>
					</tfoot>
				</table>
			</td>
		</tr>
	</table>
</form>
