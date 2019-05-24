<script type="text/javascript">
	function ChangeOption(selectedOption) {
		document.getElementById('list').style.display = "none";
		document.getElementById('adduser').style.display = "none";
		document.getElementById('addbutton').style.display = "none";

		if (selectedOption == 'list') {
			document.getElementById('list').style.display = "";
			document.getElementById('addbutton').style.display = "none";
		}
		if (selectedOption == 'adduser') {
			document.getElementById('adduser').style.display = "";
			document.getElementById('addbutton').style.display = "";
		}
	}

	var fInitStatus = false;

	function updateAction() {
		mode = document.forms['form_users'].action.value;

		if (mode == 'massSetStatus') {
			if (!fInitStatus) {
				document.forms['form_users'].newstatus.value = '4';
				fInitStatus = true;
			}
			document.forms['form_users'].newstatus.disabled = false;
		} else {
			document.forms['form_users'].newstatus.disabled = true;
		}
	}

	function validateAction() {
		mode = document.forms['form_users'].action.value;

		if (mode == '') {
			alert('Необходимо выбрать действие!');
			return;
		}

		if ((mode == 'massSetStatus') && (document.forms['form_users'].newstatus.value < 1)) {
			alert('{l_msge_setstatus}');
			return;
		}

		document.forms['form_users'].submit();
	}
</script>


<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width=100% colspan="5" class="contentHead">
			<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="?mod=users">{{ lang['users_title'] }}</a></td>
	</tr>
</table>
{% if flags.canModify %}
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="center">
			<td width="100%" class="contentNav" align="center" valign="top">
				<input type="button" onmousedown="javascript:ChangeOption('list')" value="{{ lang['users'] }}" class="navbutton"/>
				<input type="button" onmousedown="javascript:ChangeOption('adduser')" value="{{ lang['adduser'] }}" class="navbutton"/>
			</td>
		</tr>
	</table>
	<br/>{% endif %}
<table id="list" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="content">
	<tr>
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="contentNav">
				<tr>
					<td width="100%" align="left">
						<!-- Filter form: BEGIN -->
						<form method="GET" action="{{ php_self }}">
							<input type="hidden" name="mod" value="users"/>
							<input type="hidden" name="action" value="list"/>
							{{ lang['name'] }}: <input type="text" name="name" value="{{ name }}"/>
							| {{ lang['group'] }}: <select name="group">
								<option value="0">-- {{ lang['any'] }} --</option>{% for g in ugroup %}
								<option value="{{ g.id }}" {% if (group == g.id) %}selected="selected"{% endif %}>{{ g.name }}</option>{% endfor %}
							</select> |
							<input style="text-align: center;" size=3 name="rpp" value="{{ rpp }}"/>
							<input type="submit" value="{{ lang['sortit'] }}" class="button"/>
						</form>
						<!-- Filter form: END -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top">
			<!-- Mass actions form: BEGIN -->
			<form method="GET" name="form_users" id="form_users" action="{{ php_self }}">
				<input type="hidden" name="mod" value="users"/>
				<input type="hidden" name="token" value="{{ token }}"/>
				<input type="hidden" name="name" value="{{ name }}"/>
				<input type="hidden" name="how" value="{how_value}"/>
				<input type="hidden" name="sort" value="{sort_value}"/>
				<input type="hidden" name="page" value="{page_value}"/>
				<input type="hidden" name="per_page" value="{{ rpp }}"/>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="8">&nbsp;</td>
					</tr>
					<tr align="left" class="contHead">
						<td width="5%"><a href="{{ sortLink['i']['link'] }}">#</a> {{ sortLink['i']['sign'] }}</td>
						<td width="20%">
							<a href="{{ sortLink['n']['link'] }}">{{ lang['name'] }}</a> {{ sortLink['n']['sign'] }}
						</td>
						<td width="20%">
							<a href="{{ sortLink['r']['link'] }}">{{ lang['regdate'] }}</a> {{ sortLink['r']['sign'] }}
						</td>
						<td width="20%">
							<a href="{{ sortLink['l']['link'] }}">{{ lang['last_login'] }}</a> {{ sortLink['l']['sign'] }}
						</td>
						<td width="10%">
							<a href="{{ sortLink['p']['link'] }}">{{ lang['all_news2'] }}</a> {{ sortLink['p']['sign'] }}
						</td>
						{% if flags.haveComments %}
							<td width="10%">{l_listhead.comments}</td>{% endif %}
						<td width="15%">
							<a href="{{ sortLink['g']['link'] }}">{{ lang['groupName'] }}</a> {{ sortLink['g']['sign'] }}
						</td>
						<td width="5%">&nbsp;</td>
						<td width="5%">{% if flags.canModify %}
								<input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form_users)"/>{% endif %}
						</td>
					</tr>
					{% for entry in entries %}
						<tr align="left">
							<td class="contentEntry1">{{ entry.id }}</td>
							<td class="contentEntry1">{% if flags.canView %}
									<a href="{{ php_self }}?mod=users&amp;action=editForm&amp;id={{ entry.id }}">{{ entry.name }}</a>{% else %}{{ entry.name }}{% endif %}
							</td>
							<td class="contentEntry1">{{ entry.regdate }}</td>
							<td class="contentEntry1">{{ entry.lastdate }}</td>
							<td class="contentEntry1">{% if entry.cntNews > 0 %}
									<a href="{{ php_self }}?mod=news&amp;aid={{ id }}">{{ entry.cntNews }}</a>{% else %}-{% endif %}
							</td>
							{% if flags.haveComments %}
								<td width="10%" class="contentEntry1">{% if entry.cntComments > 0 %}{{ entry.cntComments }}{% else %}-{% endif %}</td>{% endif %}
							<td class="contentEntry1">{{ entry.groupName }}</td>
							<td class="contentEntry1">
								<img src="{{ skins_url }}/images/{% if entry.flags.isActive %}yes{% else %}no{% endif %}.png" alt="{% if entry.flags.isActive %}{{ lang['active'] }}{% else %}{{ lang['unactive'] }}{% endif %}"/>
							</td>
							<td class="contentEntry1">{% if flags.canModify %}{% if flags.canMassAction %}
									<input name="selected_users[]" value="{{ entry.id }}" class="check" type="checkbox" />{% endif %}{% endif %}
							</td>
						</tr>
					{% endfor %}

					<tr>
						<td width="100%" colspan="8">&nbsp;</td>
					</tr>
					<tr align="center">
						<td colspan="9" class="contentEdit" align="right" valign="top">
							{% if flags.canModify %}
								<div style="text-align: left;">
									{{ lang['action'] }}:
									<select name="action" style="font: 12px Verdana, Courier, Arial; width: 230px;" onchange="updateAction();" onclick="updateAction();">
										<option value="" style="background-color: #E0E0E0;">-- {{ lang['action'] }}--
										</option>
										<option value="massActivate">{{ lang['activate'] }}</option>
										<option value="massLock">{{ lang['lock'] }}</option>
										<option value="" style="background-color: #E0E0E0;" disabled="disabled">
											===================
										</option>
										<option value="massDel">{{ lang['delete'] }}</option>
										<option value="massDelInactive">{{ lang['delete_unact'] }}</option>
										<option value="" style="background-color: #E0E0E0;" disabled="disabled">
											===================
										</option>
										<option value="massSetStatus">{{ lang['setstatus'] }} &raquo;</option>
									</select>
									<select name="newstatus" disabled="disabled" style="font: 12px Verdana, Courier, Arial; width: 150px;">
										<option value="0"></option>
										{% for grp in ugroup %}
											<option value="{{ grp.id }}">{{ grp.id }} ({{ grp.name }})</option>
										{% endfor %}
									</select>
									<input type="button" class="button" value="{{ lang['submit'] }}" onclick="validateAction();"/>
									<br/>
								</div>
							{% endif %}
						</td>
					</tr>
					<tr>
						<td width="100%" colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" colspan="9" class="contentHead">{{ pagination }}</td>
					</tr>
				</table>
			</form>
			<!-- Mass actions form: END -->
		</td>
	</tr>
</table>


{% if flags.canModify %}
	<form method="post" action="{{ php_self }}?mod=users">
		<input type="hidden" name="action" value="add"/>
		<input type="hidden" name="token" value="{{ token }}"/>
		<table id="adduser" style="display: none;" border="0" cellspacing="0" cellpadding="0" class="content" align="center">
			<tr>
				<td width="50%" class="contentEntry1">{{ lang['name'] }}</td>
				<td width="50%" class="contentEntry2"><input size="40" type="text" name="regusername"/>
				</td>
			</tr>
			<tr>
				<td width="50%" class="contentEntry1">{l_password}</td>
				<td width="50%" class="contentEntry2"><input size="40" class="password" type="text" name="regpassword"/>
				</td>
			</tr>
			<tr>
				<td width="50%" class="contentEntry1">{l_email}</td>
				<td width="50%" class="contentEntry2"><input size="40" class="email" type="text" name="regemail"/>
				</td>
			</tr>
			<tr>
				<td width="50%" class="contentEntry1">{l_status}</td>
				<td width="50%" class="contentEntry2">
					<select name="reglevel">
						{% for grp in ugroup %}
							<option value="{{ grp.id }}">{{ grp.id }} ({{ grp.name }})</option>
						{% endfor %}
					</select>
			</tr>
		</table>
		<br/>
		<table id="addbutton" style="display: none;" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr align="center">
				<td width="100%" class="contentEdit" align="center" valign="top">
					<input type="submit" value="{l_adduser}" class="button"/>
				</td>
			</tr>
		</table>
	</form>
{% endif %}