<!-- FORM: Perform actions with tables -->
<form name="form" method="post" action="{{ php_self }}?mod=dbo">
	<input type="hidden" name="subaction" value="modify"/>
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="massbackup" value=""/>
	<input type="hidden" name="cat_recount" value=""/>
	<input type="hidden" name="masscheck" value=""/>
	<input type="hidden" name="massrepair" value=""/>
	<input type="hidden" name="massoptimize" value=""/>
	<input type="hidden" name="massdelete" value=""/>
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width=100% colspan="5" class="contentHead">
				<img src="{{ skins_url }}/images/nav.gif" hspace="8"><a href="admin.php?mod=dbo">{{ lang.dbo.title }}</a>
			</td>
		</tr>
	</table>
	<table class="content" border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="left" class="contHead">
			<td width="15%">{{ lang.dbo.table }}</td>
			<td width="15%">{{ lang.dbo.rows }}</td>
			<td width="15%">{{ lang.dbo.data }}</td>
			<td width="15%">{{ lang.dbo.overhead }}</td>
			<td width="5%">
				<input class="check" type="checkbox" name="master_box" title="{l_select_all}" onclick="javascript:check_uncheck_all(form, 'tables')"/>
			</td>
		</tr>
		{% for tbl in tables %}
			<tr align="left">
				<td class="contentEntry1">{{ tbl.table }}</td>
				<td class="contentEntry1">{{ tbl.rows }}</td>
				<td class="contentEntry1">{{ tbl.data }}</td>
				<td class="contentEntry1">{{ tbl.overhead }}</td>
				<td class="contentEntry1"><input name="tables[]" value="{{ tbl.table }}" class="check" type="checkbox"/>
				</td>
			</tr>
		{% endfor %}
		<tr>
			<td colspan="8">&nbsp;</td>
		</tr>
		<tr align="left">
			<td width="100%" colspan="8" class="contentEdit">
				<input class="button" type="submit" value="{{ lang.dbo.cat_recount }}" onclick="document.forms['form'].cat_recount.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.check }}" onclick="document.forms['form'].masscheck.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.repair }}" onclick="document.forms['form'].massrepair.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.optimize }}" onclick="document.forms['form'].massoptimize.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.delete }}" onclick="document.forms['form'].massdelete.value = 'true';"/>
			</td>
		</tr>
		<tr align="left">
			<td width="100%" colspan="8" align="right">
				<input type="checkbox" id="gz" name="gzencode" value="1" class="check"/><label for="gz"> {{ lang.dbo.gzencode }}</label><br/>
				<input type="checkbox" id="email" name="email_send" value="1" class="check"/><label for="email"> {{ lang.dbo.email_send }}</label>
			</td>
		<tr align="left">

			<td width="100%" colspan="8" class="contentEdit" align="right">
				<input class="button" type="submit" value="{{ lang.dbo.backup }}" onclick="document.forms['form'].massbackup.value = 'true';"/>
			</td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
		</tr>
	</table>
</form>

<!-- FORM: Perform actions with backups -->
<form name="backups" method="post" action="{{ php_self }}?mod=dbo">
	<input type="hidden" name="subaction" value="modify"/>
	<input type="hidden" name="token" value="{{ token }}"/>
	<input type="hidden" name="delbackup" value=""/>
	<input type="hidden" name="massdelbackup" value=""/>
	<input type="hidden" name="restore" value=""/>

	<table class="content" border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr align="left">
			<td width="100%" colspan="8" class="contentEdit">
				{{ restore }}
				<input class="button" type="submit" value="{{ lang.dbo.restore }}" onclick="document.forms['backups'].restore.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.delete }}" onclick="document.forms['backups'].delbackup.value = 'true';"/>&nbsp;
				<input class="button" type="submit" value="{{ lang.dbo.deleteall }}" onclick="document.forms['backups'].massdelbackup.value = 'true';"/>
			</td>
		</tr>
	</table>
</form>
