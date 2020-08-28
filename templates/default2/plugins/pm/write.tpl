<script type="text/javascript" src="{{ scriptLibrary }}/libsuggest.js"></script>
<style>
	.suggestWindow {
		background: #ecf1f7;
		border: 1px solid #aaaaaa;
		color: #232323;
		width: 280px;
		position: absolute;
		display: block;
		visibility: hidden;
		padding: 0;
		font: normal 12px;
		top: 0px;
		margin-top: -10px;
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
		padding: 6px;
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
		font: normal 10px;
		background: #90c1d6;
		color: white;
		padding: 3px;
		cursor: pointer;
	}
</style>
<form method=post name=form action="{{ php_self }}?action=send">
	<div class="post">
		<div class="post-header">
			<div class="post-title">{{ lang['pm:new'] }}</div>
		</div>
		<div style="height: 10px;"></div>
		<div class="post-text">
			<p>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<th colspan="5"><a href="/plugin/pm/">{{ lang['pm:inbox'] }}</a> |
						<a href="/plugin/pm/?action=outbox">{{ lang['pm:outbox'] }}</a> |
						<a href="{{ php_self }}?action=set">{{ lang['pm:set'] }}</a></th>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="color: #696969;">{{ lang['pm:subject'] }}:</td>
					<td><input type="text" class="input" name="title" tabindex="2"/></td>
				</tr>
				<tr>
					<td style="color: #696969;">{{ lang['pm:too'] }}:<br/>
						<small>{{ lang['pm:to'] }}</small>
					</td>
					<td>
						<input type="text" class="input" name="to_username" id="to_username" tabindex="3" autocomplete="off" value="{{ username }}"/><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{{ skins_url }}/images/loading.gif"/></span>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 15px;">
						{{ quicktags }}{{ smilies }}
						<br/><textarea name="content" id="pm_content" style="width: 98%;" rows="8"/></textarea>
						<br/><br/><input name="saveoutbox" type="checkbox"/> {{ lang['pm:saveoutbox'] }}
					</td>
				</tr>
			</table>
			<div style="height: 10px;"></div>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="100%" valign="top">
						<input class="btn" type="submit" value="{{ lang['pm:send'] }}">
					</td>
				</tr>
			</table>
			</p>
		</div>
	</div>
</form>
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