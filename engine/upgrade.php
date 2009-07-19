<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade.php
// Description: upgrading 2z project
// Author: Vitaly Ponomarev, Alexey Zinchenko
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

if ($_REQUEST['config_move']) {

	//
	// Check if we have config dir
	//
	if (!is_dir(root.'conf')) {
		if (!mkdir(root.'conf',0775)) {
			echo "Ошибка! Не могу создать каталог для конфигурационных файлов. Вам необходимо вручную создать каталог '".root."conf' и установить на него права 0777";
			return;
		}
	}

	//
	// Check if we have config file
	//
	if (!is_file(root.'conf/config.inc.php')) {
		// Check if we have an old config-file
		if (is_file(root.'includes/inc/config.inc.php')) {
			if (!copy(root.'includes/inc/config.inc.php', root.'conf/config.inc.php')) {
				echo "Ошибка! Не могу скопировать конфиг-файл на новое место! Установите права доступа 0777 на каталог '".root."/conf'";
				return;
			}
		}
	}

	//
	// Check for plugin-activation file
	//
	if (!is_file(root.'conf/plugins.php')) {
		// Check if we have an old plugins activation file
		if (is_file(root.'includes/inc/extras-on.inc.php')) {
			if (!copy(root.'includes/inc/extras-on.inc.php', root.'conf/plugins.php')) {
				echo "Ошибка! Не могу скопировать файл '".root."includes/inc/extras-on.inc.php' на новое место ('".root."conf/plugins.php'). Выполните эту операцию вручную и установите права 0777 на новый файл";
				return;
			}
		}
	}

	//
	// Check for plugin-data file
	//
	if (!is_file(root.'conf/plugdata.php')) {
		// Check if we have an old plugins activation file
		if (is_file(root.'includes/inc/modules_config.inc.txt')) {
			if (!copy(root.'includes/inc/modules_config.inc.txt', root.'conf/plugdata.php')) {
				echo "Ошибка! Не могу скопировать файл '".root."includes/inc/modules_config.inc.txt' на новое место ('".root."conf/plugdata.php'). Выполните эту операцию вручную и установите права 0777 на новый файл";
				return;
			}
		}
	}

	//
	// Check for links.inc.php file
	//
	if (!is_file(root.'conf/links.inc.php')) {
		// Check if we have an old links file
		if (is_file(root.'includes/inc/links.inc.php')) {
			if (!copy(root.'includes/inc/links.inc.php', root.'conf/links.inc.php')) {
				echo "Ошибка! Не могу скопировать файл '".root."includes/inc/links.inc.php' на новое место ('".root."conf/links.inc.php'). Выполните эту операцию вручную и установите права 0777 на новый файл";
				return;
			}
		}
	}

}

//
// Check for DISABLING .htaccess file
//
if (!is_file(root.'conf/.htaccess')) {
	if (!($fa = fopen(root.'conf/.htaccess', 'w'))) {
		echo "Ошибка! Не могу создать файл '".root."conf/.htaccess'. Установите права 0777 на каталог '".root."/conf'";
		return;
	}
	fwrite($fa,"<files *>\n Order Deny,Allow\n Deny from All\n</files>\n\n");
	fclose($fa);
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
$db_update = array(
	array(
		'table' => 'users',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'ip',  'type' => 'char(15)', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'authcookie', 'type' => 'char(50)'),
			array('action' => 'cmodify', 'name' => 'newpw', 'type' => 'char(32)'),
			array('action' => 'cmodify', 'name' => 'posts', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'category',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'alt_url',  'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'orderby', 'type' => 'char(30)', 'params' => "default 'id desc'"),
			array('action' => 'cmodify', 'name' => 'posts', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'posorder', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'poslevel', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'news',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'author_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'flags', 'type' => 'int(1)', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'comments',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'author_id', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'static',
		'action' => 'cmodify',
		'key' => 'primary key(id)',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'id',  'type' => 'int', 'params' => 'not null auto_increment'),
			array('action' => 'cmodify', 'name' => 'title', 'type' => 'char(255)'),
			array('action' => 'cmodify', 'name' => 'alt_name', 'type' => 'char(255)'),
			array('action' => 'cmodify', 'name' => 'template', 'type' => 'char(100)', 'params' => 'not null'),
			array('action' => 'cmodify', 'name' => 'content', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'description', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'keywords', 'type' => 'text'),
			array('action' => 'cmodify', 'name' => 'approve',  'type' => 'tinyint(1)', 'params' => 'not null'),
			array('action' => 'cmodify', 'name' => 'flags', 'type' => 'int(1)', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'images',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'owner_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'stamp', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'category', 'type' => 'int', 'params' => 'default 0'),
		)
	),
	array(
		'table' => 'files',
		'action' => 'modify',
		'fields' => array(
			array('action' => 'cmodify', 'name' => 'owner_id', 'type' => 'int', 'params' => 'default 0'),
			array('action' => 'cmodify', 'name' => 'category', 'type' => 'int', 'params' => 'default 0'),
		)
	),
);

// Load plugin list
$extras	=	get_extras_list();
// Load lang files
$lang	=	LoadLang('extra-config', 'admin');

if ($_REQUEST['db_update']) {
	if (fixdb_plugin_install('upgrade', $db_update)) {
		echo "<br /><br />Обновление БД выполнено успешно<br /><br />Восстановление индексных данных...\n";

		// news
		foreach ($mysql->select("select n.*, u.id as uid from ".prefix."_news as n left join ".uprefix."_users u on n.author=u.name where (n.author_id is NULL) or (n.author_id < 1)") as $row) {
			$mysql->query("update ".prefix."_news set author_id = '".$row['uid']."' where id = ".$row['id']);
		}

		// comments
		foreach ($mysql->select("select c.*, u.id as uid from ".prefix."_comments as c left join ".uprefix."_users u on c.author=u.name where ((c.author_id is NULL) or (c.author_id < 1)) and (c.reg = 1)") as $row) {
			$mysql->query("update ".prefix."_comments set author_id = '".$row['uid']."' where id = ".$row['id']);
		}

		echo "OK<br /><br />\n";
		echo "Создание необходимых индексов (если возникнут ошибки - не обращайте на них внимание, это штатная ситуация)...";
		$indexlist = array(	"create index c_post on ".prefix."_comments (post)",
					"create index alt_name on ".prefix."_news (alt_name)",
					"create index news_title on ".prefix."_news (title)",
					"create index news_postdate on ".prefix."_news (postdate)",
					"create index news_catid on ".prefix."_news (catid)",
					"create index news_view on ".prefix."_news (views)",
					"create index static_title on ".prefix."_static (title)",
					"create index users_name on ".prefix."_users (name)",
					"create index users_auth on ".prefix."_users (authcookie)");

		foreach ($indexlist as $i) {
			$mysql->query($i);
		}
		echo "DONE <br><br>\n";
	} else {
		echo "Ошибка: не удалось обновить БД<br />\n";
	}
}

// Now, let's check for auth_basic module
if (is_array($extras['auth_basic'])) {
	// Found
	if (!getPluginStatusActive('auth_basic')) {
		if (pluginSwitch('auth_basic', 'on')) {
			echo "Модуль 'auth_basic' активирован!<br />\n";
		} else {
			echo "Ошибка: не могу активировать модуль 'auth_basic'<br />\n";
		}
	}
} else {
	echo "Ошибка: модуль 'auth_basic' не найден! Без него невозможно восстановление системы. Установите данный модуль и запустите данный скрипт ещё раз.<br />\n";
}



if ($_REQUEST['news_html']) {
	$mysql->query("update ".prefix."_news set flags=2 where flags=0");
	$mysql->query("update ".prefix."_static set flags=2 where flags=0");
	print "<font color='blue'><b>*</b></font> Флаг 'разрешить HTML код в новостях' установлен для всех новостей/статик страниц<br/>";
}

if ($_REQUEST['news_br']) {
	$mysql->query("update ".prefix."_news set content=replace(content,'<br />', '\\n')");
	$mysql->query("update ".prefix."_static set content=replace(content,'<br />', '\\n')");
	print "<font color='blue'><b>*</b></font> Замена тега '&lt;br /&gt;' во всех новостях/статик страницах проведена!<br/>";
}

if ($_REQUEST['avatar_names']) {
	$mysql->query("update ".uprefix."_users set avatar=concat(id,'.',avatar) where (avatar <> '') and (avatar not like concat(id,'.%'))");
	print "<font color='blue'><b>*</b></font> Преобразование имён аватарок выполнено!<br/>";
}

if ($_REQUEST['photos_thumbs']) {
        $dir = root.'../uploads/photos';
        mkdir($dir.'/thumb');
        $DIR = opendir($dir);
        while (($fn = readdir($DIR)) !== false) {
        	if (preg_match('/^\d+\.thumb\./', $fn)) {
        		rename($dir.'/'.$fn, $dir.'/thumb/'.str_replace('.thumb','',$fn));
        	}	
        }
        closedir($DIR);

	print "<font color='blue'><b>*</b></font> Перемещение уменьшенных копий фотографий выполнено!<br/>";
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
  <td>Выполнить перенос конфигурационных файлов<br/>
  <small>Данная операция требуется при обновлении со старых версий (0.9.5-0.9.6).<br/>
  Для апгрейда с версий 0.9.7 alpha и старше - не требуется</td>
  <td width='10%'><input type=checkbox name='config_move' value='1' /></td></tr>
 <tr>
  <td>Выполнить обновление структуры БД<br/>
  <small>Данную операцию требуется произвести единожды при обновлении со старых версий.<br/>
  Для апгрейда с верси 0.9.7 beta2 - не требуется</td>
  <td width='10%'><input type=checkbox name='db_update' value='1' /></td></tr>
 <tr>
  <td>Устанавливать всем новостям флаг 'Разрешить HTML код в новостях/статик страницах'<br />
  <small>Данный флаг необходимо установить при обновлении до версии 0.9.7, иначе
  HTML коды в ваших новостях/статик страницах отображаться не будут.</small></td>
  <td width='10%'><input type=checkbox name='news_html' value='1' /></td></tr>
 <tr>
  <td>Провести автозамену текста '&lt;br/&gt;' в новостях/статик страницах на перевод строки<br />
  <small>В новой версии новость хранится в БД без изменений, в старой же перевод строк
  заменялся на '&lt;br/&gt;'. <br/>Автозамена позволит вам корректно просматривать для редактирования
  старые новости/статик страницы.</small></td>
  <td width='10%'><input type=checkbox name='news_br' value='1' /></td></tr>
 <tr>
  <td>Преобразовать ссылки на аватары пользователей<br/>
  <small>Из-за изменившегося механизма хранения информации об аватарах пользователей,
  вам необходимо провести преобразование ссылок.<br />
  <font color='red'><b>ВНИМАНИЕ:</b> данная операция <u>тупо</u> добавляет к аватару
  пользователя его ID и символ точки (в случае если подобного префикса ещё нет).</b></font></small></td>
  <td width='10%'><input type=checkbox name='avatar_names' value='1' /></td>
 </tr>
 <tr>
  <td>Перемещать уменьшенные копии фотографий пользователей<br/>
  <small>Из-за изменившегося механизма хранения информации о фотографиях пользователей,
  вам необходимо переместить и переименовать файлы уменьшенных копий.<br />
  <font color='red'><b>ВНИМАНИЕ:</b> данная операция просто перемещает файлы фотографий вида <b>число</b>.thumb.<b>что-то ещё</b>
  в подкаталог thumb/, убирая из названия текст '.thumb'.</font></small></td>
  <td width='10%'><input type=checkbox name='photos_thumbs' value='1' /></td>
 </tr>
 </table><br/>
 <input type='submit' value='Начать преобразование!'>
 </form>
 ";
}
