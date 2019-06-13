<script type="text/javascript" src="{{ scriptLibrary }}/libsuggest.js"></script>
<style>
	.suggestWindow {
		background: #f6f8fb;
		border: 1px solid #aaaaaa;
		color: #232323;
		width: 274px;
		position: absolute;
		display: block;
		visibility: hidden;
		padding: 0px;
		font: normal 12px tahoma, sans-serif;
		top: 0px;
		margin: 0;
		left: 80px;
		position: absolute;
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
		background: #59a6ec url(images/1px.png) repeat-x;
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
		background: #3c9c08;
		color: white;
		padding: 3px;
		cursor: pointer;
	}
</style>
<form method=post name=form action="{{ php_self }}?action=send">
	<div class="full">
		<h1>{{ lang['pm:new'] }}</h1>
		<div class="pad20_f">
			<div class="btn-group">
				<a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
				<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
				<a href="{{ php_self }}?action=set" align="right">{{ lang['pm:set'] }}</a>
			</div>
			<div class="clear20"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="30%">{{ lang['pm:subject'] }}</td>
					<td width="70%"><input type="text" name="title" tabindex="2"/></td>
				</tr>
				<tr>
					<td width="30%">{{ lang['pm:too'] }}<br/>
						<small>{{ lang['pm:to'] }}</small>
					</td>
					<td width="70%">
						<input type="text" name="to_username" id="to_username" tabindex="3" autocomplete="off" value="{{ username }}"/><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{{ skins_url }}/images/loading.gif"/></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						{{ quicktags }}<br/>{{ smilies }}
						<div class="clear20"></div>
						<div>
							<textarea name="content" id="content" tabindex="1" class="textarea"/></textarea>
						</div>
						<div>
							<label>{{ lang['pm:saveoutbox'] }}
								&nbsp;&nbsp;<input name="saveoutbox" type="checkbox"/></label>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input class="btn" type="submit" value="{{ lang['pm:send'] }}">
</form>
<div class="clear20"></div>
</td>
</tr>
</table>
</div>
</div>
<script language="javascript" type="text/javascript">
	function systemInit() {
		new ngSuggest('to_username',
			{
				'iMinLen': 1,
				'stCols': 1,
				'stColsClass': ['cleft'],
				'lId': 'suggestLoader',
				'hlr': 'true',
				'stColsHLR': [true],
				'reqMethodName': 'pm_get_username',
			}
		);
	}
	if (document.body.attachEvent) {
		document.body.onload = systemInit;
	} else {
		systemInit();
	}
</script>