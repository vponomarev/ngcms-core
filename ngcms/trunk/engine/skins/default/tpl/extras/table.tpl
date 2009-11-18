<script type="text/javascript" src="{admin_url}/includes/js/admin.js"></script>
<script language="javascript">

//
// First: Init CSS manage mechanism

var sheetRules = new Array();
if (document.styleSheets[0].cssRules) {
	sheetRules = document.styleSheets[0].cssRules;
} else if (document.styleSheets[0].rules) {
	sheetRules = document.styleSheets[0].rules;
}

var sIndexActive	= -1;
var sIndexInactive	= -1;
var sIndexUninstalled	= -1;

var qStateActive	= 1;
var qStateInactive	= 0;
var qStateUninstalled	= 0;

for (i in sheetRules) {
	var sText = ''+sheetRules[i]['selectorText'];
	sText = sText.toLowerCase();
	if (sText == '.pluginentryactive td')		sIndexActive = i;
	if (sText == '.pluginentryinactive td')		sIndexInactive = i;
	if (sText == '.pluginentryuninstalled td')	sIndexUninstalled = i;
}
//alert('Index values: '+sIndexActive+', '+sIndexInactive+', '+sIndexUninstalled+"\n"+ln);

//
// Init pre-saved in cookies values
var cookieStatus = getCookie('ngadm_pstatus');
if ((cookieStatus !== null)&&(typeof(cookieStatus) == "string")) {
	qStateActive		= (cookieStatus.substr(0,1)=='x')?1:0;
	qStateInactive		= (cookieStatus.substr(1,1)=='x')?1:0;
	qStateUninstalled	= (cookieStatus.substr(2,1)=='x')?1:0;
}
//alert('cStatus: '+qStateActive+','+qStateInactive+','+qStateUninstalled);

 // Now let's set configured values
 if ((sIndexActive	>= 0)&&(!qStateActive)) 	sheetRules[sIndexActive].style.display		= 'none'; 
 if ((sIndexInactive	>= 0)&&(!qStateInactive))	sheetRules[sIndexInactive].style.display	= 'none';
 if ((sIndexUninstalled	>= 0)&&(!qStateUninstalled))	sheetRules[sIndexUninstalled].style.display	= 'none';

//	document.getElementById('pTypeActive').className = (cStatus.substr(0,1) == 'x')?'pActive':'pInactive';
//	document.getElementById('pTypeInactive').className = (cStatus.substr(1,1) == 'x')?'pActive':'pInactive';
//	document.getElementById('pTypeUninstalled').className = (cStatus.substr(2,1) == 'x')?'pActive':'pInactive';


//
// Function for toggling display
function togglePDisplayMode(id) {
	var item = document.getElementById(id);
	item.className = (item.className == 'pInactive')?'pActive':'pInactive';
	if ((id == 'pTypeActive')&&(sIndexActive >= 0)) {
		sheetRules[sIndexActive].style.display = (item.className == 'pActive')?'':'none';
	}
	if ((id == 'pTypeInactive')&&(sIndexInactive >= 0)) {
		sheetRules[sIndexInactive].style.display = (item.className == 'pActive')?'':'none';
	}
	if ((id == 'pTypeUninstalled')&&(sIndexUninstalled >= 0)) {
		sheetRules[sIndexUninstalled].style.display = (item.className == 'pActive')?'':'none';
	}
}

</script>

<div id="pluginMenu">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr class="contHead">
<td width=100% colspan="8"><img src="{skins_url}/images/nav.gif" hspace="8" />{l_extras}</td>
</tr>
<tr>
<td width=100% colspan="8">
<div id="pluginTypeMenu">
<span id="pTypeActive" class="pInactive" onclick="togglePDisplayMode('pTypeActive');">Активные ({cntActive})</span><span class="pSeparator">&nbsp;</span>
<span id="pTypeInactive" class="pInactive" onclick="togglePDisplayMode('pTypeInactive');">Неактивные ({cntInactive})</span><span class="pSeparator">&nbsp;</span>
<span id="pTypeUninstalled" class="pInactive" onclick="togglePDisplayMode('pTypeUninstalled');">Требуют установки ({cntUninstalled})</span>
</div>
&nbsp;
</td>
</tr>
<tr align="left" class="contHead">
<td>{l_id}</td>
<td>{l_title}</td>
<td>{l_type}</td>
<td>{l_version}</td>
<td>&nbsp;</td>
<td>{l_description}</td>
<td>{l_author}</td>
<td>{l_action}</td>
</tr>
{entries}
</table>
</div>

<script language="javascript">

//
// Now let's init buttons
document.getElementById('pTypeActive').className	= (sIndexActive >= 0    )?(qStateActive?'pActive':'pInactive'):'pLocked';
document.getElementById('pTypeInactive').className	= (sIndexInactive >= 0  )?(qStateInactive?'pActive':'pInactive'):'pLocked';
document.getElementById('pTypeUninstalled').className	= (sIndexUninstalled >=0)?(qStateUninstalled?'pActive':'pInactive'):'pLocked';

</script>
