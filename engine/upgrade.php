<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
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
	echo "Ошибка! Для запуска скрипта обновления вам необходимо в каталоге engine/ создать файл <b>upgrademe.txt</b> и удалить его после обновления.";
	return;
}

// Проверка заполнения опросника
if (!$_REQUEST['doupgrade']) {
	questionare_097();
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
$query_list = array(
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
	'insert into '.prefix.'_config (name, value) values (\'database.engine.version\', \'0.9.1 beta.1\'',
);



// Load plugin list
$extras	=	get_extras_list();
// Load lang files
$lang	=	LoadLang('extra-config', 'admin');

if ($_REQUEST['db_update']) {
	// Выполнение SQL запросов на обновление
	print '<br/>Выполнение SQL запросов:<br/>';
	print '<table width="80%">';
	print '<tr><td><b>Команда</b></td><td><b>Результат</b></td></tr>';

	$flag_err = false;
	foreach ($query_list as $sql) {
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
		print "<font color='red'><b>Во время обновления БД произошла ошибка!<br/>Обновление в автоматическом режиме невозможно, Вам необходимо обновить БД вручную.</b></font>";
		exit;
	}

	echo "OK<br /><br />\n";
	echo "Создание необходимых индексов (если возникнут ошибки - не обращайте на них внимание, это штатная ситуация)...";
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
	echo "DONE <br><br>\n";
}


print "Все операции проведены.<br/><a href='?'>назад</a>";

function questionare_097() {
 print "
 <style>BODY {PADDING-RIGHT: 8px; PADDING-LEFT: 8px; PADDING-TOP: 5px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #333; FONT-FAMILY: 'Trebuchet MS', Verdana, Arial, sans-serif; BACKGROUND-COLOR: #fff; }</style>


 <form method='get' action=''>
 <input type=hidden name='doupgrade' value='1'/>
 <b><u>Перед началом обновления вам необходимо ответить на несколько вопросов:</u></b><br /><br />
 <font color='red'><b>ВНИМАНИЕ: </b> перед началом обновления вам <u>ОБЯЗАТЕЛЬНО</u> необходимо сделать
 резервную копию БД</font><br/><br/>
 <table width='80%' border='1'>
 <tr>
  <td>Выполнить обновление структуры БД<br/>
  <small>Данную операцию требуется произвести единожды при обновлении со старых версий.<br/>
  Для апгрейда с версии 0.9.1 beta1 - не требуется</td>
  <td width='10%'><input type=checkbox name='db_update' value='1' /></td></tr>
 </table><br/>
 <input type='submit' value='Начать преобразование!'>
 </form>
 ";
}