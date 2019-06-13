<div class="full">
	<header><h1>{{ lang.uprofile['profile_of'] }} {{ user.name }}</h1></header>
	<div class="telo">
		<table class="table">
			<tr>
				<td valign="top" style="text-align: center; padding: 5px;">
					<img src="{{ user.avatar }}" alt="" style="max-width: 100px; max-height: 100px;"/><br/>
					{% if (user.flags.hasPhoto) %}<a href="{{ user.photo }}" target="_blank">
						<img src="{{ user.photo_thumb }}" alt="" style="max-width: 100px; max-height: 100px;"/>
						</a>{% endif %}
					{% if not (global.user.status == 0) %}
						{% if pluginIsActive('pm') %}<a href="/plugin/pm/?action=write&name={{ user.name }}">написать
							ЛС</a>{% endif %}
						{% if (user.flags.isOwnProfile) %}<a href="/profile.html">редактировать</a>{% endif %}
					{% endif %}
				</td>
				<td width="100%" valign="top" style="padding: 5px;">
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td><b>{{ lang.uprofile['status'] }}:</b></td>
							<td>{{ user.status }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['regdate'] }}:</b></td>
							<td>{{ user.reg }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['last'] }}:</b></td>
							<td>{{ user.last }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['all_news'] }}:</b></td>
							<td>{{ user.news }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['all_comments'] }}:</b></td>
							<td>{{ user.com }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['site'] }}:</b></td>
							<td><strong>{{ user.site }}</strong></td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['icq'] }}:</b></td>
							<td valign="middle">{{ user.icq }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['from'] }}:</b></td>
							<td>{{ user.from }}</td>
						</tr>
						<tr>
							<td><b>{{ lang.uprofile['about'] }}:</b></td>
							<td>{{ user.info }}</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>