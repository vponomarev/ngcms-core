//
// JS Functions used for admin panel
//


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
	return setStr;
}

function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
      return true;
}

function toggleAdminGroup(ref) {
 // Decide to find parent node for this block
 var maxIter = 5;
 var node = ref;

 while(maxIter) {
  if (node.className == 'admGroup') { break; }
  node = node.parentNode;
  maxIter--;
 }
 if (!maxIter) { alert('Scripting Error'); }


 for (var i = 0; i < node.childNodes.length; i++) {
	var item = node.childNodes[i];
 	if (item.className == 'content') {
 		mode = (item.style.display == 'none')?1:0;
		item.style.display = mode?'block':'none';
		break;
	}
 }	
}

