{% extends localPath(0) ~ "site.body.tpl" %}
{% block content %}
	{% if (flags.link_news) %}
		<div class="msge"><b>Запрос по новости : <a href="{{ news.url }}">{{ news.title }}</a></b></div>{% endif %}
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
	<form method="post" action="{{ form_url }}" id="feedback_form" class="form-vertical" name="feedback_form">
		{{ hidden_fields }}
		<input type="hidden" name="id" value="{{ id }}"/>
		{% if (flags.error) %}
			<div class="msge">{{ errorText }}</div>{% endif %}
		{% for entry in entries %}
			{% if entry.type == 'text' %}
				<div class="input">
					<label>{{ entry.title }}</label><input type="text" name="{{ entry.name }}" value="{{ entry.value }}"/>
				</div>
			{% endif %}
			{% if entry.type == 'email' %}
				<div class="input">
					<label>{{ entry.title }}</label><input type="text" name="{{ entry.name }}" value="{{ entry.value }}"/>
				</div>
			{% endif %}
			{% if entry.type == 'textarea' %}
				<div>
					<label>{{ entry.title }}</label><textarea name="{{ entry.name }}" rows="9">{{ entry.value }}</textarea>
				</div>
			{% endif %}
			{% if entry.type == 'select' %}
				<div>
					<label>{{ entry.title }}</label>
					<select name="{{ entry.name }}">{{ entry.options.select }}</select>
				</div>
			{% endif %}
			{% if entry.type == 'date' %}
				<div>
					<label>{{ entry.title }}</label>
					<select name="{{ entry.name }}:day">{{ entry.options.day }}</select>
					<div class="clear1"></div>
					<select name="{{ entry.name }}:month">{{ entry.options.month }}</select>
					<div class="clear1"></div>
					<select name="{{ entry.name }}:year">{{ entry.options.year }}</select>
				</div>
			{% endif %}
		{% endfor %}
		{% if (flags.captcha) %}
			<div class="input">
				<label>{{ lang['feedback:sform.captcha'] }}</label>
				<img id="img_captcha" onclick="this.src='{{ captcha_url }}&rand='+Math.random();" src="{{ captcha_url }}&rand={{ captcha_rand }}" style="cursor: pointer;" alt="Security code" align="left"/>&nbsp;
				<input type="text" name="vcode" style="width:80px"/>
				<div class="clear10"></div>
			</div>
		{% endif %}
		{% if (flags.recipients) %}
			<label>{{ lang['feedback:sform.elist'] }}</label><select name="recipient">{{ recipients_list }}</select>
		{% endif %}
		<div class="clear1"></div>
		<input type="submit" {% if (flags.jcheck) %}onclick="return FBF_CHECK();"{% endif %} class="btn btn-primary btn-large" value="{{ lang['feedback:form.request'] }}"/>
	</form>
{% endblock %}