<form name="form" method="POST" action="{{ php_self }}?action=delete" style="display: inline;">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['pm:inbox'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="5"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
						<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
						<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table class="pm" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="20%" class="pm_head">{{ lang['pm:date'] }}</td>
					<td width="35%" class="pm_head">{{ lang['pm:subject'] }}</td>
					<td width="25%" class="pm_head">{{ lang['pm:from'] }}</td>
					<td width="15%" class="pm_head">{{ lang['pm:state'] }}</td>
					<td width="5%" class="pm_head">
						<input type="checkbox" name="master_box" title="{{ lang['pm:checkall'] }}" onclick="javascript:check_uncheck_all(form)">
					</td>
				</tr>
				{% for entry in entries %}
					<tr align="center">
						<td class="pm_list">{{ entry.pmdate|date('Y-m-d H:i') }}</td>
						<td class="pm_list">
							<a href="{{ php_self }}?action=read&pmid={{ entry.pmid }}&location=inbox">{{ entry.subject }}</a>
						</td>
						<td class="pm_list">{{ entry.link }}</td>
						<td class="pm_list">{% if (entry.viewed == 1) %}
								<img src="/engine/plugins/pm/img/viewed.yes.gif"/>{% else %}
								<img src="/engine/plugins/pm/img/viewed.no.gif"/>{% endif %}</td>
						<td class="pm_list"><input name="selected_pm[]" value="{{ entry.pmid }}" type="checkbox"/></td>
					</tr>
				{% endfor %}
			</table>
			<div class="paginator" style="margin: 10px 0;">
				<ul>
					{{ pagination }}
				</ul>
			</div>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{{ lang['pm:delete'] }}">
</form>
<form name="pm" method="POST" action="{{ php_self }}?action=write" style="display: inline;">
	<input class="btn" type="submit" value="{{ lang['pm:write'] }}">
</form>
</td>
</tr>
</table>
</p>
</div>
</div>