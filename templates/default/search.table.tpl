{% if (flags.found) %}
	<div class="alert alert-success">{{ lang['search.found'] }}: <b>{{ count }}</b></div>
{% endif %}

{% if (flags.notfound) %}
	<div class="alert alert-info">{{ lang['search.notfound'] }}</div>
{% endif %}

{% if (flags.error) %}
	<div class="alert alert-error"><b>{{ lang['search.error'] }}</b></div>
{% endif %}

<form action="{{ form_url }}" method="get">
	<div class="block-title">{{ lang['search.site_search'] }}</div>

	<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0 0 0;">

		<tr align="center">
			<td>{{ lang['search.filter.author'] }}
				<input type="text" name="author" class="input" value="{{ author }}" style="width:130px" />
			</td>
			<td>{{ lang['search.filter.category'] }}
				<div class="search_catz">{{ catlist }}</div>
			</td>
			<td>{{ lang['search.filter.date'] }}
				<select name="postdate">
					<option value=""></option>
					{{ datelist }}
				</select>
			</td>
		</tr>
	</table>

	<table border="0" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px 0;">
		<tr>
			<td align="center">
				<br/>
				<input type=text name="search" value="{{ search }}" style="width:400px" class="input" />
				<input type="submit" value="{{ lang['search.submit'] }}" class="button" />
			</td>
		</tr>
	</table>
</form>

<div class="articles full">
	{{ entries }}
</div>
