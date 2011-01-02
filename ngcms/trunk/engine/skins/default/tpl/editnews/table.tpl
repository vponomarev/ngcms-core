<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>
<script language="javascript" type="text/javascript">
<!--
							
function addEvent(elem, type, handler){
  if (elem.addEventListener){
    elem.addEventListener(type, handler, false)
  } else {
    elem.attachEvent("on"+type, handler)
  }
} 

// DateEdit filter
function filter_attach_DateEdit(id) {
	var field = document.getElementById(id);
	if (!field)
		return false;
	
	if (field.value == '')
		field.value = 'DD.MM.YYYY';
	
	field.onfocus = function(event) {
		var ev = event ? event : window.event;
		var elem = ev.target ? ev.target : ev.srcElement;

		if (elem.value == 'DD.MM.YYYY')
			elem.value = '';

		return true;
	}

	
	field.onkeypress = function(event) {
		var ev = event ? event : window.event;
		var keyCode = ev.keyCode ? ev.keyCode : ev.charCode;
		var elem = ev.target ? ev.target : ev.srcElement;
		var elv = elem.value;

		isMozilla = false;
		isIE = false;
		isOpera = false;
		if (navigator.appName == 'Netscape') { isMozilla = true; }
		else if (navigator.appName == 'Microsoft Internet Explorer') { isIE = true; }
		else if (navigator.appName == 'Opera') { isOpera = true; }
		else { /* alert('Unknown navigator: `'+navigator.appName+'`'); */ }
		
		//document.getElementById('debugWin').innerHTML = 'keyPress('+ev.keyCode+':'+ev.charCode+')['+(ev.shiftKey?'S':'.')+(ev.ctrlKey?'C':'.')+(ev.altKey?'A':'.')+']<br/>' + document.getElementById('debugWin').innerHTML;
		
		// FF - onKeyPress captures functional keys. Skip anything with charCode = 0
		if (isMozilla && !ev.charCode)
			return true;
		
		// Opera - dumb browser, don't let us to determine some keys
		if (isOpera) {
			var ek = '';
			//for (i in event) { ek = ek + '['+i+']: '+event[i]+'<br/>\n'; }
			//alert(ek);
			if (ev.keyCode < 32) return true;
			if (!ev.shiftKey && ((ev.keyCode >= 33) && (ev.keyCode <= 47))) return true;
			if (!ev.keyCode) return true;
			if (!ev.which) return true;
		}
		
		
		// Don't block CTRL / ALT keys
		if (ev.altKey || ev.ctrlKey || !keyCode)
			return true;

		// Allow to input only digits [0..9] and dot [.]
		if (((keyCode >= 48) && (keyCode <= 57)) || (keyCode == 46))
			return true;
		
		return false;
	}

	return true;
}

-->
</script>

<!-- DEBUG WINDOW <div id="debugWin" style="overflow: auto; position: absolute; top: 160px; left: 230px; width: 400px; height: 400px; background: white; 4px double black; padding: 2px; margin: 2px;">DEBUG WINDOW</div> -->


<!-- Hidden SUGGEST div -->
<div id="suggestWindow" class="suggestWindow">
<table id="suggestBlock" cellspacing="0" cellpadding="0" width="100%"></table>
<a href="#" align="right" id="suggestClose">close</a>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width=100% colspan="5" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_editnews_title}</td>
</tr>
</table>

<form action="{php_self}?mod=editnews" method="post" name="options_bar">
<table width="1000" border="0" cellspacing="0" cellpadding="0" class="editfilter">
  <tr>
<!--Block 1--><td rowspan="2">
<table border="0" cellspacing="0" cellpadding="0" class="filterblock">
  <tr>
    <td valign="top" >
    <label>Поиск</label>
    <input name="sl" type="text" class="bfsearch" value="{sl}"/> <select name="st"><option value="0" {st.selected0}>заголовок</option><option value="1" {st.selected1}>текст</option></select>
    </td>
  </tr>
  <tr>
    <td>
    <label>{l_author}</label>
    <input name="an" id="an" class="bfauthor" type="text"  value="{an}" autocomplete="off" /> <span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{skins_url}/images/loading.gif"/></span>
    </td>
  </tr>
</table>

</td><!--/Block 1--> 

<!--Block 2--><td rowspan="2">
<table border="0" cellspacing="0" cellpadding="0" class="filterblock">
  <tr>
    <td valign="top">
    <label>Дата</label>
    с:&nbsp; <input type="text" name="dr1" value="{dr1}" class="bfdate"/>&nbsp;&nbsp; по&nbsp;&nbsp; <input type="text" name="dr2" value="{dr2}" class="bfdate"/>
    </td>
  </tr>
  <tr>
    <td>
    <label>{l_category}</label>
    {category_select}
    </td>
  </tr>
</table>

</td><!--/Block 2-->
    
<!--Block 3--><td valign="top" >
<table border="0" cellspacing="0" cellpadding="0" class="filterblock2">
  <tr>
    <td valign="top" >
    <label>Статус</label>
    <select name="status" class="bfstatus"><option value="">{l_smode_all}</option>{statuslist}</select>
    </td>
    <td align="right" valign="top"  >
    <label>На странице</label>
    <input name="rpp" value="{rpp}" type="text" size="3" />
    </td>
  </tr>
  <tr>
    <td colspan="2">
    <label class="left">{l_sort}</label>&nbsp;&nbsp;<select name="sort" class="bfsortlist">{sortlist}</select>
    </td>
  </tr>
</table>

</td>
  </tr>
  <tr>
    <td><input type="submit" value="{l_do_show}" class="filterbutton"  /></td>
  </tr>
</table>
</form>
<!-- Конец блока фильтрации -->

<br />
<!-- List of news start here -->
<form action="{php_self}?mod=editnews" method="post" name="editnews">
<table border="0" cellspacing="0" cellpadding="0" class="content" align="center">
<tr align="left" class="contHead">
<td width="5%" nowrap>{l_postid_short}</td>
<td width="10%"  nowrap>{l_date}</td>
<td width="16">&nbsp;</td>
<td width="45%" >{l_title}</td>
[comments]<td width="10%" >{l_listhead.comments}</td>[/comments]
<td width="25%">{l_category}</td>
<td width="10%">{l_author}</td>
<td width="5%">&nbsp;</td>
<td width="5%"><input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(editnews)" /></td>
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
{l_action}: <select name="subaction" style="font: 12px Verdana, Courier, Arial; width: 230px;">
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
<input type="submit" value="{l_submit}" class="button" />
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

<script language="javascript" type="text/javascript">
<!--
// INIT NEW SUGGEST LIBRARY [ call only after full document load ]
function systemInit() {
var aSuggest = new ngSuggest('an', 
								{
									'localPrefix'	: '{localPrefix}',
									'reqMethodName'	: 'core.users.search',
									'lId'		: 'suggestLoader',
									'hlr'		: 'true',
									'iMinLen'	: 1,
									'stCols'	: 2,
									'stColsClass': [ 'cleft', 'cright' ],
									'stColsHLR'	: [ true, false ],
								}
							);

}

// Init system [ IE / Other browsers should be inited in different ways ]
if (document.body.attachEvent) {
	// IE
	document.body.onload = systemInit;
} else {
	// Others
	systemInit();
}

filter_attach_DateEdit('dr1');
filter_attach_DateEdit('dr2');
-->
</script>



