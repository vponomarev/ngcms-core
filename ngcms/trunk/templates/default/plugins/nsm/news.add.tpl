<script type="text/javascript">
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
function approveMode(mode) {
	document.getElementById('approve').value = mode;
	return true;
}
</script>

<form id="postForm" name="form" ENCTYPE="multipart/form-data" method="POST" action="{{ currentURL }}">
<input type="hidden" name="token" value="{{ token }}"/>
<input type="hidden" name="mod" value="news"/>
<input type="hidden" name="approve" id="approve" value="0"/>
<div class="block-title">���������� �������:</div>
<table class="table table-striped table-bordered">
	<tr>
		<th colspan="2"><a role="button" href="{{ listURL }}">������� � ������ ����� ��������</a></th>
	</tr>
	<tr>
		<td>���������:</td>
		<td><input type="text" name="title" class="input" value="" /></td>
	</tr>
	<tr>
		<td>����. ���:</td>
		<td><input type="text" name="alt_name" class="input" value="" /></td>
	</tr>
	<tr>
		<td>���������:</td>
		<td>{{ mastercat }}</td>
	</tr>
	{% if flags['multicat.show'] %}
	<tr>
		<td>�������������� ���������:</td>
		<td>{{ extcat }}</td>
	</tr>
	{% endif %}
	{% if (flags.edit_split) %}
	<tr>
		<td colspan="2">
			<b>������� ����� ���������:</b> (�����������!)
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea onclick="changeActive('short');" onfocus="changeActive('short');" name="ng_news_content_short" id="ng_news_content_short" style="width:98%; height: 200px;" class="textarea"></textarea>
			</div>
		</td>
	</tr>
	{% if (flags.extended_more) %} 
	<tr>
		<td>�����������:</td>
		<td><input tabindex="2" type="text" name="content_delimiter" class="input" value=""/></td>
	</tr>
	{% endif %}
	<tr>
		<td colspan="2">
			<b>�������� ���������:</b> (�������������)
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea onclick="changeActive('full');" onfocus="changeActive('full');" name="ng_news_content_full" id="ng_news_content_full" style="width:98%; height: 200px;" class="textarea"></textarea>
			</div>
		</td>
	</tr>
	{% else %}
	<tr>
		<td colspan="2">
			<div>
				<div>{{ quicktags }}<br /> {{ smilies }}<br /><br /></div>
				<textarea name="ng_news_content" id="ng_news_content" style="width:98%; height: 400px;" class="textarea"></textarea>
			</div>
		</td>
	</tr>
	{% endif %}
	<tr>
		<td colspan="2">
			<div>
				{% if not flags['mainpage.disabled'] %}
					<label><input type="checkbox" name="mainpage" value="1" id="mainpage" {% if (flags.mainpage) %}checked="checked" {% endif %}{% if flags['mainpage.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['mainpage'] }}</label><br />
				{% endif %}
				{% if not flags['pinned.disabled'] %}
					<label><input type="checkbox" name="pinned" value="1" id="pinned" {% if (flags.pinned) %}checked="checked" {% endif %}{% if flags['pinned.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_pinned'] }}</label><br />
				{% endif %}
				{% if not flags['catpinned.disabled'] %}
					<label><input type="checkbox" name="catpinned" value="1" id="catpinned" {% if (flags.catpinned) %}checked="checked" {% endif %}{% if flags['catpinned.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_catpinned'] }}</label><br />
				{% endif %}
				{% if not flags['favorite.disabled'] %}
					<label><input type="checkbox" name="favorite" value="1" id="favorite" {% if (flags.favorite) %}checked="checked" {% endif %}{% if flags['favorite.disabled'] %}disabled {% endif %}  /> {{ lang.addnews['add_favorite'] }}</label><br />
				{% endif %}
				{% if not flags['html.disabled'] %}
					<label><input name="flag_HTML" type="checkbox" id="flag_HTML" value="1" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %} /> {{ lang.addnews['flag_html'] }}</label><br />
					<label><input type="checkbox" name="flag_RAW" value="1" id="flag_RAW" {% if (flags['html.disabled']) %}disabled {% endif %}{% if flags['html'] %}checked="checked"{% endif %}  /> {{ lang.addnews['flag_raw'] }}</label><br />
				{% endif %}
			</div>
		</td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	{% if flags['can_publish'] %}
		<input class="button" type="submit" onclick="return approveMode(1);" value="�������� ��������" />
	{% else %} &nbsp; {% endif %}
	<input class="button" type="submit" onclick="return approveMode(0);" value="��������� �� ���������" />
	<input class="button" type="submit" onclick="return approveMode(-1);" value="��������� ��������" />
	<input class="button" type="button" onclick="return preview();" value="��������" />
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