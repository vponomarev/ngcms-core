<script type="text/javascript">
	// Global variable: ID of current active input area
		{% if (flags.edit_split) %}var currentInputAreaID = 'ng_news_content_short';
		{% else %}var currentInputAreaID = 'ng_news_content';{% endif %}


	function preview() {
		var form = document.getElementById("postForm");
		if (form.ng_news_content{% if (flags.edit_split) %}_short{% endif %}.value == '' || form.title.value == '') {
			alert('{{ lang.nsm['err.preview'] }}');
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
	function approveMode(mode) {
		document.getElementById('approve').value = mode;
		return true;
	}
</script>

<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="POST" action="{{ currentURL }}">
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="mod" value="news"/>
	<input type="hidden" name="approve" id="approve" value="0"/>
	<div class="post">
		<div class="post-header">
			<div class="post-title">Добавление материала:</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" width="100%">
				<tr>
					<th><a role="button" href="{{ listURL }}">Перейти к списку ваших новостей</a></th>
				</tr>
			</table>
			<div style="height: 20px;"></div>
			<table border="0" width="100%">
				<tr>
					<td width="30%">Заголовок:</td>
					<td width="70%"><input type="text" name="title" class="input" value=""/></td>
				</tr>
				<tr>
					<td width="30%">Альт. имя:</td>
					<td width="70%"><input type="text" name="alt_name" class="input" value=""/></td>
				</tr>
				<tr>
					<td width="30%">Категория:</td>
					<td width="70%">{{ mastercat }}</td>
				</tr>
				{% if flags['multicat.show'] %}
					<tr>
						<td width="30%">Дополнительные категории:</td>
						<td width="70%">{{ extcat }}</td>
					</tr>
				{% endif %}
				{% if (flags.edit_split) %}
					<tr>
						<td colspan="2">
							<b>Вводная часть материала:</b> (Обязательно)
							<div>
								<div>{{ quicktags }}<br/> {{ smilies }}</div>
								<textarea onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" style="width:98%;" rows="15" class="textarea"></textarea>
							</div>
						</td>
					</tr>
					{% if (flags.extended_more) %}
						<tr>
							<td width="30%">Разделитель:</td>
							<td width="70%">
								<input tabindex="2" type="text" name="content_delimiter" style="width: 98%;" class="input" value=""/>
							</td>
						</tr>
					{% endif %}
					<tr>
						<td colspan="2">
							<b>Материал полностью:</b> (Необязательно)
							<div>
								<div>{{ quicktags }}<br/> {{ smilies }}</div>
								<textarea onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" style="width:98%;" rows="15" class="textarea"></textarea>
							</div>
						</td>
					</tr>
				{% else %}
					<tr>
						<td colspan="2">
							<div>
								<div>{{ quicktags }}<br/> {{ smilies }}</div>
								<textarea name="ng_news_content" id="ng_news_content" style="width:98%;" rows="15" class="f_textarea"></textarea>
							</div>
						</td>
					</tr>
				{% endif %}
				<tr>
					<td colspan="2">
						<div>
							{% if not flags['mainpage.disabled'] %}
								<label><input type="checkbox" name="mainpage" value="1" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %} /> {{ lang.addnews['mainpage'] }}
								</label><br/>
							{% endif %}
							{% if not flags['pinned.disabled'] %}
								<label><input type="checkbox" name="pinned" value="1" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_pinned'] }}
								</label><br/>
							{% endif %}
							{% if not flags['catpinned.disabled'] %}
								<label><input type="checkbox" name="catpinned" value="1" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_catpinned'] }}
								</label><br/>
							{% endif %}
							{% if not flags['favorite.disabled'] %}
								<label><input type="checkbox" name="favorite" value="1" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %} /> {{ lang.addnews['add_favorite'] }}
								</label><br/>
							{% endif %}
							{% if not flags['html.disabled'] %}
								<label><input name="flag_HTML" type="checkbox" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.addnews['flag_html'] }}
								</label><br/>
								<label><input type="checkbox" name="flag_RAW" value="1" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.addnews['flag_raw'] }}
								</label><br/>
							{% endif %}
						</div>
					</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						{% if flags['can_publish'] %}
							<button class="btn" type="submit" onclick="return approveMode(1);">
								<span>Добавить материал</span></button>{% else %} &nbsp; {% endif %}
						<button class="btn" type="submit" onclick="return approveMode(0);">
							<span>Отправить на модерацию</span></button>
						<button class="btn" type="submit" onclick="return approveMode(-1);">
							<span>Сохранить черновик</span></button>
						<button class="btn" type="button" onclick="return preview();"><span>Просмотр</span></button>
					</td>
				</tr>
			</table>
			</p>
		</div>
	</div>
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