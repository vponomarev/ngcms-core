/* Simple AJAX Code-Kit (SACK) v1.6.1 */
/* ©2005 Gregory Wild-Smith */
/* www.twilightuniverse.com */
/* Software licenced under a modified X11 licence,
   see documentation or authors website for more details */

function center_div() {
	this.divname = '';
	this.divobj  = '';
}
center_div.prototype.clear_div = function() {
	try {
		if ( ! this.divobj ) {
			return;
		}
		else {
			this.divobj.style.display  = 'none';
		}
	}
	catch(e) {
		return;
	}
}
center_div.prototype.Ywindow = function() {
	var scrollY = 0;
	
	if (document.documentElement && document.documentElement.scrollTop) {
		scrollY = document.documentElement.scrollTop;
	}
	else if (document.body && document.body.scrollTop) {
		scrollY = document.body.scrollTop;
	}
	else if (window.pageYOffset) {
		scrollY = window.pageYOffset;
	}
	else if (window.scrollY) {
		scrollY = window.scrollY;
	}
	
	return scrollY;
}
center_div.prototype.move_div = function() {
	try {
		this.divobj = document.getElementById( this.divname );
	}
	catch(e) {
		return;
	}

	var my_width  = 200;
	var my_height = 50;
	
	if (typeof(window.innerWidth) == "number") {
		my_width  = window.innerWidth;
		my_height = window.innerHeight;
	}
	else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
		my_width  = document.documentElement.clientWidth;
		my_height = document.documentElement.clientHeight;
	}
	else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
		my_width  = document.body.clientWidth;
		my_height = document.body.clientHeight;
	}
	
	this.divobj.style.position	=	"absolute";
	this.divobj.style.display	=	"block";
	this.divobj.style.zIndex	=	99;
	
	var divheight = parseInt( this.divobj.style.Height );
	var divwidth  = parseInt( this.divobj.style.Width );
	
	divheight = divheight ? divheight : 30;
	divwidth  = divwidth  ? divwidth  : 180;
	
	var scrolly = this.Ywindow();
	
	var setX = ( my_width  - divwidth  ) / 2;
	var setY = ( my_height - divheight ) / 2 + scrolly;
	
	setX = ( setX < 0 ) ? 0 : setX;
	setY = ( setY < 0 ) ? 0 : setY;
	
	this.divobj.style.left = setX + "px";
	this.divobj.style.top  = setY + "px";
}

function sack(file) {
	this.xmlhttp = null;

	this.resetData = function() {
		this.whattodo = "";
		this.method = "POST";
		this.queryStringSeparator = "?";
		this.argumentSeparator = "&";
		this.URLString = "";
		this.encodeURIString = true;
		this.execute = false;
		this.element = null;
		this.elementObj = null;
		this.requestFile = file;
		this.vars = new Object();
		this.responseStatus = new Array(2);
		this.centerdiv = null;
	};

	this.resetFunctions = function() {
		this.onError = function() { };
		this.onFail = function() { };
		this.onComplete = function() { };
		this.onShow = function() {
			this.centerdiv         = new center_div();
			this.centerdiv.divname = 'loading-layer';
			this.centerdiv.move_div();
			return;
		};
		this.onHide = function() {
			try {
				if (this.centerdiv && this.centerdiv.divobj) {
					this.centerdiv.clear_div();
				}
			}
			catch(e) {}
			return;
		};
	};

	this.reset = function() {
		this.resetFunctions();
		this.resetData();
	};

	this.createAJAX = function() {
		try {
			this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e1) {
			try {
				this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e2) {
				this.xmlhttp = null;
			}
		}

		if (! this.xmlhttp) {
			if (typeof XMLHttpRequest != "undefined") {
				this.xmlhttp = new XMLHttpRequest();
			} else {
				this.failed = true;
			}
		}
	};

	this.setVar = function(name, value){
		this.vars[name] = Array(value, false);
	};

	this.encVar = function(name, value, returnvars) {
		if (true == returnvars) {
			return Array(encodeURIComponent(name), encodeURIComponent(value));
		} else {
			this.vars[encodeURIComponent(name)] = Array(encodeURIComponent(value), true);
		}
		return true;
	}

	this.processURLString = function(string, encode) {
		encoded = encodeURIComponent(this.argumentSeparator);
		regexp = new RegExp(this.argumentSeparator + "|" + encoded);
		varArray = string.split(regexp);
		for (i = 0; i < varArray.length; i++){
			urlVars = varArray[i].split("=");
			if (true == encode){
				this.encVar(urlVars[0], urlVars[1]);
			} else {
				this.setVar(urlVars[0], urlVars[1]);
			}
		}
	}

	this.createURLString = function(urlstring) {
		if (this.encodeURIString && this.URLString.length) {
			this.processURLString(this.URLString, true);
		}

		if (urlstring) {
			if (this.URLString.length) {
				this.URLString += this.argumentSeparator + urlstring;
			} else {
				this.URLString = urlstring;
			}
		}

		// prevents caching of URLString
		this.setVar("rndval", new Date().getTime());

		urlstringtemp = new Array();
		for (key in this.vars) {
			if (false == this.vars[key][1] && true == this.encodeURIString) {
				encoded = this.encVar(key, this.vars[key][0], true);
				delete this.vars[key];
				this.vars[encoded[0]] = Array(encoded[1], true);
				key = encoded[0];
			}

			urlstringtemp[urlstringtemp.length] = key + "=" + this.vars[key][0];
		}
		if (urlstring){
			this.URLString += this.argumentSeparator + urlstringtemp.join(this.argumentSeparator);
		} else {
			this.URLString += urlstringtemp.join(this.argumentSeparator);
		}
	}

	this.runResponse = function() {
		eval(this.response);
	}

	this.runAJAX = function(urlstring) {
		if (this.failed) {
			this.onFail();
		} else {
			this.createURLString(urlstring);
			if (this.element) {
				this.elementObj = document.getElementById(this.element);
			}
			if (this.xmlhttp) {
				var self = this;
				if (this.method == "GET") {
					totalurlstring = this.requestFile + this.queryStringSeparator + this.URLString;
					this.xmlhttp.open(this.method, totalurlstring, true);
				} else {
					this.xmlhttp.open(this.method, this.requestFile, true);
					try {
						this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
					} catch (e) { }
				}

				this.xmlhttp.onreadystatechange = function() {
					switch(self.xmlhttp.readyState) {
						case 1:
						break;
						case 2:
						break;
						case 3:
						break;
						case 4: 
							self.response = self.xmlhttp.responseText;
							self.responseXML = self.xmlhttp.responseXML;
							self.responseStatus[0] = self.xmlhttp.status;
							self.responseStatus[1] = self.xmlhttp.statusText;

							if (self.execute) {
								self.runResponse();
							}

							if (self.elementObj) {
								elemNodeName = self.elementObj.nodeName;
								elemNodeName.toLowerCase();

								if (elemNodeName == "input" || elemNodeName == "select" || elemNodeName == "option" || elemNodeName == "textarea") {
									self.elementObj.value = self.response;
								}
								else {
									if (self.whattodo == "append") {
										self.elementObj.innerHTML += self.response;
									}
									else {
										self.elementObj.innerHTML = self.response;
									}
								}
							}

							if (self.responseStatus[0] == "200") {
								self.onHide();
							}
							else {
								self.onError();
							}
							self.onComplete();
							self.URLString = "";
						break;
					}
				};
				this.xmlhttp.send(this.URLString);
			}
		}
	};

	this.reset();
	this.createAJAX();
}