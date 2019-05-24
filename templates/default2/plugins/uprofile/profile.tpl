<script type="text/javascript">
	function ChangeOption(selectedOption) {
		document.getElementById('maincontent').style.display = "none";
		document.getElementById('additional').style.display = "none";
		if (selectedOption == 'maincontent') {
			document.getElementById('maincontent').style.display = "";
		}
		if (selectedOption == 'additional') {
			document.getElementById('additional').style.display = "";
		}
	}
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
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang.uprofile['profile_of'] }} {{ user.name }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr align="center">
					<td width="100%" align="center" valign="top">
						<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{{ lang.uprofile['maincontent'] }}" class="btn"/>
						<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{{ lang.uprofile['additional'] }}" class="btn"/>
					</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table id="maincontent" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td width="30%">{{ lang.uprofile['email'] }}:</td>
					<td width="70%"><input class="input" type="text" name="editmail" value="{{ user.email }}"/></td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['site'] }}:</td>
					<td width="70%"><input class="input" type="text" name="editsite" value="{{ user.site }}"/></td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['icq'] }}:</td>
					<td width="70%"><input class="input" type="text" name="editicq" value="{{ user.icq }}"/></td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['from'] }}:</td>
					<td width="70%"><input class="input" type="text" name="editfrom" value="{{ user.from }}"/></td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['about'] }}:</td>
					<td width="70%">
						<textarea class="textarea" name="editabout" style="width:98%; height: 80px;">{{ user.info }}</textarea><br/>{{ info_sizelimit_text }}
					</td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['new_pass'] }}:</td>
					<td width="70%"><input class="input" type="password" name="editpassword" autocomplete="off"/><br/>
						<small>{l_uprofile:pass_left}</small>
					</td>
				</tr>
				<tr>
					<td width="30%">{{ lang.uprofile['oldpass'] }}:</td>
					<td width="70%">
						<input class="input" type="password" name="oldpass" value="" autocomplete="off"/><br/>
						<small>{{ lang.uprofile['oldpass#desc'] }}</small>
					</td>
				</tr>
				{% if pluginIsActive('xfields') %}{{ plugin_xfields_1 }}{% endif %}
			</table>
			<table id="additional" style="display: none;" border="0" width="100%" cellspacing="0" cellpadding="0">
				{% if (flags.photoAllowed) %}
					<tr>
						<td width="30%">{{ lang.uprofile['photo'] }}:</td>
						<td width="70%">
							<input type="file" name="newphoto" class="input"/><br/>{% if (user.flags.hasPhoto) %}
							<a href="{{ user.photo }}" target="_blank">
								<img src="{{ user.photo_thumb }}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
							</a><br/>
						<input type="checkbox" name="delphoto" id="delphoto"/>&nbsp;{{ lang.uprofile['delete'] }}{% endif %}
						</td>
					</tr>
				{% else %}
					<tr>
						<td width="30%">{{ lang.uprofile['photo'] }}:</td>
						<td width="70%">{{ lang.uprofile['photos_denied'] }}</td>
					</tr>
				{% endif %}
				{% if (flags.avatarAllowed) %}
					<tr>
						<td width="30%">{{ lang.uprofile['avatar'] }}:</td>
						<td width="70%">
							<input type="file" name="newavatar" class="input"/><br/>{% if (user.flags.hasAvatar) %}
							<img src="{{ user.avatar }}" style="margin: 5px; border: 0px; max-width: 80px; max-height: 80px;" alt=""/>
						<br/>
						<input type="checkbox" name="delavatar" id="delavatar"/>&nbsp;{{ lang.uprofile['delete'] }}{% endif %}
						</td>
					</tr>
				{% else %}
					<tr>
						<td width="30%">{{ lang.uprofile['avatar'] }}:</td>
						<td width="70%">{{ lang.uprofile['avatars_denied'] }}</td>
					</tr>
				{% endif %}
				{% if pluginIsActive('xfields') %}{{ plugin_xfields_0 }}{% endif %}
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input type="submit" value="{{ lang.uprofile['save'] }}" class="btn" onclick="return validate_form();"/>
						<input type="hidden" name="plugin_cmd" value="apply"/>
					</td>
				</tr>
			</table>
			</p>
		</div>
	</div>
</form>