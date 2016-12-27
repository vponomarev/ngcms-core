{% for entry in entries %}
<tr align="center" class="contRow1">
<td>
{% if (flags.canModify) %}
 <a href="#" onclick="categoryModifyRequest('up', {{ entry.id }});"><img src="{{ skins_url }}/images/up.gif"/></a>
 <a href="#" onclick="categoryModifyRequest('down', {{ entry.id }});"><img src="{{ skins_url }}/images/down.gif"/></a>
{% endif %}
</td>
<td>
<div style="float: left; margin-right: 5px;">{{ entry.level }}</div>
<div style="float: left;">
{% if (flags.canView) %}<a href="admin.php?mod=categories&amp;action=edit&amp;catid={{ entry.id }}" title="ID: {{ entry.id }}">{{ entry.name }}</a>{% else %}{{ entry.name }}{% endif %}
<br/><small><a href="{{ entry.linkView }}" title="{{ lang['site.view'] }}" target="_blank">{{ entry.linkView }}</a></small>
</div>{% if (entry.info|length>0) %}<div style="float: left;"><img src="{{ skins_url }}/images/comments.gif"/></div>{% endif %}
</td>
<td>{{ entry.id }}</td>
<td>{{ entry.alt }}</td>
<td>
{% if (entry.flags.showMain) %}<img src="{{ skins_url }}/images/yes.png" alt="{{ lang['yesa'] }}" title="{{ lang['yesa'] }}"/>{% else %}<img src="{{ skins_url }}/images/no.png" alt="{{ lang['noa'] }}" title="{{ lang['noa'] }}"/>{% endif %}</td>
<td>{% if (entry.template == '') %}--{% else %}{{ entry.template }}{% endif %}</td>
<td><a href="admin.php?mod=news&amp;category={{ entry.id }}">{% if (entry.news == 0) %}--{% else %}{{ entry.news }}{% endif %}</a></td>
<td>{% if (flags.canModify) %}<a href="#" onclick="categoryModifyRequest('del', {{ entry.id }});"><img title="{{ lang['delete'] }}" alt="{{ lang['delete'] }}" src="{{ skins_url }}/images/delete.gif" /></a>{% endif %}</td>
</tr>
{% endfor %}