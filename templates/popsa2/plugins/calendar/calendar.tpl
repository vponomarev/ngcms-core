{% if (not flags.ajax) %}
	<script type="text/javascript" language="javascript">
		function ng_calendar_walk(month, year, offset) {
			$.post('/engine/rpc.php', {
				json: 1,
				methodName: 'plugin.calendar.show',
				rndval: new Date().getTime(),
				params: json_encode({'year': year, 'offset': offset, 'month': month})
			}, function (data) {
				// Try to decode incoming data
				try {
					resTX = eval('(' + data + ')');
				} catch (err) {
					alert('Error parsing JSON output. Result: ' + linkTX.response);
				}
				if (!resTX['status']) {
					ngNotifyWindow('Error [' + resTX['errorCode'] + ']: ' + resTX['errorText'], 'ERROR');
				} else {
					$('#ngCalendarDiv').html(resTX['data']);
				}
			}, "text").error(function () {
				ngHideLoading();
				ngNotifyWindow('HTTP error during request', 'ERROR');
			});

		}
	</script>
{% endif %}
<div id="ngCalendarDiv">
	<table id="calendar" align="center" class="table">
		<tr>
			<td colspan="7" class="month">
				<a href="{{ prevMonth.link }}" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'prev'); return false;">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ currentMonth.link }}">{{ currentMonth.name }} {{ currentEntry.year }}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ nextMonth.link }}" onclick="ng_calendar_walk({{ currentEntry.month }}, {{ currentEntry.year }}, 'next'); return false;">&raquo;</a>
			</td>
		</tr>
		<tr>
			<th class="weekday">{{ weekdays[1] }}</th>
			<th class="weekday">{{ weekdays[2] }}</th>
			<th class="weekday">{{ weekdays[3] }}</th>
			<th class="weekday">{{ weekdays[4] }}</th>
			<th class="weekday">{{ weekdays[5] }}</th>
			<th class="weekend">{{ weekdays[6] }}</th>
			<th class="weekend">{{ weekdays[7] }}</th>
		</tr>
		{% for week in weeks %}
			<tr>
				<td class="{{ week[1].className }}">{% if (week[1].countNews>0) %}
						<a href="{{ week[1].link }}">{{ week[1].dayNo }}</a>{% else %}{{ week[1].dayNo }}{% endif %}
				</td>
				<td class="{{ week[2].className }}">{% if (week[2].countNews>0) %}
						<a href="{{ week[2].link }}">{{ week[2].dayNo }}</a>{% else %}{{ week[2].dayNo }}{% endif %}
				</td>
				<td class="{{ week[3].className }}">{% if (week[3].countNews>0) %}
						<a href="{{ week[3].link }}">{{ week[3].dayNo }}</a>{% else %}{{ week[3].dayNo }}{% endif %}
				</td>
				<td class="{{ week[4].className }}">{% if (week[4].countNews>0) %}
						<a href="{{ week[4].link }}">{{ week[4].dayNo }}</a>{% else %}{{ week[4].dayNo }}{% endif %}
				</td>
				<td class="{{ week[5].className }}">{% if (week[5].countNews>0) %}
						<a href="{{ week[5].link }}">{{ week[5].dayNo }}</a>{% else %}{{ week[5].dayNo }}{% endif %}
				</td>
				<td class="{{ week[6].className }}">{% if (week[6].countNews>0) %}
						<a href="{{ week[6].link }}">{{ week[6].dayNo }}</a>{% else %}{{ week[6].dayNo }}{% endif %}
				</td>
				<td class="{{ week[7].className }}">{% if (week[7].countNews>0) %}
						<a href="{{ week[7].link }}">{{ week[7].dayNo }}</a>{% else %}{{ week[7].dayNo }}{% endif %}
				</td>
			</tr>
		{% endfor %}
	</table>
</div>