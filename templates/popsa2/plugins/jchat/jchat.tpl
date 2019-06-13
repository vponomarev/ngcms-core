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
<h3>Чатик</h3>
<div class="l300_green_blue"></div>
<div class="block_cal" align="left">
	<div style="overflow: auto; height: 320px;" onclick="jchatProcessAreaClick(event);">
		<table id="jChatTable" class="table">
			<tr>
				<td>Loading chat...</td>
			</tr>
		</table>
	</div>
	[post-enabled]
	<div class="telo">
		<div class="clear10"></div>
		<form method="post" name="jChatForm" id="jChatForm" onsubmit="chatSubmitForm(); return false;">
			[not-logged]
			<input type="text" name="name" class="form_pad20" value="{l_jchat:input.username}" onfocus="if(!jChatInputUsernameDefault){this.value='';jChatInputUsernameDefault=1;}"/>
			<div class="clear10"></div>
			[/not-logged]
			<textarea id="jChatText" name="text" class="form_pad20" onfocus="jchatCalculateMaxLen(this,'jchatWLen', {maxlen});" onkeyup="jchatCalculateMaxLen(this,'jchatWLen', {maxlen});"></textarea>
			<small id="jchatWLen">Осталось знаков: <strong>{maxlen}</strong></small>
			[selfwin]&nbsp;&nbsp;<a target="_blank" href="{link_selfwin}">
				<small>Чат на отдельной странице</small>
			</a>
			<div class="clear10"></div>
			[/selfwin]
			<input id="jChatSubmit" class="btn btn-primary btn-large btn-block" type="submit" value="{l_jchat:button.post}"/>
			<div class="clear10"></div>
		</form>
	</div>
	[/post-enabled]
</div>
[:include jchat.script.footer.js]