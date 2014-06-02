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
	}, "text").error(function() { ngHideLoading(); ngNotifyWindow('HTTP error during request', 'ERROR'); });

}
</script>
{% endif %}
<div id="ngCalendarDiv">
	<div class="block calendar-block">
		<div class="block-title">Календарь</div>
		<table width="100%" cellspacing="0" cellpadding="0" id="calendar">
			<tr>
				<td>
					<table id="table3" cellspacing="0" cellpadding="0">
						<tr>
							<td id="month"><a href="{{ prevMonth.link }}" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'prev'); return false;" class="prev-month">«</a> &nbsp;&nbsp; <a href="{{ currentMonth.link }}">{{ currentMonth.name }} {{ currentEntry.year }}</a> &nbsp;&nbsp; <a href="{{ nextMonth.link }}" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'next'); return false;" class="next-month">»</a></td>
						</tr>
					</table>
					<table id="table2" cellspacing="0" cellpadding="0">
						<tr class="weeks">
							<td class="weekday"><span>{{ weekdays[1] }}</span></td>
							<td class="weekday"><span>{{ weekdays[2] }}</span></td>
							<td class="weekday"><span>{{ weekdays[3] }}</span></td>
							<td class="weekday"><span>{{ weekdays[4] }}</span></td>
							<td class="weekday"><span>{{ weekdays[5] }}</span></td>
							<td class="weekend"><span>{{ weekdays[6] }}</span></td>
							<td class="weekend"><span>{{ weekdays[7] }}</span></td>
						</tr>
						{% for week in weeks %}
						<tr>
							<td class="{{ week[1].className }}">{% if (week[1].countNews>0) %}<a href="{{ week[1].link }}">{{ week[1].dayNo}}</a>{% else %}<span>{{ week[1].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[2].className }}">{% if (week[2].countNews>0) %}<a href="{{ week[2].link }}">{{ week[2].dayNo}}</a>{% else %}<span>{{ week[2].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[3].className }}">{% if (week[3].countNews>0) %}<a href="{{ week[3].link }}">{{ week[3].dayNo}}</a>{% else %}<span>{{ week[3].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[4].className }}">{% if (week[4].countNews>0) %}<a href="{{ week[4].link }}">{{ week[4].dayNo}}</a>{% else %}<span>{{ week[4].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[5].className }}">{% if (week[5].countNews>0) %}<a href="{{ week[5].link }}">{{ week[5].dayNo}}</a>{% else %}<span>{{ week[5].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[6].className }}">{% if (week[6].countNews>0) %}<a href="{{ week[6].link }}">{{ week[6].dayNo}}</a>{% else %}<span>{{ week[6].dayNo }}</span>{% endif %}</td>
							<td class="{{ week[7].className }}">{% if (week[7].countNews>0) %}<a href="{{ week[7].link }}">{{ week[7].dayNo}}</a>{% else %}<span>{{ week[7].dayNo }}</span>{% endif %}</td>
						</tr>
						{% endfor %}
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>