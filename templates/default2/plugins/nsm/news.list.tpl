<div class="post">
	<div class="post-header">
		<div class="post-title">Список ваших новостей:</div>
	</div>
	<div style="height: 10px;"></div>
	<div class="post-text">
		<p>
		<table border="0" width="100%">
			<tr>
				<th><a href="{{ addURL }}">Добавить новость..</a></th>
			</tr>
		</table>
		<div style="height: 20px;"></div>
		<table class="nsm" border="0" width="100%">
			<tr align="center">
				<td class="nsm_head" width="50">Статус</td>
				<td class="nsm_head" width="70">Дата</td>
				<td class="nsm_head" width="50">&nbsp;</td>
				<td class="nsm_head">Заголовок</td>
			</tr>
			{% for entry in entries %}
				<tr>
					<td class="nsm_list" width="25" align="center">
						{% if (entry.state == 1) %}
							<img src="{{ skins_url }}/images/yes.png" alt="{{ lang['state.published'] }}"/>
						{% elseif (entry.state == 0) %}
							<img src="{{ skins_url }}/images/no.png" alt="{{ lang['state.unpiblished'] }}"/>
						{% else %}
							<img src="{{ skins_url }}/images/no_plug.png" alt="{{ lang['state.draft'] }}"/>
						{% endif %}
					</td>
					<td width="60" align="center" class="nsm_list">{% if entry.flags.canEdit %}
						<a href="{{ entry.editlink }}">{% endif %}{{ entry.itemdate }}{% if entry.flags.canView %}</a>{% endif %}
					</td>
					<td width="48" cellspacing=0 cellpadding=0 align="center" class="nsm_list">
						{% if entry.flags.mainpage %}
							<img src="{{ skins_url }}/images/mainpage.png" border="0" width="16" height="16" title="На главной"/> {% endif %}
						{% if (entry.attach_count > 0) %}
							<img src="{{ skins_url }}/images/attach.png" border="0" width="16" height="16" title="Файлов: {{ entry.attach_count }}"/> {% endif %}
						{% if (entry.images_count > 0) %}
							<img src="{{ skins_url }}/images/img_group.png" border="0" width="16" height="16" title="Картинок: {{ entry.images_count }}"/> {% endif %}
					</td>
					<td class="nsm_list">
						{% if entry.flags.status %}
						<a href="{{ entry.link }}">{% endif %}{{ entry.title }}{% if entry.flags.status %}</a>{% endif %}
					</td>
				</tr>
			{% else %}
				<tr>
					<td colspan="4" class="nsm_list">У вас нет новостей</td>
				</tr>
			{% endfor %}
		</table>
		</p>
	</div>
</div>