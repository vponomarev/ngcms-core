<style>
	#modalmsgDialog {
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		display: none;
	}

	#modalmsgWindow {
		margin: 5px;
		padding: 5px;
		border: 1px solid #CCCCCC;
		background-color: #F0F0F0;
		width: 400px;
		position: absolute;
		left: 40%;
		top: 40%;
	}

	#modalmsgWindowText {
		background-color: #FFFFFF;
	}

	#modalmsgWindowButton {
		background-color: #FFFFFF;
		text-align: center;
		padding: 5px;
	}
</style>
<script>
	function showModal(text) {
		document.getElementById('modalmsgDialog').style.display = 'block';
		document.getElementById('modalmsgWindowText').innerHTML = text;
	}
	function _modal_close() {
		document.getElementById('modalmsgDialog').style.display = 'none';
	}
</script>
<div id="modalmsgDialog" onclick="_modal_close();"><span id="modalmsgWindow"><div id="modalmsgWindowText"></div><div id="modalmsgWindowButton"><input type="button" value="OK"/></div></span>
</div>

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
	<tr>
		<td width="50%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['server'] }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['os'] }}</td>
					<td width="50%" class="contentEntry2">{{ php_os }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['php_version'] }} / {{ lang['mysql_version'] }}</td>
					<td width="50%" class="contentEntry2">{{ php_version }} / {{ mysql_version }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['gd_version'] }}</td>
					<td width="50%" class="contentEntry2">{{ gd_version }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['pdo_support'] }}</td>
					<td width="50%" class="contentEntry2">{{ pdo_support }}</td>
				</tr>
			</table>
		</td>
		<td width="50%" style="padding-left:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>Next
						Generation CMS
					</td>
				</tr>
				<tr>
					<td width="45%" class="contentEntry1">{{ lang['current_version'] }}</td>
					<td width="55%" class="contentEntry2">
						<span style="font-weight: bold; color: #6cb7ef;">{{ currentVersion }}</span></td>
				</tr>
				<tr>
					<td width="45%" class="contentEntry1">{{ lang['last_version'] }}</td>
					<td width="55%" class="contentEntry2"><span id="syncLastVersion">loading..</span></td>
				</tr>
				<tr>
					<td width="45%" class="contentEntry1">{{ lang['git_version'] }}</td>
					<td width="55%" class="contentEntry2"><span id="syncSVNVersion">loading..</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td width="50%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="4" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['size'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group'] }}</td>
					<td class="contentEntry1" align="right">{{ lang['amount'] }}</td>
					<td class="contentEntry1" align="right">{{ lang['volume'] }}</td>
					<td class="contentEntry1" align="right"> &nbsp; {{ lang['permissions'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group_images'] }}</td>
					<td class="contentEntry1" align="right">{{ image_amount }}</td>
					<td class="contentEntry1" align="right">{{ image_size }}</td>
					<td class="contentEntry1" align="right"> &nbsp; {{ image_perm }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group_files'] }}</td>
					<td class="contentEntry1" align="right">{{ file_amount }}</td>
					<td class="contentEntry1" align="right">{{ file_size }}</td>
					<td class="contentEntry1" align="right"> &nbsp; {{ file_perm }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group_photos'] }}</td>
					<td class="contentEntry1" align="right">{{ photo_amount }}</td>
					<td class="contentEntry1" align="right">{{ photo_size }}</td>
					<td class="contentEntry1" align="right">&nbsp; {{ photo_perm }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group_avatars'] }}</td>
					<td class="contentEntry1" align="right">{{ avatar_amount }}</td>
					<td class="contentEntry1" align="right">{{ avatar_size }}</td>
					<td class="contentEntry1" align="right"> &nbsp; {{ avatar_perm }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['group_backup'] }}</td>
					<td class="contentEntry1" align="right">{{ backup_amount }}</td>
					<td class="contentEntry1" align="right">{{ backup_size }}</td>
					<td class="contentEntry1" align="right"> &nbsp; {{ backup_perm }}</td>
				</tr>
			</table>

			<script type="text/javascript" language="JavaScript">
				{{ versionNotify }}
			</script>

			<br/><br/>

			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['size'] }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['allowed_size'] }}</td>
					<td width="50%" class="contentEntry2">{{ allowed_size }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['mysql_size'] }}</td>
					<td width="50%" class="contentEntry2">{{ mysql_size }}</td>
				</tr>
			</table>
		</td>

		<td width="50%" style="padding-left:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['system'] }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_cats'] }}</td>
					<td width="30%" class="contentEntry2">{{ categories }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_news'] }}</td>
					<td width="30%" class="contentEntry2"><a href="?mod=news&status=1">{{ news_draft }}</a> /
						<a href="?mod=news&status=2">{{ news_unapp }}</a> / <a href="?mod=news&status=3">{{ news }}</a>
					</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_comments'] }}</td>
					<td width="30%" class="contentEntry2">{{ comments }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_users'] }}</td>
					<td width="30%" class="contentEntry2">{{ users }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_users_unact'] }}</td>
					<td width="30%" class="contentEntry2">{{ users_unact }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_images'] }}</td>
					<td width="30%" class="contentEntry2">{{ images }}</td>
				</tr>
				<tr>
					<td width="70%" class="contentEntry1">{{ lang['all_files'] }}</td>
					<td width="30%" class="contentEntry2">{{ files }}</td>
				</tr>
			</table>
			<!--
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8" alt="" />{{ lang['system'] }}</td>
</tr>
<tr>
</table>
-->
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>

	<tr>
		<td width="50%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['note'] }}</td>
				</tr>
				<tr>
					<td width="50%" colspan="2" class="contentEntry1">
						<form method="post" action="{{ php_self }}?mod=statistics">
							<input type="hidden" name="action" value="save"/>
							<textarea name="note" rows="6" cols="70" style="border: 1px solid #ccc; background-color: lightyellow; width: 100%; margin-bottom: 5px;" {% if (not admin_note) %}placeholder="{{ lang['no_notes'] }}"{% endif %}>{{ admin_note }}</textarea><br/>
							<input type="submit" class="button" value="{{ lang['save_note'] }}"/>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" style="padding-left:10px;" valign="top">
			{% if (flags.confError) %}
				<!-- Configuration errors -->
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="2" class="contentHead">
							<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/><font color="red">{{ lang['pconfig.error'] }}</font>
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%">
								<thead>
								<tr>
									<td>{{ lang['perror.parameter'] }}</td>
									<td>{{ lang['perror.shouldbe'] }}</td>
									<td>{{ lang['perror.set'] }}</td>
								</thead>
								<tr>
									<td>Register Globals</td>
									<td>Отключено</td>
									<td>{{ flags.register_globals }}</td>
								</tr>
								<tr>
									<td>Magic Quotes GPC</td>
									<td>Отключено</td>
									<td>{{ flags.magic_quotes_gpc }}</td>
								</tr>
								<tr>
									<td>Magic Quotes Runtime</td>
									<td>Отключено</td>
									<td>{{ flags.magic_quotes_runtime }}</td>
								</tr>
								<tr>
									<td>Magic Quotes Sybase</td>
									<td>Отключено</td>
									<td>{{ flags.magic_quotes_sybase }}</td>
								</tr>
							</table>
							<br/>
							&nbsp;<a style="cursor: pointer; color: red;" onclick="document.getElementById('perror_resolve').style.display='block';">{{ lang['perror.howto'] }}</a><br/>
							<div id="perror_resolve" style="display: none;">
								{{ lang['perror.descr'] }}
							</div>
						</td>
					</tr>
				</table>
			{% endif %}
		</td>
	</tr>
</table>