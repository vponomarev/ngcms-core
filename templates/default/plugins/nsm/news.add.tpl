<div style="padding-top: 10px; padding-left: 10px; background-image: url(http://engine.ngcms.ru/templates/default/images/2z_41.gif); height: 26px;">
<a href="{{ listURL }}">Перейти к списку ваших новостей</a>
</div>

<script type="text/javascript">
// Global variable: ID of current active input area
{% if (flags.edit_split) %}var currentInputAreaID = 'ng_news_content_short';{% else %}var currentInputAreaID = 'ng_news_content';{% endif %}


function preview(){
 var form = document.getElementById("postForm");
 if (form.ng_news_content{% if (flags.edit_split) %}_short{% endif %}.value == '' || form.title.value == '') {
  alert('{l_msge_preview}');
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
function approveMode(mode) {
	document.getElementById('approve').value = mode;
	return true;
}
</script>

<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="POST" action="{{ currentURL }}">
<input type="hidden" name="token" value="{{ token }}"/>
<input type="hidden" name="mod" value="news"/>
<input type="hidden" name="approve" id="approve" value="0"/>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<img border="0" src="{tpl_url}/images/2z_40.gif" width="7" height="36" /></td>
			<td style="background-image:url('{tpl_url}/images/2z_41.gif');" width="100%">&nbsp;<img border="0" src="{tpl_url}/images/bib.gif" />&nbsp;<b><font color="#FFFFFF">Добавление новости</font></b></td>
			<td>
			<img border="0" src="{tpl_url}/images/2z_44.gif" width="7" height="36" /></td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-image:url('{tpl_url}/images/2z_54.gif');" width="7">&nbsp;</td>
			<td bgcolor="#FFFFFF">
 			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="20"><img src="{{ skins_url }}/images/nav.png" /></td>
					<td>Заголовок:</td>
				</tr>
				<tr><td width="20">&nbsp;</td><td><input type="text" tabindex="1" value="" name="title" size="79" class="important"></td></tr>
				<tr>
					<td width="20"><img src="{{ skins_url }}/images/nav.png" /></td>
					<td><span class="nadd_f15">{{ lang.addnews['category'] }}</span></td>
				</tr>
				<tr>
					<td></td><td><div class="nadd_list">{{ mastercat }}</div></td>
				</tr>
				<tr><td colspan=2>&nbsp;</td></tr>
{% if flags['multicat.show'] %}
				<tr>
					<td></td>
					<td><span class="nadd_f15">{{ lang['editor.extcat'] }}</span></td>
				</tr>
				<tr>
					<td></td>
					<td><div style="overflow: auto; height: 100px;" class="nadd_list">{{ extcat }}</div></td>
				</tr>
				<tr><td colspan=2>&nbsp;</td></tr>
{% endif %}
				<tr>
					<td valign="top" colspan=3>{{ quicktags }}<br /> {{ smilies }}<br />
{% if (flags.edit_split) %}
					<div id="container.content.short" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" rows="10" tabindex="2"></textarea></div>
{% if (flags.extended_more) %}    <table cellspacing="2" cellpadding="0" width="100%"><tr><td nowrap>{{ lang.addnews['editor.divider'] }}: &nbsp;</td><td style="width: 90%"><input tabindex="2" type="text" name="content_delimiter" style="width: 99%;" value=""/></td></tr></table>{% endif %}
					<div id="container.content.full" class="contentInactive"><textarea style="width: 99%; padding: 1px; margin: 1px;" onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" rows="10" tabindex="2"></textarea></div>
{% else %}
					<div id="container.content" class="contentActive"><textarea style="width: 99%; padding: 1px; margin: 1px;" name="ng_news_content" id="ng_news_content" rows="10" tabindex="2"></textarea></div>
{% endif %}
				</tr>
				<tr>
					<td><img src="{{ skins_url }}/images/nav.png" hspace="8" alt="" /></td>
					<td>{{ lang.addnews['alt_name'] }}:</td>
				</tr>
				<tr>	<td>&nbsp;</td>
					<td><input type="text" name="alt_name" value="" size="60" tabindex="3" /></td>
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
{% if not flags['mainpage.disabled'] %}  <label><input type="checkbox" name="mainpage" value="1" class="check" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['mainpage'] }}</label><br />{% endif %}
{% if not flags['pinned.disabled'] %}  <label><input type="checkbox" name="pinned" value="1" class="check" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_pinned'] }}</label><br />{% endif %}
{% if not flags['catpinned.disabled'] %}  <label><input type="checkbox" name="catpinned" value="1" class="check" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_catpinned'] }}</label><br />{% endif %}
{% if not flags['favorite.disabled'] %}  <label><input type="checkbox" name="favorite" value="1" class="check" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_favorite'] }}</label><br />{% endif %}

{% if not flags['html.disabled'] %}    <label><input name="flag_HTML" type="checkbox" class="check" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.addnews['flag_html'] }}</label><br />
  <label><input type="checkbox" name="flag_RAW" value="1" class="check" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %}  /> {{ lang.addnews['flag_raw'] }}</label><br />{% endif %}
  </td>
 </tr>

			</table>
			</td>
			<td style="background-image:url('{tpl_url}/images/2z_59.gif');" width="7">&nbsp;</td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="left"><td colspan="3">
<input type="button" value="{{ lang.addnews['preview'] }}" class="button" onclick="return preview();" /><br/><br/>
</tr>
<tr align="left">
<td width="30%" class="contentEditW" align="center" valign="top">
	<input type="submit" value="Сохранить черновик" class="button" onclick="return approveMode(-1);" /> &nbsp; &nbsp; &nbsp;
</td>
<td width="30%" class="contentEditW" align="center" valign="top">
	<input type="submit" value="Отправить на модерацию" class="button" onclick="return approveMode(0);" /> &nbsp; &nbsp; &nbsp;
</td>
<td width="40%" class="contentEditW" align="center" valign="top">
{% if flags['can_publish'] %}	<input type="submit" value="Опубликовать" class="button" onclick="return approveMode(1);" />{% else %} &nbsp; {% endif %}
</td>
</tr>
</table>
	</td>
</tr>
<tr>
	<td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
			<img border="0" src="{tpl_url}/images/2z_68.gif" width="7" height="4" /></td>
			<td style="background-image:url('{tpl_url}/images/2z_69.gif');" width="100%"></td>
			<td>
			<img border="0" src="{tpl_url}/images/2z_70.gif" width="7" height="4" /></td>
		</tr>
	</table>
	</td>
</tr>
</table>
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
