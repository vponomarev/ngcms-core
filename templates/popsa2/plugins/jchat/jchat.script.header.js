<
script
language = "javascript" >
	function chatSubmitForm() {
		var formID = document.getElementById('jChatForm');
		CHATTER.postMessage(formID.name.value, formID.text.value);
	}

function jChat(maxRows, refresh, tableID, msgOrder) {
	var thisObject = this;

	this.init = function (maxRows, refresh, tableID, msgOrder) {
		this.timerInterval = ((refresh < 5) ? 5 : refresh) * 1000;
		this.timerActive = false;
		this.scanActive = false;
		this.timerID = 0;
		this.tickCount = 0;
		this.lastEventID = 0;
		this.maxLoadedID = 0;
		this.idleStart = 0;
		this.winMode = 0;
		this.messageOrder = msgOrder;

		this.maxRows = maxRows ? maxRows : 40;
		this.tableRef = document.getElementById(tableID);
		this.fatalError = (this.tableRef == null) ? true : false;
		this.linkTX = new sack();
		this.linkRX = new sack();
		this.linkRX.onComplete = function () {
			if (this.responseStatus[0] != "200")
				return;

			var data = eval(this.response);

			if (typeof(data) == 'object')
				thisObject.loadData(data);
		}
		if (!this.fatalError) {
			while (this.tableRef.rows.length) this.tableRef.deleteRow(-1);
		} else {
			alert('fatal error:' + tableID);
		}
		return this.fatalError;
	}

	//
	this.timerStart = function () {
		this.timerActive = true;
		this.scanActive = true;
		dateTime = new Date();
		thisObject.idleStart = Math.round(dateTime.getTime() / 1000);
		thisObject.timerID = setInterval(
			function () {
				thisObject.tickCount++;
				//document.getElementById('timerDebug').innerHTML = thisObject.tickCount;
				if (thisObject.scanActive) {
					dateTime = new Date();
					thisObject.linkRX.requestFile = '{link_show}';
					//thisObject.linkRX.setVar('plugin_cmd', 'show');
					thisObject.linkRX.setVar('lastEvent', thisObject.lastEventID);
					thisObject.linkRX.setVar('start', thisObject.maxLoadedID);
					thisObject.linkRX.setVar('win', thisObject.winMode);
					thisObject.linkRX.setVar('timer', thisObject.timerInterval / 1000);
					thisObject.linkRX.setVar('idle', Math.round((dateTime.getTime() / 1000) - thisObject.idleStart));
					thisObject.linkRX.method = 'GET';
					thisObject.linkRX.runAJAX();
				}

			}, this.timerInterval);
	}

	//
	this.timerStop = function () {
		this.timerActive = false;
		clearInterval(this.timerID);
	}

	//
	this.timerRestart = function () {
		this.timerStop();
		this.timerStart();
	}

	//
	this.loadData = function (bundle) {
		if (this.fatalError)
			return false;

		// Extract passed commands
		var cmdList = bundle[0];
		var cmdLen = cmdList.length;
		for (var i = 0; i < cmdLen; i++) {
			var cmd = cmdList[i];
			if (cmd[0] == 'settimer') {
				this.timerInterval = cmd[1] * 1000;
				// alert('new timer interval: '+this.timerInterval);
				this.timerRestart();
			}
			if (cmd[0] == 'reload') {
				document.location = document.location;
				return;
			}
			if (cmd[0] == 'stop') {
				this.timerStop();
			}
			if (cmd[0] == 'clear') {
				while (this.tableRef.rows.length) {
					this.tableRef.deleteRow(0);
				}
				thisObject.maxLoadedID = 0;
			}
			if (cmd[0] == 'setLastEvent') {
				this.lastEventID = cmd[1];
			}
			if (cmd[0] == 'setWinMode') {
				this.winMode = cmd[1];
			}
		}

		// Extract passed data
		var data = bundle[1];

		// Add rows
		var len = data.length;
		var loadedRows = 0;
		var lastRow = this.tableRef.rows.length;
		for (var i = 0; i < len; i++) {
			var rec = data[i];

			// Skip already loaded data
			if (thisObject.maxLoadedID >= rec['id']) {
				//alert('DUP: '+thisObject.maxLoadedID+' >= '+rec['id']);
				continue;
			}
			loadedRows++;

			var row = this.tableRef.insertRow(this.messageOrder ? 0 : lastRow);
			row.className = ((rec['id'] % 2) == 0) ? 'jchat_ODD' : 'jchat_EVEN';
			lastRow++;

			var cell = row.insertCell(0);

			// **                                                                  **
			// ** NOTIFICATION FOR ADMIN                                           **
			// ** YOU CAN MAKE CHANGES IN THIS LINE TO CHANGE VIEW OF jChat RECORD **
			// **                                                                  **
			cell.innerHTML =
				// 1. Floating DIV with add date/time
				'<div style="float: right; font-size: 75%;" title="' + rec['datetime'] + '">' + rec['time'] + '</div>' +
				// 2. Image to identify registered user. Also it will contain external link in case if uprofile plugin is enabled
				((rec['author_id'] > 0) ? ('[isplugin uprofile]<a target="_blank" href="' + rec['profile_link'] + '">[/isplugin]<img src="{skins_url}/images/profile.png" width="13" height="13" border="0"/>[isplugin uprofile]</a>[/isplugin] ') : '') +
				// 3. Author's name [ BOLD ]
				'<span class="jchat_userName">' + rec['author'] + '</span>' +
				// 4. DELETE button (for admins)
				'[is.admin] <img src="{skins_url}/images/delete.gif" alt="x" style="cursor: pointer;" onclick="CHATTER.deleteMessage(' + rec['id'] + ');"/>[/is.admin]' +
				// 5. New line delimiter
				'<br/> ' +
				// 6. Chat message test
				rec['text'];

			thisObject.maxLoadedID = rec['id'];
		}
		if (loadedRows > 0) {
			// Clear old rows from chat [ if needed ]
			while (thisObject.tableRef.rows.length > thisObject.maxRows)
				thisObject.tableRef.deleteRow(this.messageOrder ? thisObject.tableRef.rows.length - 1 : 0);

			thisObject.tableRef.parentNode.scrollTop = thisObject.tableRef.parentNode.scrollHeight;
		}
	}

	//
	this.addMessage = function (msg, className) {
		if (this.fatalError)
			return false;

		var lastRow = this.tableRef.rows.length;
		var row = this.tableRef.insertRow(this.messageOrder ? 0 : lastRow);
		row.className = className;

		var cell = row.insertCell(0);
		cell.innerHTML = msg;
		this.tableRef.parentNode.scrollTop = this.tableRef.parentNode.scrollHeight;

	}

	// POST new message
	this.postMessage = function (name, text) {
		var TX = this.linkTX;
		var sButton = document.getElementById('jChatSubmit');
		if (sButton != null)
			sButton.disabled = true;

		TX.requestFile = '{link_add}';
		//TX.setVar('plugin_cmd', 'add');
		[not - logged]
		TX.setVar('name', name);
		[ / not - logged
		]
		TX.setVar('lastEvent', this.lastEventID);
		TX.setVar('win', this.winMode);
		TX.setVar('start', this.maxLoadedID);
		TX.setVar('text', text);
		TX.method = 'POST';
		TX.onComplete = function () {
			var data = eval('(' + this.response + ')');

			if (typeof(data) == 'object') {
				if (data['status']) {
					thisObject.addMessage('<i>message posted</i>', 'jchat_INFO');
					var sText = document.getElementById('jChatText');
					if (sText != null)
						sText.value = '';
				} else {
					thisObject.addMessage('<i>ERROR: <b>' + data['error'] + '</b></i>', 'jchat_INFO');
				}
				if (typeof(data['bundle']) == 'object') {
					thisObject.loadData(data['bundle']);
				}
			} else {
				thisObject.addMessage('<i><b>Bad reply from server</b></i>', 'jchat_INFO');
			}
			var sButton = document.getElementById('jChatSubmit');
			if (sButton != null)
				sButton.disabled = false;

		}
		TX.runAJAX();

		// Restart idle timer
		dateTime = new Date();
		thisObject.idleStart = Math.round(dateTime.getTime() / 1000);

		// Restart scanner if it's turned off
		if (!this.timerActive)
			this.timerStart();
	}

	// DELETE message
	this.deleteMessage = function (id) {
		var TX = this.linkTX;

		TX.requestFile = '{link_del}';
		//TX.setVar('plugin_cmd', 'add');
		TX.setVar('id', id);
		TX.setVar('lastEvent', this.lastEventID);
		TX.setVar('win', this.winMode);
		TX.method = 'POST';
		TX.onComplete = function () {
			var data = eval('(' + this.response + ')');

			if (typeof(data) == 'object') {
				if (!data['status']) {
					thisObject.addMessage('<i>ERROR: <b>' + data['error'] + '</b></i>', 'jchat_INFO');
				}
				if (typeof(data['bundle']) == 'object') {
					thisObject.loadData(data['bundle']);
				}
			} else {
				thisObject.addMessage('<i><b>Bad reply from server</b></i>', 'jchat_INFO');
			}
		}
		TX.runAJAX();

		// Restart idle timer
		dateTime = new Date();
		thisObject.idleStart = Math.round(dateTime.getTime() / 1000);

		// Restart scanner if it's turned off
		if (!this.timerActive)
			this.timerStart();
	}


	this.init(maxRows, refresh, tableID, msgOrder);
}

function jchatCalculateMaxLen(oId, tName, maxLen) {
	var delta = maxLen - oId.value.length;
	var tId = document.getElementById(tName);

	if (tId) {
		tId.innerHTML = delta;
		tId.style.color = (delta > 0) ? 'black' : 'red';
	}
}

function jchatProcessAreaClick(event) {
	var evt = event ? event : window.event;
	if (!evt) return;
	var trg = evt.target ? evt.target : evt.srcElement;
	if (!trg) return;
	if (trg.className != 'jchat_userName') return;
	var mText = document.getElementById('jChatText');
	if (mText) {
		mText.value += '@' + trg.innerHTML + ': ';
		mText.focus();
	} else {
		alert('Cannot add this nickname, sorry.');
	}
}

</
script >
