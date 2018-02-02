{% extends localPath(0) ~ "site.body.tpl" %}
{% block content %}
	{% if (flags.link_news) %}
		<b>Запрос по новости : <a href="{{ news.url }}">{{ news.title }}</a></b>
		<br/><br/>
	{% endif %}
	{{ description }}
	<br/><br/>
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
							if ((frm[i + ':day'].value == '1') && (frm[i + ':month'].value == '1') && (frm[i + ':year'].value == '1970')) {
								alert('{l_feedback:form.err.notfilled} (' + FBF_INIT[i][2] + ')!');
								frm[i + ':day'].focus();
								return false;
							}
						} else if (frm[i].value == '') {
							alert('{l_feedback:form.err.notfilled} (' + FBF_INIT[i][2] + ')!');
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
		<table border="0" width="100%">
			{% for entry in entries %}
				{% if entry.type == 'text' %}
					<tr>
						<td width="30%">{{ entry.title }}</td>
						<td width="70%"><input type="text" name="{{ entry.name }}" class="input"/></td>
					</tr>
				{% endif %}
				{% if entry.type == 'email' %}
					<tr>
						<td width="30%">{{ entry.title }}</td>
						<td width="70%"><input type="text" name="{{ entry.name }}" class="input"/></td>
					</tr>
				{% endif %}
				{% if entry.type == 'textarea' %}
					<tr>
						<td width="30%">{{ entry.title }}</td>
						<td width="70%">
							<textarea name="{{ entry.name }}" style="width: 380px; height: 160px" class="textarea"/>{{ entry.value }}</textarea>
						</td>
					</tr>
				{% endif %}
				{% if entry.type == 'select' %}
					<tr>
						<td width="30%">{{ entry.title }}</td>
						<td width="70%"><select name="{{ entry.name }}">{{ entry.options.select }}</select></td>
					</tr>
				{% endif %}
				{% if entry.type == 'date' %}
					<tr>
						<td width="30%">{{ entry.title }}</td>
						<td width="70%">
							<select name="{{ entry.name }}:day">{{ entry.options.day }}</select>.<select name="{{ entry.name }}:month">{{ entry.options.month }}</select>.<select name="{{ entry.name }}:year">{{ entry.options.year }}</select>
						</td>
					</tr>
				{% endif %}
			{% endfor %}
			{% if (flags.captcha) %}
				<tr>
					<td width="30%">
						<img id="img_captcha" onclick="this.src='{{ captcha_url }}&rand='+Math.random();" src="{{ captcha_url }}&rand={{ captcha_rand }}" alt="captcha"/>
					</td>
					<td width="70%"><input type="text" name="vcode" style="width:80px" class="input"/></td>
				</tr>
			{% endif %}
			{% if (flags.recipients) %}
				<tr>
					<td width="30%">{{ lang['feedback:sform.elist'] }}</td>
					<td width="70%"><select name="recipient">{{ recipients_list }}</select></td>
				</tr>
			{% endif %}
		</table>
		<div style="height: 10px;"></div>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr align="center">
				<td width="100%" valign="top">
					<input {% if (flags.jcheck) %}onclick="return FBF_CHECK();" {% endif %} type="submit" value="{{ lang['feedback:form.request'] }}" class="btn"/>
				</td>
			</tr>
		</table>
	</form>
{% endblock %}