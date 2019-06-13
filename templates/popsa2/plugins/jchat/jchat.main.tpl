<style>
	.jchat_ODD TD {
		background-color: #d8e4d2;
		width: 100%;
		text-align: left;
		padding: 5px 20px;
		font-size: 12px;
		border-bottom: 1px solid #e1e1e1;
	}

	.jchat_EVEN TD {
		width: 100%;
		text-align: left;
		padding: 5px 20px;
		font-size: 12px;
		border-bottom: 1px solid #e0e0e0;
	}

	.jchat_INFO TD {
		background-color: #FFFFFF;
		width: 100%;
		text-align: left;
		font: 10px arial;
		border-bottom: 1px solid #DDDDDD;
	}

	.jchat_userName {
		font-weight: bold;
		cursor: pointer;
	}
</style>
[:include jchat.script.header.js]
<div class="full">
	<h1>Чат-бокс</h1>
	<div class="pad20_f">
		<div style="overflow: auto; height: 400px;" onclick="jchatProcessAreaClick(event);">
			<table id="jChatTable" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>Loading chat...</td>
				</tr>
			</table>
		</div>
		<div class="clear10"></div>
		[post-enabled]
		<form method="post" name="jChatForm" id="jChatForm" onsubmit="chatSubmitForm(); return false;">
			[not-logged]
			<div>
				<input type="text" name="name" value="{l_jchat:input.username}" onfocus="if(!jChatInputUsernameDefault){this.value='';jChatInputUsernameDefault=1;}"/>
			</div>
			[/not-logged]
			<div>
				<textarea id="jChatText" name="text" style="width: 98%; height: 60px;" onfocus="jchatCalculateMaxLen(this,'jchatWLen', {maxlen});" onkeyup="jchatCalculateMaxLen(this,'jchatWLen', {maxlen});"></textarea>
			</div>
			<small id="jchatWLen">Осталось знаков: <strong>{maxlen}</strong></small>
			<div class="clear10"></div>
			<input id="jChatSubmit" type="submit" class="btn btn-primary btn-large" value="{l_jchat:button.post}"/>
		</form>
		<div class="clear20"></div>
		[/post-enabled]
	</div>
</div>
[:include jchat.script.footer.js]