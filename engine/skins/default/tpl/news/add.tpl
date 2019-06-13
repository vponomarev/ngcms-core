<script type="text/javascript" src="{{ home }}/lib/ajax.js"></script>
<script type="text/javascript" src="{{ home }}/lib/libsuggest.js"></script>
<script type="text/javascript">
	//
	// Global variable: ID of current active input area
		{% if (flags.edit_split) %}var currentInputAreaID = 'ng_news_content_short';
		{% else %}var currentInputAreaID = 'ng_news_content';{% endif %}
	function ChangeOption(optn) {
		document.getElementById('maincontent').style.display = (optn == 'maincontent') ? "block" : "none";
		document.getElementById('additional').style.display = (optn == 'additional') ? "block" : "none";
		document.getElementById('attaches').style.display = (optn == 'attaches') ? "block" : "none";
	}

	function approveMode(mode) {
		document.getElementById('approve').value = mode;
		return true;
	}

	function preview() {
		var form = document.getElementById("postForm");
		if (form.ng_news_content{% if (flags.edit_split) %}_short{% endif %}.value == '' || form.title.value == '') {
			alert('{{ lang.addnews['msge_preview'] }}');
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

<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
	<input type="hidden" name="area" value=""/>
</form>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"/><a href="?mod=news">{{ lang.addnews['news_title'] }}</a>
			&#8594; {{ lang.addnews['addnews_title'] }}
		</td>
	</tr>
</table>

<!-- Main content form -->
<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="post" action="{{ php_self }}" target="_self">
	<input type="hidden" name="token" value="{{ token }}"/>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td valign="top">
				<!-- Left edit column -->

				<table border="0" cellspacing="1" cellpadding="0" width="100%">
					<tr>
						<td class="contentNav" align="center">
							<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{{ lang.addnews['bar.maincontent'] }}" class="navbutton"/>
							<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{{ lang.addnews['bar.additional'] }}" class="navbutton"/>
							<input type="button" onmousedown="javascript:ChangeOption('attaches')" value="{{ lang.addnews['bar.attaches'] }}" class="navbutton"/>
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
										<td width="100"><span class="f15">{{ lang.addnews['title'] }}</span></td>
										<td>
											<input type="text" class="important" size="79" id="newsTitle" name="title" value="" tabindex="1"/>
										</td>
									</tr>
									<tr>
										<td valign="top" colspan=3>{% if (not isBBCode) %}{{ quicktags }}
												<br/> {{ smilies }}<br/>{% else %}<br/>{% endif %}
											{% if (flags.edit_split) %}
												<div id="container.content.short" class="contentActive">
													<textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content_short"{% endif %} rows="10" tabindex="2"></textarea>
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
													<textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content_full"{% endif %} rows="10" tabindex="2"></textarea>
												</div>
											{% else %}
												<div id="container.content" class="contentActive">
													<textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" {% if (isBBCode) %}class="{{ attributBB }}" {% else %}id="ng_news_content"{% endif %} rows="10" tabindex="2"></textarea>
												</div>
											{% endif %}
										</td>
									</tr>

									{% if not flags['altname.disabled'] %}
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang.addnews['alt_name'] }}:</td>
											<td><input type="text" name="alt_name" value="" size="60" tabindex="3"/>
											</td>
										</tr>
									{% endif %}
									{% if (flags.meta) %}
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang.addnews['description'] }}:</td>
											<td><textarea name="description" cols="80"></textarea></td>
										</tr>
										<tr>
											<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt=""/></td>
											<td>{{ lang.addnews['keywords'] }}:</td>
											<td><textarea id="newsKeywords" name="keywords" cols="80"></textarea></td>
										</tr>
									{% endif %}
									{% if (pluginIsActive('xfields')) %}
										<!-- XFields -->
										{{ plugin.xfields[1] }}
										<!-- /XFields -->
									{% endif %}
								</table>
							</div>
						</td>
					</tr>
				</table>


				<!-- ADDITIONAL -->
				<div id="additional" style="display: none;">
					<table border="0" cellspacing="1" cellpadding="0" width="100%">
						{% if not flags['customdate.disabled'] %}
							<tr>
								<td class="contentHead">
									<input type="checkbox" name="customdate" id="customdate" value="customdate" class="check"/>
									<label for="customdate">{{ lang.addnews['custom_date'] }}</label></td>
							</tr>
							<tr>
								<td class="contentEntry1">
									<input type="text" id="cdate" name="cdate" value="{{ cdate }}"/></td>
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
						currentText: "DD.MM.YYYY HH:MM",
						dateFormat: "dd.mm.yy",
						timeFormat: 'HH:mm'
					});
				</script>

				<!-- ATTACHES -->
				<div id="attaches" style="display: none;">
					<br/>
					<span class="f15">{{ lang.addnews['attach.list'] }}</span>
					<table width="100%" cellspacing="1" cellpadding="2" border="0" id="attachFilelist">
						<thead>
						<tr class="contHead">
							<td>#</td>
							<td width="80">{{ lang.editnews['attach.date'] }}</td>
							<td>{{ lang.editnews['attach.filename'] }}</td>
							<td width="90">{{ lang.editnews['attach.size'] }}</td>
							<td width="40">DEL</td>
						</tr>
						</thead>
						<tbody>
						<!-- <tr><td>*</td><td>New file</td><td colspan="2"><input type="file"/></td><td><input type="button" size="40" value="-"/></td></tr> -->
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">
								<input type="button" value="{{ lang.editnews['attach.more_rows'] }}" class="button" style="width: 100%;" onclick="attachAddRow();"/>
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
								<!-- <a style="font-family: Tahoma, Sans-serif;" href="{{ php_self }}?mod=users&amp;action=editForm&amp;id={{ authorid }}"><b>{{ author }}</b></a> {% if (pluginIsActive('uprofile')) %} <a href="{{ author_page }}" target="_blank" title="{{ lang.editnews['site.viewuser'] }}"><img src="{{ skins_url }}/images/open_new.png" alt="{{ lang.editnews['newpage'] }}"/></a>{% endif %} -->
								<br/>
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
					{% if flags['multicat.show'] %}
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
					{% endif %}
					<tr>
						<td></td>
						<td><span class="f15">{{ lang['editor.configuration'] }}</span></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div class="list">
								<label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %} /> {{ lang.addnews['mainpage'] }}
								</label><br/>
								<label><input type="checkbox" name="pinned" value="1" class="check" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_pinned'] }}
								</label><br/>
								<label><input type="checkbox" name="catpinned" value="1" class="check" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_catpinned'] }}
								</label><br/>
								<label><input type="checkbox" name="favorite" value="1" class="check" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_favorite'] }}
								</label><br/>

								<label><input name="flag_HTML" type="checkbox" class="check" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %} {% if (flags['html']) %}checked="checked"{% endif %}/> {{ lang.addnews['flag_html'] }}
								</label><br/>
								<label><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %} {% if (flags['raw']) %}checked="checked"{% endif %}/> {{ lang.addnews['flag_raw'] }}
								</label><br/>
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
	<input type="hidden" name="mod" value="news"/>
	<input type="hidden" name="action" value="add"/>
	<input type="hidden" name="subaction" value="submit"/>
	<input type="hidden" name="approve" id="approve" value="0"/>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center">
			<td width="30%" class="contentEditW" align="center" valign="top">
				<input type="button" value="{{ lang.addnews['preview'] }}" class="button" onclick="return preview();"/>
				&nbsp; &nbsp;
				<input type="submit" value="{{ lang.addnews['save_draft'] }}" class="button" onclick="return approveMode(-1);"/>
				&nbsp; &nbsp; &nbsp;
			</td>
			<td width="30%" class="contentEditW" align="center" valign="top">
				<input type="submit" value="{{ lang.addnews['send_moderation'] }}" class="button" onclick="return approveMode(0);"/>
				&nbsp; &nbsp; &nbsp;
			</td>
			<td width="40%" class="contentEditW" align="center" valign="top">
				{% if flags['can_publish'] %}
					<input type="submit" value="{{ lang.addnews['publish'] }}" class="button" onclick="return approveMode(1);" />{% else %} &nbsp; {% endif %}
			</td>
		</tr>
	</table>

	{% if (pluginIsActive('xfields')) %}
		<!-- XFields [GENERAL] -->
		{{ plugin.xfields.general }}
		<!-- /XFields [GENERAL] -->
	{% endif %}
</form>


<script language="javascript" type="text/javascript">
	// Restore variables if needed
	var jev = {{ JEV }};
	var form = document.getElementById('postForm');
	for (i in jev) {
		//try { alert(i+' ('+form[i].type+')'); } catch (err) {;}
		if (typeof(jev[i]) == 'object') {
			for (j in jev[i]) {
				//alert(i+'['+j+'] = '+ jev[i][j]);
				try {
					form[i + '[' + j + ']'].value = jev[i][j];
				} catch (err) {
					;
				}
			}
		} else {
			try {
				if ((form[i].type == 'text') || (form[i].type == 'textarea') || (form[i].type == 'select-one')) {
					form[i].value = jev[i];
				} else if (form[i].type == 'checkbox') {
					form[i].checked = (jev[i] ? true : false);
				}
			} catch (err) {
				;
			}
		}
	}
</script>

<script language="javascript" type="text/javascript">
	<!--
	function attachAddRow() {
		var tbl = document.getElementById('attachFilelist');
		var lastRow = tbl.rows.length;
		var row = tbl.insertRow(lastRow - 1);

		// Add cells
		row.insertCell(0).innerHTML = '*';
		row.insertCell(1).innerHTML = '{{ lang.editnews['attach.new_file'] }}';

		// Add file input
		var el = document.createElement('input');
		el.setAttribute('type', 'file');
		el.setAttribute('name', 'userfile[' + (++attachAbsoluteRowID) + ']');
		el.setAttribute('size', '80');

		var xCell = row.insertCell(2);
		xCell.colSpan = 2;
		xCell.appendChild(el);


		el = document.createElement('input');
		el.setAttribute('type', 'button');
		el.setAttribute('onclick', 'document.getElementById("attachFilelist").deleteRow(this.parentNode.parentNode.rowIndex);');
		el.setAttribute('value', '-');
		row.insertCell(3).appendChild(el);
	}
	// Add first row
	var attachAbsoluteRowID = 0;
	attachAddRow();
	-->
</script>

{{ includ_bb }}