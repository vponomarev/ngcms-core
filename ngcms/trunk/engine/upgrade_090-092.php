<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade.php
// Description: Upgrade NGCMS 0.9.0 => 0.9.1
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
	questionare_0971();
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
$query_list_090_091 = array(
	'alter table '.prefix.'_category add column flags char(10) default \'\' after alt',
	'update '.prefix.'_category set flags=cat_show',
	'alter table '.prefix.'_category drop column cat_show',
	'alter table '.prefix.'_files add column storage int(1) default 0 after linked_id',
	'alter table '.prefix.'_images add column storage int(1) default 0 after linked_id',
	'drop table '.prefix.'_ipban',
	'create table '.prefix.'_ipban (`id` int not null auto_increment,`addr` char(20),'.
		'`atype` int default 0, `addr_start` int default 0, `addr_stop` int default 0,'.
		'`netlen` int default 0, `flags` char(10) default \'\', `createdate` datetime,'.
		'`reason` char(255), `hitcount` int default 0, PRIMARY KEY  (`id`),'.
		'KEY `ban_start` (`addr_start`) )',
	'alter table '.prefix.'_news add column attach_count int(10) default 0 after flags',
	'create table '.prefix.'_news_map (`newsID` int(11) default NULL,'.
		'`categoryID` int(11) default NULL, KEY `newsID` (`newsID`), KEY `categoryID` (`categoryID`))',
	'create table '.prefix.'_config (name char(60), value char(100), primary key(name))',
	'insert into '.prefix.'_config (name, value) values (\'database.engine.version\', \'0.9.1 beta.1\')',
);

$query_list_091_091fp1 = array(
	'alter table '.prefix.'_ipban modify column `addr` char(32)',
	'alter table '.prefix.'_ipban modify column addr_start bigint default 0',
	'alter table '.prefix.'_ipban modify column addr_stop bigint default 0',
	'create table if not exists '.prefix.'_config (name char(60), value char(100), primary key(name))',
	'insert into '.prefix.'_config (name, value) values (\'database.engine.version\', \'0.9.1.fp01\') on duplicate key update value=\'0.9.1.fp01\'',
);

$query_list_091fp1_092rc1 = array(
	'alter table '.prefix.'_news drop column attach_count',
	'alter table '.prefix.'_news add column num_images int(10) default 0 after flags',
	'alter table '.prefix.'_news add column num_files int(10) default 0 after flags',
);

$query_list_092rc1_092 = array(
	'update '.uprefix.' users set activation=\'\'',
	'alter table '.prefix.'_category add column allow_com int default 2',
);


// Load plugin list
$extras	=	get_extras_list();
// Load lang files
$lang	=	LoadLang('extra-config', 'admin');

if ($_REQUEST['update090_091']) {
	// ���������� SQL �������� �� ����������
	print '<br/>���������� SQL ��������:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>�������</b></td><td><b>���������</b></td></tr>';

	$flag_err = false;
	foreach ($query_list_090_091 as $sql) {
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

	echo "OK<br /><br />\n";
	echo "�������� ����������� �������� (���� ��������� ������ - �� ��������� �� ��� ��������, ��� ������� ��������)...";
	$indexlist = array(
				"create index news_editdate on ".prefix."_news (editdate)",
				"create index news_archive on ".prefix."_news (favorite, approve)",
				"create index news_main on ".prefix."_news (`pinned`,`postdate`,`approve`,`mainpage`)",
				"create index news_mainid on ".prefix."_news (`pinned`,`id`,`approve`,`mainpage`)",
				"create index news_mainpage on ".prefix."_news (`approve`,`pinned`,`id`)",
				"create index news_mcount on ".prefix."_news (`mainpage`,`approve`)",
				"create index static_altname on ".prefix."_static (`alt_name`)",
		);

	foreach ($indexlist as $i) {
		$mysql->query($i);
	}

	// DSN
	$dir = preg_split('#\\\|/#', dirname(__FILE__));
	array_pop($dir);
	$dsn_dir = join('/', $dir) . '/uploads/dsn';
	echo "�������� �������� ��� �������� ����������� � �������� ������ ... ".(mkdir($dsn_dir)?'OK':'FAIL')."<br/>\n";


	// ������� �������� � �������
	$p_vars_map = array(
		'com_for_reg'		=> array(
			'plugin'	=> 'comments',
			'name'		=> 'regonly',
			'type'		=> 'int',
			'reverse'	=> false),
		'reverse_comments'	=> array(
			'plugin'	=> 'comments',
			'name'		=> 'backorder',
			'type'		=> 'int'),
		'com_length'		=> array(
			'plugin'	=> 'comments',
			'name'		=> 'maxlen',
			'type'		=> 'int'),
		'com_wrap'			=> array(
			'plugin'	=> 'comments',
			'name'		=> 'maxwlen',
			'type'		=> 'int'),
		'block_many_com'	=> array(
			'plugin'	=> 'comments',
			'name'		=> 'multi',
			'type'		=> 'int',
			'reverse'	=> true),
		'timestamp_comment' => array(
			'plugin'	=> 'comments',
			'name'		=> 'timestamp',
			'type'		=> 'string'),
	);

	foreach ($p_vars_map as $old => $set) {
		if (isset($config[$old])) {
			if ($set['type'] == 'int') {
				$nv = $set['reverse']?!$config[$old]:$config[$old];
			} else {
				$nv = $config[$old];
			}

			pluginSetVariable($set['plugin'], $set['name'], $nv);
			unset($config[$old]);
		}
	}

	// ��������� ���������� ������-����
	$handler			=	fopen(confroot.'config.php', "w");
	$save_config		=	"<?php\n";
	$save_config		.=	'$config = ';
	$save_config		.=	var_export($config, true);
	$save_config		.=	";\n";
	$save_config		.=	"?>";
	fwrite($handler, $save_config);
	fclose($handler);

	// ��������� ���������� ���������� ��������
	pluginsSaveConfig();

	echo "DONE <br><br>\n<b><u>��������!</u></b><br/>����� ���������� ���������� ��� ���������� ����� � �����-������ � ������������ ��������� �������: comments, uprofile.";
}

if ($_REQUEST['update091_091fp01']) {
	// ���������� SQL �������� �� ����������
	print '<br/>���������� SQL ��������:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>�������</b></td><td><b>���������</b></td></tr>';

	$flag_err = false;
	foreach ($query_list_091_091fp1 as $sql) {
		$res = mysql_query($sql);
		print '<tr><td>'.$sql.'</td><td>'.($res?'OK':'<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
		if (!$res) {
			$flag_err = true;
			break;
		}
	}
	print "</table><br/>\n\n";

	if ($flag_err) {
		//
		print "<font color='red'><b>�� ����� ���������� �� ��������� ������!<br/>���������� � �������������� ������ ����������, ��� ���������� �������� �� �������.</b></font>";
		exit;
	}
	echo "OK<br /><br />\n";
}

if ($_REQUEST['update091fp1_092rc1']) {
	// ���������� SQL �������� �� ����������
	print '<br/>���������� SQL ��������:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>�������</b></td><td><b>���������</b></td></tr>';

	$flag_err = false;
	foreach ($query_list_091fp1_092rc1 as $sql) {
		$res = mysql_query($sql);
		print '<tr><td>'.$sql.'</td><td>'.($res?'OK':'<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
		if (!$res) {
			$flag_err = true;
			break;
		}
	}
	print "</table><br/>\n\n";

	if ($flag_err) {
		//
		print "<font color='red'><b>�� ����� ���������� �� ��������� ������!<br/>���������� � �������������� ������ ����������, ��� ���������� �������� �� �������.</b></font>";
		exit;
	}

	echo "OK<br /><br />\n";
}

if ($_REQUEST['update092rc1_092']) {
	// ���������� SQL �������� �� ����������
	print '<br/>���������� SQL ��������:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>�������</b></td><td><b>���������</b></td></tr>';

	$flag_err = false;
	foreach ($query_list_092rc_092 as $sql) {
		$res = mysql_query($sql);
		print '<tr><td>'.$sql.'</td><td>'.($res?'OK':'<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
		if (!$res) {
			$flag_err = true;
			break;
		}
	}
	print "</table><br/>\n\n";

	if ($flag_err) {
		//
		print "<font color='red'><b>�� ����� ���������� �� ��������� ������!<br/>���������� � �������������� ������ ����������, ��� ���������� �������� �� �������.</b></font>";
		exit;
	}

	echo "OK<br /><br />\n";
}


print "��� �������� ���������.<br/><a href='?'>�����</a><br/><br/><br/>����� ��������� ���������� ��� <font color=\"red\"><u>����������</u></font> ������� ���� <b>upgrademe.txt</b> �� �������� engine/";

function questionare_0971() {
 print "
 <style>BODY {PADDING-RIGHT: 8px; PADDING-LEFT: 8px; PADDING-TOP: 5px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #333; FONT-FAMILY: 'Trebuchet MS', Verdana, Arial, sans-serif; BACKGROUND-COLOR: #fff; }</style>


 <form method='get' action=''>
 <input type=hidden name='doupgrade' value='1'/>
 <b><u>����� ������� ���������� ��� ���������� �������� �� ��������� ��������:</u></b><br /><br />
 <font color='red'><b>��������: </b> ����� ������� ���������� ��� <u>�����������</u> ���������� �������
 ��������� ����� ��</font><br/><br/>
 <table width='80%' border='1'>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.0 => 0.9.1<br/>
  <small>������ �������� ��������� ���������� �������� ��� ���������� �� ������ ������.<br/>
  ��� �������� � ������ 0.9.1 beta1 - �� ���������</td>
  <td width='10%'><input type=checkbox name='update090_091' value='1' /></td>
 </tr>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.1 => 0.9.1 FixPack #01<br/>
  <small>������ �������� ��������� ���������� �������� ��� ��������� FixPack #01</td>
  <td width='10%'><input type=checkbox name='update091_091fp01' value='1' /></td>
 </tr>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.1 FixPack #01 => 0.9.2 Release Candidate 1<br/>
  <small>������ �������� ��������� ���������� �������� ��� ���������� � ������ 0.9.1 FixPack #1 �� ������ 0.9.2<br/>����� ���������� �� ��� ���������� ����� � '���������' => '���������� ����� ������' � ��������� '�������� ������� ��������'</td>
  <td width='10%'><input type=checkbox name='update091fp1_092rc1' value='1' /></td>
 </tr>
 <tr>
  <td>��������� ���������� ��������� �� 0.9.2 Release Candidate 1 => 0.9.2 Release<br/>
  <small>������ �������� ��������� ���������� �������� ��� ���������� � ������ 0.9.1 Release Candidate 1 �� ������ 0.9.2</td>
  <td width='10%'><input type=checkbox name='update02rc1_092' value='1' /></td>
 </tr>

 </table><br/>
 <input type='submit' value='������ ��������������!'>
 </form>
 ";
}