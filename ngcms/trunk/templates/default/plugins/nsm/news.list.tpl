<div class="block-title">������ ����� ��������:</div>
<table class="table table-striped table-bordered">
	<tr>
		<th colspan="4"><a href="{{ addURL }}">�������� �������..</a></th>
	</tr>
	<tr align="center">
		<td width="40">������</td>
		<td width="60">����</td>
		<td>&nbsp;</td>
		<td>���������</td>
	</tr>
	{% for entry in entries %}
	<tr>
		<td width="25" align="center">
			{% if (entry.state == 1) %}
				<img src="{{ skins_url }}/images/yes.png" alt="{{ lang['state.published'] }}" />
			{% elseif (entry.state == 0) %}
				<img src="{{ skins_url }}/images/no.png" alt="{{ lang['state.unpiblished'] }}" />
			{% else %}
				<img src="{{ skins_url }}/images/no_plug.png" alt="{{ lang['state.draft'] }}" />
			{% endif %}
		</td>
		<td width="60">{% if entry.flags.canEdit %}<a href="{{ entry.editlink }}">{% endif %}{{ entry.itemdate }}{% if entry.flags.canView %}</a>{% endif %}</td>
		<td width="48" cellspacing=0 cellpadding=0 align="center">
			{% if entry.flags.mainpage %}<img src="{{ skins_url }}/images/mainpage.png" border="0" width="16" height="16" title="�� �������"/> {% endif %}
			{% if (entry.attach_count > 0) %}<img src="{{ skins_url }}/images/attach.png" border="0" width="16" height="16" title="������: {{ entry.attach_count }}"/> {% endif %}
			{% if (entry.images_count > 0) %}<img src="{{ skins_url }}/images/img_group.png" border="0" width="16" height="16" title="��������: {{ entry.images_count }}"/> {% endif %}
		</td>
		<td>
			{% if entry.flags.status %}<a href="{{ entry.link }}">{% endif %}{{ entry.title }}{% if entry.flags.status %}</a>{% endif %}
		</td>
	</tr>
	{% else %}
	<tr>
		<td colspan="4">� ��� ��� ��������</td>
	</tr>
	{% endfor %}
</table>