<form method="POST" action="{php_self}?mod=pm&action=reply&pmid={pmid}">
	<input type="hidden" name="title" value="{title}">
	<input type="hidden" name="from" value="{from}">
	<table class="content" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="100%" style="padding-right:10px;" valign="top">
				<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" class="contentHead"><img src="{skins_url}/images/nav.gif" hspace="8">{title}
						</td>
					</tr>
					<tr>
						<td width="100%">
							<blockquote>{content}</blockquote>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr align="center">
						<td width="100%" class="contentEdit">
							<input class="button" type="submit" value="{l_reply}">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>