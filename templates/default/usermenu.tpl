{% if (global.flags.isLogged) %}
	<div id="auth">
		{% if pluginIsActive('nsm') %}<a href="/plugin/nsm/" class="auth-add-news">Добавить новость</a>{% endif %}
		<a href="#" class="auth-profile">{{ lang.profile }}</a>
		<div id="profile">
			<div class="profile-top-bg"></div>
			<div class="profile-block">
				<div class="title">{{ lang.profile }}</div>
				<ul>
					[if-have-perm]
					<li><a href="{{ admin_url }}/" target="_blank"><b>{{ lang.admin_panel }}</b></a></li>
					<li><a href="{{ addnews_link }}">{{ lang.add_news }}</a></li>
					[/if-have-perm]
					{% if pluginIsActive('uprofile') %}
						<li><a href="{{ profile_link }}">{{ lang.edit_profile }}</a></li>{% endif %}
					{% if pluginIsActive('pm') %}
						<li><a href="{{ p.pm.link }}">{{ lang.private_messages }} ({{ p.pm.pm_unread }})</a>
						</li>{% endif %}
					<li><a href="{{ logout_link }}">{{ lang.log_out }}</a></li>
				</ul>
			</div>
		</div>
	</div>
{% else %}
	<script language="javascript">
		var set_login = 0;
		var set_pass = 0;
	</script>
	<!-- .modal -->
	<div class="modal" id="auth-modal">
		<div class="modal-box">
			<div class="modal-clouse"></div>
			<div class="title">{{ lang.theme['login_title'] }}</div>
			<div class="modal-content clearfix">
				<form name="login" method="post" action="{{ form_action }}" id="login">
					<input type="hidden" name="redirect" value="{{ redirect }}"/>
					<div class="label">
						<label for="login">{{ lang.username }}:</label>
						<input type="text" id="login" name="username" class="input">
					</div>
					<div class="label clearfix">
						<label for="password">{{ lang.password }}:</label>
						<input type="password" id="password" name="password" class="input">
						<a href="{{ lost_link }}" class="pull-right">{{ lang.lostpassword }}</a>
					</div>
					<div class="label pull-left">
						<label><input type="checkbox"> {{ lang.remember_me }}</label>
					</div>
					<div class="label pull-right">
						<input type="submit" value="{{ lang.login }}" class="button">
					</div>
				</form>
			</div>
			{% if pluginIsActive('auth_loginza') %}
				<div class="modal-footer">
					Вход через социальные сети: <br>
					<div class="social-in-modal">
						<script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
						<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/" class="loginza"><img src="{{ tpl_url }}/img/social/fb.png" alt="">
							Facebook</a>
						<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/" class="loginza"><img src="{{ tpl_url }}/img/social/vk.png" alt="">
							Вконтакте</a>
						<a href="https://loginza.ru/api/widget?token_url={home}/plugin/auth_loginza/" class="loginza"><img src="{{ tpl_url }}/img/social/tw.png" alt="">
							Twitter</a>
					</div>
				</div>
			{% endif %}
		</div>
	</div>
{% endif %}