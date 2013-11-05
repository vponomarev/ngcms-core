{% extends localPath(0) ~ "site.body.tpl" %}
{% block content %}
{% if (flags.link_news) %}
<div class="alert alert-info">
	<b>Запрос по новости : <a href="{{ news.url }}">{{ news.title }}</a></b>
</div>
{%endif %}
{{ description }}<br /><br />
{% if (flags.jcheck) %}
<script language="JavaScript">
var FBF_INIT = {{ FBF_DATA }};
function FBF_CHECK() {
 var frm = document.getElementById('feedback_form');
 if (frm == null) return true;

 var i;
 for (i in FBF_INIT) {
 	if (FBF_INIT[i][1]) {
 	 if (FBF_INIT[i][0] == 'date') {
		if ((frm[i+':day'].value == '1') && (frm[i+':month'].value == '1') && (frm[i+':year'].value == '1970')) {
 			alert('{l_feedback:form.err.notfilled} ('+FBF_INIT[i][2]+')!');
 			frm[i+':day'].focus();
 			return false;
		}
 	 } else if (frm[i].value == '') {
 		alert('{l_feedback:form.err.notfilled} ('+FBF_INIT[i][2]+')!');
 		frm[i].focus();
 		return false;
 	}
   }
 }
 return true;
}
</script>
{% endif %}
<form method="post" action="{{ form_url }}" id="feedback_form" name="feedback_form">
{{ hidden_fields }}
<input type="hidden" name="id" value="{{ id }}"/>
{% for entry in entries %}
	{% if entry.type == 'text' %}
		<div class="label label-table">
			<label>{{ entry.title }}</label>
			<input type="text" name="{{ entry.name }}" class="input" />
		</div>
	{% endif %}
	{% if entry.type == 'email' %}
		<div class="label label-table">
			<label>{{ entry.title }}</label>
			<input type="text" name="{{ entry.name }}" class="input" />
		</div>
	{% endif %}
	{% if entry.type == 'textarea' %}
		<div class="label label-table">
			<label>{{ entry.title }}</label>
			<textarea name="{{ entry.name }}" style="height: 60px" class="textarea" />{{ entry.value }}</textarea>
		</div>
	{% endif %}
	{% if entry.type == 'select' %}
		<div class="label label-table">
			<label>{{ entry.title }}</label>
			<select name="{{ entry.name }}">{{ entry.options.select }}</select>
		</div>
	{% endif %}
	{% if entry.type == 'date' %}
		<div class="label label-table">
			<label>{{ entry.title }}</label>
			<select name="{{ entry.name }}:day">{{ entry.options.day }}</select>.<select name="{{ entry.name }}:month">{{ entry.options.month }}</select>.<select name="{{ entry.name }}:year">{{ entry.options.year }}</select>
		</div>
	{% endif %}
{% endfor %}
{% if (flags.captcha) %}
	<div class="label label-table captcha pull-left">
		<label for="captcha">Введите код безопасности:</label>
		<input type="text" name="vcode" class="input" />
		<img id="img_captcha" onclick="this.src='{{ captcha_url }}&rand='+Math.random();" src="{{ captcha_url }}&rand={{ captcha_rand }}" style="cursor: pointer;" alt="captcha" />
		<div class="label-desc">Вам предстоит специальный код для подтверждения вашего действия.</div>
	</div>
{% endif %}
{% if (flags.recipients) %}
	<div class="label label-table">
		<label>{{ lang['feedback:sform.elist'] }}</label>
		<select name="recipient">{{ recipients_list }}</select>
	</div>
{% endif %}
<div class="clearfix"></div>
<div class="label">
	<input type="submit" {% if (flags.jcheck) %}onclick="return FBF_CHECK();" {% endif %} value="Отправить" class="button">
</div>
</form>
{% endblock %}