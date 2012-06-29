<div style="padding-top: 10px; padding-left: 10px; background-image: url(http://engine.ngcms.ru/templates/default/images/2z_41.gif); height: 26px;">
<a href="{{ listURL }}">Перейти к списку ваших новостей</a>
</div>
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



<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<img border="0" src="{{ tpl_url }}/images/2z_40.gif" width="7" height="36" /></td>
			<td style="background-image:url('{{ tpl_url }}/images/2z_41.gif');" width="100%">&nbsp;<img border="0" src="{{ tpl_url }}/images/bib.gif" />&nbsp;<b><font color="#FFFFFF">{{ lang.editnews['editnews_title'] }} &#8594; "{{ title }}"</font></b></td>
			<td>
			<img border="0" src="{{ tpl_url }}/images/2z_44.gif" width="7" height="36" /></td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td>

<form name="DATA_tmp_storage" action="" id="DATA_tmp_storage">
<input type=hidden name="area" value="" />
</form>
<form name="form" ENCTYPE="multipart/form-data" method="post" action="{{ php_self }}" id="postForm">
<input type="hidden" name="token" value="{{ token }}"/>
<input type="hidden" name="mod" value="news"/>
<input type="hidden" name="action" value="edit"/>
<input type="hidden" name="subaction" value="submit"/>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr>
<td valign="top" >
 <!-- Left edit column -->

<table border="0" cellspacing="1" cellpadding="0" width="98%">
<tr><td>

<!-- MAIN CONTENT -->
<div id="maincontent" style="display: block;">
<table width="100%" cellspacing="1" cellpadding="0" border="0">
	<tr>
		<td width="20"><img src="{{ skins_url }}/images/nav.png" /></td>
		<td>{{ lang.editnews['title'] }}</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td>
		<td><input type="text" tabindex="1" name="title" size="79" class="important" value="{{ title }}"></td>
	</tr>
	<tr>
		<td width="20"><img src="{{ skins_url }}/images/nav.png" /></td>
		<td>{{ lang.editnews['category'] }}</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td>
		<td><div class="nadd_list">{{ mastercat }}</div></td>
	</tr>

<tr><td colspan=3>&nbsp;</td></tr>
{% if flags['multicat.show'] %}
	<tr>
		<td><img src="{{ skins_url }}/images/nav.png" /></td>
		<td><span class="nadd_f15">{{ lang['editor.extcat'] }}</span></td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td>
		<td><div style="overflow: auto; height: 100px;" class="nadd_list">{{ extcat }}</div></td>
	</tr>
<tr><td colspan=3>&nbsp;</td></tr>
{% endif %}
  <tr>
   <td valign="top" colspan=3>{{ quicktags }}<br /> {{ smilies }}<br />
{% if (flags.edit_split) %}
    <div id="container.content.short" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" rows="10" tabindex="2">{{ content.short }}</textarea></div>
{% if (flags.extended_more) %}    <table cellspacing="2" cellpadding="0" width="100%"><tr><td nowrap>{{ lang.editnews['editor.divider'] }}: &nbsp;</td><td style="width: 90%"><input tabindex="2" type="text" name="content_delimiter" style="width: 99%;" value="{{ content.delimiter }}"/></td></tr></table>{% endif %}
    <div id="container.content.full" class="contentInactive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" rows="10" tabindex="2">{{ content.full }}</textarea></div>
{% else %}
    <div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" id="ng_news_content" rows="10" tabindex="2">{{ content.short }}</textarea></div>
{% endif %}
	</td>
  </tr>
	<tr>
		<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt="" /></td>
		<td>{{ lang.editnews['alt_name'] }}:</td>
	</tr>
	<tr>	<td>&nbsp;</td>
		<td><input type="text" name="alt_name" value="{{ alt_name }}" {% if flags['altname.disabled'] %}disabled="disabled" {% endif %}size="60" tabindex="3" /></td>
	</tr>

  <tr><td colspan=2>&nbsp;</td></tr>
  <tr>
   <td></td>
   <td><span class="nadd_f15">{{ lang['editor.configuration'] }}</span></td>
  </tr>
  <tr>
   <td></td>
   <td>
  <div class="nadd_list">
{% if not flags['mainpage.disabled'] %}  <label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['mainpage'] }}</label><br />{% endif %}
{% if not flags['pinned.disabled'] %}  <label><input type="checkbox" name="pinned" value="1" class="check" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_pinned'] }}</label><br />{% endif %}
{% if not flags['catpinned.disabled'] %}  <label><input type="checkbox" name="catpinned" value="1" class="check" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_catpinned'] }}</label><br />{% endif %}
{% if not flags['favorite.disabled'] %}  <label><input type="checkbox" name="favorite" value="1" class="check" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %}  /> {{ lang.editnews['add_favorite'] }}</label><br />{% endif %}

{% if not flags['html.disabled'] %}    <label><input name="flag_HTML" type="checkbox" class="check" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.editnews['flag_html'] }}</label><br />
  <label><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %}  /> {{ lang.editnews['flag_raw'] }}</label><br />{% endif %}
  </td>
 </tr>


 </table>
</td></tr>
</table>
</div>

</td>
</tr>
</table>

<br />

<div id="showEditNews" style="display: block;">
<table id="edit" width="100%" border="0" cellspacing="0" cellpadding="0">
{% if flags['params.lost'] %}
<tr><td colspan="3" class="contentEditErr">
Обратите снимание - у вас недостаточно прав для полноценного редактирования новости.<br/>
При сохранении будут произведены следующие изменения:<br/><br/>
{% if flags['publish.lost'] %}<div class="errMessage">&#8594; Новость будет снята с публикации</div>{% endif %}
{% if flags['html.lost'] %}<div class="errMessage">&#8594; В новости будет запрещено использование HTML тегов и автоформатирование</div>{% endif %}
{% if flags['mainpage.lost'] %}<div class="errMessage">&#8594; Новость будет убрана с главной страницы</div>{% endif %}
{% if flags['pinned.lost'] %}<div class="errMessage">&#8594; С новости будет снято прикрепление на главной</div>{% endif %}
{% if flags['catpinned.lost'] %}<div class="errMessage">&#8594; С новости будет снято прикрепление в категории</div>{% endif %}
{% if flags['favorite.lost'] %}<div class="errMessage">&#8594; Новость будет удалена из закладок администратора</div>{% endif %}
{% if flags['multicat.lost'] %}<div class="errMessage">&#8594; Из новости будут удалены все дополнительные категории</div>{% endif %}
</td></tr>
{% endif %}
<tr>
<td width="150" class="contentEditW" align="left" valign="top"><input type="button" value="{{ lang.editnews['preview'] }}" class="button" onClick="preview()" /> </td>
<td class="contentEditW" align="left" valign="top">
<input type="hidden" name="id" value="{{ id }}" />
{% if flags.editable %}
Статус новости:<br/>
<select size="1" disabled>
	<option>{% if (approve == -1) %}{{ lang.editnews['state.draft'] }}{% elseif (approve == 0) %}{{ lang.editnews['state.unpublished'] }}{% else %}{{ lang.editnews['state.published'] }}{% endif %}</option>
</select> &#8594;
<select size="1" name="approve" id="approve">
{% if flags.can_draft %}	<option value="-1" {% if (approve == -1) %}selected="selected"{% endif %}>{{ lang.editnews['state.draft'] }}</option>{% endif %}
{% if flags.can_unpublish %}		<option value="0" {% if (approve == 0) %}selected="selected"{% endif %}>{{ lang.editnews['state.unpublished'] }}</option>{% endif %}
{% if flags.can_publish %}		<option value="1" {% if (approve == 1) %}selected="selected"{% endif %}>{{ lang.editnews['state.published'] }}</option>{% endif %}
</select><br/><br/>
<input type="submit" value="{{ lang.editnews['do_editnews'] }}" accesskey="s" class="button" />&nbsp;{% endif %}
</td>
{% if flags.deleteable %}
<td class="contentEditW" align="right" valign="top" width="150">
<input type="button" value="{{ lang.editnews['delete'] }}" onClick="confirmit('{{ deleteURL }}', '{{ lang.editnews['sure_del'] }}')" class="button" />
</td>
{% endif %}
</tr>
</table>
</div>
</form>


	</td>
</tr>
</table>

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
