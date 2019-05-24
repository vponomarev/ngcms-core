<script language="javascript" type="text/javascript">
	var currentInputAreaID = 'content';
</script>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=static">{{ lang['static_title'] }}</a>
			&#8594; {% if (flags.editMode) %}{{ lang['static_title_edit'] }} "{{ data.title }}"{% else %}{{ lang['static_title_add'] }}{% endif %}
		</td>
	</tr>
</table>
<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
	<input type=hidden name="area" value=""/>
</form>

<form name="form" id="postForm" method="post" action="{{ php_self }}?mod=static" target="_self">
	<input type="hidden" name="token" value="{{ token }}"/>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td valign="top">
				<!-- Left edit column -->

				<table border="0" cellspacing="1" cellpadding="0" width="98%">
					<tr>
						<td>

							<!-- MAIN CONTENT -->
							<div id="maincontent" style="display: block;">
								<table width="100%" border="0" cellspacing="1" cellpadding="0">
									<tr>
										<td width="10"><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/>
										</td>
										<td width="100"><span class="f15">{{ lang['title'] }}</span></td>
										<td>
											<input type="text" class="important" size="79" name="title" value="{{ data.title }}" tabindex="1"/>
										</td>
									</tr>
									<tr>
										<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
										<td>{{ lang['alt_name'] }}:</td>
										<td>
											<input type="text" name="alt_name" value="{{ data.alt_name }}" size="79" tabindex="3"/>
										</td>
									</tr>
									{% if (flags.isPublished) %}
										<tr>
										<td>&nbsp;</td>
										<td width="100">{{ lang['url_static_page'] }}:</td>
										<td>
											<input type="text" class="important" size="79" name="url" readonly="readonly" value="{{ data.url }}" tabindex="1"/>
											[ <a target="_blank" href="{{ data.url }}">{{ lang['open'] }}</a> ]
										</td>
										</tr>{% endif %}
									<tr>
										<td valign="top" colspan=3>{% if (not isBBCode) %}{{ quicktags }}
												<br/> {{ smilies }}<br/>{% else %}<br/>{% endif %}
											<textarea style="margin-left: 0px; margin-right: 0px; margin-top: 1px; width: 99%;" name="content" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="content"{% endif %} rows="16" tabindex="2">{{ data.content }}</textarea>
										</td>
									</tr>

									{% if (flags.meta) %}
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang['description'] }}:</td>
											<td>
												<input type="text" name="description" value="{{ data.description }}" size="60" tabindex="4"/>
											</td>
										</tr>
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang['keywords'] }}:</td>
											<td>
												<input type="text" name="keywords" value="{{ data.keywords }}" size="60" tabindex="5"/>
											</td>
										</tr>
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang['postdate'] }}</td>
											<td><input type="text" id="cdate" name="cdate" value="{{ data.cdate }}"/>
												<input name="set_postdate" type="checkbox" value="1"/> {{ lang['set_postdate'] }}
												<script language="javascript" type="text/javascript">$("#cdate").datetimepicker({
														currentText: "{{ data.cdate }}",
														dateFormat: "dd.mm.yy",
														timeFormat: 'HH:mm'
													});</script>
											</td>
										</tr>
									{% endif %}
								</table>
							</div>

						</td>
					</tr>
				</table>

			</td>
			<td id="rightBar" width="300" valign="top">
				<!-- Right edit column -->
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><img src="{{ skins_url }}/images/nav.png" hspace="0" alt=""/></td>
						<td><span class="f15">{{ lang['editor.configuration'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">
								<label><input type="checkbox" name="flag_published" value="1" {% if (not flags.canPublish) or (not flags.canUnpublish) %}disabled="disabled" {% endif %} {% if (data.flag_published) %}checked="checked" {% endif %}class="check"/> {{ lang['approve'] }}
								</label><br/>
								<label><input type="checkbox" name="flag_html" value="1" class="check" {% if (data.flag_html) %}checked="checked" {% endif %}/> {{ lang['flag_html'] }}
								</label><br/>
								<label><input type="checkbox" name="flag_raw" value="1" class="check" {% if (data.flag_raw) %}checked="checked" {% endif %}/> {{ lang['flag_raw'] }}
								</label>
							</div>

						</td>
					</tr>
					<!--  <tr><td colspan=2>&nbsp;</td></tr> -->
					<tr>
						<td><img src="{{ skins_url }}/images/nav.png" hspace="0" alt=""/></td>
						<td><span class="f15">{{ lang['editor.template'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">
								<select name="template" style="width: 200px;">{% for t in templateList %}
									<option value="{{ t }}" {% if (data.template == t) %}selected="selected"{% endif %}>{{ t }}{% endfor %}
								</select><br/><br/>
								<label><input type="checkbox" name="flag_template_main" value="1" {% if (data.flag_template_main) %}checked="checked" {% endif %} class="check"/> {{ lang['flag_main'] }}
								</label>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br/>

	<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td width="100%" class="contentEdit" align="center" valign="top">
				{% if (flags.editMode) %}<input type="hidden" name="id" value="{{ data.id }}" />
					<input type="hidden" name="action" value="edit"/>
					{% if (flags.canModify) %}
						<input type="submit" value="{{ lang['do_editnews'] }}" class="button"/>&nbsp;
						<input type="button" value="{{ lang['delete'] }}" onClick="confirmit('{{ php_self }}?mod=static&token={{ token }}&action=do_mass_delete&selected[]={{ data.id }}', '{{ lang['sure_del'] }}')" class="button" />{% endif %}{% else %}
					<input type="hidden" name="action" value="add"/>
					{% if (flags.canAdd) %}<input type="submit" value="{{ lang['addstatic'] }}" class="button" />
					{% endif %}
				{% endif %}

			</td>
		</tr>
	</table>
</form>

{{ includ_bb }}