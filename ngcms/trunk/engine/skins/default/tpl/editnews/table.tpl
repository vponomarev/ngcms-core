<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>
<script language="javascript">
<!--
							
function addEvent(elem, type, handler){
  if (elem.addEventListener){
    elem.addEventListener(type, handler, false)
  } else {
    elem.attachEvent("on"+type, handler)
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
-->
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
<!--
// INIT NEW SUGGEST LIBRARY [ call only after full document load ]
function systemInit() {
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

}

// Init system [ IE / Other browsers should be inited in different ways ]
if (document.body.attachEvent) {
	// IE
	document.body.onload = systemInit;
} else {
	// Others
	systemInit();
}

-->
</script>

