<script type="text/javascript" src="{admin_url}/includes/js/libsuggest.js"></script>
<style>
.suggestWindow {
 background:#f6f8fb;
 border: 1px solid #aaaaaa;
 color: #232323;
 width: 274px;
 position: absolute;
 display: block;
 visibility: hidden;
 padding: 0px;
 font: normal 12px  tahoma, sans-serif;
  top: 0px; margin: 0;
 left: 80px; position: absolute;
}

#suggestBlock {
 padding-top: 2px;
 padding-bottom: 2px;  width: 100%;
 border: 0px;
}

#suggestBlock td {
 padding-left: 2px;
}

#suggestBlock tr {
 padding: 3px;
 padding-left: 8px;
 background: white;
}

/* #suggestBlock tr:hover, */
#suggestBlock .suggestRowHighlight {
 background: #59a6ec url(images/1px.png) repeat-x;
 color: white;
 cursor: default;
}

#suggestBlock .cleft {
 padding-left: 5px;
}

#suggestBlock .cright {
 text-align: right;
 padding-right: 5px;
}

.suggestClose {
 display: block;
 text-align: right;
 font: normal 10px verdana, tahoma, sans-serif;
 background:#3c9c08;
 color: white;
 padding:3px; cursor: pointer;
}
</style>


<div class="full">
  <h1>{l_pm:new}</h1>
  <div class="pad20_f">
    <div class="btn-group">
      <a href="/plugin/pm/" class="btn">{l_pm:inbox}</a>
      <a href="/plugin/pm/?action=outbox" class="btn">{l_pm:outbox}</a>
    </div>
    <div class="clear20"></div>



<table class="table">
<form method=post name=form action="{php_self}?action=send">
  <tr>
    <td>{l_pm:subject}</td>
    <td><input type="text" class="pm" size="40" name="title" tabindex="2" maxlength="50" /></td>
  </tr>
    <tr>
    <td>{l_pm:too}<br /><small>{l_pm:to}</small></td>
    <td><input type="text" class="pm" name="to_username" id="to_username" size="20" tabindex="3" autocomplete="off" maxlength="70" value="{username}" /><span id="suggestLoader" style="width: 20px; visibility: hidden;"><img src="{skins_url}/images/loading.gif"/></span></td>
  </tr>
  <tr>
    <td colspan="2">
     <h3>{l_pm:textmessage}</h3>
 {quicktags}<br>{smilies}
 <div class="clear20"></div>
 
 <div>
   <textarea name="content" id="pm_content" tabindex="1" maxlength="3000" class="textarea" /></textarea>
 </div>
 <div>
  <label>{l_pm:saveoutbox}&nbsp;&nbsp;<input name="saveoutbox" class="check" type="checkbox"/> </label>
 </div>
 <div class="clear10"></div>
  <input class="btn btn-large btn-success" type="submit" value="{l_pm:send}" accesskey="s" />

    </td>
  </tr>
  </form>
</table>

</div>
</div>
<script language="javascript" type="text/javascript">

function systemInit() {
	new ngSuggest('to_username', 
								{ 
									'iMinLen'	: 1,
									'stCols'	: 1,
									'stColsClass': ['cleft'],
									'lId'		: 'suggestLoader',
									'hlr'		: 'true',
									'stColsHLR'	: [ true ],
									'reqMethodName' : 'pm_get_username',
								}
							);
}

if (document.body.attachEvent) {
	document.body.onload = systemInit;
} else {
	systemInit();
}
</script>