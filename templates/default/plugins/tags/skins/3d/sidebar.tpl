<div class="block tags-block">
	<div class="block-title">Облако тегов</div>
	<div id="insertTagCloud">{entries}</div><br /><a href="/plugin/tags/">Показать все теги</a>
</div>
<script type="text/javascript" src="{tpl_url}/plugins/tags/skins/3d/js/swfobject.js"></script>
<script language="javascript">
var insertCloudElementID = 'insertTagCloud';
var insertCloudClientWidth = document.getElementById(insertCloudElementID).clientWidth;
var insertCloudClientHeight = insertCloudClientWidth; //140;
var tagLine = '{cloud3d}';
var rnumber = Math.floor(Math.random()*9999999);
var widget_so = new SWFObject("{tpl_url}/plugins/tags/skins/3d/js/tagcloud.swf?r="+rnumber, "tagcloudflash", insertCloudClientWidth, insertCloudClientHeight, "9", "#ffffff");
widget_so.addParam("allowScriptAccess", "always");
widget_so.addParam("wmode", "transparent");
widget_so.addVariable("tcolor", "0x333333");
widget_so.addVariable("tspeed", "115");
widget_so.addVariable("distr", "true");
widget_so.addVariable("mode", "tags");
widget_so.addVariable("tagcloud", tagLine);
widget_so.write(insertCloudElementID);
</script>