<h2>������ ����� ��������:</h2>
<br/>
<a href="{{ addURL }}">�������� �������..</a>
<br/>
<br/>

<table border="0" cellspacing="0" cellpadding="0" class="content" align="center" width="100%">
<tr align="left" class="contHead">
<td width="16">&nbsp;</td>
<td width="60"  nowrap>����</td>
<td>&nbsp;</td>
<td>���������</td>
</tr>
{% for entry in entries %}
<tr align="left" >
	<td width="25" align="left" class="contentEntry1">{% if (entry.state == 1) %}<img src="{{ skins_url }}/images/yes.png" alt="{{ lang['state.published'] }}" />{% elseif (entry.state == 0) %}<img src="{{ skins_url }}/images/no.png" alt="{{ lang['state.unpiblished'] }}" />{% else %}<img src="{{ skins_url }}/images/no_plug.png" alt="{{ lang['state.draft'] }}" />{% endif %} </td>
	<td width="60" class="contentEntry1">{% if entry.flags.canEdit %}<a href="{{ entry.editlink }}">{% endif %}{{ entry.itemdate }}{% if entry.flags.canView %}</a>{% endif %}</td>
	<td width="48" class="contentEntry1" cellspacing=0 cellpadding=0 style="padding:0; margin:0;" nowrap>
		{% if entry.flags.mainpage %}<img src="{{ skins_url }}/images/mainpage.png" border="0" width="16" height="16" title="�� �������"/> {% endif %}
		{% if (entry.attach_count > 0) %}<img src="{{ skins_url }}/images/attach.png" border="0" width="16" height="16" title="������: {{ entry.attach_count }}"/> {% endif %}
		{% if (entry.images_count > 0) %}<img src="{{ skins_url }}/images/img_group.png" border="0" width="16" height="16" title="��������: {{ entry.images_count }}"/> {% endif %}
	</td>
	<td class="contentEntry1">
		{% if entry.flags.status %}<a href="{{ entry.link }}">{% endif %}{{ entry.title }}{% if entry.flags.status %}</a>{% endif %}
	</td>
</tr>
{% else %}
<tr><td colspan="2">� ��� ��� ��������</td></tr>
{% endfor %}
</table>