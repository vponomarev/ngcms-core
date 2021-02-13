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
			<h1 class="m-0 text-dark">{{ lang.addnews['addnews_title'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item"><a href="{{ php_self }}?mod=news">{{ lang.addnews['news_title'] }}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang.addnews['addnews_title'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->


<!-- Main content form -->
<form id="postForm" name="form" enctype="multipart/form-data" method="post" action="{{ php_self }}" target="_self">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="mod" value="news" />
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="subaction" value="submit" />

	<div class="row">
		<!-- Left edit column -->
		<div class="col-lg-8">

			<!-- MAIN CONTENT -->
			<div id="maincontent" class="card mb-4">
				<div class="card-header"><i class="fa fa-th-list mr-2"></i> {{ lang.addnews['bar.maincontent'] }}</div>
				<div class="card-body">
					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">{{ lang.addnews['title'] }}</label>
						<div class="col-lg-9">
							<input id="newsTitle" type="text" name="title" value="" class="form-control" />
						</div>
					</div>

					{% if not flags['altname.disabled'] %}
						<div class="form-row mb-3">
							<label class="col-lg-3 col-form-label">{{ lang.addnews['alt_name'] }}</label>
							<div class="col-lg-9">
								<input type="text" name="alt_name" value="" class="form-control" />
							</div>
						</div>
					{% endif %}

					<div class="form-row mb-3">
						<label class="col-lg-3 col-form-label">
							{{ lang.addnews['category'] }}
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
							<div id="container.content.short">
								<textarea id="ng_news_content_short" name="ng_news_content_short" onclick="changeActive('short');" onfocus="changeActive('short');" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10"></textarea>
							</div>
						</div>

						{% if (flags.extended_more) %}
							<div class="form-row mb-3">
								<label class="col-lg-3 col-form-label">{{ lang.addnews['editor.divider'] }}</label>
								<div class="col-lg-9">
									<input type="text" name="content_delimiter" value="" class="form-control" />
								</div>
							</div>
						{% endif %}

						<div class="mb-3">
							<div id="container.content.full">
								<textarea id="ng_news_content_full" name="ng_news_content_full" onclick="changeActive('full');" onfocus="changeActive('full');" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10"></textarea>
							</div>
						</div>
					{% else %}
						<div id="container.content" class="mb-3">
							<textarea id="ng_news_content" name="ng_news_content" class="{{ editorClassName ? editorClassName : 'form-control' }}" rows="10"></textarea>
						</div>
					{% endif %}

					{% if (flags.meta) %}
						<div class="form-row mb-3">
							<label class="col-lg-3 col-form-label">{{ lang.addnews['description'] }}</label>
							<div class="col-lg-9">
								<textarea name="description" cols="80" class="form-control"></textarea>
							</div>
						</div>

						<div class="form-row mb-3">
							<label class="col-lg-3 col-form-label">{{ lang.addnews['keywords'] }}</label>
							<div class="col-lg-9">
								<textarea name="keywords" cols="80" class="form-control"></textarea>
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
							{{ lang.addnews['bar.additional'] }}
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
							{{ lang.addnews['bar.attaches'] }}
						</a>
					</div>

					<div id="collapseNewsAttaches" class="collapse" aria-labelledby="headingTwo" data-parent="#attaches">
						<!-- <span class="f15">{{ lang.addnews['attach.list'] }}</span> -->
						<table id="attachFilelist" class="table table-sm mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th width="80">{{ lang.editnews['attach.date'] }}</th>
									<th>{{ lang.editnews['attach.filename'] }}</th>
									<th width="90">{{ lang.editnews['attach.size'] }}</th>
									<th width="40">DEL</th>
								</tr>
							</thead>
							<tbody>
								<!-- <tr><td>*</td><td>New file</td><td colspan="2"><input type="file"/></td><td><input type="button" size="40" value="-"/></td></tr> -->
								<tr>
									<td colspan="5" class="text-right">
										<input type="button" value="{{ lang.editnews['attach.more_rows'] }}" class="btn btn-sm btn-outline-primary" onclick="attachAddRow();" />
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
			{% if flags['multicat.show'] %}
				<div class="card mb-4">
					<div class="card-header">{{ lang['editor.extcat'] }}</div>
					<div class="card-body">
						<div style="overflow: auto; height: 150px;">{{ extcat }}</div>
					</div>
				</div>
			{% endif %}

			<div class="card mb-4">
				<div class="card-header">{{ lang['editor.configuration'] }}</div>
				<div class="card-body">
					<label class="col-form-label d-block">
						<input id="mainpage" type="checkbox" name="mainpage" value="1" {% if (flags.mainpage) %}checked {% endif %} {% if flags['mainpage.disabled'] %}disabled {% endif %} />
						{{ lang.addnews['mainpage'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="pinned" type="checkbox" name="pinned" value="1" {% if (flags.pinned) %}checked {% endif %} {% if flags['pinned.disabled'] %}disabled {% endif %} />
						{{ lang.addnews['add_pinned'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="catpinned" type="checkbox" name="catpinned" value="1" {% if (flags.catpinned) %}checked {% endif %} {% if flags['catpinned.disabled'] %}disabled {% endif %} />
						{{ lang.addnews['add_catpinned'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="favorite" type="checkbox" name="favorite" value="1" {% if (flags.favorite) %}checked {% endif %} {% if flags['favorite.disabled'] %}disabled {% endif %} />
						{{ lang.addnews['add_favorite'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="flag_HTML" type="checkbox" name="flag_HTML" value="1" {% if (flags['html']) %}checked {% endif %} {% if (flags['html.disabled']) %}disabled {% endif %} />
						{{ lang.addnews['flag_html'] }}
					</label>

					<label class="col-form-label d-block">
						<input id="flag_RAW" type="checkbox" name="flag_RAW" value="1" {% if (flags['raw']) %}checked {% endif %} {% if (flags['html.disabled']) %}disabled {% endif %} />
						{{ lang.addnews['flag_raw'] }}
					</label>
				</div>
			</div>

			{% if not flags['customdate.disabled'] %}
				<div class="card mb-4">
					<div class="card-header">{{ lang.addnews['custom_date'] }}</div>
					<div class="card-body">
						<label class="col-form-label d-block">
							<input type="checkbox" name="customdate" value="1" class=""  onclick="document.getElementById('setdate_current').checked=false;">
							<!-- setdate_custom -->
							{{ lang.editnews['date.setdate'] }}
						</label>

						<div class="form-group">
							<input id="cdate" type="text" name="cdate" value="" class="form-control" pattern="[0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}:[0-9]{2}" placeholder="{{ "now" | date('d.m.Y H:i') }}" autocomplete="off">
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
				<div class="col mt-4">
					<button type="button" class="btn btn-outline-success" onclick="return preview();">
						<span class="d-xl-none"><i class="fa fa-eye"></i></span>
						<span class="d-none d-xl-block">{{ lang.addnews['preview'] }}</span>
					</button>
				</div>

				<div class="col mt-4">
					<div class="input-group">
						<select name="approve" class="custom-select">
							{% if flags['can_publish'] %}
								<option value="1">{{ lang.addnews['publish'] }}</option>
							{% endif %}
							<option value="0">{{ lang.addnews['send_moderation'] }}</option>
							<option value="-1">{{ lang.addnews['save_draft'] }}</option>
						</select>
						<div class="input-group-append">
							<button type="submit" class="btn btn-outline-success">
								<span class="d-xl-none"><i class="fa fa-floppy-o"></i></span>
								<span class="d-none d-xl-block">{{ lang.addnews['addnews'] }}</span>
							</button>
						</div>
					</div>
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

<script type="text/javascript">
	function attachAddRow() {
		var tbl = document.getElementById('attachFilelist');
		var lastRow = tbl.rows.length;
		var row = tbl.insertRow(lastRow - 1);

		// Add cells
		row.insertCell(0).innerHTML = '*';
		row.insertCell(1).innerHTML = '{{ lang.editnews['attach.new_file '] }}';

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
		el.setAttribute('class', 'btn btn-sm btn-outline-danger');
		row.insertCell(3).appendChild(el);
	}

	// Add first row
	var attachAbsoluteRowID = 0;
	attachAddRow();
</script>
