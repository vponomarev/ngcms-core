<div class="post">
	<div class="post-header">
		<div class="post-title">{{ lang.uprofile['profile_of'] }} {{ user.name }}</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>
		<table border="0" width="100%">
			<tr>
				<td valign="top" style="text-align: center; padding: 15px;">
					<img src="{{ user.avatar }}" alt="{{ lang.uprofile['avatar'] }}" style="max-width: 100px; max-height: 100px;"/><br/>
					{% if (user.flags.hasPhoto) %}
					<a href="{{ user.photo }}" alt="{{ lang.uprofile['photo'] }}" target="_blank">{% endif %}
						<img src="{{ user.photo_thumb }}" alt="{{ lang.uprofile['photo'] }}" style="max-width: 100px; max-height: 100px;"/>
						{% if (user.flags.hasPhoto) %}</a>{% endif %}
					{% if not (global.user.status == 0) %}
						{% if pluginIsActive('pm') %}<a href="/plugin/pm/?action=write&name={{ user.name }}">написать
							ЛС</a>{% endif %}
						{% if (user.flags.isOwnProfile) %}<a href="/profile.html">редактировать</a>{% endif %}
					{% endif %}
				</td>
				<td width="100%" valign="top" style="padding: 5px;">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="40%" style="padding: 5px;"><b>{{ lang.uprofile['status'] }}:</b></td>
							<td width="60%" style="padding: 5px;">{{ user.status }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['regdate'] }}:</b></td>
							<td style="padding: 5px;">{{ user.reg }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['last'] }}:</b></td>
							<td style="padding: 5px;">{{ user.last }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['all_news'] }}:</b></td>
							<td style="padding: 5px;">{{ user.news }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['all_comments'] }}:</b></td>
							<td style="padding: 5px;">{{ user.com }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['site'] }}:</b></td>
							<td style="padding: 5px;">{{ user.site }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['icq'] }}:</b></td>
							<td style="padding: 5px;">{{ user.icq }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['from'] }}:</b></td>
							<td style="padding: 5px;">{{ user.from }}</td>
						</tr>
						<tr>
							<td style="padding: 5px;"><b>{{ lang.uprofile['about'] }}:</b></td>
							<td style="padding: 5px;">{{ user.info }}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</p>
	</div>
</div>