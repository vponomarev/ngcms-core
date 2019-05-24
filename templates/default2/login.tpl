<form name="login" method="post" action="{form_action}">
	<input type="hidden" name="redirect" value="{redirect}"/>
	<div class="post">
		<div class="post-header">
			<div class="post-title">{l_login.title}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" width="100%">
				[error]
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{l_login.error}
					</td>
				</tr>
				[/error]
				[banned]
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{l_login.banned}
					</td>
				</tr>
				[/banned]
				[need.activate]
				<tr>
					<td colspan="2" align="center" style="background-color: red; color: white; font-weight: bold;">
						{l_login.need.activate}
					</td>
				</tr>
				[/need.activate]
				<tr>
					<td width="50%">{l_login.name}:</td>
					<td width="50%"><input type="text" name="username" class="input"/></td>
				</tr>
				<tr>
					<td width="50%">{l_login.password}:</td>
					<td width="50%"><input type="password" name="password" class="input"/></td>
				</tr>
			</table>
			<input type="submit" value="{l_login.submit}" class="btn">
			</p>
		</div>
	</div>
</form>