<form name="login" method="post" action="{{ form_action }}">
	<input type="hidden" name="redirect" value="{{ redirect }}"/>
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['login.title'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" width="100%">
				{% if (flags.error) %}
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{{ lang['login.error'] }}
					</td>
				</tr>
				{% endif %}
				{% if (flags.banned) %}
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{{ lang['login.banned'] }}
					</td>
				</tr>
				{% endif %}
				{% if (flags.need_activate) %}
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{{ lang['login.need_activate'] }}
					</td>
				</tr>
				{% endif %}
				<tr>
					<td width="50%">{{ lang['login.name'] }}:</td>
					<td width="50%"><input type="text" name="username" class="input"/></td>
				</tr>
				<tr>
					<td width="50%">{{ lang['login.password'] }}:</td>
					<td width="50%"><input type="password" name="password" class="input"/></td>
				</tr>
			</table>
			<input type="submit" value="{{ lang['login.submit'] }}" class="btn">
			</p>
		</div>
	</div>
</form>
