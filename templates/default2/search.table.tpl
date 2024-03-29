<form method="get" action="{{ form_url }}">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['search.site_search'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="30%" align="center">{{ lang['search.filter.author'] }}
						<input type="text" name="author" value="{{ author }}" class="input" style="width:100px"/></td>
					<td width="30%" align="center">{{ lang['search.filter.category'] }} {{ catlist }}</td>
					<td width="30%" align="center">{{ lang['search.filter.date'] }} <select name="postdate">
							<option value=""></option>
							{{ datelist }}</select></td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
						<br/><input type=text name="search" value="{{ search }}" class="input" style="width:300px" value="{{ search }}" onblur="if(this.value=='') this.value='{search}';" onfocus="if(this.value=='{search}') this.value='';"/>
						<input class="btn" type="submit" style="height: 40px; width:80px" value="{{ lang['search.submit'] }}"/>
					</td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
				</tr>
				<tr>
					<td align="center">
						{% if (flags.found) %}
							{{ lang['search.found'] }}: <b>{{ count }}</b>
						{% endif %}

						{% if (flags.notfound) %}
							{{ lang['search.notfound'] }}
						{% endif %}

						{% if (flags.error) %}
							<font color="red"><b>{{ lang['search.error'] }}</b></font>
						{% endif %}
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
			</p>
		</div>
	</div>
</form>

{{ entries }}
