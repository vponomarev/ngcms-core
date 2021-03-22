<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6 d-none d-md-block ">
			<h1 class="m-0 text-dark">{{ lang['configuration_title'] }}</h1>
	  </div><!-- /.col -->
	  <div class="col-12 col-sm-12 col-md-6 ">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="admin.php"><i class="fa fa-home"></i></a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ lang['configuration_title'] }}</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->

<form action="{{ php_self }}" method="post">
	<input type="hidden" name="token" value="{{ token }}" />
	<input type="hidden" name="mod" value="configuration" />
	<input type="hidden" name="subaction" value="save" />
	<input type="hidden" name="save" value="" />
	<input id="selectedOption" type="hidden" name="selectedOption" />

	<ul class="nav nav-tabs nav-fill mb-3 d-md-flex d-block" role="tablist">
		<li class="nav-item"><a href="#userTabs-db" class="nav-link active" data-toggle="tab">{{ lang['db'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-security" class="nav-link" data-toggle="tab">{{ lang['security'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-system" class="nav-link" data-toggle="tab">{{ lang['syst'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-news" class="nav-link" data-toggle="tab">{{ lang['sn'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-users" class="nav-link" data-toggle="tab">{{ lang['users'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-imgfiles" class="nav-link" data-toggle="tab">{{ lang['files'] }}/{{ lang['img'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-cache" class="nav-link" data-toggle="tab">{{ lang['cache'] }}</a></li>
		<li class="nav-item"><a href="#userTabs-multi" class="nav-link" data-toggle="tab">{{ lang['multi'] }}</a></li>
	</ul>

	<div id="userTabs" class="tab-content">
		<!-- ########################## DB TAB ########################## -->
		<div id="userTabs-db" class="tab-pane show active">
			<!-- TABLE DB//Connection -->
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['db_connect'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbtype'] }} <small class="form-text text-muted">{{ lang['example'] }} pdo</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[dbtype]', 'value' : config['dbtype'], 'id' : 'db_dbtype', 'values' : { 'pdo' : lang['pdo'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbhost'] }} <small class="form-text text-muted">{{ lang['example'] }} localhost</small></td>
					<td width="50%">
						<input id="db_dbhost" type="text" name="save_con[dbhost]" value="{{ config['dbhost'] }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbname'] }} <small class="form-text text-muted">{{ lang['example'] }} ng</small></td>
					<td width="50%">
						<input id="db_dbname" type="text" name='save_con[dbname]' value='{{ config['dbname'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbuser'] }} <small class="form-text text-muted">{{ lang['example'] }} root</small></td>
					<td width="50%">
						<input id="db_dbuser" type="text" name='save_con[dbuser]' value='{{ config['dbuser'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbpass'] }} <small class="form-text text-muted">{{ lang['example'] }} password</small></td>
					<td width="50%">
						<input id="db_dbpasswd" type="password" name='save_con[dbpasswd]' value='{{ config['dbpasswd'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['dbprefix'] }} <small class="form-text text-muted">{{ lang['example'] }} ng</small></td>
					<td width="50%">
						<input type="text" name='save_con[prefix]' value='{{ config['prefix'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">&nbsp;</td>
					<td width="50%">
						<button type="button" onclick="ngCheckDB();" class="btn btn-outline-primary">{{ lang['btn_checkDB'] }}</button>
					</td>
				</tr>
			</table>
			<!-- END: TABLE DB//Connection -->

			<!-- TABLE DB//Backup -->
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['db_backup'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['auto_backup'] }} <small class="form-text text-muted">{{ lang['auto_backup_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[auto_backup]', 'value' : config['auto_backup'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['auto_backup_time'] }} <small class="form-text text-muted">{{ lang['auto_backup_time_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[auto_backup_time]' value='{{ config['auto_backup_time'] }}' class="form-control" maxlength="5" />
					</td>
				</tr>
			</table>
			<!-- END: TABLE DB//Backup -->
		</div>

		<!-- ########################## SECURITY TAB ########################## -->
		<div id="userTabs-security" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['logging'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['x_ng_headers'] }} <small class="form-text text-muted">{{ lang['x_ng_headers#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[x_ng_headers]', 'value' : config['x_ng_headers'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['syslog'] }} <small class="form-text text-muted">{{ lang['syslog_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[syslog]', 'value' : config['syslog'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['load'] }} <small class="form-text text-muted">{{ lang['load_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[load_analytics]', 'value' : config['load_analytics'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['load_profiler'] }} <small class="form-text text-muted">{{ lang['load_profiler_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name="save_con[load_profiler]" value="{{ config['load_profiler'] }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['security'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['flood_time'] }} <small class="form-text text-muted">{{ lang['flood_time_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[flood_time]' value='{{ config['flood_time'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_captcha'] }} <small class="form-text text-muted">{{ lang['use_captcha_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_captcha]', 'value' : config['use_captcha'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['captcha_font'] }} <small class="form-text text-muted">{{ lang['captcha_font_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[captcha_font]', 'value' : config['captcha_font'], 'values' : list['captcha_font'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_cookies'] }} <small class="form-text text-muted">{{ lang['use_cookies_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_cookies]', 'value' : config['use_cookies'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_sessions'] }} <small class="form-text text-muted">{{ lang['use_sessions_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_sessions]', 'value' : config['use_sessions'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['sql_error'] }} <small class="form-text text-muted">{{ lang['sql_error_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[sql_error_show]', 'value' : config['sql_error_show'], 'values' : { 0 : lang['sql_error_0'], 1 : lang['sql_error_1'], 2 : lang['sql_error_2'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['multiext_files'] }} <small class="form-text text-muted">{{ lang['multiext_files_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[allow_multiext]', 'value' : config['allow_multiext'] }) }}
					</td>
				</tr>
			</table>
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['debug_generate'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['debug'] }} <small class="form-text text-muted">{{ lang['debug_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[debug]', 'value' : config['debug'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['debug_queries'] }} <small class="form-text text-muted">{{ lang['debug_queries_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[debug_queries]', 'value' : config['debug_queries'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['debug_profiler'] }} <small class="form-text text-muted">{{ lang['debug_profiler_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[debug_profiler]', 'value' : config['debug_profiler'] }) }}
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## SYSTEM TAB ########################## -->
		<div id="userTabs-system" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['syst'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['home_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com</small></td>
					<td width="50%">
						<input type="text" name='save_con[home_url]' value='{{ config['home_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['admin_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/engine</small></td>
					<td width="50%">
						<input type="text" name='save_con[admin_url]' value='{{ config['admin_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['home_title'] }} <small class="form-text text-muted">{{ lang['example'] }} NGCNS</small></td>
					<td width="50%">
						<input type="text" name='save_con[home_title]' value="{{ config['home_title']|escape }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['admin_mail'] }} <small class="form-text text-muted">{{ lang['example'] }} admin@server.com</small></td>
					<td width="50%">
						<input type="text" name='save_con[admin_mail]' value='{{ config['admin_mail'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['lock'] }} <small class="form-text text-muted">{{ lang['lock_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[lock]', 'value' : config['lock'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['lock_reason'] }} <small class="form-text text-muted">{{ lang['lock_reason_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[lock_reason]' value='{{ config['lock_reason'] }}' maxlength="200" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['meta'] }} <small class="form-text text-muted">{{ lang['meta_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[meta]', 'value' : config['meta'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['description'] }} <small class="form-text text-muted">{{ lang['description_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name="save_con[description]" value="{{ config['description'] }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['keywords'] }} <small class="form-text text-muted">{{ lang['keywords_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name="save_con[keywords]" value="{{ config['keywords'] }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['theme'] }} <small class="form-text text-muted">{{ lang['theme_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[theme]', 'value' : config['theme'], 'values' : list['theme'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['lang'] }} <small class="form-text text-muted">{{ lang['lang_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[default_lang]', 'value' : config['default_lang'], 'values' : list['default_lang'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_gzip'] }} <small class="form-text text-muted">{{ lang['use_gzip_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_gzip]', 'value' : config['use_gzip'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['404_mode'] }} <small class="form-text text-muted">{{ lang['404_mode_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[404_mode]', 'value' : config['404_mode'], 'values' : { 0 : lang['404.int'], 1 : lang['404.ext'], 2 : lang['404.http'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['libcompat'] }} <small class="form-text text-muted">{{ lang['libcompat_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[libcompat]', 'value' : config['libcompat'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">
						{{ lang['url_external_nofollow'] }} <small class="form-text text-muted">{{ lang['url_external_nofollow_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[url_external_nofollow]', 'value' : config['url_external_nofollow'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['url_external_target_blank'] }} <small class="form-text text-muted">{{ lang['url_external_target_blank_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[url_external_target_blank]', 'value' : config['url_external_target_blank'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['timezone'] }} <small class="form-text text-muted">{{ lang['timezone#desc'] }}</small></td>
					<td width="50%">
						<select id="timezone" name="save_con[timezone]" class="custom-select">
							{% for zone in list['timezoneList'] %}
							<option value="{{ zone }}" {% if (config['timezone'] == zone) %}selected {% endif %}>{{ zone }}</option>
							{% endfor %}
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['email_configuration'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['mailfrom_name'] }} <small class="form-text text-muted">{{ lang['example'] }} Administrator</small></td>
					<td width="50%">
						<input id="mail_fromname" type="text" name='save_con[mailfrom_name]' value='{{ config['mailfrom_name'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['mailfrom'] }} <small class="form-text text-muted">{{ lang['example'] }} mailbot@server.com</small></td>
					<td width="50%">
						<input id="mail_frommail" type="text" name='save_con[mailfrom]' value='{{ config['mailfrom'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['mail_mode'] }}: <small class="form-text text-muted">{{ lang['mail_mode#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[mail_mode]', 'id' : 'mail_mode', 'value' : config['mail_mode'], 'values' : { 'mail' : 'mail', 'sendmail' : 'sendmail', 'smtp' : 'smtp' } }) }}
					</td>
				</tr>
				<tr class="useSMTP">
					<td colspan="2" class="h3 font-weight-light">{{ lang['smtp_config'] }}</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_host'] }}: <small class="form-text text-muted">{{ lang['example'] }} smtp.mail.ru</small></td>
					<td width="50%">
						<input id="mail_smtp_host" type="text" name="save_con[mail][smtp][host]" value="{{ config['mail']['smtp']['host'] }}" class="form-control" />
					</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_port'] }}: <small class="form-text text-muted">{{ lang['example'] }} 25</small></td>
					<td width="50%">
						<input id="mail_smtp_port" type="text" name="save_con[mail][smtp][port]" value="{{ config['mail']['smtp']['port'] }}" class="form-control" />
					</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_auth'] }}: <small class="form-text text-muted">{{ lang['smtp_auth#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[mail][smtp][auth]', 'id' : 'mail_smtp_auth', 'value' : config['mail']['smtp']['auth'] }) }}
					</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_secure'] }}: <small class="form-text text-muted">{{ lang['smtp_secure#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[mail][smtp][secure]', 'id' : 'mail_smtp_secure', 'value' : config['mail']['smtp']['secure'], 'values' : { '' : 'None', 'tls' : 'TLS', 'ssl' : 'SSL' } }) }}
					</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_auth_login'] }}: <small class="form-text text-muted">{{ lang['example'] }} email@mail.ru</small></td>
					<td width="50%">
						<input id="mail_smtp_login" type="text" name="save_con[mail][smtp][login]" value="{{ config['mail']['smtp']['login'] }}" class="form-control" />
					</td>
				</tr>
				<tr class="useSMTP">
					<td width="50%">{{ lang['smtp_auth_pass'] }}: <small class="form-text text-muted">{{ lang['example'] }} mySuperPassword</small></td>
					<td width="50%">
						<input id="mail_smtp_pass" type="text" name="save_con[mail][smtp][pass]" value="{{ config['mail']['smtp']['pass'] }}" class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%"></td>
					<td width="50%">
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">EMail:</label>
							<div class="col-sm-8">
								<input id="mail_tomail" type="text" name="" value="" class="form-control" />
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-8 offset-sm-4">
								<button type="button" class="btn btn-block btn-outline-primary" onclick="ngCheckEmail(); return false;">{{ lang['btn_checkSMTP'] }}</button>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## NEWS TAB ########################## -->
		<div id="userTabs-news" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['sn'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['number'] }}</td>
					<td width="50%">
						<input type="text" name='save_con[number]' value='{{ config['number'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['news_multicat_url'] }} <small class="form-text text-muted">{{ lang['news_multicat_url#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[news_multicat_url]', 'value' : config['news_multicat_url'], 'values' : { 0 : lang['news_multicat:0'], 1 : lang['news_multicat:1'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['nnavigations'] }} <small class="form-text text-muted">{{ lang['nnavigations_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[newsNavigationsCount]' value='{{ config['newsNavigationsCount'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['nnavigations_admin'] }} <small class="form-text text-muted">{{ lang['nnavigations_admin_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[newsNavigationsAdminCount]' value='{{ config['newsNavigationsAdminCount'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['category_counters'] }} <small class="form-text text-muted">{{ lang['category_counters_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[category_counters]', 'value' : config['category_counters'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['news_view_counters'] }} <small class="form-text text-muted">{{ lang['news_view_counters#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[news_view_counters]', 'value' : config['news_view_counters'], 'values' : {1: lang['yesa'], 0: lang['noa'], 2: lang['news_view_counters#2'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['news.edit.split'] }} <small class="form-text text-muted">{{ lang['news.edit.split#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[news.edit.split]', 'value' : config['news.edit.split'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['news_without_content'] }} <small class="form-text text-muted">{{ lang['news_without_content_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[news_without_content]', 'value' : config['news_without_content'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['date_adjust'] }} <small class="form-text text-muted">{{ lang['date_adjust_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[date_adjust]' value='{{ config['date_adjust'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['timestamp_active'] }} <small class="form-text text-muted">{{ lang['date_help'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[timestamp_active]' value='{{ config['timestamp_active'] }}' class="form-control" />
						<small class="form-text text-muted">{{ lang['date_now'] }} {{ timestamp_active_now }}</small>
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['timestamp_updated'] }} <small class="form-text text-muted">{{ lang['date_help'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[timestamp_updated]' value='{{ config['timestamp_updated'] }}' class="form-control" />
						<small class="form-text text-muted">{{ lang['date_now'] }} {{ timestamp_updated_now }}</small>
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['smilies'] }} <small class="form-text text-muted">{{ lang['smilies_desc'] }} (<b>,</b>)</small></td>
					<td width="50%">
						<input type="text" name='save_con[smilies]' value='{{ config['smilies'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['blocks_for_reg'] }} <small class="form-text text-muted">{{ lang['blocks_for_reg_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[blocks_for_reg]', 'value' : config['blocks_for_reg'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['extended_more'] }} <small class="form-text text-muted">{{ lang['extended_more_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[extended_more]', 'value' : config['extended_more'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_smilies'] }} <small class="form-text text-muted">{{ lang['use_smilies_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_smilies]', 'value' : config['use_smilies'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_bbcodes'] }} <small class="form-text text-muted">{{ lang['use_bbcodes_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_bbcodes]', 'value' : config['use_bbcodes'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_htmlformatter'] }} <small class="form-text text-muted">{{ lang['use_htmlformatter_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_htmlformatter]', 'value' : config['use_htmlformatter'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['default_newsorder'] }} <small class="form-text text-muted">{{ lang['default_newsorder_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[default_newsorder]', 'value' : config['default_newsorder'], 'values' : { 'id desc' : lang['order_id_desc'], 'id asc' : lang['order_id_asc'], 'postdate desc' : lang['order_postdate_desc'], 'postdate asc' : lang['order_postdate_asc'], 'title desc' : lang['order_title_desc'], 'title asc' : lang['order_title_asc'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['template_mode'] }} <small class="form-text text-muted">{{ lang['template_mode#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[template_mode]', 'value' : config['template_mode'], 'values' : { 1 : lang['template_mode.1'], 2 : lang['template_mode.2'] } }) }}
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## USERS TAB ########################## -->
		<div id="userTabs-users" class="tab-pane">
			<!-- TABLE AUTH -->
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['auth'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['remember'] }} <small class="form-text text-muted">{{ lang['remember_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[remember]', 'value' : config['remember'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['auth_module'] }} <small class="form-text text-muted">{{ lang['auth_module_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[auth_module]', 'value' : config['auth_module'], 'values' : list['auth_module'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['auth_db'] }} <small class="form-text text-muted">{{ lang['auth_db_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[auth_db]', 'value' : config['auth_db'], 'values' : list['auth_db'] }) }}
					</td>
				</tr>
			</table>
			<!-- END: TABLE AUTH -->

			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['users'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['users_selfregister'] }} <small class="form-text text-muted">{{ lang['users_selfregister_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[users_selfregister]', 'value' : config['users_selfregister'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['register_type'] }} <small class="form-text text-muted">{{ lang['register_type_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[register_type]', 'value' : config['register_type'], 'values' : { 0 : lang['register_extremly'], 1 : lang['register_simple'], 2 : lang['register_activation'], 3 : lang['register_manual'], 4 : lang['register_manual_confirm']  } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['user_aboutsize'] }} <small class="form-text text-muted">{{ lang['user_aboutsize_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[user_aboutsize]' value='{{ config['user_aboutsize'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['users.avatars'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_avatars'] }} <small class="form-text text-muted">{{ lang['use_avatars_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_avatars]', 'value' : config['use_avatars'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['avatars_gravatar'] }} <small class="form-text text-muted">{{ lang['avatars_gravatar_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[avatars_gravatar]', 'value' : config['avatars_gravatar'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['avatars_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/uploads/avatars</small></td>
					<td width="50%">
						<input type="text" name='save_con[avatars_url]' value='{{ config['avatars_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['avatars_dir'] }} <small class="form-text text-muted">{{ lang['example'] }} /home/servercom/public_html/uploads/avatars/</small></td>
					<td width="50%">
						<input type="text" name='save_con[avatars_dir]' value='{{ config['avatars_dir'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['avatar_wh'] }} <small class="form-text text-muted">{{ lang['avatar_wh_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[avatar_wh]' value='{{ config['avatar_wh'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['avatar_max_size'] }} <small class="form-text text-muted">{{ lang['avatar_max_size_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[avatar_max_size]' value='{{ config['avatar_max_size'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['users.photos'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['use_photos'] }} <small class="form-text text-muted">{{ lang['use_photos_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectYN({'name' : 'save_con[use_photos]', 'value' : config['use_photos'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['photos_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/uploads/photos</small></td>
					<td width="50%">
						<input type="text" name='save_con[photos_url]' value='{{ config['photos_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['photos_dir'] }} <small class="form-text text-muted">{{ lang['example'] }} /home/servercom/public_html/uploads/photos/</small></td>
					<td width="50%">
						<input type="text" name='save_con[photos_dir]' value='{{ config['photos_dir'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['photos_max_size'] }} <small class="form-text text-muted">{{ lang['photos_max_size_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[photos_max_size]' value='{{ config['photos_max_size'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['photos_thumb_size'] }} <small class="form-text text-muted">{{ lang['photos_thumb_size_desc'] }}</small></td>
					<td width="50%">
						<div class="input-group mb-3">
							<input type="text" name='save_con[photos_thumb_size_x]' value='{{ config['photos_thumb_size_x'] }}' class="form-control" />
							<div class="input-group-prepend input-group-append">
								<label class="input-group-text">x</label>
							</div>
							<input type="text" name='save_con[photos_thumb_size_y]' value='{{ config['photos_thumb_size_y'] }}' class="form-control" />
						</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## IMAGES TAB ########################## -->
		<div id="userTabs-imgfiles" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['files'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['files_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/uploads/files</small></td>
					<td width="50%">
						<input type="text" name='save_con[files_url]' value='{{ config['files_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['files_dir'] }} <small class="form-text text-muted">{{ lang['example'] }} /home/servercom/public_html/uploads/files/</small></td>
					<td width="50%">
						<input type="text" name='save_con[files_dir]' value='{{ config['files_dir'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['attach_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/uploads/dsn</small></td>
					<td width="50%">
						<input type="text" name='save_con[attach_url]' value='{{ config['attach_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['attach_dir'] }} <small class="form-text text-muted">{{ lang['example'] }} /home/servercom/public_html/uploads/dsn/</small></td>
					<td width="50%">
						<input type="text" name='save_con[attach_dir]' value='{{ config['attach_dir'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['files_ext'] }} <small class="form-text text-muted">{{ lang['files_ext_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[files_ext]' value='{{ config['files_ext'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['files_max_size'] }} <small class="form-text text-muted">{{ lang['files_max_size_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[files_max_size]' value='{{ config['files_max_size'] }}' class="form-control" />
					</td>
				</tr>
			</table>
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['img'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_url'] }} <small class="form-text text-muted">{{ lang['example'] }} http://server.com/uploads/images</small></td>
					<td width="50%">
						<input type="text" name='save_con[images_url]' value='{{ config['images_url'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_dir'] }} <small class="form-text text-muted">{{ lang['example'] }} /home/servercom/public_html/uploads/images/</small></td>
					<td width="50%">
						<input type="text" name='save_con[images_dir]' value='{{ config['images_dir'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_ext'] }} <small class="form-text text-muted">{{ lang['images_ext_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[images_ext]' value='{{ config['images_ext'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_max_size'] }} <small class="form-text text-muted">{{ lang['images_max_size_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[images_max_size]' value='{{ config['images_max_size'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_dim_action'] }} <small class="form-text text-muted">{{ lang['images_dim_action#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[images_dim_action]', 'value' : config['images_dim_action'], 'values' : { 0 : lang['images_dim_action#0'], 1 : lang['images_dim_action#1'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['images_max_dim'] }} <small class="form-text text-muted">{{ lang['images_max_dim#desc'] }}</small></td>
					<td width="50%">
						<div class="input-group mb-3">
							<input type="text" name='save_con[images_max_x]' value='{{ config['images_max_x'] }}' class="form-control" />
							<div class="input-group-prepend input-group-append">
								<label class="input-group-text">x</label>
							</div>
							<input type="text" name='save_con[images_max_y]' value='{{ config['images_max_y'] }}' class="form-control" />
						</div>
					</td>
				</tr>

				<!-- IMAGE transform control -->
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['img.thumb'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['thumb_mode'] }} <small class="form-text text-muted">{{ lang['thumb_mode_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[thumb_mode]', 'value' : config['thumb_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['thumb_size'] }} <small class="form-text text-muted">{{ lang['thumb_size_desc'] }}</small></td>
					<td width="50%">
						<div class="input-group mb-3">
							<input type="text" name='save_con[thumb_size_x]' value='{{ config['thumb_size_x'] }}' class="form-control" />
							<div class="input-group-prepend input-group-append">
								<label class="input-group-text">x</label>
							</div>
							<input type="text" name='save_con[thumb_size_y]' value='{{ config['thumb_size_y'] }}' class="form-control" />
						</div>
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['thumb_quality'] }} <small class="form-text text-muted">{{ lang['thumb_quality_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[thumb_quality]' value='{{ config['thumb_quality'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['img.shadow'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['shadow_mode'] }} <small class="form-text text-muted">{{ lang['shadow_mode_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[shadow_mode]', 'value' : config['shadow_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['shadow_place'] }} <small class="form-text text-muted">{{ lang['shadow_place_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[shadow_place]', 'value' : config['shadow_place'], 'values' : { 0 : lang['mode_orig'], 1 : lang['mode_copy'], 2 : lang['mode_origcopy'] } }) }}
					</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['img.stamp'] }}</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['stamp_mode'] }} <small class="form-text text-muted">{{ lang['stamp_mode_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[stamp_mode]', 'value' : config['stamp_mode'], 'values' : { 0 : lang['mode_demand'], 1 : lang['mode_forbid'], 2 : lang['mode_always'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['stamp_place'] }} <small class="form-text text-muted">{{ lang['stamp_place_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[stamp_place]', 'value' : config['stamp_place'], 'values' : { 0 : lang['mode_orig'], 1 : lang['mode_copy'], 2 : lang['mode_origcopy'] } }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['wm_image'] }} <small class="form-text text-muted">{{ lang['wm_image_desc'] }}</small></td>
					<td width="50%">
						{{ mkSelect({'name' : 'save_con[wm_image]', 'value' : config['wm_image'], 'values' : list['wm_image'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['wm_image_transition'] }} <small class="form-text text-muted">{{ lang['wm_image_transition_desc'] }}</small></td>
					<td width="50%">
						<input type="text" name='save_con[wm_image_transition]' value='{{ config['wm_image_transition'] }}' class="form-control" />
					</td>
				</tr>
				<!-- END: IMAGE transform control -->
			</table>
		</div>

		<!-- ########################## MULTI TAB ########################## -->
		<div id="userTabs-multi" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['multi_info'] }}</td>
				</tr>
				<tr>
					<td width="50%" valign=top>{{ lang['mydomains'] }} <small class="form-text text-muted">{{ lang['mydomains_desc'] }}</small></td>
					<td width="50%">
						<textarea cols="45" rows="3" name="save_con[mydomains]" class="form-control">{{ config['mydomains'] }}</textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="h3 font-weight-light">{{ lang['multisite'] }}</td>
				</tr>
				<tr>
					<td colspan=2>
						<table class="table table-sm">
							<thead>
								<tr>
									<th>{{ lang['status'] }}</th>
									<th>{{ lang['title'] }}</th>
									<th>{{ lang['domains'] }}</th>
									<th>{{ lang['flags'] }}</th>
								</tr>
							</thead>
							<tbody>
								{% for MR in multiConfig %}
								<tr>
									<td>{% if (MR['active']) %}On{% else %}Off{% endif %}</td>
									<td>{{ MR['key'] }}</td>
									<td>{% for domain in MR['domains'] %}{{ domain }}
										{% else %}- {{ lang['not_specified'] }} -{% endfor %}</td>
									<td>&nbsp;</td>
								</tr>
								{% else %}
								<tr>
									<td colspan="4">- {{ lang['not_used'] }} -</td>
								</tr>
								{% endfor %}
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<!-- ########################## CACHE TAB ########################## -->
		<div id="userTabs-cache" class="tab-pane">
			<table class="table table-sm">
				<tr>
					<td colspan="2" class="h3 font-weight-light">Memcached</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['memcached_enabled'] }} <small class="form-text text-muted">{{ lang['memcached_enabled#desc'] }}</small></td>
					<td width="50%">
						{{ mkSelectNY({'name' : 'save_con[use_memcached]', 'value' : config['use_memcached'] }) }}
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['memcached_ip'] }} <small class="form-text text-muted">{{ lang['example'] }} localhost</small></td>
					<td width="50%">
						<input id="memcached_ip" type="text" name='save_con[memcached_ip]' value='{{ config['memcached_ip'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['memcached_port'] }} <small class="form-text text-muted">{{ lang['example'] }} 11211</small></td>
					<td width="50%">
						<input id="memcached_port" type="text" name='save_con[memcached_port]' value='{{ config['memcached_port'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">{{ lang['memcached_prefix'] }} <small class="form-text text-muted">{{ lang['example'] }} ng</small></td>
					<td width="50%">
						<input id="memcached_prefix" type="text" name='save_con[memcached_prefix]' value='{{ config['memcached_prefix'] }}' class="form-control" />
					</td>
				</tr>
				<tr>
					<td width="50%">&nbsp;</td>
					<td width="50%">
						<input type="button" value="{{ lang['btn_checkMemcached'] }}" class="btn btn-outline-primary" onclick="ngCheckMemcached(); return false;" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="form-group my-3 text-center">
		<button type="submit" class="btn btn-outline-success">{{ lang['save'] }}</button>
	</div>
</form>

<script type="text/javascript">
	$("#mail_mode").on('change', toggleSmtp)
		.trigger('change');

	function toggleSmtp(event) {
		$(".useSMTP").toggle("smtp" === $("#mail_mode option:selected").val());
	}

	// Check DB connection
	function ngCheckDB() {
		post('admin.configuration.dbCheck', {
			'token': '{{ token }}',
			'dbtype': $("#db_dbtype").val(),
			'dbhost': $("#db_dbhost").val(),
			'dbname': $("#db_dbname").val(),
			'dbuser': $("#db_dbuser").val(),
			'dbpasswd': $("#db_dbpasswd").val(),
		});
	}

	// Check MEMCached connection
	function ngCheckMemcached() {
		post('admin.configuration.memcachedCheck', {
			'token': '{{ token }}',
			'ip': $("#memcached_ip").val(),
			'port': $("#memcached_port").val(),
			'prefix': $("#memcached_prefix").val(),
		});
	}

	// Send test e-mail message
	function ngCheckEmail() {
		post('admin.configuration.emailCheck', {
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
		});
	}
</script>
