<form method="POST" action="{{ php_self }}?action=set">
<div class="block-title">{{ lang['pm:set'] }}</div>
<table class="table table-striped table-bordered">
	<tr>
		<th><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> | <a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> | <a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
	</tr>
	<tr align="center">
		<td><input type="checkbox" name="email" id="email" {{ checked }} /> {{ lang['pm:email_set'] }}</td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input type="submit" class="button" value="{{ lang['pm:send'] }}" /></form>
</div>