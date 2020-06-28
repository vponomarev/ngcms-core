<script type="text/javascript" language="javascript">

	// Check DB connection
	function ngCheckDB() {
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.configuration.dbCheck',
			rndval: new Date().getTime(),
			params: json_encode(
				{
					'token': '{{ token }}',
					'dbtype': $("#db_dbtype").val(),
					'dbhost': $("#db_dbhost").val(),
					'dbname': $("#db_dbname").val(),
					'dbuser': $("#db_dbuser").val(),
					'dbpasswd': $("#db_dbpasswd").val(),
				}
			)
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				ngNotifyWindow('{{ lang['rpc_jsonError'] }} ' + data, '{{ lang['notifyWindowError'] }}');
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('{{ lang['rpc_httpError'] }}', '{{ lang['notifyWindowError'] }}');
		});


	}

	// Check MEMCached connection
	function ngCheckMemcached() {
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.configuration.memcachedCheck',
			rndval: new Date().getTime(),
			params: json_encode(
				{
					'token': '{{ token }}',
					'ip': $("#memcached_ip").val(),
					'port': $("#memcached_port").val(),
					'prefix': $("#memcached_prefix").val(),
				}
			)
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				ngNotifyWindow('{{ lang['rpc_jsonError'] }} ' + data, '{{ lang['notifyWindowError'] }}');
			}
			if (!resTX['status']) {
				ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('{{ lang['rpc_httpError'] }}', '{{ lang['notifyWindowError'] }}');
		});
	}

	// Send test e-mail message
	function ngCheckEmail() {
		ngShowLoading();
		$.post('/engine/rpc.php', {
			json: 1,
			methodName: 'admin.configuration.emailCheck',
			rndval: new Date().getTime(),
			params: json_encode(
				{
					'token': '{{ token }}',
					'mode': $("#mail_mode").val(),
					'from': {
						'name': $("#mail_fromname").val(),
						'email': $("#mail_frommail").val(),
					},
					'to': {
						'email': $("#mail_tomail").val(),
					},
					'smtp': {
						'host': $("#mail_smtp_host").val(),
						'port': $("#mail_smtp_port").val(),
						'auth': $("#mail_smtp_auth").val(),
						'login': $("#mail_smtp_login").val(),
						'pass': $("#mail_smtp_pass").val(),
						'secure': $("#mail_smtp_secure").val(),
					},
				}
			)
		}, function (data) {
			ngHideLoading();
			// Try to decode incoming data
			try {
				resTX = eval('(' + data + ')');
			} catch (err) {
				ngNotifyWindow('{{ lang['rpc_jsonError'] }} ' + data, '{{ lang['notifyWindowError'] }}');
			}
			if (!resTX['status']) {
				ngNotifyWindow('{{ lang['notifyWindowError'] }} [' + resTX['errorCode'] + ']: ' + resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			} else {
				ngNotifyWindow(resTX['errorText'], '{{ lang['notifyWindowInfo'] }}');
			}
		}, "text").error(function () {
			ngHideLoading();
			ngNotifyWindow('{{ lang['rpc_httpError'] }}', '{{ lang['notifyWindowError'] }}');
		});
	}

</script>
<!-- Navigation bar -->
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=configuration">{{ lang['configuration_title'] }}</a>
		</td>
	</tr>
</table>

<form method="post" action="{{ php_self }}">
	<input type="hidden" name="mod" value="configuration"/>
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type=hidden name="selectedOption" id="selectedOption"/>


	<div id="userTabs">
		<ul>
			<li><a href="#userTabs-db">{{ lang['db'] }}</a></li>
			<li><a href="#userTabs-security">{{ lang['security'] }}</a></li>
			<li><a href="#userTabs-system">{{ lang['syst'] }}</a></li>
			<li><a href="#userTabs-news">{{ lang['sn'] }}</a></li>
			<li><a href="#userTabs-users">{{ lang['users'] }}</a></li>
			<li><a href="#userTabs-imgfiles">{{ lang['files'] }}/{{ lang['img'] }}</a></li>
			<li><a href="#userTabs-auth">{{ lang['auth'] }}</a></li>
			<li><a href="#userTabs-cache">{{ lang['cache'] }}</a></li>
			<li><a href="#userTabs-multi">{{ lang['multi'] }}</a></li>
		</ul>
		<!-- ########################## DB TAB ########################## -->
		<div id="userTabs-db">
			<!-- TABLE DB//Connection -->
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['db_connect'] }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbtype'] }}<br/>
						<small>{{ lang['example'] }} pdo</small>
					</td>
					<td class="contentEntry2" valign="middle">
						{{ mkSelect({'name' : 'save_con[dbtype]', 'value' : config['dbtype'], 'id' : 'db_dbtype', 'values' : { 'mysqli' : lang['mysqli'], 'pdo' : lang['pdo'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbhost'] }}<br/>
						<small>{{ lang['example'] }} localhost</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name="save_con[dbhost]" value="{{ config['dbhost'] }}" id="db_dbhost" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbname'] }}<br/>
						<small>{{ lang['example'] }} ng</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[dbname]' value='{{ config['dbname'] }}' id="db_dbname" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbuser'] }}<br/>
						<small>{{ lang['example'] }} root</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[dbuser]' value='{{ config['dbuser'] }}' id="db_dbuser" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbpass'] }}<br/>
						<small>{{ lang['example'] }} password</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="password" type="password" name='save_con[dbpasswd]' value='{{ config['dbpasswd'] }}' id="db_dbpasswd" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['dbprefix'] }}<br/>
						<small>{{ lang['example'] }} ng</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[prefix]' value='{{ config['prefix'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">&nbsp;</td>
					<td width="50%" class="contentEntry2">
						<input type="button" value="{{ lang['btn_checkDB'] }}" onclick="ngCheckDB(); return false;"/>
					</td>
				</tr>
			</table>
			<!-- END: TABLE DB//Connection -->
			<!-- TABLE DB//Backup -->
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['db_backup'] }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['auto_backup'] }}<br/>
						<small>{{ lang['auto_backup_desc'] }}</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[auto_backup]', 'value' : config['auto_backup'] }) }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['auto_backup_time'] }}<br/>
						<small>{{ lang['auto_backup_time_desc'] }}</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input type="text" name='save_con[auto_backup_time]' value='{{ config['auto_backup_time'] }}' size="5" maxlength="5"/>
					</td>
				</tr>
			</table>
			<!-- END: TABLE DB//Backup -->
		</div>
		<!-- ########################## SECURITY TAB ########################## -->
		<div id="userTabs-security">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['logging'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['x_ng_headers'] }}<br/>
						<small>{{ lang['x_ng_headers#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[x_ng_headers]', 'value' : config['x_ng_headers'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['syslog'] }}<br/>
						<small>{{ lang['syslog_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[syslog]', 'value' : config['syslog'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['load'] }}<br/>
						<small>{{ lang['load_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[load_analytics]', 'value' : config['load_analytics'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['load_profiler'] }}<br/>
						<small>{{ lang['load_profiler_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name="save_con[load_profiler]" value="{{ config['load_profiler'] }}"/></td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['security'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['flood_time'] }}<br/>
						<small>{{ lang['flood_time_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[flood_time]' value='{{ config['flood_time'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_captcha'] }}<br/>
						<small>{{ lang['use_captcha_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_captcha]', 'value' : config['use_captcha'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['captcha_font'] }}<br/>
						<small>{{ lang['captcha_font_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[captcha_font]', 'value' : config['captcha_font'], 'values' : list['captcha_font'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_cookies'] }}<br/>
						<small>{{ lang['use_cookies_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_cookies]', 'value' : config['use_cookies'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_sessions'] }}<br/>
						<small>{{ lang['use_sessions_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_sessions]', 'value' : config['use_sessions'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['sql_error'] }}<br/>
						<small>{{ lang['sql_error_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[sql_error_show]', 'value' : config['sql_error_show'], 'values' : { 0 : lang['sql_error_0'], 1 : lang['sql_error_1'], 2 : lang['sql_error_2'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['multiext_files'] }}<br/>
						<small>{{ lang['multiext_files_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[allow_multiext]', 'value' : config['allow_multiext'] }) }}</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['debug_generate'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['debug'] }}<br/>
						<small>{{ lang['debug_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[debug]', 'value' : config['debug'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['debug_queries'] }}<br/>
						<small>{{ lang['debug_queries_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[debug_queries]', 'value' : config['debug_queries'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['debug_profiler'] }}<br/>
						<small>{{ lang['debug_profiler_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[debug_profiler]', 'value' : config['debug_profiler'] }) }}</td>
				</tr>
			</table>
		</div>
		<!-- ########################## SYSTEM TAB ########################## -->
		<div id="userTabs-system">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['syst'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['home_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="home" type="text" name='save_con[home_url]' value='{{ config['home_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['admin_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/engine</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="home" type="text" name='save_con[admin_url]' value='{{ config['admin_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['home_title'] }}<br/>
						<small>{{ lang['example'] }} NGcms</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[home_title]' value="{{ config['home_title']|escape }}" size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['admin_mail'] }}<br/>
						<small>{{ lang['example'] }} admin@server.com</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="email" type="text" name='save_con[admin_mail]' value='{{ config['admin_mail'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['lock'] }}<br/>
						<small>{{ lang['lock_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[lock]', 'value' : config['lock'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['lock_reason'] }}<br/>
						<small>{{ lang['lock_reason_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[lock_reason]' value='{{ config['lock_reason'] }}' size="40" maxlength="200"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['meta'] }}<br/>
						<small>{{ lang['meta_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[meta]', 'value' : config['meta'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['description'] }}<br/>
						<small>{{ lang['description_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name="save_con[description]" value="{{ config['description'] }}" size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['keywords'] }}<br/>
						<small>{{ lang['keywords_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name="save_con[keywords]" value="{{ config['keywords'] }}" size="40"/></td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['theme'] }}<br/>
						<small>{{ lang['theme_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[theme]', 'value' : config['theme'], 'values' : list['theme'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['lang'] }}<br/>
						<small>{{ lang['lang_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[default_lang]', 'value' : config['default_lang'], 'values' : list['default_lang'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_gzip'] }}<br/>
						<small>{{ lang['use_gzip_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_gzip]', 'value' : config['use_gzip'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['404_mode'] }}<br/>
						<small>{{ lang['404_mode_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[404_mode]', 'value' : config['404_mode'], 'values' : { 0 : lang['404.int'], 1 : lang['404.ext'], 2 : lang['404.http'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['libcompat'] }}<br/>
						<small>{{ lang['libcompat_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[libcompat]', 'value' : config['libcompat'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['url_external_nofollow'] }}<br/>
						<small>{{ lang['url_external_nofollow_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[url_external_nofollow]', 'value' : config['url_external_nofollow'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['url_external_target_blank'] }}<br/>
						<small>{{ lang['url_external_target_blank_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[url_external_target_blank]', 'value' : config['url_external_target_blank'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['timezone'] }}<br/>
						<small>{{ lang['timezone#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<select name="save_con[timezone]" id="timezone">
							{% for zone in list['timezoneList'] %}
								<option value="{{ zone }}" {% if (config['timezone'] == zone) %}selected="selected"{% endif %}>{{ zone }}</option>
							{% endfor %}
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['email_configuration'] }}
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['mailfrom_name'] }}<br/>
						<small>{{ lang['example'] }} Administrator</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom_name" type="text" id="mail_fromname" name='save_con[mailfrom_name]' value='{{ config['mailfrom_name'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['mailfrom'] }}<br/>
						<small>{{ lang['example'] }} mailbot@server.com</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom" type="text" id="mail_frommail" name='save_con[mailfrom]' value='{{ config['mailfrom'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['mail_mode'] }}:<br/>
						<small>{{ lang['mail_mode#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[mail_mode]', 'id' : 'mail_mode', 'value' : config['mail_mode'], 'values' : { 'mail' : 'mail', 'sendmail' : 'sendmail', 'smtp' : 'smtp' } }) }}</td>
				</tr>
				<tr class="useSMTP">
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['smtp_config'] }}</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_host'] }}:<br/>
						<small>{{ lang['example'] }} smtp.mail.ru</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom" type="text" name="save_con[mail][smtp][host]" id="mail_smtp_host" value="{{ config['mail']['smtp']['host'] }}" size="40"/>
					</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_port'] }}:<br/>
						<small>{{ lang['example'] }} 25</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom" type="text" name="save_con[mail][smtp][port]" id="mail_smtp_port" size="40" value="{{ config['mail']['smtp']['port'] }}"/>
					</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_auth'] }}:<br/>
						<small>{{ lang['smtp_auth#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[mail][smtp][auth]', 'id' : 'mail_smtp_auth', 'value' : config['mail']['smtp']['auth'] }) }}</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_secure'] }}:<br/>
						<small>{{ lang['smtp_secure#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[mail][smtp][secure]', 'id' : 'mail_smtp_secure', 'value' : config['mail']['smtp']['secure'], 'values' : { '' : 'None', 'tls' : 'TLS', 'ssl' : 'SSL' } }) }}</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_auth_login'] }}:<br/>
						<small>{{ lang['example'] }} email@mail.ru</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom" type="text" id="mail_smtp_login" name="save_con[mail][smtp][login]" value="{{ config['mail']['smtp']['login'] }}" size="40"/>
					</td>
				</tr>
				<tr class="useSMTP">
					<td class="contentEntry1">{{ lang['smtp_auth_pass'] }}:<br/>
						<small>{{ lang['example'] }} mySuperPassword</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="mailfrom" type="text" name="save_con[mail][smtp][pass]" id="mail_smtp_pass" value="{{ config['mail']['smtp']['pass'] }}" size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">
						<input type="button" value="{{ lang['btn_checkSMTP'] }}" onclick="ngCheckEmail(); return false;"/>
					</td>
					<td class="contentEntry2" valign="middle" style="display: block;">EMail:
						<input class="mailfrom" id="mail_tomail" type="text" name='' value='' size="30"/></td>
				</tr>

			</table>
		</div>

		<!-- ########################## NEWS TAB ########################## -->
		<div id="userTabs-news">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['sn'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['number'] }}</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[number]' value='{{ config['number'] }}' size="6"/></td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['news_multicat_url'] }}<br/>
						<small>{{ lang['news_multicat_url#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[news_multicat_url]', 'value' : config['news_multicat_url'], 'values' : { 0 : lang['news_multicat:0'], 1 : lang['news_multicat:1'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['nnavigations'] }}<br/>
						<small>{{ lang['nnavigations_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[newsNavigationsCount]' value='{{ config['newsNavigationsCount'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['nnavigations_admin'] }}<br/>
						<small>{{ lang['nnavigations_admin_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[newsNavigationsAdminCount]' value='{{ config['newsNavigationsAdminCount'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['category_counters'] }}<br/>
						<small>{{ lang['category_counters_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[category_counters]', 'value' : config['category_counters'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['news_view_counters'] }}<br/>
						<small>{{ lang['news_view_counters#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[news_view_counters]', 'value' : config['news_view_counters'], 'values' : { 1 : lang['yesa'], 0 : lang['noa'], 2 : lang['news_view_counters#2'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['news.edit.split'] }}<br/>
						<small>{{ lang['news.edit.split#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[news.edit.split]', 'value' : config['news.edit.split'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['news_without_content'] }}<br/>
						<small>{{ lang['news_without_content_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[news_without_content]', 'value' : config['news_without_content'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['date_adjust'] }}<br/>
						<small>{{ lang['date_adjust_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[date_adjust]' value='{{ config['date_adjust'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['timestamp_active'] }}<br/>
						<small>{{ lang['date_help'] }}</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input type="text" name='save_con[timestamp_active]' value='{{ config['timestamp_active'] }}' size="20"/><br/>
						<small>{{ lang['date_now'] }} {{ timestamp_active_now }}</small>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['timestamp_updated'] }}<br/>
						<small>{{ lang['date_help'] }}</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input type="text" name='save_con[timestamp_updated]' value='{{ config['timestamp_updated'] }}' size="20"/><br/>
						<small>{{ lang['date_now'] }} {{ timestamp_updated_now }}</small>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['smilies'] }}<br/>
						<small>{{ lang['smilies_desc'] }} (<b>,</b>)</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[smilies]' value='{{ config['smilies'] }}' style="width: 400px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['blocks_for_reg'] }}<br/>
						<small>{{ lang['blocks_for_reg_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[blocks_for_reg]', 'value' : config['blocks_for_reg'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['extended_more'] }}<br/>
						<small>{{ lang['extended_more_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[extended_more]', 'value' : config['extended_more'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_smilies'] }}<br/>
						<small>{{ lang['use_smilies_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_smilies]', 'value' : config['use_smilies'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_bbcodes'] }}<br/>
						<small>{{ lang['use_bbcodes_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_bbcodes]', 'value' : config['use_bbcodes'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_htmlformatter'] }}<br/>
						<small>{{ lang['use_htmlformatter_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_htmlformatter]', 'value' : config['use_htmlformatter'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['default_newsorder'] }}<br/>
						<small>{{ lang['default_newsorder_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[default_newsorder]', 'value' : config['default_newsorder'], 'values' : { 'id desc' : lang['order_id_desc'], 'id asc' : lang['order_id_asc'], 'postdate desc' : lang['order_postdate_desc'], 'postdate asc' : lang['order_postdate_asc'], 'title desc' : lang['order_title_desc'], 'title asc' : lang['order_title_asc'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['template_mode'] }}<br/>
						<small>{{ lang['template_mode#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[template_mode]', 'value' : config['template_mode'], 'values' : { 1 : lang['template_mode.1'], 2 : lang['template_mode.2'] } }) }}</td>
				</tr>
			</table>
		</div>
		<!-- ########################## USERS TAB ########################## -->
		<div id="userTabs-users">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['users'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['users_selfregister'] }}<br/>
						<small>{{ lang['users_selfregister_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[users_selfregister]', 'value' : config['users_selfregister'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['register_type'] }}<br/>
						<small>{{ lang['register_type_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[register_type]', 'value' : config['register_type'], 'values' : { 0 : lang['register_extremly'], 1 : lang['register_simple'], 2 : lang['register_activation'], 3 : lang['register_manual'], 4 : lang['register_manual_confirm']  } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['user_aboutsize'] }}<br/>
						<small>{{ lang['user_aboutsize_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[user_aboutsize]' value='{{ config['user_aboutsize'] }}' style="width: 40px"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['users.avatars'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_avatars'] }}<br/>
						<small>{{ lang['use_avatars_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_avatars]', 'value' : config['use_avatars'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['avatars_gravatar'] }}<br/>
						<small>{{ lang['avatars_gravatar_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[avatars_gravatar]', 'value' : config['avatars_gravatar'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['avatars_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/uploads/avatars</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[avatars_url]' value='{{ config['avatars_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['avatars_dir'] }}<br/>
						<small>{{ lang['example'] }} /home/servercom/public_html/uploads/avatars/</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[avatars_dir]' value='{{ config['avatars_dir'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['avatar_wh'] }}<br/>
						<small>{{ lang['avatar_wh_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[avatar_wh]' value='{{ config['avatar_wh'] }}' style="width: 40px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['avatar_max_size'] }}<br/>
						<small>{{ lang['avatar_max_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[avatar_max_size]' value='{{ config['avatar_max_size'] }}' style="width: 40px"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['users.photos'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['use_photos'] }}<br/>
						<small>{{ lang['use_photos_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[use_photos]', 'value' : config['use_photos'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['photos_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/uploads/photos</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[photos_url]' value='{{ config['photos_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['photos_dir'] }}<br/>
						<small>{{ lang['example'] }} /home/servercom/public_html/uploads/photos/</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[photos_dir]' value='{{ config['photos_dir'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['photos_max_size'] }}<br/>
						<small>{{ lang['photos_max_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[photos_max_size]' value='{{ config['photos_max_size'] }}' style="width: 40px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['photos_thumb_size'] }}<br/>
						<small>{{ lang['photos_thumb_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[photos_thumb_size_x]' value='{{ config['photos_thumb_size_x'] }}' style="width: 40px"/>
						x
						<input type="text" name='save_con[photos_thumb_size_y]' value='{{ config['photos_thumb_size_y'] }}' style="width: 40px"/>
					</td>
				</tr>
			</table>
		</div>
		<!-- ########################## IMAGES TAB ########################## -->
		<div id="userTabs-imgfiles">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['files'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['files_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/uploads/files</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[files_url]' value='{{ config['files_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['files_dir'] }}<br/>
						<small>{{ lang['example'] }} /home/servercom/public_html/uploads/files/</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[files_dir]' value='{{ config['files_dir'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['attach_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/uploads/dsn</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[attach_url]' value='{{ config['attach_url'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['attach_dir'] }}<br/>
						<small>{{ lang['example'] }} /home/servercom/public_html/uploads/dsn/</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[attach_dir]' value='{{ config['attach_dir'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['files_ext'] }}<br/>
						<small>{{ lang['files_ext_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[files_ext]' value='{{ config['files_ext'] }}' size="40"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['files_max_size'] }}<br/>
						<small>{{ lang['files_max_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[files_max_size]' value='{{ config['files_max_size'] }}' style="width: 40px"/>
					</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['img'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_url'] }}<br/>
						<small>{{ lang['example'] }} http://server.com/uploads/images</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[images_url]' value='{{ config['images_url'] }}' style="width: 400px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_dir'] }}<br/>
						<small>{{ lang['example'] }} /home/servercom/public_html/uploads/images/</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="folder" type="text" name='save_con[images_dir]' value='{{ config['images_dir'] }}' style="width: 400px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_ext'] }}<br/>
						<small>{{ lang['images_ext_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[images_ext]' value='{{ config['images_ext'] }}' style="width: 400px"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_max_size'] }}<br/>
						<small>{{ lang['images_max_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[images_max_size]' value='{{ config['images_max_size'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_dim_action'] }}<br/>
						<small>{{ lang['images_dim_action#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[images_dim_action]', 'value' : config['images_dim_action'], 'values' : { 0 : lang['images_dim_action#0'], 1 : lang['images_dim_action#1'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['images_max_dim'] }}<br/>
						<small>{{ lang['images_max_dim#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[images_max_x]' value='{{ config['images_max_x'] }}' size="6"/>
						x
						<input type="text" name='save_con[images_max_y]' value='{{ config['images_max_y'] }}' size="6"/>
					</td>
				</tr>

				<!-- IMAGE transform control -->
				<tr>
					<td colspan="2" class="contentHead">{{ lang['img.thumb'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['thumb_mode'] }}<br/>
						<small>{{ lang['thumb_mode_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[thumb_mode]', 'value' : config['thumb_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['thumb_size'] }}<br/>
						<small>{{ lang['thumb_size_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[thumb_size_x]' value='{{ config['thumb_size_x'] }}' size="6"/>
						x
						<input type="text" name='save_con[thumb_size_y]' value='{{ config['thumb_size_y'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['thumb_quality'] }}<br/>
						<small>{{ lang['thumb_quality_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[thumb_quality]' value='{{ config['thumb_quality'] }}' size="6"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">{{ lang['img.shadow'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['shadow_mode'] }}<br/>
						<small>{{ lang['shadow_mode_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[shadow_mode]', 'value' : config['shadow_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['shadow_place'] }}<br/>
						<small>{{ lang['shadow_place_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[shadow_place]', 'value' : config['shadow_place'], 'values' : { 0 : lang['mode_orig'], 1 : lang['mode_copy'], 2 : lang['mode_origcopy'] } }) }}</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">{{ lang['img.stamp'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['stamp_mode'] }}<br/>
						<small>{{ lang['stamp_mode_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[stamp_mode]', 'value' : config['stamp_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['stamp_place'] }}<br/>
						<small>{{ lang['stamp_place_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[stamp_place]', 'value' : config['stamp_place'], 'values' : { 0 : lang['mode_orig'], 1 : lang['mode_copy'], 2 : lang['mode_origcopy'] } }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['wm_image'] }}<br/>
						<small>{{ lang['wm_image_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[wm_image]', 'value' : config['wm_image'], 'values' : list['wm_image'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['wm_image_transition'] }}<br/>
						<small>{{ lang['wm_image_transition_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">
						<input type="text" name='save_con[wm_image_transition]' value='{{ config['wm_image_transition'] }}' size="6"/>
					</td>
				</tr>
				<!-- END: IMAGE transform control -->
			</table>
		</div>
		<!-- ########################## AUTH TAB ########################## -->
		<div id="userTabs-auth">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['auth'] }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['remember'] }}<br/>
						<small>{{ lang['remember_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectYN({'name' : 'save_con[remember]', 'value' : config['remember'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['auth_module'] }}<br/>
						<small>{{ lang['auth_module_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[auth_module]', 'value' : config['auth_module'], 'values' : list['auth_module'] }) }}</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['auth_db'] }}<br/>
						<small>{{ lang['auth_db_desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelect({'name' : 'save_con[auth_db]', 'value' : config['auth_db'], 'values' : list['auth_db'] }) }}</td>
				</tr>
			</table>
		</div>
		<!-- ########################## MULTI TAB ########################## -->
		<div id="userTabs-multi">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['multi_info'] }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1" valign=top>{{ lang['mydomains'] }}<br/>
						<small>{{ lang['mydomains_desc'] }}</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<textarea cols=45 rows=3 name="save_con[mydomains]">{{ config['mydomains'] }}</textarea></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang['multisite'] }}</td>
				</tr>
				<tr>
					<td colspan=2>
						<table class="contentNav" width="100%">
							<tr>
								<td><b>{{ lang['status'] }}</b></td>
								<td><b>{{ lang['title'] }}</b></td>
								<td><b>{{ lang['domains'] }}</b></td>
								<td><b>{{ lang['flags'] }}</b></td>
							</tr>
							{% for MR in multiConfig %}
								<tr class='contentEntry1'>
									<td>{% if (MR['active']) %}On{% else %}Off{% endif %}</td>
									<td>{{ MR['key'] }}</td>
									<td>{% for domain in MR['domains'] %}{{ domain }}
											<br/>{% else %}- {{ lang['not_specified'] }} -{% endfor %}</td>
									<td>&nbsp;</td>
								</tr>
							{% else %}
								<tr class='contentEntry1'>
									<td colspan="4">- {{ lang['not_used'] }} -</td>
								</tr>
							{% endfor %}
						</table>
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## CACHE TAB ########################## -->
		<div id="userTabs-cache">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="contentHead"><img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>Memcached
					</td>
				</tr>
				<tr>
					<td class="contentEntry1">{{ lang['memcached_enabled'] }}<br/>
						<small>{{ lang['memcached_enabled#desc'] }}</small>
					</td>
					<td class="contentEntry2" valign="middle">{{ mkSelectNY({'name' : 'save_con[use_memcached]', 'value' : config['use_memcached'] }) }}</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['memcached_ip'] }}<br/>
						<small>{{ lang['example'] }} localhost</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[memcached_ip]' value='{{ config['memcached_ip'] }}' id="memcached_ip" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['memcached_port'] }}<br/>
						<small>{{ lang['example'] }} 11211</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[memcached_port]' value='{{ config['memcached_port'] }}' id="memcached_port" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">{{ lang['memcached_prefix'] }}<br/>
						<small>{{ lang['example'] }} ng</small>
					</td>
					<td width="50%" class="contentEntry2" valign="middle">
						<input class="important" type="text" name='save_con[memcached_prefix]' value='{{ config['memcached_prefix'] }}' id="memcached_prefix" size="40"/>
					</td>
				</tr>
				<tr>
					<td width="50%" class="contentEntry1">&nbsp;</td>
					<td width="50%" class="contentEntry2">
						<input type="button" value="{{ lang['btn_checkMemcached'] }}" onclick="ngCheckMemcached(); return false;"/>
					</td>
				</tr>
			</table>
		</div>


	</div>
	<script type="text/javascript" language="javascript">
		$(function () {
			$("#userTabs").tabs();
		});

		if ($("#mail_mode option:selected").val() != "smtp") {
			$(".useSMTP").hide();
		}

		$("#mail_mode").on('change', function () {
			if ($("#mail_mode option:selected").val() == "smtp") {
				$(".useSMTP").show();
			}
			else {
				$(".useSMTP").hide();
			}
		});


	</script>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr align="center">
						<td width="100%" class="contentEdit" align="center" valign="top">
							<input type="hidden" name="subaction" value="save"/>
							<input type="hidden" name="save" value=""/>
							<input type="submit" value="{{ lang['save'] }}" class="button"/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>