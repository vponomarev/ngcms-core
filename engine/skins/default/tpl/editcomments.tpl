<script language="JavaScript">
	function ChangeOption(selectedOption) {
		document.getElementById('maincontent').style.display = "none";
		document.getElementById('additional').style.display = "none";

		if (selectedOption == 'maincontent') {
			document.getElementById('maincontent').style.display = "";
		}
		if (selectedOption == 'additional') {
			document.getElementById('additional').style.display = "";
		}
	}
</script>

<form name="form" method="post" action="{php_self}?mod=editcomments">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="center">
			<td width="100%" class="contentNav" align="center" valign="top">
				<input type="button" onmousedown="javascript:ChangeOption('maincontent')" value="{l_maincontent}" class="navbutton"/>
				<input type="button" onmousedown="javascript:ChangeOption('additional')" value="{l_additional}" class="navbutton"/>
			</td>
		</tr>
	</table>
	<br/>
	<table id="maincontent" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"/>{l_answer}
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top" class="contentEntry1">
				<textarea id="content" name="content" rows="10" cols="70">{answer}</textarea></td>
			<td width="50%" valign="top" class="contentEntry1">{quicktags}<br/>{smilies}</td>
		</tr>
		<tr>
			<td width="100%" colspan="2" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8"/>{l_comment}
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1" valign="top">
				<textarea name="comment" rows="10" cols="70">{text}</textarea></td>
			<td width="50%" class="contentEntry1" valign="top">
				<input type="checkbox" id="send" name="send_notice" value="send_notice" class="check"/>
				&nbsp;<label for="send">{l_send_notice}</label></td>
		</tr>
	</table>

	<table id="additional" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
		<tr>
			<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_date}</td>
			<td width="47%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_ip}</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{comdate}</td>
			<td width="47%" class="contentEntry1"><a href="http://www.nic.ru/whois/?ip={ip}" target="_blank">{ip}</a>
			</td>
		</tr>
		<tr>
			<td width="50%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_name}</td>
			<td width="47%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{l_email}</td>
		</tr>
		<tr>
			<td width="50%" class="contentEntry1">{author}</td>
			<td width="47%" class="contentEntry1"><input type="text" name="mail" value="{mail}"/></td>
		</tr>
	</table>
	<br/>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="center">
			<td width="100%" class="contentEdit">
				<input type=submit value="{l_save}" accesskey="s" class="button" tabindex="5">
				<input type=button value="{l_delete}" onClick="confirmit('{php_self}?mod=editcomments&subaction=deletecomment&newsid={newsid}&comid={comid}&poster={author}', '{l_sure_del}')" class="button" tabindex="6">
				<input type="button" value="{l_block_ip}" onClick="document.location='{php_self}?mod=ipban&iplock={ip}'" class="button"/>
				<input type=hidden name=mod value="editcomments">
				<input type=hidden name=newsid value="{newsid}">
				<input type=hidden name=comid value="{comid}">
				<input type=hidden name=poster value="{author}">
				<input type=hidden name=subaction value="doeditcomment">
			</td>
		</tr>
	</table>
</form>