<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{l_langcode}" lang="{l_langcode}" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset={l_encoding}" />
<meta http-equiv="content-language" content="{l_langcode}" />
<meta name="generator" content="{what} {version}" />
<meta name="document-state" content="dynamic" />
{htmlvars}
<link href="{tpl_url}/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="{home}/rss.xml" rel="alternate" type="application/rss+xml" title="RSS" />
<script type="text/javascript" src="{admin_url}/includes/js/functions.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/ajax.js"></script>
<script type="text/javascript" src="{admin_url}/includes/js/jquery-1.6.3.min.js"></script>
<title>{titles}</title>
</head>
<body>
[sitelock]
<div id="loading-layer"><img src="{tpl_url}/images/loading.gif" alt="" /></div>
<table align="center" border="0" width="1000" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_01.gif" width="225" height="142" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_02.gif');" width="100%">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>&nbsp;</td>
						<td width="257">
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td><a onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://site.ru/');" href="#">Сделать стартовой</a> | <a style="cursor: pointer;" onclick="window.external.AddFavorite('http://site.ru/', 'Site.Ru!');">Добавить в избранное</a></td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td>{search_form}</td>
							</tr>
							<tr>
								<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
									<tr>
										<td>
										<img border="0" src="{tpl_url}/images/2z_19.gif" width="6" height="72" /></td>
										<td style="background-image:url('{tpl_url}/images/2z_20.gif');" width="100%">{personal_menu}</td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_04.gif" width="10" height="142" /></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="background-image:url('{tpl_url}/images/2z_78.gif');" width="12">&nbsp;</td>
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top" width="201">
								<table border="0" width="200" cellspacing="0" cellpadding="0">
									<tr>
										<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_35.gif" width="7" height="36" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_36.gif');" width="100%">&nbsp;<font color="#FFFFFF"><b>Категории</b></font></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_38.gif" width="7" height="36" /></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="background-image:url('{tpl_url}/images/2z_56.gif');" width="7">&nbsp;</td>
				<td bgcolor="#FFFFFF"><ul><li style="list-style-type: none;"><a href="{home}">Главная</a></li>{categories}</ul></td>
				<td style="background-image:url('{tpl_url}/images/2z_58.gif');" width="7">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<img border="0" src="{tpl_url}/images/2z_60.gif" width="7" height="11" /></td>
				<td style="background-image:url('{tpl_url}/images/2z_61.gif');" width="100%"></td>
				<td>
				<img border="0" src="{tpl_url}/images/2z_62.gif" width="7" height="11" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table></td>
</tr>

<tr><td>&nbsp;</td></tr>

[isplugin tags]
<!-- TAGS start -->
<tr><td>
{plugin_tags}
</td></tr>
<!-- TAGS end -->

<tr><td>&nbsp;</td></tr>
[/isplugin]

<!-- FAVORITES start -->
<tr><td>
{plugin_favorites}
</td></tr>
<!-- FAVORITES end -->

<tr><td>&nbsp;</td></tr>

<!-- POPULAR start -->
<tr><td>
{plugin_popular}
</td></tr>
<!-- POPULAR end -->

<tr><td>&nbsp;</td></tr>

<!-- LASTCOMMENTS start -->
<tr><td>
{plugin_lastcomments}
</td></tr>
<!-- LASTCOMMENTS end -->

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>

</table>
</td>
<td valign="top" width="8">&nbsp;</td>
<td valign="top" width="608">{mainblock}</td>
<td valign="top" width="10">&nbsp;</td>
<td valign="top" width="173">
<table border="0" width="200" cellspacing="0" cellpadding="0">

[isplugin calendar]
<!-- CALENDAR start -->
<tr><td>
{plugin_calendar}
</td></tr>
<tr><td>&nbsp;</td></tr>
<!-- CALENDAR end -->
[/isplugin]

[isplugin archive]
<!-- Archive -->
<tr><td>
{plugin_archive}
</td></tr>
[/isplugin]

[isplugin jchat]
<!-- JChat -->
<tr><td>
{plugin_jchat}
</td></tr>
[/isplugin]

[isplugin lastnews]
<!-- LastComments -->
<tr><td>
{plugin_lastnews}
</td></tr>
[/isplugin]

<!-- inc begin -->
[isplugin voting]
<tr><td>&nbsp;</td></tr>
<tr><td>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><img border="0" src="{tpl_url}/images/2z_35.gif" width="7" height="36" /></td>
		<td style="background-image:url('{tpl_url}/images/2z_36.gif');" width="100%">&nbsp;<b><font color="#FFFFFF">Наш опрос</font></b></td>
		<td><img border="0" src="{tpl_url}/images/2z_38.gif" width="7" height="36" /></td>
		</tr>
	</table>
    </td>
</tr>
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td style="background-image:url('{tpl_url}/images/2z_56.gif');" width="7">&nbsp;</td>
		<td bgcolor="#FFFFFF">{voting}</td>
		<td style="background-image:url('{tpl_url}/images/2z_58.gif');" width="7">&nbsp;</td>
	</tr>
	</table>
    </td>
</tr>
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><img border="0" src="{tpl_url}/images/2z_60.gif" width="7" height="11" /></td>
		<td style="background-image:url('{tpl_url}/images/2z_61.gif');" width="100%"></td>
		<td><img border="0" src="{tpl_url}/images/2z_62.gif" width="7" height="11" /></td>
	</tr>
	</table>
    </td>
</tr>
</table></td>
</tr>

<tr><td>&nbsp;</td></tr>
[/isplugin]
<!-- inc end -->
								
<tr><td>&nbsp;</td></tr>

								</table>
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</td>
				<td style="background-image:url('{tpl_url}/images/2z_80.gif');" width="16">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr><td>
			<img border="0" src="{tpl_url}/images/2z_81.gif" width="12" height="65" /></td>
			<td style="background-image:url('{tpl_url}/images/2z_83.gif');" width="100%">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="mw_copy">
					Copyright &copy; 2007-2012 <a title="{home_title}" href="{home}">{home_title}</a><br />Powered by <a title="Next Generation CMS" target="_blank" href="http://ngcms.ru/">NG CMS</a> 
				</td><td class="mw_copy" align=right>SQL запросов: <b>{queries}</b> | Генерация страницы: <b>{exectime}</b> сек | <b>{memPeakUsage}</b> Mb&nbsp;</td>
			</tr>
			</table></td>
				<td><img border="0" src="{tpl_url}/images/2z_85.gif" width="16" height="65" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
[/sitelock]
[debug]
{debug_queries}<br/>{debug_profiler}
[/debug]
</body>
</html>