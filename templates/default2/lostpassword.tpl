<form name="lostpassword" action="{{ form_action }}" method="post">
	<input type="hidden" name="type" value="send" />

	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['lostpassword'] }}</div>
		</div>

		<div style="height: 10px;"></div>

		<div class="post-text">
			<p>
				<table border="0" width="100%">
					{{ entries }}

					{% if flags.hasCaptcha %}
						<tr>
							<td style="padding: 5px;">
								<img src="{{ captcha_source_url }}" alt="{{ lang['captcha'] }}" />
							</td>
							<td style="padding: 5px;">
								<input class="input" type="text" name="vcode" style="width:80px" />
							</td>
						</tr>
					{% endif %}
				</table>

				<input type="submit" value="{{ lang['send_pass'] }}" class="btn">
			</p>
		</div>
	</div>
</form>
