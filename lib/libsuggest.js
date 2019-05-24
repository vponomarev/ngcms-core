// ************************************************************************************ //
// Suggest helper (c) Vitaly Ponomarev (vp7@mail.ru)                                    //
// Specially developed for NGCMS ( http://ngcms.ru/ ), but can be used anywhere else    //
// Build: 08 ( 2014-09-22)                                                              //
// ************************************************************************************ //
//
var ngSuggest = function(fieldID, params) {

	// Check for DOM
	if (!document.getElementById)
		return false;

	// Get field
	this.field = document.getElementById(fieldID);
	if (!this.field)
		return false;

	// Init internal variables
	this.searchDest		= '';
	this.iLen			= 0;
	this.sHighlighted	= 0;
	this.sValue			= '';
	this.sCount			= 0;
	this.sList			= [];

	// Init parameters
	this.opts = params ? params : {};

	if (!this.opts.localPrefix)		this.opts.localPrefix	= '';						// URL prefix for system location
	if (!this.opts.postURL)			this.opts.postURL	= this.opts.localPrefix + '/engine/rpc.php';		// URL where to send POST request (relative)
	if (!this.opts.iMinLen)			this.opts.iMinLen	= 2;						// input: minimal len for search
	if (!this.opts.sId)				this.opts.sId		= null;						// search: search DIV element ID
	if (!this.opts.sClass)			this.opts.sClass	= 'suggestWindow';			// search: search DIV element class
	if (!this.opts.stId)			this.opts.stId		= null;						// search table: ID
	if (!this.opts.stClass)			this.opts.stClass	= 'suggestBlock';			// search table: class for search table
	if (!this.opts.stRClass)		this.opts.stRClass	= 'suggestRowNormal';		// search table: class for normal row
	if (!this.opts.stRHClass)		this.opts.stRHClass	= 'suggestRowHighlight';	// search table: class for HIGHLIGHTED row
	if (!this.opts.stCols)			this.opts.stCols	= 1;						// number of columns in returning result
	if (!this.opts.stColsClass)		this.opts.stColsClass = [];						// list of classes (1 by one) that should be applied to cols
	if (!this.opts.stColsHLR)		this.opts.stColsHLR	= [];						// list of flags: do we need highlighing for this col
	if (!this.opts.hlr)				this.opts.hlr 		= false;					// should we manually HighLight Results
	if (!this.opts.reqMethodName)		this.opts.reqMethodName = 'dumb.ping';		// AJAX RPC Request method name

	if (!this.opts.lId)				this.opts.lId		= null;						// ID of loading layer
	if (!this.opts.delay)			this.opts.delay		= 500;						// Delay before making AJAX request (ms)

	if (!this.opts.cId)				this.opts.cId		= null;						// `CLOSE SUGGEST` element ID
	if (!this.opts.cClass)			this.opts.cClass	= 'suggestClose';			// `CLOSE SUGGEST` class

	if (!this.opts.listDelimiter)	this.opts.listDelimiter	= null;					// Delimiter for list of values. Will be used if specified, AJAX query will be made for current edited value
	if (!this.opts.columnReturn)	this.opts.columnReturn = 0; 					// Number of column to return

	if (!this.opts.outputGenerator)
		this.outputGenerator = function(obj) {
			var searchLine;
			if (obj.opts.listDelimiter) {
				searchLine = obj.searchDest.split(obj.opts.listDelimiter).pop().replace(/^\s+|\s+$/g,'');
				//alert('Call: '+searchLine);
			} else {
				searchLine = obj.searchDest;
			}

			return json_encode(searchLine);
		}
	else
		this.outputGenerator = this.opts.outputGenerator;

	// Save link to our object
	var pointer = this;

	// Now let's init search DIV
	this.searchDIV = this.opts.sId ? document.getElementById(this.opts.sId) : false;
	if (!this.searchDIV) {
		this.searchDIV = document.createElement('div');

		//document.body.appendChild(this.searchDIV);
		var iDiv = document.createElement('div');
		iDiv.style.position = 'relative';
		iDiv.style.overflow='visible';

		this.field.parentNode.appendChild(iDiv);
		iDiv.appendChild(this.searchDIV);

	}
	if (this.opts.sClass)
		this.searchDIV.className = this.opts.sClass;

	// Let's init search TABLE
	this.searchTBL = this.opts.stId ? document.getElementById(this.opts.stId) : false;
	if (!this.searchTBL) {
		this.searchTBL = document.createElement('table');
		this.searchTBL.style.width	= '100%';
		this.searchTBL.cellSpacing = '0';
		this.searchTBL.cellPadding = '0';

		this.searchDIV.appendChild(this.searchTBL);
	}
	if (this.opts.stClass)
		this.searchTBL.className = this.opts.stClass;

	// Let's init loader
	this.loader = (this.opts.lId && document.getElementById(this.opts.lId)) ? document.getElementById(this.opts.lId) : null;

	// Now let's setup correctly DIV position
	//this.searchDIV.style.left	= this.DOMelementPosX(this.field)+'px';
	//this.searchDIV.style.top	= (this.DOMelementPosY(this.field)+this.field.clientHeight+4)+'px';
	this.searchDIV.style.left	= '0px';
	this.searchDIV.style.top	= '3px';

	this.field.onkeypress 	= function(event){ return pointer.onKeyPress(event); }
	this.field.onkeyup 		= function(event){ return pointer.onKeyUp(event); }

	// Initiate `CLOSE SUGGEST` event handler
	if (!this.opts.cId) {
		this.closeElement = document.createElement('A');
		this.closeElement.innerHTML = 'close';
        this.closeElement.className = 'suggestClose';

		this.searchDIV.appendChild(this.closeElement);
	} else {
		closeElement = document.getElementById(this.opts.cId);
	}

	if (this.opts.cClass)
		this.closeElement.className = this.opts.cClass;

	if (this.closeElement) {
		this.closeElement.onclick = function() { pointer.suggestShow(false); return false; }
	}

	this.debugDIV = document.createElement('div');
	this.debugDIV.style.position = 'absolute';
	this.debugDIV.style.width = '150px';
	this.debugDIV.style.height = '100px';
	this.debugDIV.style.right = '10px';
	this.debugDIV.style.background = 'white';
	this.debugDIV.style.display = 'block';

	//document.body.appendChild(this.debugDIV);
	return this;
}


ngSuggest.prototype.focusField = function() { this.field.focus(); }

ngSuggest.prototype.onKeyUp = function(event) {
	var key = window.event ? window.event.keyCode : event.keyCode;

	//alert('onKeyUp: '+key);
	// This allows to move between highlighted items using UP/DOWN keyboard keys
	var keyUP = 38;
	var keyDN = 40;

	// Flag if we should return this event to next processing stage
	var catchContinue = true;

	switch(key) {
		case keyUP:	this.highlightMove(-1);
					cathContinue = false;
					break;
		case keyDN: this.highlightMove( 1);
					catchContinue = false;
					break;
		default:	this.suggestSearch(this.field.value);
	}

	return catchContinue;
}

ngSuggest.prototype.onKeyPress = function(event) {
	var key = window.event ? window.event.keyCode : event.keyCode;

	//alert('onKeyPress: '+key);
	// This allows to move between highlighted items using UP/DOWN keyboard keys
	var keyENTER  = 13;
	var keyESCAPE = 27;
	var keyTAB    = 9;

	// Flag if we should return this event to next processing stage
	var catchContinue = true;

	switch(key) {
		case keyTAB:	if (this.hightlightAccept())
							catchContinue = false;
						break;
		case keyESCAPE: this.suggestClear();
						break;
		default:		this.suggestSearch(this.field.value);
	}

	return catchContinue;
}

ngSuggest.prototype.highlightClear = function () {
	if (this.sHighlighted > 0)
		this.searchTBL.rows[this.sHighlighted - 1].className = this.opts.stRClass;

}

ngSuggest.prototype.hightlightAccept = function() {
	if (this.sHighlighted > 0) {
		// TEMPORALLY DISABLE SUGGEST MECHANISM
		this.sValue = this.sList[this.sHighlighted - 1][0];
		this.sCount = 0;
		this.setValue(this.sList[this.sHighlighted - 1][0]);
		this.suggestShow(false);
		return true;
	}
	return false;
}

ngSuggest.prototype.highlightSet = function (pos) {
	this.highlightClear();
	this.sHighlighted = pos;
	if (this.sHighlighted > 0)
		this.searchTBL.rows[this.sHighlighted - 1].className = this.opts.stRHClass;
}

ngSuggest.prototype.highlightMove = function (pos) {
	var hNew = this.sHighlighted + pos;

	if (hNew > this.sCount)
		hNew = 0;

	if (hNew < 0)
		hNew = this.sCount;

	this.highlightSet(hNew);
}

ngSuggest.prototype.suggestShow = function(mode) {
	//window.status = 'suggestShow('+mode+')';
	this.searchDIV.style.visibility = mode? 'visible' : 'hidden';

	// Delete highlight info
	this.sHighlighted = 0;
}

ngSuggest.prototype.suggestStatus = function() {
	return (this.searchDIV.style.visibility == 'visible') ? true : false;
}

ngSuggest.prototype.loaderShow = function(mode) {
	if (this.loader) {
		//alert('loaderStatus => '+mode);
		this.loader.style.visibility = mode? 'visible' : 'hidden';
	}
}

// Search for suggest
ngSuggest.prototype.suggestSearch = function(text) {

	var pointer = this;
	//alert('runSearch: '+text);
	if (text.length < this.opts.iMinLen) {
		this.suggestShow(false);
		return;
	}

	// Nothing changed
	if (text == this.sValue) {
		// BUT - if suggest window is not showed now - let's show it
		if (!this.suggestStatus() && (this.sCount > 0))
			this.suggestShow(true);

		return;
	}

	// Prevent from running multiple times for the same data
	this.searchDest = text;

	// Call AJAX after timeout
	clearTimeout(this.timeoutID);
	this.timeoutID = setTimeout( function() { pointer.callAJAX() }, this.opts.delay);
}

ngSuggest.prototype.setValue = function(value) {
	// 1234567
	if (this.opts.listDelimiter) {
		var parts = this.field.value.split(this.opts.listDelimiter);
		parts.pop();
		parts.push(value);
		this.field.value = parts.join(this.opts.listDelimiter);
	} else {
		this.field.value = value;
	}
}

// perform AJAX request
ngSuggest.prototype.callAJAX = function() {
	var pointer = this;

	var linkTX = new sack();
	linkTX.requestFile = this.opts.postURL;
	linkTX.setVar('json', '1');
	linkTX.setVar('methodName', this.opts.reqMethodName);
//	linkTX.setVar('params', json_encode(this.searchDest));
	linkTX.setVar('params', this.outputGenerator(this));
	linkTX.method='POST';
	linkTX.onComplete = function() {
		pointer.loaderShow(false);
		if (linkTX.responseStatus[0] == 200) {
		var resTX;
		try {
			resTX = eval('('+linkTX.response+')');
		} catch (err) { alert('Error parsing JSON output. Result: '+linkTX.response); }

		// First - check error state
		if (!resTX['status']) {
			// Mark a row if recID is set
			if (resTX['recID'])
				document.getElementById('re.row.'+resTX['recID']).style.background = '#AAAAAA';
			// ERROR. Display it
			alert('Error ('+resTX['errorCode']+'): '+resTX['errorText']);
		} else {
			//alert('Request complete');

			var sInput	= resTX['data'][0];
			var sReturn	= resTX['data'][1];

			pointer.sValue = sInput;
			pointer.sCount = sReturn.length;
			pointer.sList  = sReturn;
			pointer.sHighlighted = 0;

			//var sReturn = resTX['data'];
			var sB = pointer.searchTBL;

			// Clear (remove all rows)
			while (sB.rows.length) { sB.deleteRow(0); }

			// Exit if number of found records < 1
			if (sReturn.length < 1) {
				pointer.suggestShow(false);
				return;
			}

			for (var i = 0; i < sReturn.length; i++) {
				var r = sB.insertRow(-1);
				r.onclick = function() { pointer.setValue(this.dataOutput); pointer.focusField(); pointer.suggestShow(false); }
				r.onmouseover = function() { pointer.highlightSet(this.dataId); }

				r.dataOutput = sReturn[i][pointer.opts.columnReturn];
				r.dataId = i+1;

				for (var fNo = 0; fNo < pointer.opts.stCols; fNo++) {
					var d = document.createElement('td');

					// Set className if needed
					if (pointer.opts.stColsClass[fNo])
						d.className = pointer.opts.stColsClass[fNo];

					// Make HighLightResults if needed
					if (pointer.opts.stColsHLR[fNo]) {
						var rE = new RegExp(sInput.replace(/([\|\!\[\]\^\$\(\)\{\}\+\=\?\.\*\\])/g, "\\$1"), 'i');
						var rV = sReturn[i][fNo].replace(rE, function (x) { return '<b>'+x+'</b>'; });
						d.innerHTML = rV;
					} else {
						d.innerHTML = sReturn[i][fNo];
					}
					r.appendChild(d);
				}
				/*
				var tl = document.createElement('td');
				tl.className = 'tl';
				if (pointer.opts.hlr) {
					var rE = new RegExp(sInput.replace(/([\|\!\[\]\^\$\(\)\{\}\+\=\?\.\*\\])/g, "\\$1"), 'i');
					var rV = sReturn[i][0].replace(rE, function (x) { return '<b>'+x+'</b>'; });
					//alert('E: '+rE+'; V: '+rV);
					tl.innerHTML = rV;
				} else {
					tl.innerHTML = sReturn[i][0];
				}
				var tr = document.createElement('td');
				tr.className = 'tr';
				tr.innerHTML = sReturn[i][1];

				r.appendChild(tl);
				r.appendChild(tr);
				*/
			}
			pointer.suggestShow(true);
		}
		} else {
		alert('TX.fail: HTTP code '+linkTX.responseStatus[0]+' for URL "'+linkTX.requestFile+'"');
		}
	}
	this.loaderShow(true);
	linkTX.runAJAX();
}

// ======================================
// DOM managment functions
// ======================================

// Find object's position (X)
ngSuggest.prototype.DOMelementPosX = function(obj) {
    var curleft = 0;
    if (obj.offsetParent) {
        while (1) {
	    var clo = curleft;
            curleft+=obj.offsetLeft;
	    //alert('CL: '+clo+' + '+obj.offsetLeft+'['+obj.offsetParent+'] = '+(clo+obj.offsetLeft)+' ('+curleft+')');
            if (!obj.offsetParent) {
                break;
            }
            obj=obj.offsetParent;
        }
    } else if (obj.x) {
        curleft+=obj.x;
    }
    return curleft;
}

// Find object's position (Y)
ngSuggest.prototype.DOMelementPosY = function(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
        while (1) {
            curtop+=obj.offsetTop;
            if (!obj.offsetParent) {
                break;
            }
            obj=obj.offsetParent;
        }
    } else if (obj.y) {
        curtop+=obj.y;
    }
    return curtop;
}
