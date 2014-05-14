<script language="javascript" type="text/javascript">

//
// Global variable: ID of current active input area
{% if (flags.edit_split) %}var currentInputAreaID = 'ng_news_content_short';{% else %}var currentInputAreaID = 'ng_news_content';{% endif %}

function preview(){
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
	document.getElementById('container.content.full').className  = 'contentActive';
	document.getElementById('container.content.short').className = 'contentInactive';
	currentInputAreaID = 'ng_news_content_full';
 } else {
	document.getElementById('container.content.short').className = 'contentActive';
	document.getElementById('container.content.full').className  = 'contentInactive';
	currentInputAreaID = 'ng_news_content_short';
 }
}
</script>


<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form name="form" ENCTYPE="multipart/form-data" method="post" action="{{ php_self }}" id="postForm">
<input type="hidden" name="token" value="{{ token }}"/>
<input type="hidden" name="mod" value="news"/>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="subaction" value="submit"/>
<div class="block-title">{{ lang.editnews['editnews_title'] }}:</div>
<table class="table table-striped table-bordered">
	<tr>
		<th colspan="2"><a role="button" href="{{ listURL }}">Перейти к списку ваших новостей</a></th>
	</tr>
	<tr>
		<td>{{ lang.editnews['title'] }}:</td>
		<td><input type="text" name="title" class="input" value="{{ title }}" /></td>
	</tr>
	<tr>
		<td>{{ lang.editnews['alt_name'] }}:</td>
		<td><input type="text" name="alt_name" class="input" value="{{ alt_name }}" /></td>
	</tr>
	<tr>
		<td>{{ lang.editnews['category'] }}:</td>
		<td>{{ mastercat }}</td>
	</tr>
	{% if flags['multicat.show'] %}
	<tr>
		<td>{{ lang['editor.extcat'] }}:</td>
		<td>{{ extcat }}</td>
	</tr>
	{% endif %}
	{% if (flags.edit_split) %}
	<tr>
		<td colspan="2">
			<b>Вводная часть материала:</b> (Обязательно!)
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" style="width:98%; height: 200px;" class="textarea">{{ content.short }}</textarea>
			</div>
		</td>
	</tr>
	{% if (flags.extended_more) %} 
	<tr>
		<td>Разделитель:</td>
		<td><input tabindex="2" type="text" name="content_delimiter" class="input" value="{{ content.delimiter }}"/></td>
	</tr>
	{% endif %}
	<tr>
		<td colspan="2">
			<b>Материал полностью:</b> (Необязательно)
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" style="width:98%; height: 200px;" class="textarea">{{ content.full }}</textarea>
			</div>
		</td>
	</tr>
	{% else %}
	<tr>
		<td colspan="2">
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea name="ng_news_content" id="ng_news_content" style="width:98%; height: 400px;" class="textarea">{{ content.short }}</textarea>
			</div>
		</td>
	</tr>
	{% endif %}
	<tr>
		<td colspan="2">
			<div>
				{% if not flags['mainpage.disabled'] %}
					<label><input type="checkbox" name="mainpage" value="1" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['mainpage'] }}</label><br />
				{% endif %}
				{% if not flags['pinned.disabled'] %}
					<label><input type="checkbox" name="pinned" value="1" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_pinned'] }}</label><br />
				{% endif %}
				{% if not flags['catpinned.disabled'] %}
					<label><input type="checkbox" name="catpinned" value="1" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_catpinned'] }}</label><br />
				{% endif %}
				{% if not flags['favorite.disabled'] %}
					<label><input type="checkbox" name="favorite" value="1" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_favorite'] }}</label><br />
				{% endif %}
				{% if not flags['html.disabled'] %}
					<label><input name="flag_HTML" type="checkbox" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.editnews['flag_html'] }}</label><br />
					<label><input type="checkbox" name="flag_RAW" value="1" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %}  /> {{ lang.editnews['flag_raw'] }}</label><br />
				{% endif %}
			</div>
		</td>
	</tr>
	{% if flags['params.lost'] %}
	<tr>
		<td colspan="2">
			<div>
				Обратите снимание - у вас недостаточно прав для полноценного редактирования новости.<br/>
				При сохранении будут произведены следующие изменения:<br/><br/>
				{% if flags['publish.lost'] %}&#8594; Новость будет снята с публикации{% endif %}
				{% if flags['html.lost'] %}&#8594; В новости будет запрещено использование HTML тегов и автоформатирование{% endif %}
				{% if flags['mainpage.lost'] %}&#8594; Новость будет убрана с главной страницы{% endif %}
				{% if flags['pinned.lost'] %}&#8594; С новости будет снято прикрепление на главной{% endif %}
				{% if flags['catpinned.lost'] %}&#8594; С новости будет снято прикрепление в категории{% endif %}
				{% if flags['favorite.lost'] %}&#8594; Новость будет удалена из закладок администратора{% endif %}
				{% if flags['multicat.lost'] %}&#8594; Из новости будут удалены все дополнительные категории{% endif %}
			</div>
		</td>
	</tr>
	{% endif %}
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input type="hidden" name="id" value="{{ id }}" />
	{% if flags.editable %}
	<select size="1" disabled>
		<option>{% if (approve == -1) %}{{ lang.editnews['state.draft'] }}{% elseif (approve == 0) %}{{ lang.editnews['state.unpublished'] }}{% else %}{{ lang.editnews['state.published'] }}{% endif %}</option>
	</select> &#8594;
	<select size="1" name="approve" id="approve">
		{% if flags.can_draft %}<option value="-1" {% if (approve == -1) %}selected="selected"{% endif %}>{{ lang.editnews['state.draft'] }}</option>{% endif %}
		{% if flags.can_unpublish %}<option value="0" {% if (approve == 0) %}selected="selected"{% endif %}>{{ lang.editnews['state.unpublished'] }}</option>{% endif %}
		{% if flags.can_publish %}<option value="1" {% if (approve == 1) %}selected="selected"{% endif %}>{{ lang.editnews['state.published'] }}</option>{% endif %}
	</select>
	<input class="button" type="submit" onclick="return approveMode(-1);" value="Отправить" />{% endif %}
	<input class="button" type="button" onClick="preview()" value="Просмотр" />
	{% if flags.deleteable %}
		<input class="button" type="button" onClick="confirmit('{{ deleteURL }}', '{{ lang.editnews['sure_del'] }}')" value="Удалить" />
	{% endif %}
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
 		try { form[i+'['+j+']'].value = jev[i][j]; } catch (err) {;}
 	}
 } else {
  try {
   if ((form[i].type == 'text')||(form[i].type == 'textarea')||(form[i].type == 'select-one')) {
    form[i].value = jev[i];
   } else if (form[i].type == 'checkbox') {
    form[i].checked = (jev[i]?true:false);
   }
  } catch(err) {;}
 }
}
</script>
