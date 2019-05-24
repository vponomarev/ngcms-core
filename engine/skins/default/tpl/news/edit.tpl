<script type="text/javascript" src="{{ home }}/lib/ajax.js"></script>
<script type="text/javascript" src="{{ home }}/lib/libsuggest.js"></script>
<script language="javascript" type="text/javascript">

	//
	// Global variable: ID of current active input area
		{% if (flags.edit_split) %}var currentInputAreaID = 'ng_news_content_short';
		{% else %}var currentInputAreaID = 'ng_news_content';{% endif %}

	function ChangeOption(optn) {
		document.getElementById('maincontent').style.display = (optn == 'maincontent') ? "block" : "none";
		document.getElementById('additional').style.display = (optn == 'additional') ? "block" : "none";
		document.getElementById('attaches').style.display = (optn == 'attaches') ? "block" : "none";
		{% if (pluginIsActive('comments')) %}    document.getElementById('comments').style.display = (optn == 'comments') ? "block" : "none";
		document.getElementById('showEditNews').style.display = (optn == 'comments') ? "none" : "block";
		document.getElementById('rightBar').style.display = (optn == 'comments') ? "none" : "";{% endif %}
	}
	function preview() {
		var form = document.getElementById("postForm");
		if (form.ng_news_content{% if (flags.edit_split) %}_short{% endif %}.value == '' || form.title.value == '') {
			alert('{{ lang.editnews['msge_preview'] }}');
			return false;
		}

		form['mod'].value = "preview";
		form.target = "_blank";
		form.submit();

		form['mod'].value = "news";
		form.target = "_self";
		return true;
	}

	function changeActive(name) {
		if (name == 'full') {
			document.getElementById('container.content.full').className = 'contentActive';
			document.getElementById('container.content.short').className = 'contentInactive';
			currentInputAreaID = 'ng_news_content_full';
		} else {
			document.getElementById('container.content.short').className = 'contentActive';
			document.getElementById('container.content.full').className = 'contentInactive';
			currentInputAreaID = 'ng_news_content_short';
		}
	}
</script>

<!-- Hidden SUGGEST div -->
<div id="suggestWindow" class="suggestWindow">
	<table id="suggestBlock" cellspacing="0" cellpadding="0" width="100%"></table>
	<a href="#" align="right" id="suggestClose">close</a>
</div>


<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
	<input type="hidden" name="area" value=""/>
</form>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=news">{{ lang.editnews['news_title'] }}</a>
			&#8594; {{ lang.editnews['editnews_title'] }} "<a href="?mod=news&action=edit&id={{ id }}">{{ title }}</a>"
			({% if (approve == -1) %}{{ lang['state.draft'] }}{% elseif (approve == 0) %}{{ lang['state.unpublished'] }}{% else %}{{ lang['state.published'] }} &#8594;
			<small><a href="{{ link }}" target="_blank">{{ link }}</a></small>{% endif %})
		</td>
	</tr>
</table>

<!-- Main content form -->
<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="post" action="{{ php_self }}" target="_self">
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="mod" value="news"/>
	<input type="hidden" name="action" value="edit"/>
	<input type="hidden" name="subaction" value="submit"/>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td valign="top">
				<!-- Left edit column -->

				<table border="0" cellspacing="1" cellpadding="0" width="100%">
					<tr>
						<td class="contentNav" align="center">
							<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{{ lang.editnews['bar.maincontent'] }}" class="navbutton"/>
							<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{{ lang.editnews['bar.additional'] }}" class="navbutton"/>
							<input type="button" onmousedown="javascript:ChangeOption('attaches')" value="{{ lang.editnews['bar.attaches'] }} ({% if (attachCount>0) %}{{ attachCount }}{% else %}{{ lang['noa'] }}{% endif %})" class="navbutton"/>
							{% if (pluginIsActive('comments')) %}
								<input type="button" onmousedown="javascript:ChangeOption('comments')" value="{{ lang.editnews['bar.comments'] }} ({{ plugin.comments.count }})" class="navbutton" />{% endif %}
						</td>
					</tr>
					<tr>
						<td>

							<!-- MAIN CONTENT -->
							<div id="maincontent" style="display: block;">
								<table width="100%" cellspacing="1" cellpadding="0" border="0">
									<tr>
										<td width="10"><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/>
										</td>
										<td width="100"><span class="f15">{{ lang.editnews['title'] }}</span></td>
										<td>
											<input type="text" class="important" size="79" id="newsTitle" name="title" value="{{ title }}" tabindex="1"/>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan=3>{% if (not isBBCode) %}{{ quicktags }}
												<br/> {{ smilies }}<br/>{% else %}<br/>{% endif %}
											{% if (flags.edit_split) %}
												<div id="container.content.short" class="contentActive">
													<textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content_short"{% endif %} rows="10" tabindex="2">{{ content.short }}</textarea>
												</div>
												{% if (flags.extended_more) %}
													<table cellspacing="2" cellpadding="0" width="100%">
													<tr>
														<td nowrap>{{ lang.addnews['editor.divider'] }}: &nbsp;</td>
														<td style="width: 90%">
															<input tabindex="2" type="text" name="content_delimiter" style="width: 99%;" value=""/>
														</td>
													</tr></table>{% endif %}
												<div id="container.content.full" class="contentInactive">
													<textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content_full"{% endif %} rows="10" tabindex="2">{{ content.full }}</textarea>
												</div>
											{% else %}
												<div id="container.content" class="contentActive">
													<textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content"{% endif %} rows="10" tabindex="2">{{ content.short }}</textarea>
												</div>
											{% endif %}
										</td>
									</tr>
									<tr>
										<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
										<td>{{ lang.editnews['alt_name'] }}:</td>
										<td>
											<input type="text" name="alt_name" value="{{ alt_name }}" {% if flags['altname.disabled'] %}disabled="disabled" {% endif %}size="60" tabindex="3"/>
										</td>
									</tr>
									{% if (flags.meta) %}
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang.editnews['description'] }}:</td>
											<td><textarea name="description" cols="80">{{ description }}</textarea></td>
										</tr>
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang.editnews['keywords'] }}:</td>
											<td>
												<textarea name="keywords" id="newsKeywords" cols="80">{{ keywords }}</textarea>
											</td>
										</tr>
									{% endif %}
									{% if (pluginIsActive('xfields')) %}
										<!-- XFields -->
										{{ plugin.xfields[1] }}
										<!-- /XFields -->
									{% endif %}
								</table>
						</td>
					</tr>
				</table>
				</div>

				<!-- ADDITIONAL -->
				<div id="additional" style="display: none;">
					<table border="0" cellspacing="1" cellpadding="0" width="98%">
						{% if not flags['customdate.disabled'] %}
							<tr>
								<td class="contentHead">
									<img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/>{{ lang.editnews['date.manage'] }}
								</td>
							</tr>
							<tr>
								<td class="contentEntry1">
									<table cellspacing=1 cellpadding=1 style="font: 11px verdana, sans-serif;">
										<tr>
											<td>
												<input type="checkbox" name="setdate_custom" id="setdate_custom" value="1" class="check" onclick="document.getElementById('setdate_current').checked=false;"/>
											</td>
											<td><label for="setdate_custom">{{ lang.editnews['date.setdate'] }}</label>
											</td>
											<td><input type="text" id="cdate" name="cdate" value="{{ cdate }}"/></td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="setdate_current" id="setdate_current" value="1" class="check" onclick="document.getElementById('setdate_custom').checked=false;"/>
											</td>
											<td>
												<label for="setdate_current">{{ lang.editnews['date.setcurrent'] }}</label>
												&nbsp;</td>
											<td>&nbsp;</td>
									</table>
								</td>
							</tr>
						{% endif %}
						{% if (pluginIsActive('xfields')) %}
							<!-- XFields -->
							{{ plugin.xfields[0] }}
							<!-- /XFields -->
						{% endif %}
						{% if (pluginIsActive('nsched')) %}{{ plugin.nsched }}{% endif %}
						{% if (pluginIsActive('finance')) %}{{ plugin.finance }}{% endif %}
						{% if (pluginIsActive('tags')) %}{{ plugin.tags }}{% endif %}
						{% if (pluginIsActive('tracker')) %}{{ plugin.tracker }}{% endif %}
					</table>
				</div>
				<script language="javascript" type="text/javascript">
					$("#cdate").datetimepicker({
						currentText: "{{ cdate }}",
						dateFormat: "dd.mm.yy",
						timeFormat: 'HH:mm'
					});
				</script>
				<!-- ATTACHES -->
				<div id="attaches" style="display: none;">
					<br/>
					<span class="f15">{{ lang.editnews['attach.list'] }}</span>
					<table width="98%" cellspacing="1" cellpadding="2" border="0" id="attachFilelist">
						<thead>
						<tr class="contHead">
							<td>ID</td>
							<td width="80">{{ lang.editnews['attach.date'] }}</td>
							<td width="10">&nbsp;</td>
							<td>{{ lang.editnews['attach.filename'] }}</td>
							<td width="90">{{ lang.editnews['attach.size'] }}</td>
							<td width="40">DEL</td>
						</tr>
						</thead>
						<tbody>
						<!-- <tr><td colspan="5">No data</td></tr> -->
						{% for entry in attachEntries %}
							<tr>
								<td>{{ entry.id }}</td>
								<td>{{ entry.date }}</td>
								<td>
									<a style="cursor:pointer" onclick="ChangeOption('maincontent'); insertext('[attach#{{ entry.id }}]{{ entry.orig_name }}[/attach]','', currentInputAreaID)" title='{{ lang['tags.file'] }}'><img src="{{ skins_url }}/tags/attach.png" width="16" height="16" border="0"/></a>
								</td>
								<td><a href="{{ entry.url }}">{{ entry.orig_name }}</a></td>
								<td>{{ entry.filesize }}</td>
								<td><input type="checkbox" name="delfile_{{ entry.id }}" value="1"/></td>
							</tr>
						{% else %}
							<tr>
								<td colspan="6">{{ lang.editnews['attach.no_files_attached'] }}</td>
							</tr>
						{% endfor %}
						<!-- <tr><td>*</td><td>New file</td><td colspan="2"><input type="file"/></td><td><input type="button" size="40" value="-"/></td></tr> -->
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">
								<input type="button" class="button" value="{{ lang.editnews['attach.more_rows'] }}" style="width: 100%;" onclick="attachAddRow();"/>
							</td>
						</tr>
					</table>
				</div>

			</td>
			<td id="rightBar" width="300" valign="top">
				<!-- Right edit column -->
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td></td>
						<td><span class="f15">{{ lang['editor.comminfo'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">
								{{ lang['editor.author'] }}:
								<a style="font-family: Tahoma, Sans-serif;" href="{{ php_self }}?mod=users&amp;action=editForm&amp;id={{ authorid }}"><b>{{ author }}</b></a> {% if (pluginIsActive('uprofile')) %}
								<a href="{{ author_page }}" target="_blank" title="{{ lang.editnews['site.viewuser'] }}">
									<img src="{{ skins_url }}/images/open_new.png" alt="{{ lang.editnews['newpage'] }}"/>
								</a>{% endif %}<br/>
								{{ lang['editor.dcreate'] }}: <b>{{ createdate }}</b><br/>
								{{ lang['editor.dedit'] }}: <b>{{ editdate }}</b>
							</div>
						</td>
					</tr>
					<tr>
						<td width="20"></td>
						<td><span class="f15">{{ lang.editnews['category'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">{{ mastercat }} {% if (flags.mondatory_cat) %}&nbsp;
									<span style="font-size: 16px; color: red;"><b>*</b></span>{% endif %}</div>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><span class="f15">{{ lang['editor.extcat'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div style="overflow: auto; height: 150px;" class="list">{{ extcat }}</div>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><span class="f15">{{ lang['editor.configuration'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">
								<label><input type="checkbox" name="mainpage" value="1" {% if (flags.mainpage) %}checked="checked"{% endif %} class="check" id="mainpage" {% if (flags['mainpage.disabled']) %}disabled{% endif %} /> {{ lang.editnews['mainpage'] }}
								</label><br/>
								<label><input type="checkbox" name="pinned" value="1" {% if (flags.pinned) %}checked="checked"{% endif %} class="check" id="pinned" {% if (flags['pinned.disabled']) %}disabled{% endif %} /> {{ lang.editnews['add_pinned'] }}
								</label><br/>
								<label><input type="checkbox" name="catpinned" value="1" {% if (flags.catpinned) %}checked="checked"{% endif %} class="check" id="catpinned" {% if (flags['catpinned.disabled']) %}disabled{% endif %} /> {{ lang.editnews['add_catpinned'] }}
								</label><br/>
								<label><input type="checkbox" name="favorite" value="1" {% if (flags.favorite) %}checked="checked"{% endif %} class="check" id="favorite" {% if (flags['favorite.disabled']) %}disabled{% endif %} /> {{ lang.editnews['add_favorite'] }}
								</label><br/>
								<label><input type="checkbox" name="setViews" value="1" class="check" id="setViews" {% if (flags['setviews.disabled']) %}disabled{% endif %} /> {{ lang.editnews['set_views'] }}
									:</label>
								<input type="text" size="4" name="views" value="{{ views }}" {% if (flags['setviews.disabled']) %}disabled{% endif %}/><br/>
								<label><input name="flag_HTML" type="checkbox" class="check" id="flag_HTML" value="1" {% if (flags.html) %}checked="checked"{% endif %} {% if (flags['html.disabled']) %}disabled{% endif %} /> {{ lang.editnews['flag_html'] }}
								</label><br/>
								<label><input type="checkbox" name="flag_RAW" value="1" {% if (flags.raw) %}checked="checked"{% endif %} class="check" id="flag_RAW" {% if (flags['html.disabled']) %}disabled{% endif %} /> {{ lang.editnews['flag_raw'] }}
								</label> {% if (flags['raw.disabled']) %}[
									<font color=red>{{ lang.editnews['flags_lost'] }}</font>]{% endif %}
								{% if (pluginIsActive('comments')) %}
							<hr/>{{ lang['comments:mode.header'] }}:
								<select name="allow_com">
									<option value="0"{{ plugin.comments['acom:0'] }}>{{ lang['comments:mode.disallow'] }}
									<option value="1"{{ plugin.comments['acom:1'] }}>{{ lang['comments:mode.allow'] }}
									<option value="2"{{ plugin.comments['acom:2'] }}>{{ lang['comments:mode.default'] }}
								</select>
								{% endif %}<br/>
							</div>
						</td>
					</tr>

				</table>

			</td>
		</tr>
	</table>

	<br/>

	<div id="showEditNews" style="display: block;">
		<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
			{% if flags['params.lost'] %}
				<tr>
					<td colspan="3" class="contentEditErr">
						Обратите снимание - у вас недостаточно прав для полноценного редактирования новости.<br/>
						При сохранении будут произведены следующие изменения:<br/><br/>
						{% if flags['publish.lost'] %}
							<div class="errMessage">&#8594; Новость будет снята с публикации</div>{% endif %}
						{% if flags['html.lost'] %}
							<div class="errMessage">&#8594; В новости будет запрещено использование HTML тегов и
								автоформатирование
							</div>{% endif %}
						{% if flags['mainpage.lost'] %}
							<div class="errMessage">&#8594; Новость будет убрана с главной страницы</div>{% endif %}
						{% if flags['pinned.lost'] %}
							<div class="errMessage">&#8594; С новости будет снято прикрепление на главной
							</div>{% endif %}
						{% if flags['catpinned.lost'] %}
							<div class="errMessage">&#8594; С новости будет снято прикрепление в категории
							</div>{% endif %}
						{% if flags['favorite.lost'] %}
							<div class="errMessage">&#8594; Новость будет удалена из закладок администратора
							</div>{% endif %}
						{% if flags['multicat.lost'] %}
							<div class="errMessage">&#8594; Из новости будут удалены все дополнительные категории
							</div>{% endif %}
					</td>
				</tr>
			{% endif %}
			<tr>
				<td width="150" class="contentEditW" align="left" valign="top">
					<input type="button" value="{{ lang.editnews['preview'] }}" class="button" onClick="preview()"/>
				</td>
				<td class="contentEditW" align="center" valign="top">
					<input type="hidden" name="id" value="{{ id }}"/>
					{% if flags.editable %}
						{{ lang['news_status'] }}:
						<select size="1" disabled>
							<option>{% if (approve == -1) %}{{ lang['state.draft'] }}{% elseif (approve == 0) %}{{ lang['state.unpublished'] }}{% else %}{{ lang['state.published'] }}{% endif %}</option>
						</select> &#8594;
						<select size="1" name="approve" id="approve">
							{% if flags.can_draft %}
								<option value="-1" {% if (approve == -1) %}selected="selected"{% endif %}>{{ lang['state.draft'] }}</option>{% endif %}
							{% if flags.can_unpublish %}
								<option value="0" {% if (approve == 0) %}selected="selected"{% endif %}>{{ lang['state.unpublished'] }}</option>{% endif %}
							{% if flags.can_publish %}
								<option value="1" {% if (approve == 1) %}selected="selected"{% endif %}>{{ lang['state.published'] }}</option>{% endif %}
						</select>
						<input type="submit" value="{{ lang.editnews['do_editnews'] }}" accesskey="s" class="button"/>&nbsp;{% endif %}
				</td>
				{% if flags.deleteable %}
					<td class="contentEditW" align="right" valign="top" width="150">
						<input type="button" value="{{ lang.editnews['delete'] }}" onClick="confirmit('{{ php_self }}?mod=news&amp;action=manage&amp;subaction=mass_delete&amp;selected_news[]={{ id }}&amp;token={{ token }}', '{{ lang.editnews['sure_del'] }}')" class="button"/>
					</td>
				{% endif %}
			</tr>
		</table>
	</div>

	{% if (pluginIsActive('xfields')) %}
		<!-- XFields [GENERAL] -->
		{{ plugin.xfields.general }}
		<!-- /XFields [GENERAL] -->
	{% endif %}
</form>

<form method="post" name="commentsForm" id="commentsForm" action="{{ php_self }}?mod=news">
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="mod" value="news"/>
	<input type="hidden" name="action" value="edit"/>
	<input type="hidden" name="subaction" value="mass_com_delete"/>
	<input type="hidden" name="id" value="{{ id }}"/>
	<!-- COMMENTS -->
	<div id="comments" style="display: none;">
		<table border="0" cellspacing="0" cellpadding="0" width="98%">
			<tr align="center">
				<td class="contentHead">{{ lang.editnews['author'] }}</td>
				<td class="contentHead">{{ lang.editnews['date'] }}</td>
				<td class="contentHead">{{ lang.editnews['comment'] }}</td>
				<td class="contentHead">{{ lang.editnews['edit_comm'] }}</td>
				<td class="contentHead">{{ lang.editnews['block_ip'] }}</td>
				<td class="contentHead">
					<input type="checkbox" name="master_box" value="all" onclick="javascript:check_uncheck_all(commentsForm)" class="check"/>
				</td>
			</tr>
			{{ plugin.comments.list }}
			<tr>
				<td colspan="5">&nbsp;</td>
			</tr>
			<tr align="center">
				<td width="100%" colspan="6" class="contentEdit" align="center" valign="top">
					<input type="submit" value="{{ lang.editnews['comdelete'] }}" onClick="if (!confirm('{{ lang.editnews['sure_del_com'] }}')) {return false;}" class="button"/>
				</td>
			</tr>
		</table>
	</div>
</form>


<script language="javascript" type="text/javascript">
	<!--
	function attachAddRow() {
		var tbl = document.getElementById('attachFilelist');
		var lastRow = tbl.rows.length;
		var row = tbl.insertRow(lastRow - 1);

		// Add cells
		row.insertCell(-1).innerHTML = '*';
		row.insertCell(-1).innerHTML = '{{ lang.editnews['attach.new_file'] }}';
		row.insertCell(-1).innerHTML = '';

		// Add file input
		var el = document.createElement('input');
		el.setAttribute('type', 'file');
		el.setAttribute('name', 'userfile[' + (++attachAbsoluteRowID) + ']');
		el.setAttribute('size', '80');

		var xCell = row.insertCell(-1);
		xCell.colSpan = 2;
		xCell.appendChild(el);


		el = document.createElement('input');
		el.setAttribute('type', 'button');
		el.setAttribute('onclick', 'document.getElementById("attachFilelist").deleteRow(this.parentNode.parentNode.rowIndex);');
		el.setAttribute('value', '-');
		row.insertCell(-1).appendChild(el);
	}
	// Add first row
	var attachAbsoluteRowID = 0;
	attachAddRow();
	-->
</script>

{{ includ_bb }}