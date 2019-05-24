<table class="content" border="0" cellspacing="0" cellpadding="2" align="center">
	<tr>
		<td width="66%" style="padding-right:10px;" valign="top">
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width=100% colspan="5" class="contentHead">
						<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/><a href="admin.php?mod=ipban">{{ lang.ipban['hdr.list'] }}</a>
					</td>
				</tr>

				<tr align="left" class="contHead">
					<td>{{ lang.ipban['hdr.ip'] }}</td>
					<td>{{ lang.ipban['hdr.counter'] }}</td>
					<td>{{ lang.ipban['hdr.type'] }}</td>
					<td>{{ lang.ipban['hdr.reason'] }}</td>
					<td>&nbsp;</td>
				</tr>
				{% for entry in entries %}
					<tr>
						<td nowrap class=contentEntry1>
							<a href="http://www.nic.ru/whois/?ip={{ entry.whoisip }}" target="_blank">?</a> {{ entry.ip }}
						</td>
						<td class=contentEntry1>{{ entry.hitcount }}</td>
						<td class=contentEntry1>{{ entry.type }}</td>
						<td class=contentEntry1>{{ entry.descr }}</td>
						<td class=contentEntry1>{% if flags.permModify %}
							<a href="{{ php_self }}?mod=ipban&amp;action=del&amp;id={{ entry.id }}&amp;token={{ token }}">
								<img src="{{ skins_url }}/images/delete.gif" hspace="8" alt="{{ lang.ipban['act.unblock'] }}" title="{{ lang.ipban['act.unblock'] }}"/>
								</a>{% endif %}</td>
					</tr>
				{% endfor %}


			</table>
		</td>
		<td width="33%" style="padding-left:5px;" valign="top">
			{% if flags.permModify %}
				<form name="form" method="post" action="{{ php_self }}?mod=ipban">
					<input type="hidden" name="token" value="{{ token }}"/>
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width=100% colspan="2" class="contentHead">
								<img src="{{ skins_url }}/images/nav.gif" hspace="8" alt=""/>{{ lang.ipban['hdr.block'] }}
							</td>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.ip'] }}:</td>
							<td><input type="text" name="ip" value="{{ iplock }}" size="31"/></td>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.block.open'] }}:</td>
							<td><select disabled="disabled" name="lock:open">
									<option value="0">--</option>
									<option value="1" style="color: blue;">{{ lang.ipban['lock.block'] }}</option>
									<option value="2" style="color: red;">{{ lang.ipban['lock.silent'] }}</option>
								</select>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.block.reg'] }}:</td>
							<td><select name="lock:reg">
									<option value="0">--</option>
									<option value="1" style="color: blue;">{{ lang.ipban['lock.block'] }}</option>
									<option value="2" style="color: red;">{{ lang.ipban['lock.silent'] }}</option>
								</select>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.block.login'] }}:</td>
							<td><select name="lock:login">
									<option value="0">--</option>
									<option value="1" style="color: blue;">{{ lang.ipban['lock.block'] }}</option>
									<option value="2" style="color: red;">{{ lang.ipban['lock.silent'] }}</option>
								</select>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.block.comm'] }}:</td>
							<td><select name="lock:comm">
									<option value="0">--</option>
									<option value="1" style="color: blue;">{{ lang.ipban['lock.block'] }}</option>
									<option value="2" style="color: red;">{{ lang.ipban['lock.silent'] }}</option>
								</select>
						</tr>
						<tr>
							<td class="contentEntry2">{{ lang.ipban['add.block.rsn'] }}</td>
							<td><input type="text" name="lock:rsn" size="30"/></td>
						</tr>
						<tr>
							<td width=100% class="contentEntry" colspan="2" valign="middle" align="center">
								<input type="submit" value="{{ lang.ipban['add.submit'] }}" class="button"/>
								<input type="hidden" name="action" value="add"/>
							</td>
						</tr>
					</table>
				</form>
			{% endif %}
		</td>
	</tr>
</table>
<br/>
<br/>
{{ lang.ipban['info.descr'] }}
