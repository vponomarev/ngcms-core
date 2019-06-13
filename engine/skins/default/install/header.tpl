<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{l_header.title}</title>
	<link href="{templateURL}/style.css" rel="stylesheet" type="text/css" media="screen"/>
	<style type="text/css">
		.mainMenu {
			padding: 0px;
			margin: 0px;
			font-size: 12px;
			margin-bottom: 10px;
		}

		.mainMenu .hover {
			padding-left: 10px;
			padding-right: 10px;
			background-color: #dbe4ed;
			color: #000;
			border-bottom: #317e08 2px solid;
		}

		.mainMenu TD {
			text-align: center;
			padding-left: 10px;
			padding-right: 10px;
			background-color: #dbe4ed;
		}

		h1 {
			padding: 0px;
			margin: 0px;
			margin-left: 5px;
			padding-bottom: 10px;
			font-size: 18px;
			text-decoration: underline;
		}

		.body {
			margin-left: 10px;
			margin-right: 10px;
		}

		.req {
			color: red;
			font-weight: bold;
			vertical-align: super;
		}

		.permBlock {
			font-size: 15px;
			border: #dbe4ed 1px solid;
		}

		.permBlock .permHead {
			color: #000;
			background: #B8D9E7;
			padding: 3px;
		}

		.permBlock .permData {
			padding: 3px;
			padding-bottom: 6px;
		}

		.permBlock .permData table {
			background-color: none;
		}

		.permBlock .permData TD {
			border-bottom: white 1px solid;
			padding: 3px;
		}

		.permBlock .permData THEAD {
			background-color: white;
			color: #3c9c08;
		}

		.plugTable {
			font-size: 12px;
			margin: 5px;
			border: #3c9c08 1px solid;
		}

		.plugTable THEAD {
			background-color: #0000AD;
			color: white;
		}

		.plugTable TBODY {
			background-color: white;
			color: black;
		}

		.plugTable TBODY TD {
			vertical-align: top;
			padding: 3px;
		}

		.plugTable TBODY .box {
			vertical-align: middle;
			text-align: center;
			padding: 3px;
		}

		/*
		.plugBlock .plugHead { color: white; background: #0000AD; padding: 3px; }
		.plugBlock .plugData { padding: 3px; padding-bottom: 6px; font-size: 12px; }
		.pligBlock .plugData TD { border-bottom: white 1px solid; padding: 3px; }
		*/

	</style>
</head>
<body>
<script type="text/javascript" src="{scriptLibrary}/functions.js"></script>
<script type="text/javascript" src="{scriptLibrary}/ajax.js"></script>
<div class="header" align="center">{l_header.headtitle}</div>
<br/>
<table width="100%" border="0" class="mainMenu" cellspacing="0" cellpadding="0">
	<tr>
		<td width="80" {menu_begin}>{l_header.menu.begin}</td>
		<td width="80" {menu_db}>{l_header.menu.db}</td>
		<td width="80" {menu_perm}>{l_header.menu.perm}</td>
		<td width="80" {menu_plugins}>{l_header.menu.plugins}</td>
		<td width="80" {menu_template}>{l_header.menu.template}</td>
		<td width="80" {menu_common}>{l_header.menu.common}</td>
		<td width="80" {menu_install}>{l_header.menu.install}</td>

	</tr>
</table>