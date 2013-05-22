{% if (not flags.ajax) %}
<script type="text/javascript" language="javascript">
function ng_calendar_walk(month, year, offset) {
	$.post('/engine/rpc.php', { json : 1, methodName : 'plugin.calendar.show', rndval: new Date().getTime(), params : json_encode({ 'year' : year, 'offset' : offset, 'month' : month }) }, function(data) {
		// Try to decode incoming data
		try {
			resTX = eval('('+data+')');
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }
		if (!resTX['status']) {
			ngNotifyWindow('Error ['+resTX['errorCode']+']: '+resTX['errorText'], 'ERROR');
		} else {
			$('#ngCalendarDiv').html(resTX['data']);
		}
	}).error(function() { ngHideLoading(); ngNotifyWindow('HTTP error during request', 'ERROR'); });

}
</script>
{% endif %}
<div id="ngCalendarDiv">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td><img border="0" src="{{ tpl_url }}/images/2z_35.gif" width="7" height="36" /></td>
	<td style="background-image:url('{{ tpl_url }}/images/2z_36.gif');" width="100%">&nbsp;<b><font color="#FFFFFF">Календарь</font></b></td>
	<td><img border="0" src="{{ tpl_url }}/images/2z_38.gif" width="7" height="36" /></td>
	</tr>
	</table>
</td></tr>
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td style="background-image:url('{{ tpl_url }}/images/2z_56.gif');" width="7">&nbsp;</td>
	<td bgcolor="#FFFFFF">
	<div class="block_cal" align="left">
<table id="calendar" align="center">
<tr>
	<td class="month" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'prev'); return false;">[prev_link]&laquo;[/prev_link]</td>
	<td colspan="5" class="month"><a href="{{ currentMonth.link }}">{{ currentMonth.name }}</a></td>
	<td class="month" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'next'); return false;">[next_link]&raquo;[/next_link]</td>
</tr>
<tr>
	<td class="weekday">{{ weekdays[1] }}</td>
	<td class="weekday">{{ weekdays[2] }}</td>
	<td class="weekday">{{ weekdays[3] }}</td>
	<td class="weekday">{{ weekdays[4] }}</td>
	<td class="weekday">{{ weekdays[5] }}</td>
	<td class="weekend">{{ weekdays[6] }}</td>
	<td class="weekend">{{ weekdays[7] }}</td>
</tr>

{% for week in weeks %}
<tr>
	<td class="{{ week[1].className }}">{% if (week[1].countNews>0) %}<a href="{{ week[1].link }}">{{ week[1].dayNo}}</a>{% else %}{{ week[1].dayNo }}{% endif %}</td>
	<td class="{{ week[2].className }}">{% if (week[2].countNews>0) %}<a href="{{ week[2].link }}">{{ week[2].dayNo}}</a>{% else %}{{ week[2].dayNo }}{% endif %}</td>
	<td class="{{ week[3].className }}">{% if (week[3].countNews>0) %}<a href="{{ week[3].link }}">{{ week[3].dayNo}}</a>{% else %}{{ week[3].dayNo }}{% endif %}</td>
	<td class="{{ week[4].className }}">{% if (week[4].countNews>0) %}<a href="{{ week[4].link }}">{{ week[4].dayNo}}</a>{% else %}{{ week[4].dayNo }}{% endif %}</td>
	<td class="{{ week[5].className }}">{% if (week[5].countNews>0) %}<a href="{{ week[5].link }}">{{ week[5].dayNo}}</a>{% else %}{{ week[5].dayNo }}{% endif %}</td>
	<td class="{{ week[6].className }}">{% if (week[6].countNews>0) %}<a href="{{ week[6].link }}">{{ week[6].dayNo}}</a>{% else %}{{ week[6].dayNo }}{% endif %}</td>
	<td class="{{ week[7].className }}">{% if (week[7].countNews>0) %}<a href="{{ week[7].link }}">{{ week[7].dayNo}}</a>{% else %}{{ week[7].dayNo }}{% endif %}</td>
</tr>
{% endfor %}
</table>


</div></td>
	<td style="background-image:url('{{ tpl_url }}/images/2z_58.gif');" width="7">&nbsp;</td>
	</tr>
	</table>
</td></tr>
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
	<td><img border="0" src="{{ tpl_url }}/images/2z_60.gif" width="7" height="11" /></td>
	<td style="background-image:url('{{ tpl_url }}/images/2z_61.gif');" width="100%"></td>
	<td><img border="0" src="{{ tpl_url }}/images/2z_62.gif" width="7" height="11" /></td>
	</tr>
	</table>
</td></tr>
</table>
</div>