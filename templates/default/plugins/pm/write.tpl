<script type="text/javascript" src="{{ admin_url }}/includes/js/libsuggest.js"></script>
<style>
.suggestWindow {
	background: #f9f9f9;
	border: 1px solid #efefef;
	width: 316px;
	position: absolute;
	display: block;
	visibility: hidden;
	padding: 0px;
	font: normal 12px  tahoma, sans-serif;
	top: 0px;
	margin: 0;
	left: 80px;
}
#suggestBlock {
	padding-top: 2px;
	padding-bottom: 2px;
	width: 100%;
	border: 0px;
}
#suggestBlock td {
	padding-left: 2px;
}
#suggestBlock tr {
	padding: 3px;
	padding-left: 8px;
	background: white;
}
#suggestBlock .suggestRowHighlight {
	background: #59a6ec;
	color: white;
	cursor: default;
}
#suggestBlock .cleft {
	padding-left: 5px;
}
#suggestBlock .cright {
	text-align: right;
	padding-right: 5px;
}
.suggestClose {
	display: block;
	text-align: right;
	font: normal 10px verdana, tahoma, sans-serif;
	background: #efefef;
	padding: 5px;
	cursor: pointer;
}
</style>
<form method=post name=form action="{{ php_self }}?action=send">
<div class="block-title">{{ lang['pm:new'] }}</div>
<table class="table table-striped table-bordered">
	<tr>
		<th colspan="2"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> | <a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> | <a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a></th>
	</tr>
	<tr>
		<td width="30%">{{ lang['pm:subject'] }}</td>
		<td width="70%"><input class="input" type="text"  name="title" tabindex="2" /></td>
	</tr>
	
	<tr>
		<td width="30%">{{ lang['pm:too'] }}<br /><small>{{ lang['pm:to'] }}</small></td>
		<td width="70%"><input class="input" type="text" name="to_username" id="to_username" tabindex="3" autocomplete="off" value="{{ username }}" /><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{{skins_url}}/images/loading.gif"/></span></td>
	</tr>
	<tr>
		<td width="100%" colspan="2">
			<div class="clearfix"></div>
			{{ quicktags }} {{ smilies }}
			<div class="clearfix"></div>
			<div class="label">
				<label></label>
				<textarea name="content" id="pm_content" style="width: 100%; height: 120px;" /></textarea>
				<br /><br /><input name="saveoutbox" type="checkbox"/> {{ lang['pm:saveoutbox'] }}
			</div>
		</td>
	</tr>
</table>
<div class="clearfix"></div>
<div class="label pull-right">
	<label class="default">&nbsp;</label>
	<input class="button" type="submit" value="{{ lang['pm:send'] }}" accesskey="s" />
</div>
</form>
<script language="javascript" type="text/javascript">
	function systemInit() {
		new ngSuggest('to_username',
			{
				'iMinLen' : 1,
				'stCols' : 1,
				'stColsClass': ['cleft'],
				'lId' : 'suggestLoader',
				'hlr' : 'true',
				'stColsHLR'	: [ true ],
				'reqMethodName' : 'pm_get_username',
			}
		);
	}
	if (document.body.attachEvent) {
		document.body.onload = systemInit;
	} else {
		systemInit();
	}
</script>