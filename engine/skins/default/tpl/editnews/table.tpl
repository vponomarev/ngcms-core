<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<script language="javascript">

// ************************************************************************************ //
// Suggest helper (c) Vitaly Ponomarev (vp7@mail.ru)                                    //
// Specially developed for NGCMS ( http://ngcms.ru/ ), but can be used anywhere else    //
// ************************************************************************************ //

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
	
	if (!this.opts.iMinLen)			this.opts.iMinLen	= 2;		// input: minimal len for search
	if (!this.opts.sId)				this.opts.sId		= null;		// search: search DIV element ID
	if (!this.opts.sClass)			this.opts.sClass	= '';		// search: search DIV element class
	if (!this.opts.stId)			this.opts.stId		= null;		// search table: ID
	if (!this.opts.stClass)			this.opts.stClass	= '';		// search table: class for search table
	if (!this.opts.stRClass)		this.opts.stRClass	= '';		// search table: class for normal row
	if (!this.opts.stRHClass)		this.opts.stHRClass	= '';		// search table: class for HIGHLIGHTED row
	if (!this.opts.stCols)			this.opts.stCols	= 1;		// number of columns in returning result
	if (!this.opts.stColsClass)		this.opts.stColsClass = [];		// list of classes (1 by one) that should be applied to cols
	if (!this.opts.stColsHLR)		this.opts.stColsHLR	= [];		// list of flags: do we need highlighing for this col
	if (!this.opts.hlr)				this.opts.hlr 		= false;	// should we manually HighLight Results

	if (!this.opts.lId)				this.opts.lId		= null;		// ID of loading layer
	if (!this.opts.delay)			this.opts.delay		= 500;		// Delay before making AJAX request (ms)

	if (!this.opts.cId)				this.opts.cId		= null;		// `CLOSE SUGGEST` element ID
	
	// Save link to our object
	var pointer = this;
	
	// Now let's init search DIV
	this.searchDIV = this.opts.sId ? document.getElementById(this.opts.sId) : false;
	if (!this.searchDIV)
		this.searchDIV = document.createElement('div');
	if (this.opts.sClass)
		this.searchDIV.className = this.opts.sClass;
	
	// Let's init search TABLE
	this.searchTBL = this.opts.stId ? document.getElementById(this.opts.stId) : false;
	if (!this.searchTBL)
		this.searchTBL = document.createElement('table');
	if (this.opts.stClass)
		this.searchTBL.className = this.opts.stClass;

	// Let's init loader
	this.loader = (this.opts.lId && document.getElementById(this.opts.lId)) ? document.getElementById(this.opts.lId) : null;
		
	// Now let's setup correctly DIV position
	this.searchDIV.style.left	= findPosX(this.field)+'px';
	this.searchDIV.style.top	= (findPosY(this.field)+this.field.clientHeight+4)+'px';	
		
	this.field.onkeypress 	= function(event){ return pointer.onKeyPress(event); }
	this.field.onkeyup 		= function(event){ return pointer.onKeyUp(event); }
	
	// Initiate `CLOSE SUGGEST` event handler
	if (this.opts.cId) {
		var closeElement = document.getElementById(this.opts.cId);
		if (closeElement) {
			closeElement.onclick = function() { pointer.suggestShow(false); return false; }
		}
	}
	
	this.debugDIV = document.createElement('div');
	this.debugDIV.style.position = 'absolute';
	this.debugDIV.style.width = '150px';
	this.debugDIV.style.height = '100px';
	this.debugDIV.style.right = '10px';
	this.debugDIV.style.background = 'white';
	this.debugDIV.style.display = 'block';
	document.body.appendChild(this.debugDIV);
	
	return this;
}


ngSuggest.prototype.focusField = function() { this.field.focus(); }

ngSuggest.prototype.onKeyUp = function(event) {
	var key = event.keyCode;
	
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
	var key = event.keyCode;
	
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

ngSuggest.prototype.setValue = function(value) { this.field.value = value; }

// perform AJAX request
ngSuggest.prototype.callAJAX = function() {
	var pointer = this;

	var linkTX = new sack();
	linkTX.requestFile = 'rpc.php';
	linkTX.setVar('json', '1');
	linkTX.setVar('methodName', 'admin.users.search');
	linkTX.setVar('params', json_encode(this.searchDest));
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

			// Clear
			sB.innerHTML = '';

			// Exit if number of found records < 1
			if (sReturn.length < 1) {
				pointer.suggestShow(false);
				return;
			}
			
			for (var i = 0; i < sReturn.length; i++) {
				var r = sB.insertRow(-1);
				r.onclick = function() { pointer.setValue(this.dataOutput); pointer.focusField(); pointer.suggestShow(false); }
				r.onmouseover = function() { pointer.highlightSet(this.dataId); }
				r.dataOutput = sReturn[i][0];
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
		alert('TX.fail: HTTP code '+linkTX.responseStatus[0]);
		}	
	}
	this.loaderShow(true);
	linkTX.runAJAX();
}

// **********************************************************************************

							
function addEvent(elem, type, handler){
  if (elem.addEventListener){
    elem.addEventListener(type, handler, false)
  } else {
    elem.attachEvent("on"+type, handler)
  }
} 

// Hide menu on ESC (global event)
function onKey(event){
 if ((event.keyCode == 27) && (document.getElementById('suggestWindow').style.visibility=='visible')) {
	suggestHideLoader();
	suggestHideWindow();
 }
}  

// Find object's position (X)
function findPosX(obj) {
    var curleft = 0;
    if (obj.offsetParent) {
        while (1) {
            curleft+=obj.offsetLeft;
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
function findPosY(obj) {
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

// Author name [key up event]

function anKeyUp(event) {

	var ARRUP = 38;
	var ARRDN = 40;
	var TAB   = 9;
	var ESC   = 27;

	switch(event.keyCode) {
		case ESC:	return;
		case ARRUP: alert('UP'); break;
		case ARRDN: alert('DN'); break;
		case TAB:	alert('TAB'); break;
	}

	var dInput = document.getElementById('an');
	suggestSearch(dInput.value);
}

function swClick(t) { 
	var s = '';
	var cnt = 0;
	for (i in t) { cnt++; if (cnt > 40) s = s+ i+': '+t[i]+'\n'; }
	alert(t.target.parentNode.dataValue);
	if ((t.target.tagName == 'TD') && (t.target.parentNode.tagName == 'TR')) {
		var dc = t.target.parentNode;
		document.getElementById('an').value = dc['cells'][0].innerHTML;
		suggestHideWindow();
	} else {
//		alert(t.target.tagName);
//		alert(t.target.parentNode.tagName);
//		alert(s); 
	}
}

</script>

<!-- Hidden SUGGEST div -->
<div id="suggestWindow" style="position:absolute; top: 0px; left: 0px;">
<table id="suggestBlock" cellspacing="0" cellpadding="0" width="100%"></table>
<a href="#" align="right" id="suggestClose">close</a>
</div>


<form action="{php_self}?mod=editnews" method="POST" name="options_bar">
<table border="0" width="100%" cellspacing="2" cellpadding="2" align="center" class="contentNav">
<tr>
<td>

<!-- Блок фильтрации -->
<table border="0" cellspacing="2" cellpadding="2">
<td  valign="top">Поиск:</td>
<td><input name="sl" type="text" size="40" value="{sl}"/> <select name="st"><option value="0" {st.selected0}>заголовок</option><option value="1" {st.selected1}>текст</option></select></td>
<td rowspan="2" width="3px" style="background-image: url({skins_url}/images/delim.png);  background-repeat: repeat-y;">&nbsp;</td>
<td valign="top">Дата с:</td>
<td><input type="text" name="dr1" value="{dr1}" size="10"/> по <input type="text" name="dr2" value="{dr2}" size="10"/></td>
<td rowspan="3" width="5" style="background-image: url({skins_url}/images/delim.png); background-repeat: repeat-y;">&nbsp;</td>
<td>Статус:</td>
<td valign="top"><select name="status" size="1"><option value="">{l_smode_all}</option>{statuslist}</select> &nbsp;</td>
</tr>

<tr>
<td valign="top">{l_author}:</td><td><input name="an" id="an" type="text" size="25" value="{an}"autocomplete="off" /> <span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{skins_url}/images/loading.gif"/></span></td>
<td>{l_category}:</td>
<td>{category_select}</td>
<td>На странице:</td>
<td><input style="text-align: center" name="rpp" value="{rpp}" type="text" size="3" /></td>
</tr>

<tr>
<td colspan="5">
<input type="submit" value="{l_do_show}" class="button" style="width: 303px;" />
</td>
<td>{l_sort}</td><td><select name="sort">{sortlist}</select></td>
</tr>

</td>
</tr>
</table>

</td>
</tr>
</table>
</form>
<!-- Конец блока фильтрации -->

<br />
<!-- List of news start here -->
<form action="{php_self}?mod=editnews" method="post" name="editnews">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left">
<td width="5%" class="contentHead" nowrap>{l_postid_short}</td>
<td width="10%" class="contentHead" nowrap>{l_date}</td>
<td width="45%" class="contentHead">{l_title}</td>
[comments]<td width="10%" class="contentHead">{l_listhead.comments}</td>[/comments]
<td width="25%" class="contentHead">{l_category}</td>
<td width="10%" class="contentHead">{l_author}</td>
<td width="5%" class="contentHead">&nbsp;</td>
<td width="5%" class="contentHead"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(editnews)" /></td>
</tr>
[no-news]<tr><td colspan="6"><p>- {l_not_found} -</p></td></tr>[/no-news]
{entries}
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>

[actions]
<tr align="center">
<td colspan="8" class="contentEdit" align="right" valign="top">
<div style="text-align: left;">
Действие: <select name="subaction" style="font: 12px Verdana, Courier, Arial; width: 230px;">
<option value="">-- {l_action} --</option>
<option value="do_mass_approve">{l_approve}</option>
<option value="do_mass_forbidden">{l_forbidden}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
<option value="do_mass_mainpage">{l_massmainpage}</option>
<option value="do_mass_unmainpage">{l_massunmainpage}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
<option value="do_mass_currdate">{l_modify.mass.currdate}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>
[comments]<option value="do_mass_com_approve">{l_com_approve}</option>
<option value="do_mass_com_forbidden">{l_com_forbidden}</option>
<option value="" style="background-color: #E0E0E0;" disabled="disabled">===================</option>[/comments]
<option value="do_mass_delete">{l_delete}</option>
</select>
<input type="submit" value="Выполнить.." class="button" />
<input type="hidden" name="mod" value="editnews" />
<br/>
</div>
</td>
</tr>
<tr>
<td width="100%" colspan="8">&nbsp;</td>
</tr>
[/actions]
<tr>
<td align="center" colspan="8" class="contentHead">{pagesss}</td>
</tr>
</table>
</form>

<script language="javascript">
/*
// ** Init suggesting mechanism **

// Register global `keydown` event
//addEvent(document, 'keydown', onKey);
//suggestInitWindow();
*/

// INIT NEW SUGGEST LIBRARY
var aSuggest = new ngSuggest('an', 
								{ 
									'sId'		: 'suggestWindow', 
									'stId'		: 'suggestBlock',
									'lId'		: 'suggestLoader',
									'hlr'		: 'true',
									'iMinLen'	: 1,
									'stCols'	: 2,
									'stColsClass': [ 'cleft', 'cright' ],
									'stColsHLR'	: [ true, false ],
									'cId'		: 'suggestClose',
									'stRHClass'	: 'trHL'
								}
							);





</script>