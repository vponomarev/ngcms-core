<!-- Оставляем эти скрипты и формы так как ими могут пользоваться плагины -->
<script type="text/javascript" src="{{ home }}/lib/ajax.js"></script>
<script type="text/javascript" src="{{ home }}/lib/libsuggest.js"></script>

<!-- Preload JS/CSS for plugins -->
{{ preloadRAW }}
<!-- /end preload -->

<!-- Hidden SUGGEST div -->
<!-- <div id="suggestWindow" class="suggestWindow">
	<table id="suggestBlock" cellspacing="0" cellpadding="0" width="100%"></table>
	<a href="#" align="right" id="suggestClose">close</a>
</div> -->

<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
	<input type="hidden" name="area" value=""/>
</form>

<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['extras'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
		<li class="breadcrumb-item"><a href="{{ php_self }}?mod=news">{{ lang.editnews['news_title'] }}</a></li>
		<li class="breadcrumb-item active" aria-current="page">{{ title }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

{% if (flags['params.lost']) %}
<div class="alert alert-warning">
	<p>Обратите внимание – у вас недостаточно прав для полноценного редактирования новости.</p>
	<hr>
	<p>При сохранении будут произведены следующие изменения:</p>
	<ul>
		{% if flags['publish.lost'] %}<li>Новость будет снята с публикации</li>{% endif %}
		{% if flags['html.lost'] %}<li>В новости будет запрещено использование HTML тегов и автоформатирование</li>{% endif %}
		{% if flags['mainpage.lost'] %}<li>Новость будет убрана с главной страницы</li>{% endif %}
		{% if flags['pinned.lost'] %}<li>С новости будет снято прикрепление на главной</li>{% endif %}
		{% if flags['catpinned.lost'] %}<li>С новости будет снято прикрепление в категории</li>{% endif %}
		{% if flags['favorite.lost'] %}<li>Новость будет удалена из закладок администратора</li>{% endif %}
		{% if flags['multicat.lost'] %}<li>Из новости будут удалены все дополнительные категории</li>{% endif %}
	</ul>
</div>
{% endif %}

<!-- Main content form -->
<form id="postForm" name="form" enctype="multipart/form-data" method="post" action="{{ php_self }}" target="_self">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="mod" value="news" />
	<input type="hidden" name="action" value="edit" />
	<input type="hidden" name="subaction" value="submit" />
	<input type="hidden" name="id" value="{{ id }}" />

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card mb-4">
				<div class="card-header"><i class="fa fa-th-list mr-2"></i> {{ lang.editnews['bar.maincontent'] }}</div>
				<div class="card-body">
					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang.editnews['title'] }}</label>
						<div class="col-lg-9">
							{% if (link) %}
								<div class="input-group">
									<input id="newsTitle" type="text" name="title" value="{{ title }}" class="form-control" />
									<div class="input-group-append">
										<span class="input-group-text"><a href="{{ link }}" target="_blank"><i class="fa fa-external-link"></i></a></span>
									</div>
								</div>
							{% else %}
								<input id="newsTitle" type="text" name="title" value="{{ title }}" class="form-control" />
							{% endif %}
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang.editnews['alt_name'] }}</label>
						<div class="col-lg-9">
							<input type="text" name="alt_name" value="{{ alt_name }}" {% if flags['altname.disabled'] %}disabled="disabled" {% endif %} class="form-control" />
						</div>
					</div>

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">
							{{ lang.editnews['category'] }}
							{% if (flags.mondatory_cat) %}
								<span style="font-size: 16px; color: red;"><b>*</b></span>
							{% endif %}
						</label>
						<div class="col-lg-9">
							<div class="list">
								{{ mastercat }}
							</div>
						</div>
					</div>

					{% if (not flags.disableTagsSmilies) %}
						{{ quicktags }}
						<!-- SMILES -->
						<div id="modal-smiles" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="smiles-modal-label" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 id="smiles-modal-label" class="modal-title">Вставить смайл</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										{{ smilies }}
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					{% endif %}

					{% if (flags.edit_split) %}
						<div class="mb-3">
							<div id="container.content.short" class="contentActive">
								<textarea id="ng_news_content_short" name="ng_news_content_short" onclick="changeActive('short');" onfocus="changeActive('short');" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10">{{ content.short }}</textarea>
							</div>
						</div>

						{% if (flags.extended_more) %}
							<div class="form-row mb-3">
								<label class="col-lg-3 col-form-label">{{ lang.editnews['editor.divider'] }}</label>
								<div class="col-lg-9">
									<input type="text" name="content_delimiter" value="{{ content.delimiter }}" class="form-control" />
								</div>
							</div>
						{% endif %}

						<div class="mb-3">
							<div id="container.content.full" class="contentInactive">
								<textarea id="ng_news_content_full" name="ng_news_content_full" onclick="changeActive('full');" onfocus="changeActive('full');" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10">{{ content.full }}</textarea>
							</div>
						</div>
					{% else %}
						<div id="container.content" class="mb-3" class="contentActive">
							<textarea id="ng_news_content" name="ng_news_content" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10">{{ content.short }}</textarea>
						</div>
					{% endif %}

					{% if (flags.meta) %}
						<div class="form-row mb-3">
							<label class="col-lg-3 col-form-label">{{ lang.editnews['description'] }}</label>
							<div class="col-lg-9">
								<textarea name="description" cols="80" class="form-control">{{ description }}</textarea>
							</div>
						</div>

						<div class="form-row mb-3">
							<label class="col-lg-3 col-form-label">{{ lang.editnews['keywords'] }}</label>
							<div class="col-lg-9">
								<textarea name="keywords" cols="80" class="form-control">{{ keywords }}</textarea>
							</div>
						</div>
					{% endif %}
				</div>

				{% if (pluginIsActive('xfields')) %}
				<table class="table table-sm mb-0">
					<tbody>
					<!-- XFields -->
					{{ plugin.xfields[1] }}
					<!-- /XFields -->
					</tbody>
				</table>
				{% endif %}
			</div>

			<!-- ADDITIONAL -->
			<div id="additional" class="accordion mb-4">
				<div class="card">
					<div class="card-header" id="headingOne">
						<a href="#" class="btn-block collapsed" data-toggle="collapse" data-target="#collapseNewsAdditional" aria-expanded="false" aria-controls="collapseNewsAdditional">
							{{ lang.editnews['bar.additional'] }}
						</a>
					</div>

					<div id="collapseNewsAdditional" class="collapse" aria-labelledby="headingOne" data-parent="#additional">
						<table class="table table-sm mb-0">
							<tbody>
							{% if (pluginIsActive('xfields')) %}
								<!-- XFields -->
								{{ plugin.xfields[0] }}
								<!-- /XFields -->
							{% endif %}
							{% if (pluginIsActive('nsched')) %}{{ plugin.nsched }}{% endif %}
							{% if (pluginIsActive('finance')) %}{{ plugin.finance }}{% endif %}
							{% if (pluginIsActive('tags')) %}{{ plugin.tags }}{% endif %}
							{% if (pluginIsActive('tracker')) %}{{ plugin.tracker }}{% endif %}
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- ATTACHES -->
			<div id="attaches" class="accordion mb-4">
				<div class="card">
					<div id="headingTwo" class="card-header">
						<a href="#" class="btn-block collapsed" data-toggle="collapse" data-target="#collapseNewsAttaches" aria-expanded="false" aria-controls="collapseNewsAttaches">
							{{ lang.editnews['bar.attaches'] }} ({{ attachCount }})
						</a>
					</div>

					<div id="collapseNewsAttaches" class="collapse" aria-labelledby="headingTwo" data-parent="#attaches">
						<!-- <span class="f15">{{ lang.editnews['attach.list'] }}</span> -->
						<table id="attachFilelist" class="table table-sm mb-0">
							<thead>
								<tr>
									<th>ID</th>
									<th width="80">{{ lang.editnews['attach.date'] }}</th>
									<th nowrap></th>
									<th>{{ lang.editnews['attach.filename'] }}</th>
									<th width="90">{{ lang.editnews['attach.size'] }}</th>
									<th width="40">DEL</th>
								</tr>
							</thead>
							<tbody>
								{% for entry in attachEntries %}
									<tr>
										<td>{{ entry.id }}</td>
										<td>{{ entry.date }}</td>
										<td>
											<a href="#" onclick="insertext('[attach#{{ entry.id }}]{{ entry.orig_name }}[/attach]','', currentInputAreaID)" title='{{ lang['tags.file'] }}'>
												{{ lang['tags.file'] }}
											</a>
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
									<td colspan="6" class="text-right">
										<input type="button" value="{{ lang.editnews['attach.more_rows'] }}" class="btn btn-sm btn-outline-primary" onclick="attachAddRow();"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Right edit column -->
		<div id="rightBar" class="col col-lg-4">
			<div class="card mb-4">
				<div class="card-header">{{ lang['editor.comminfo'] }}</div>
				<div class="card-body">
					<ul class="list-unstyled mb-0">
						<li>
							{{ lang['editor.author'] }}: <a href="{{ php_self }}?mod=users&action=editForm&id={{ authorid }}"><b>{{ author }}</b></a>
							{% if (pluginIsActive('uprofile')) %}
								<a href="{{ author_page }}" target="_blank" title="{{ lang.editnews['site.viewuser'] }}">
									<i class="fa fa-external-link"></i>
								</a>
							{% endif %}
						</li>
						<li>{{ lang['editor.dcreate'] }}: <b>{{ createdate }}</b></li>
						<li>{{ lang['editor.dedit'] }}: <b>{{ editdate }}</b></li>
						<li>{{ lang['news_status'] }}:
							{% if (approve == -1) %}
								<b class="text-danger">{{ lang['state.draft'] }}</b>
							{% elseif (approve == 0) %}
								<b class="text-warning">{{ lang['state.unpublished'] }}</b>
							{% else %}
								<b class="text-success">{{ lang['state.published'] }}</b>
							{% endif %}
						</li>
					</ul>
				</div>
			</div>

			<div class="card mb-4">
				<div class="card-header">{{ lang['editor.extcat'] }}</div>
				<div class="card-body">
					<div style="overflow: auto; height: 150px;">{{ extcat }}</div>
				</div>
			</div>

			<div class="card mb-4">
				<div class="card-header">{{ lang['editor.configuration'] }}</div>
				<div class="card-body">
					<label class="col-form-label d-block">
						<input id="mainpage" type="checkbox" name="mainpage" value="1" {% if (flags.mainpage) %}checked {% endif %} {% if flags['mainpage.disabled'] %}disabled {% endif %} />
						{{ lang.editnews['mainpage'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="pinned" type="checkbox" name="pinned" value="1" {% if (flags.pinned) %}checked {% endif %} {% if flags['pinned.disabled'] %}disabled {% endif %} />
						{{ lang.editnews['add_pinned'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="catpinned" type="checkbox" name="catpinned" value="1" {% if (flags.catpinned) %}checked {% endif %} {% if flags['catpinned.disabled'] %}disabled {% endif %} />
						{{ lang.editnews['add_catpinned'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="favorite" type="checkbox" name="favorite" value="1" {% if (flags.favorite) %}checked {% endif %} {% if flags['favorite.disabled'] %}disabled {% endif %} />
						{{ lang.editnews['add_favorite'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="flag_HTML" type="checkbox" name="flag_HTML" value="1" {% if (flags['html']) %}checked {% endif %} {% if (flags['html.disabled']) %}disabled {% endif %} />
						{{ lang.editnews['flag_html'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="flag_RAW" type="checkbox" name="flag_RAW" value="1" {% if (flags['raw']) %}checked {% endif %} {% if (flags['html.disabled']) %}disabled {% endif %} />
						{{ lang.editnews['flag_raw'] }}
						<div class="{{ flags['raw.disabled'] ? 'alert alert-warning mb-0' : 'd-none' }}">{{ lang.editnews['flags_lost'] }}</div>
					</label>
				</div>
			</div>

			<div class="card mb-4">
				<div class="card-header">{{ lang.editnews['set_views'] }}</div>
				<div class="card-body">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<input type="checkbox" name="setViews" class="" value="1" {% if (flags['setviews.disabled']) %}disabled{% endif %}>
							</div>
						</div>
						<input type="number" name="views" value="{{ views }}" class="form-control" {% if (flags['setviews.disabled']) %}disabled{% endif %} autocomplete="off">
					</div>
				</div>
			</div>

			{% if not flags['customdate.disabled'] %}
				<div class="card mb-4">
					<div class="card-header">{{ lang.editnews['custom_date'] }}</div>
					<div class="card-body">
						<label class="col-form-label d-block">
							<input id="setdate_current" type="checkbox" name="setdate_current" value="1" onclick="document.getElementById('setdate_custom').checked=false;"/>
							{{ lang.editnews['date.setcurrent'] }}
						</label>

						<label class="col-form-label d-block">
							<input id="setdate_custom" type="checkbox" name="setdate_custom" value="1"  onclick="document.getElementById('setdate_current').checked=false;">
							{{ lang.editnews['date.setdate'] }}
						</label>

						<div class="form-group">
							<input id="cdate" type="text" name="cdate" value="{{ cdate }}" class="form-control" pattern="[0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}:[0-9]{2}" placeholder="{{ "now" | date('d.m.Y H:i') }}" autocomplete="off">
						</div>
					</div>
				</div>
			{% endif %}

			{% if (pluginIsActive('comments')) %}
				<div class="card mb-4">
					<div class="card-header">{{ lang['comments:mode.header'] }}</div>
					<div class="card-body">
						<select name="allow_com" class="custom-select">
							<option value="0" {{ plugin.comments['acom:0'] }}>{{ lang['comments:mode.disallow'] }}</option>
							<option value="1" {{ plugin.comments['acom:1'] }}>{{ lang['comments:mode.allow'] }}</option>
							<option value="2" {{ plugin.comments['acom:2'] }}>{{ lang['comments:mode.default'] }}</option>
						</select>
					</div>
				</div>
			{% endif %}
		</div>
	</div>

	<div class="row">
		<div class="col col-lg-8">
			<div class="row">
				<div class="col-md-6 mt-4">
					<button type="button" class="btn btn-outline-success" onclick="return preview();">
						<span class="d-xl-none"><i class="fa fa-eye"></i></span>
						<span class="d-none d-xl-block">{{ lang.addnews['preview'] }}</span>
					</button>

					{% if flags.deleteable %}
						<button type="button" class="btn btn-outline-danger" onclick="confirmit('{{ php_self }}?mod=news&action=manage&subaction=mass_delete&selected_news[]={{ id }}&token={{ token }}', '{{ lang.editnews['sure_del'] }}')">
							<span class="d-xl-none"><i class="fa fa-trash"></i></span>
							<span class="d-none d-xl-block">{{ lang.editnews['delete'] }}</span>
						</button>
					{% endif %}
				</div>

				<div class="col-md-6 mt-4">
					{% if flags.editable %}
						<div class="input-group">
							<select name="approve" class="custom-select">
								{% if flags['can_publish'] %}<option value="1" {{ approve ? 'selected' : '' }}>{{ lang.addnews['publish'] }}</option>{% endif %}
								{% if flags.can_unpublish %}<option value="0" {{ not(approve) ? 'selected' : '' }}>{{ lang.addnews['send_moderation'] }}</option>{% endif %}
								{% if flags.can_draft %}<option value="-1" {{ approve == -1 ? 'selected' : '' }}>{{ lang.addnews['save_draft'] }}</option>{% endif %}
							</select>
							<div class="input-group-append">
								<button type="submit" class="btn btn-outline-success">
									<span class="d-xl-none"><i class="fa fa-floppy-o"></i></span>
									<span class="d-none d-xl-block">{{ lang.editnews['do_editnews'] }}</span>
								</button>
							</div>
						</div>
					{% endif %}
				</div>
			</div>
		</div>
	</div>

	{% if (pluginIsActive('xfields')) %}
		<!-- XFields [GENERAL] -->
		{{ plugin.xfields.general }}
		<!-- /XFields [GENERAL] -->
	{% endif %}
</form>

{% if (pluginIsActive('comments')) %}
	<!-- COMMENTS -->
	<div id="additional" class="accordion mt-5">
		<div class="card">
			<div class="card-header" id="headingThree">
				<a href="#" class="btn-block collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
					{{ lang.editnews['bar.comments'] }} ({{ plugin.comments.count }})
				</a>
			</div>

			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#additional">
				<form id="commentsForm" name="commentsForm" action="{{ php_self }}?mod=news" method="post">
					<input type="hidden" name="token" value="{{ token }}" />
					<input type="hidden" name="mod" value="news" />
					<input type="hidden" name="action" value="edit" />
					<input type="hidden" name="subaction" value="mass_com_delete" />
					<input type="hidden" name="id" value="{{ id }}" />

					<!-- COMMENTS -->
					<div id="comments" class="table-responsive">
						<table class="table table-sm mb-0">
							<thead>
								<tr>
									<th>{{ lang.editnews['author'] }}</th>
									<th>{{ lang.editnews['date'] }}</th>
									<th>{{ lang.editnews['comment'] }}</th>
									<th>{{ lang.editnews['edit_comm'] }}</th>
									<th nowrap>{{ lang.editnews['block_ip'] }}</th>
									<th>
										<input type="checkbox" name="master_box" value="all" onclick="javascript:check_uncheck_all(commentsForm)" class="check"/>
									</th>
								</tr>
							</thead>
							<tbody>
								{{ plugin.comments.list }}
								<tr>
									<td colspan="6" class="text-right">
										<button type="submit" class="btn btn-outline-danger" onclick="if (!confirm('{{ lang.editnews['sure_del_com'] }}')) {return false;}">
											{{ lang.editnews['comdelete'] }}
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endif %}

<script type="text/javascript">
	// Global variable: ID of current active input area
	var currentInputAreaID = 'ng_news_content{{ flags.edit_split ? '_short' : '' }}';

	function preview() {
		var form = document.getElementById("postForm");

		if (form.querySelector('[name*=ng_news_content]').value == '' || form.title.value == '') {
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

<script type="text/javascript">
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
		el.setAttribute('class', 'btn btn-sm btn-outline-danger');
		row.insertCell(-1).appendChild(el);
	}

	// Add first row
	var attachAbsoluteRowID = 0;
	attachAddRow();
</script>
