<form action="{{ php_self }}?mod=users" method="post">
	<input type="hidden" name="token" value="{{ token }}"/>
	<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="100%" style="padding-right:10px;" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="0" align="left">
					<tr>
						<td width=100% colspan="2" class="contentHead">
							<img src="{{ skins_url }}/images/nav.gif" hspace="8"/><a href="?mod=users">{{ lang['users_title'] }}</a>
							&#8594; {{ lang['profile_of'] }} "{{ name }}"
						</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['groupName'] }}</td>
						<td width=50% class=contentEntry2 valign=middle><select name="status">{{ status }}</select></td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['regdate'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>{{ regdate }}</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['last_login'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>{{ last }}</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['last_ip'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>{{ ip }}
							<a href="http://www.nic.ru/whois/?ip={{ ip }}" title="{{ lang['whois'] }}">{{ lang['whois'] }}</a>
						</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['all_news'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>{{ news }}</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['all_comments'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>{{ com }}</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['new_pass'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<input class="password" name="password" size="40" maxlength="16"/><br/>
							<small>{{ lang['pass_left'] }}</small>
						</td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['email'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<input class="email" type="text" name="mail" value="{{ mail }}" size=40/></td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['site'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<input type="text" name="site" value="{{ site }}" size=40/></td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['icq'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<input type="text" name="icq" value="{{ icq }}" size=40 maxlength=10/></td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['from'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<input type="text" name="where_from" value="{{ where_from }}" size=40 maxlength=60/></td>
					</tr>
					<tr>
						<td width=50% class=contentEntry1>{{ lang['about'] }}</td>
						<td width=50% class=contentEntry2 valign=middle>
							<textarea name="info" rows="7" cols="60">{{ info }}</textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td width=100% class="contentEdit" colspan="2">
				{% if (perm.modify) %}<input type="submit" value="{{ lang['save'] }}" class="button"/>
					<input type="button" value="{{ lang['cancel'] }}" onClick="history.back();" class="button"/>{% endif %}
				&nbsp;
				<input type="hidden" name="id" value="{{ id }}"/>
				<input type="hidden" name="action" value="edit"/>
			</td>
		</tr>
	</table>
</form>


{% if (pluginIsActive('xfields')) %}
	<table width="100%">
		<tr>
			<td colspan="8" width="100%" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8">Доп.
				поля в профиле пользователя (только просмотр)
			</td>
		</tr>
		<tr align="left">
			<td class="contentHead"><b>ID поля</b></td>
			<td class="contentHead"><b>Название поля</b></td>
			<td class="contentHead"><b>Тип поля</b></td>
			<td class="contentHead"><b>Блок</b></td>
			<!-- <td class="contentHead"><b>V</b></td> -->
			<td class="contentHead"><b>Значение</b></td>
		</tr>
		{% for xFN,xfV in p.xfields.fields %}
			<tr>
				<td>{{ xFN }}</td>
				<td>{{ xfV.title }}</td>
				<td>{{ xfV.data.type }}</td>
				<td>{{ xfV.data.area }}</td>
				<!-- 	<td>{% if (xfV.data.type == "select") and (xfV.data.storekeys) %}<span style="font-color: red;"><b>{{ xfV.secure_value }}{% else %}&nbsp;{% endif %}</td> -->
				<td>{{ xfV.input }}</td>

			</tr>
		{% endfor %}
	</table>
{% endif %}