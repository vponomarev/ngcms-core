<script type="text/javascript">
	function validate_form() {
		var f = document.getElementById('profileForm');
		// ICQ
		var icq = f.editicq.value;
		if ((icq.length > 0) && (!icq.match(/^\d{4,10}$/))) {
			alert("{{ lang.uprofile['wrong_icq'] }}");
			return false;
		}
		// Email
		var email = f.editmail.value;
		if ((email.length > 0) && (!emailCheck(email))) {
			alert("{{ lang.uprofile['wrong_email'] }}");
			return false;
		}
		// About
		var about = f.editabout.value;
		if (({about_sizelimit} > 0) && (about.length > {about_sizelimit})) {
			alert("{{ info_sizelimit_text }}");
			return false;
		}
		return true;
	}
</script>
<form id="profileForm" method="post" action="{{ form_action }}" enctype="multipart/form-data">
	<input type="hidden" name="token" value="{{ token }}"/>
	<div class="full">
		<header><h1>{{ lang.uprofile['profile_of'] }} {{ user.name }}</h1></header>
		<div class="telo">
			<table class="table">
				<tr>
					<td>{{ lang.uprofile['email'] }}:</td>
					<td class="input"><input type="text" name="editmail" value="{{ user.email }}"/></td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['site'] }}:</td>
					<td class="input"><input type="text" name="editsite" value="{{ user.site }}"/></td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['icq'] }}:</td>
					<td class="input"><input type="text" name="editicq" value="{{ user.icq }}"/></td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['from'] }}:</td>
					<td class="input"><input type="text" name="editfrom" value="{{ user.from }}"/></td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['about'] }}: {{ info_sizelimit_text }}</td>
					<td><textarea name="editabout" style="width:98%; height: 80px;">{{ user.info }}</textarea></td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['new_pass'] }}:</td>
					<td class="input"><input type="password" name="editpassword" autocomplete="off"/><br/>
						<small>{l_uprofile:pass_left}</small>
					</td>
				</tr>
				<tr>
					<td>{{ lang.uprofile['oldpass'] }}:</td>
					<td class="input"><input type="password" name="oldpass" value="" autocomplete="off"/><br/>
						<small>{{ lang.uprofile['oldpass#desc'] }}</small>
					</td>
				</tr>
				{% if (flags.photoAllowed) %}
					<tr>
						<td>{{ lang.uprofile['photo'] }}:</td>
						<td class="input"><input type="file" name="newphoto"/><br/>{% if (user.flags.hasPhoto) %}
							<a href="{{ user.photo }}" target="_blank">
								<img src="{{ user.photo_thumb }}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
							</a><br/>
						<input type="checkbox" name="delphoto" id="delphoto"/>&nbsp;{{ lang.uprofile['delete'] }}{% endif %}
						</td>
					</tr>
				{% else %}
					<tr>
						<td>{{ lang.uprofile['photo'] }}:</td>
						<td class="input">{{ lang.uprofile['photos_denied'] }}</td>
					</tr>
				{% endif %}
				{% if (flags.avatarAllowed) %}
					<tr>
						<td>{{ lang.uprofile['avatar'] }}:</td>
						<td class="input"><input type="file" name="newavatar"/><br/>{% if (user.flags.hasAvatar) %}
							<img src="{{ user.avatar }}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
						<br/>
						<input type="checkbox" name="delavatar" id="delavatar"/>&nbsp;{{ lang.uprofile['delete'] }}{% endif %}
						</td>
					</tr>
				{% else %}
					<tr>
						<td>{{ lang.uprofile['avatar'] }}:</td>
						<td class="input">{{ lang.uprofile['avatars_denied'] }}</td>
					</tr>
				{% endif %}
				{% if pluginIsActive('xfields') %}{{ plugin_xfields_0 }}{% endif %}
				<tr align="center">
					<td valign="top">
						<input type="submit" value="{{ lang.uprofile['save'] }}" class="btn btn-primary btn-large" onclick="return validate_form();"/>
						<input type="hidden" name="plugin_cmd" value="apply"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>