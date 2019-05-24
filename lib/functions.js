//
// Basic JS functions for NGCMS core
//


//
// Function from PHP to Javascript Project: php.js
// URL: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_json_encode/
function json_encode(mixed_val) {
	// http://kevin.vanzonneveld.net
	// +      original by: Public Domain (http://www.json.org/json2.js)
	// + reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// *     example 1: json_encode(['e', {pluribus: 'unum'}]);
	// *     returns 1: '[\n    "e",\n    {\n    "pluribus": "unum"\n}\n]'

	/*
	 http://www.JSON.org/json2.js
	 2008-11-19
	 Public Domain.
	 NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
	 See http://www.JSON.org/js.html
	 */

	var indent;
	var value = mixed_val;
	var i;

	var quote = function (string) {
		var escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
		var meta = {    // table of character substitutions
			'\b': '\\b',
			'\t': '\\t',
			'\n': '\\n',
			'\f': '\\f',
			'\r': '\\r',
			'"': '\\"',
			'\\': '\\\\'
		};

		escapable.lastIndex = 0;
		return escapable.test(string) ?
			'"' + string.replace(escapable, function (a) {
				var c = meta[a];
				return typeof c === 'string' ? c :
					'\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
			}) + '"' :
			'"' + string + '"';
	}

	var str = function (key, holder) {
		var gap = '';
		var indent = '    ';
		var i = 0;          // The loop counter.
		var k = '';          // The member key.
		var v = '';          // The member value.
		var length = 0;
		var mind = gap;
		var partial = [];
		var value = holder[key];

		// If the value has a toJSON method, call it to obtain a replacement value.
		if (value && typeof value === 'object' &&
			typeof value.toJSON === 'function') {
			value = value.toJSON(key);
		}

		// What happens next depends on the value's type.
		switch (typeof value) {
			case 'string':
				return quote(value);

			case 'number':
				// JSON numbers must be finite. Encode non-finite numbers as null.
				return isFinite(value) ? String(value) : 'null';

			case 'boolean':
			case 'null':
				// If the value is a boolean or null, convert it to a string. Note:
				// typeof null does not produce 'null'. The case is included here in
				// the remote chance that this gets fixed someday.

				return String(value);

			case 'object':
				// If the type is 'object', we might be dealing with an object or an array or
				// null.
				// Due to a specification blunder in ECMAScript, typeof null is 'object',
				// so watch out for that case.
				if (!value) {
					return 'null';
				}

				// Make an array to hold the partial results of stringifying this object value.
				gap += indent;
				partial = [];

				// Is the value an array?
				if (Object.prototype.toString.apply(value) === '[object Array]') {
					// The value is an array. Stringify every element. Use null as a placeholder
					// for non-JSON values.

					length = value.length;
					for (i = 0; i < length; i += 1) {
						partial[i] = str(i, value) || 'null';
					}

					// Join all of the elements together, separated with commas, and wrap them in
					// brackets.
					v = partial.length === 0 ? '[]' :
						gap ? '[\n' + gap +
							partial.join(',\n' + gap) + '\n' +
							mind + ']' :
							'[' + partial.join(',') + ']';
					gap = mind;
					return v;
				}

				// Iterate through all of the keys in the object.
				for (k in value) {
					if (Object.hasOwnProperty.call(value, k)) {
						v = str(k, value);
						if (v) {
							partial.push(quote(k) + (gap ? ': ' : ':') + v);
						}
					}
				}

				// Join all of the member texts together, separated with commas,
				// and wrap them in braces.
				v = partial.length === 0 ? '{}' :
					gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' +
						mind + '}' : '{' + partial.join(',') + '}';
				gap = mind;
				return v;
		}
		return null;
	};

	// Make a fake root object containing our value under the key of ''.
	// Return the result of stringifying the value.
	return str('', {
		'': value
	});
}


function toggleSpoiler(s, shdr) {
	var mode = 0;

	for (var i = 0; i <= s.childNodes.length; i++) {
		var item = s.childNodes[i];

		if (item.className == 'sp-body') {
			mode = (item.style.display == 'block') ? 0 : 1;
			item.style.display = mode ? 'block' : 'none';
			break;
		}
	}

	for (var i = 0; i <= shdr.childNodes.length; i++) {
		var item = shdr.childNodes[i];

		if (item.tagName == 'B') {
			item.className = (mode ? 'expanded' : '');
			break;
		}
	}
}


function addcat() {

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
	} else if (document.all) {
		item = document.all[id];
	} else if (document.layers) {
		item = document.layers[id];
	}
	if (!item) {
	}
	else if (item.style) {
		if (item.style.display == "none") {
			item.style.display = "";
		}
		else {
			item.style.display = "none";
		}
	} else {
		item.visibility = "show";
	}
}

function check_uncheck_all(area, prefix) {
	var frm = area;
	var p = (prefix) ? prefix : '';
	for (var i = 0; i < frm.elements.length; i++) {
		var e = frm.elements[i];
		if ((e.type == "checkbox") && (e.name != "master_box") &&
			((p.length == 0) || (e.name.substr(0, p.length) == p))
		) {
			e.checked = frm.master_box.checked ? true : false;
		}
	}
}

function showpreview(image, name) {
	if (image != "") {
		document.images[name].src = image;
	} else {
		document.images[name].src = "skins/images/blank.gif";
	}
}

function insertext(open, close, field) {
	try {
		msgfield = document.getElementById((field == '') ? 'content' : field);
	} catch (err) {
		return false;
	}

	// IE support
	if (document.selection && document.selection.createRange) {
		msgfield.focus();
		sel = document.selection.createRange();
		sel.text = open + sel.text + close;
		msgfield.focus();
	}
	// Moz support
	else if (msgfield.selectionStart || msgfield.selectionStart == "0") {
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

function setCookie(name, value) {
	document.cookie = name + "=" + value + "; path=/;" + " expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function deleteCookie(name) {
	document.cookie = name + "=" + "; path=/;" + " expires=Sut, 1 Jan 2000 00:00:01 GMT;";
}

function getCookie(name) {
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return (setStr);
}

function insertimage(text, area) {
	var win = window.opener;
	var form = win.document.forms['form'];
	try {
		var xarea = win.document.forms['DATA_tmp_storage'].area.value;
		if (xarea != '') area = xarea;
	} catch (err) {
		;
	}
	var control = win.document.getElementById(area);

	control.focus();

	// IE
	if (win.selection && win.selection.createRange) {
		sel = win.selection.createRange();
		sel.text = text = sel.text;
	} else
	// Mozilla
	if (control.selectionStart || control.selectionStart == "0") {
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
		insertext('[b', ']' + q_name + '[/b], ', '')
	}
	if (txt.replace(" ", "") != "") {
		insertext('[quote=' + q_name, ']' + txt + '[/quote]', '')
	}
}

function confirmit(url, text) {
	var agree = confirm(text);

	if (agree) {
		document.location = url;
	}
}

function emailCheck(emailStr) {
	var emailPat = /^(.+)@(.+)$/
	var specialChars = "\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
	var validChars = "\[^\\s" + specialChars + "\]"
	var quotedUser = "(\"[^\"]*\")"
	var ipDomainPat = /^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
	var atom = validChars + '+'
	var word = "(" + atom + "|" + quotedUser + ")"
	var userPat = new RegExp("^" + word + "(\\." + word + ")*$")
	var domainPat = new RegExp("^" + atom + "(\\." + atom + ")*$")

	var matchArray = emailStr.match(emailPat)
	if (matchArray == null) {
		return false
	}
	var user = matchArray[1]
	var domain = matchArray[2]

	if (user.match(userPat) == null) {
		return false
	}

	var IPArray = domain.match(ipDomainPat)
	if (IPArray != null) {
		for (var i = 1; i <= 4; i++) {
			if (IPArray[i] > 255) {
				return false
			}
		}
		return true
	}

	var domainArray = domain.match(domainPat)
	if (domainArray == null) {
		return false
	}

	var atomPat = new RegExp(atom, "g")
	var domArr = domain.match(atomPat)
	var len = domArr.length
	if (domArr[domArr.length - 1].length < 2 ||
		domArr[domArr.length - 1].length > 3) {
		return false
	}

	if (len < 2) {
		return false
	}

	return true;
}

function in_array(needle, haystack, argStrict) {
	// http://kevin.vanzonneveld.net
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: vlado houba
	// +   input by: Billy
	// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	// *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	// *     returns 1: true
	// *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	// *     returns 2: false
	// *     example 3: in_array(1, ['1', '2', '3']);
	// *     returns 3: true
	// *     example 3: in_array(1, ['1', '2', '3'], false);
	// *     returns 3: true
	// *     example 4: in_array(1, ['1', '2', '3'], true);
	// *     returns 4: false

	var key = '', strict = !!argStrict;

	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}

	return false;
}
