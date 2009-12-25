
function toggleSpoiler(s, shdr) {
 var mode = 0;

 for (var i=0; i<= s.childNodes.length; i++) {
 	var item = s.childNodes[i];

 	if (item.className == 'sp-body') {
 		mode = (item.style.display == 'block')?0:1;
		item.style.display = mode?'block':'none';
		break;
	}
 }	

 for (var i=0; i<= shdr.childNodes.length; i++) {
 	var item = shdr.childNodes[i];

 	if (item.tagName == 'B') {
 		item.className = (mode?'expanded':'');
 		break;
 	}
 }	
}


function addcat(){

	if (document.getElementById('categories').value != '' && document.getElementById('catmenu').value != '') {
		document.getElementById('categories').value = document.getElementById('categories').value + ", " + document.getElementById('catmenu').value;
	}
	else if (document.getElementById('catmenu').value != '') {
		document.getElementById('categories').value = document.getElementById('catmenu').value;
	}
	document.getElementById('catmenu').options[document.getElementById('catmenu').selectedIndex] = null;

	if (document.getElementById('catmenu').options.length == 0) {
		document.getElementById('catmenu').disabled = true;
		document.getElementById('catbutton').disabled = true;
	}
}

function ShowOrHide(d1, d2) {
if (d1 != '') DoDiv(d1);
if (d2 != '') DoDiv(d2);
}

function DoDiv(id) {
var item = null;
if (document.getElementById) {
item = document.getElementById(id);
} else if (document.all){
item = document.all[id];
} else if (document.layers){
item = document.layers[id];
}
if (!item) {
}
else if (item.style) {
if (item.style.display == "none"){ item.style.display = ""; }
else {item.style.display = "none"; }
}else{ item.visibility = "show"; }
}

function check_uncheck_all(area, prefix) {
	var frm = area;
	var p = (prefix)?prefix:'';
	for (var i=0; i<frm.elements.length; i++) {
		var e = frm.elements[i];
		if ((e.type == "checkbox") && (e.name != "master_box") && 
			((p.length == 0)||(e.name.substr(0,p.length) == p))
		) {
			e.checked = frm.master_box.checked ? true : false;
		}
	}
}

function showpreview(image,name){
if (image != ""){
document.images[name].src = image;
} else {
document.images[name].src = "skins/images/blank.gif";
}
}

function insertext(open, close, field){
        try {
        	msgfield = document.getElementById((field=='')?'content':field);
        } catch (err) {
        	return false;
        }	

	// IE support
	if (document.selection && document.selection.createRange){
		msgfield.focus();
		sel = document.selection.createRange();
		sel.text = open + sel.text + close;
		msgfield.focus();
	}
	// Moz support
	else if (msgfield.selectionStart || msgfield.selectionStart == "0"){
		var startPos = msgfield.selectionStart;
		var endPos = msgfield.selectionEnd;
		var scrollPos = msgfield.scrollTop;		

		msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
		msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
		msgfield.scrollTop = scrollPos;
		msgfield.focus();
	}
	// Fallback support for other browsers
	else {
		msgfield.value += open + close;
		msgfield.focus();
	}
	return true;
}

function setCookie(name, value){
document.cookie = name + "=" + value + "; path=/;" + " expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function deleteCookie(name){
document.cookie = name + "=" + "; path=/;" + " expires=Sut, 1 Jan 2000 00:00:01 GMT;";
}

function insertimage(text, area) {
	var win = window.opener;
	var form = win.document.forms['form'];
	try {
	 var xarea = win.document.forms['DATA_tmp_storage'].area.value;
	 if (xarea != '') area = xarea;
	} catch(err) {;}
	var control = (area == "short") ? form.contentshort : ( (area == "full")? form.contentfull : form.content );

	control.focus();

	// IE
	if (win.selection && win.selection.createRange){
		sel = win.selection.createRange();
		sel.text = text = sel.text;
	} else
	// Mozilla
	if (control.selectionStart || control.selectionStart == "0"){
		var startPos = control.selectionStart;
		var endPos = control.selectionEnd;

		control.value = control.value.substring(0, startPos) + text + control.value.substring(startPos, control.value.length);
		//control.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
	} else {
		control.value += text;
	}
	control.focus();
}

function quote(q_name) {

	txt = ''

	if (document.getSelection) {
		txt = document.getSelection()
	}
	else if (document.selection) {
		txt = document.selection.createRange().text;
	}

	if (txt == "") {
		insertext('[b',']'+q_name+'[/b], ', '')
	}
	if (txt.replace(" ","") != "") {
		insertext('[quote='+q_name,']'+txt+'[/quote]', '')
	}
}

function confirmit(url, text){
	var agree = confirm(text);

	if (agree) {
		document.location=url;
	}
}

function emailCheck (emailStr) {
var emailPat=/^(.+)@(.+)$/
var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
var validChars="\[^\\s" + specialChars + "\]"
var quotedUser="(\"[^\"]*\")"
var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
var atom=validChars + '+'
var word="(" + atom + "|" + quotedUser + ")"
var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")

var matchArray=emailStr.match(emailPat)
if (matchArray==null) {
	return false
}
var user=matchArray[1]
var domain=matchArray[2]

if (user.match(userPat)==null) {
    return false
}

var IPArray=domain.match(ipDomainPat)
if (IPArray!=null) {
	  for (var i=1;i<=4;i++) {
	    if (IPArray[i]>255) {
		return false
	    }
    }
    return true
}

var domainArray=domain.match(domainPat)
if (domainArray==null) {
    return false
}

var atomPat=new RegExp(atom,"g")
var domArr=domain.match(atomPat)
var len=domArr.length
if (domArr[domArr.length-1].length<2 || 
    domArr[domArr.length-1].length>3) {
   return false
}

if (len<2) {
   return false
}

return true;
}