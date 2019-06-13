<form name="register" action="{{ form_action }}" method="post">
	<input type="hidden" name="type" value="doregister"/>
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['registration'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" width="100%">
				{% for entry in entries %}
					<tr>
						<td width="50%">{{ entry.title }}<br/>
							<small>{{ entry.descr }}</small>
						</td>
						<td width="50%" class="fi">{{ entry.input }}</td>
					</tr>
				{% endfor %}
				{% if flags.hasCaptcha %}
					<tr>
						<td><img src="{{ admin_url }}/captcha.php"></td>
						<td><input class="input" type="text" name="vcode" style="width:80px"/></td>
					</tr>
				{% endif %}
			</table>
			<input type="submit" value="{{ lang['register'] }}" class="btn">
			</p>
		</div>
	</div>
</form>