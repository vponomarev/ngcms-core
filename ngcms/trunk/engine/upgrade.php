<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade.php
// Description: Upgrade NGCMS 0.9.2 => 0.9.3
// Author: Vitaly Ponomarev
//

@define('root', dirname(__FILE__).'/');
@error_reporting (E_ALL ^ E_NOTICE);

//
// Check if we have upgrademe.txt file
//
if (!is_file(root.'upgrademe.txt')) {
	echo "������! ��� ������� ������� ���������� ��� ���������� � �������� engine/ ������� ���� <b>upgrademe.txt</b> � ������� ��� ����� ����������.";
	return;
}

// �������� ���������� ���������
if (!$_REQUEST['doupgrade']) {
	questionare_093();
	exit;
}


@include_once 'core.php';
@include_once root.'includes/inc/extraconf.inc.php';
@include_once root.'includes/inc/extrainst.inc.php';

@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");
$PHP_SELF = "admin.php";

if (($config['skin'] && $config['skin'] != "") && file_exists("./skins/$config[skin]/index.php")) {
	require_once("./skins/$config[skin]/index.php");
}
else { require_once("./skins/default/index.php"); }
echo $skin_header;

//
// Create required fields in DB
//
$query_list_092_093rc1 = array(
	"alter table ".prefix."_images add column plugin char(30) default ''",
	"alter table ".prefix."_images add column pidentity char(30) default ''",
	"alter table ".prefix."_files add column plugin char(30) default ''",
	"alter table ".prefix."_files add column pidentity char(30) default ''",
	"alter table ".prefix."_category add column image_id int default 0 after icon",
	"alter table ".prefix."_category add column info text",
	"insert into ".prefix."_config (name, value) values ('database.engine.version', '0.9.3.rc.1') on duplicate key update value='0.9.3.rc.1'",
);

$query_xfUpdateDB = array(
	"alter table ".prefix."_category add column xf_group char(40) default 0",
	"alter table ".uprefix."_users add column xfields text default null",
	"create table ".prefix."_xfields (id int not null auto_increment, primary key(id), linked_ds int default 0, linked_id int default 0, xfields text default null)",
);

$query_list_093svn = array(
"alter table ".prefix."_news_map add column dt datetime default NULL",
"alter table ".prefix."_news  drop index news_mainid",
"alter table ".prefix."_news  drop index news_catid",
"alter table ".prefix."_news   add index news_mainid (`approve`,`mainpage`,`pinned`,`id`)",
"alter table ".prefix."_news   add index news_catid  (`approve`,`catpinned`,`id`)",
"alter table ".prefix."_news add column catpinned tinyint(1) default '0'",
);
// Load plugin list
$extras	=	get_extras_list();
// Load lang files
$lang	=	LoadLang('extra-config', 'admin');

if ($_REQUEST['update092_093rc1'] || $_REQUEST['xfUpdateDB'] || $_REQUEST['update093svn']) {
	// ���������� SQL �������� �� ����������
	print '<br/>���������� SQL ��������:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>�������</b></td><td><b>���������</b></td></tr>';

	$flag_err = false;

	$queryList = array();
	if ($_REQUEST['update092_093rc1']) {
		$queryList = $query_list_092_093rc1;
	}

	if ($_REQUEST['xfUpdateDB']) {
		$queryList = array_merge($queryList, $query_xfUpdateDB);
	}

	if ($_REQUEST['update093svn']) {
		$queryList = array_merge($queryList, $query_list_093svn);
	}

	foreach ($queryList as $sql) {
		$res = mysql_query($sql);
		$sqlErrorCode = 0;
		$sqlErrorFatal = 0;
		if ($res) {
			// OK
			print '<tr><td>'.$sql.'</td><td>OK</td></tr>'."\n";
		} else {
			$sqlErrorCode = mysql_errno();
			if (in_array($sqlErrorCode, array(1060, 1054, 1091, 1050))) {
				print '<tr><td>'.$sql.'</td><td>OK/Non fatal error ('.$sqlErrorCode.': '.mysql_error().')</td></tr>'."\n";
			} else {
				print '<tr><td>'.$sql.'</td><td><font color="red"><b>FAIL</b></font> ('.$sqlErrorCode.': '.mysql_error().')</td></tr>'."\n";
				$flag_err = true;
				break;
			}
		}
	}
	print "</table><br/>\n\n";

	if ($flag_err) {
		//
		print "<font color='red'><b>�� ����� ���������� �� ��������� ������!<br/>���������� � �������������� ������ ����������, ��� ���������� �������� �� �������.</b></font>";
		exit;
	}

	echo "DONE <br><br>\n<b><u>��������!</u></b><br/>����� ���������� ���������� ��� ���������� ����� � �����-������ � ���������-�������� ��������� �������: uprofile, xfields.";
}


print "��� �������� ���������.<br/><a href='?'>�����</a><br/><br/><br/>����� ��������� ���������� ��� <font color=\"red\"><u>����������</u></font> ������� ���� <b>upgrademe.txt</b> �� �������� engine/";

function questionare_093() {
 print "
 <style>BODY {PADDING-RIGHT: 8px; PADDING-LEFT: 8px; PADDING-TOP: 5px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #333; FONT-FAMILY: 'Trebuchet MS', Verdana, Arial, sans-serif; BACKGROUND-COLOR: #f0f0f0; }</style>


 <form method='get' action=''>
 <input type=hidden name='doupgrade' value='1'/>
 <b><u>����� ������� ���������� ��� ���������� �������� �� ��������� ��������:</u></b><br /><br />
 <font color='red'><b>��������: </b> ����� ������� ���������� ��� <u>�����������</u> ���������� �������
 ��������� ����� ��</font><br/><br/>
 <table width='80%' border='1'>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.2 Release => 0.9.3 Release Candidate 1<br/>
   <small>������ �������� ��������� ���������� �������� ��� ���������� � ������ 0.9.2 Release �� ������ 0.9.3 Release Candidate 1<br/>
   </small>
  </td>
  <td width='10%'><input type=checkbox name='update092_093rc1' value='1' /></td>
 </tr>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.3 Release => 0.9.3 SVN+<br/>
   <small>������ �������� ��������� ���������� �������� ��� ���������� � ������ 0.9.3 Release �� ������� SVN ������ 970<br/>
   </small>
  </td>
  <td width='10%'><input type=checkbox name='update093svn' value='1' /></td>
 </tr>
 <tr>
  <td>�������� �� ������� xfields (��������� ��� ������ 0.10 � ����)<br/>
   <small>������ �������� ��������� ���������� �������� ��� ��������� ����� ������ ������� xfields, ���� ��� ����� ���������� ����� �� ����� ������.<br/>
   ���� ������ xfields � ��� �� ����������, �� ������ �������� �� ���������.
   </small>
  </td>
  <td width='10%'><input type=checkbox name='xfUpdateDB' value='1' /></td>
 </tr>
 </table><br/>
 <input type='submit' value='������ ��������������!'>
 </form>
 ";
}