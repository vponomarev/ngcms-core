<!-- XFields :: Show images from field [ fieldName ] -->
<table width="100%">
{% for image in images %}
<tr style="background-color: #{% if (loop.index is odd) %}D0D0D0{% else %}F0F0F0{% endif %};">
 <td>{{image.number}}</td>
 {% if image.flags.exist %}<td><a href="{{ image.image.url }}" target="_blank">{% if image.flags.preview %}<img src="{{ image.preview.url }}" width="{{image.preview.width}}" height="{{image.preview.height}}"/>{% else %}NO PREVIEW{% endif %}</a><br/><label><input type="checkbox" value="1" name="xfields_{{image.id}}_del[{{image.image.id}}]">удалить</label></td>{% else %}
 <td colspan="2"><input type="file" name="xfields_{{image.id}}[]"/></td>{% endif %}
</tr>
{% endfor %}
</table>
<!-- /XFields [ fieldName ] -->
