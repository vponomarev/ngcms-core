<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade_090-092.php
// Description: Upgrade NGCMS 0.9.0 => 0.9.2
// Author: Vitaly Ponomarev
//

@define('root', dirname(__FILE__).'/');
@error_reporting(E_ALL ^ E_NOTICE);

//
// Check if we have upgrademe.txt file
//
if (!is_file(root.'upgrademe.txt')) {
    echo 'Ошибка! Для запуска скрипта обновления вам необходимо в каталоге engine/ создать файл <b>upgrademe.txt</b> и удалить его после обновления.';

    return;
}

// Проверка заполнения опросника
if (!$_REQUEST['doupgrade']) {
    questionare_0971();
    exit;
}

@include_once 'core.php';
@include_once root.'includes/inc/extraconf.inc.php';
@include_once root.'includes/inc/extrainst.inc.php';

@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
$PHP_SELF = 'admin.php';

if (($config['skin'] && $config['skin'] != '') && file_exists("./skins/$config[skin]/index.php")) {
    require_once "./skins/$config[skin]/index.php";
} else {
    require_once './skins/default/index.php';
}
echo $skin_header;

//
// Create required fields in DB
//
$query_list_090_091 = [
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
];

$query_list_091_091fp1 = [
    'alter table '.prefix.'_ipban modify column `addr` char(32)',
    'alter table '.prefix.'_ipban modify column addr_start bigint default 0',
    'alter table '.prefix.'_ipban modify column addr_stop bigint default 0',
    'create table if not exists '.prefix.'_config (name char(60), value char(100), primary key(name))',
    'insert into '.prefix.'_config (name, value) values (\'database.engine.version\', \'0.9.1.fp01\') on duplicate key update value=\'0.9.1.fp01\'',
];

$query_list_091fp1_092rc1 = [
    'alter table '.prefix.'_news drop column attach_count',
    'alter table '.prefix.'_news add column num_images int(10) default 0 after flags',
    'alter table '.prefix.'_news add column num_files int(10) default 0 after flags',
];

$query_list_092rc1_092 = [
    'update '.uprefix.'_users set activation=\'\'',
    'alter table '.prefix.'_category add column allow_com int default 2',
];

// Load plugin list
$extras = pluginsGetList();
// Load lang files
$lang = LoadLang('extra-config', 'admin');

if ($_REQUEST['update090_091']) {
    // Выполнение SQL запросов на обновление
    echo '<br/>Выполнение SQL запросов:<br/>';
    echo '<table width="80%">';
    echo '<tr><td><b>Команда</b></td><td><b>Результат</b></td></tr>';

    $flag_err = false;
    foreach ($query_list_090_091 as $sql) {
        $res = $mysql->query($sql);
        $sqlErrorCode = 0;
        $sqlErrorFatal = 0;
        if ($res) {
            // OK
            echo '<tr><td>'.$sql.'</td><td>OK</td></tr>'."\n";
        } else {
            $sqlErrorCode = $mysql->db_errno();
            if (in_array($sqlErrorCode, [1060, 1054, 1091, 1050])) {
                echo '<tr><td>'.$sql.'</td><td>OK/Non fatal error ('.$sqlErrorCode.': '.$mysql->db_error().')</td></tr>'."\n";
            } else {
                echo '<tr><td>'.$sql.'</td><td><font color="red"><b>FAIL</b></font> ('.$sqlErrorCode.': '.$mysql->db_error().')</td></tr>'."\n";
                $flag_err = true;
                break;
            }
        }
    }
    echo "</table><br/>\n\n";

    if ($flag_err) {
        //
        echo "<font color='red'><b>Во время обновления БД произошла ошибка!<br/>Обновление в автоматическом режиме невозможно, Вам необходимо обновить БД вручную.</b></font>";
        exit;
    }

    echo "OK<br /><br />\n";
    echo 'Создание необходимых индексов (если возникнут ошибки - не обращайте на них внимание, это штатная ситуация)...';
    $indexlist = [
        'create index news_editdate on '.prefix.'_news (editdate)',
        'create index news_archive on '.prefix.'_news (favorite, approve)',
        'create index news_main on '.prefix.'_news (`pinned`,`postdate`,`approve`,`mainpage`)',
        'create index news_mainid on '.prefix.'_news (`pinned`,`id`,`approve`,`mainpage`)',
        'create index news_mainpage on '.prefix.'_news (`approve`,`pinned`,`id`)',
        'create index news_mcount on '.prefix.'_news (`mainpage`,`approve`)',
        'create index static_altname on '.prefix.'_static (`alt_name`)',
    ];

    foreach ($indexlist as $i) {
        $mysql->query($i);
    }

    // DSN
    $dir = preg_split('#\\\|/#', dirname(__FILE__));
    array_pop($dir);
    $dsn_dir = implode('/', $dir).'/uploads/dsn';
    echo 'Создание каталога для хранения приложенных к новостям файлов ... '.(mkdir($dsn_dir) ? 'OK' : 'FAIL')."<br/>\n";

    // Перенос настроек в плагины
    $p_vars_map = [
        'com_for_reg'       => [
            'plugin'  => 'comments',
            'name'    => 'regonly',
            'type'    => 'int',
            'reverse' => false,
        ],
        'reverse_comments'  => [
            'plugin' => 'comments',
            'name'   => 'backorder',
            'type'   => 'int',
        ],
        'com_length'        => [
            'plugin' => 'comments',
            'name'   => 'maxlen',
            'type'   => 'int',
        ],
        'com_wrap'          => [
            'plugin' => 'comments',
            'name'   => 'maxwlen',
            'type'   => 'int',
        ],
        'block_many_com'    => [
            'plugin'  => 'comments',
            'name'    => 'multi',
            'type'    => 'int',
            'reverse' => true,
        ],
        'timestamp_comment' => [
            'plugin' => 'comments',
            'name'   => 'timestamp',
            'type'   => 'string',
        ],
    ];

    foreach ($p_vars_map as $old => $set) {
        if (isset($config[$old])) {
            if ($set['type'] == 'int') {
                $nv = $set['reverse'] ? !$config[$old] : $config[$old];
            } else {
                $nv = $config[$old];
            }

            pluginSetVariable($set['plugin'], $set['name'], $nv);
            unset($config[$old]);
        }
    }

    // Сохраняем обновлённый конфиг-файл
    $handler = fopen(confroot.'config.php', 'w');
    $save_config = "<?php\n";
    $save_config .= '$config = ';
    $save_config .= var_export($config, true);
    $save_config .= ";\n";
    $save_config .= '?>';
    fwrite($handler, $save_config);
    fclose($handler);

    // Сохраняем обновления переменных плагинов
    pluginsSaveConfig();

    echo "DONE <br><br>\n<b><u>Внимание!</u></b><br/>После завершения обновления Вам необходимо зайти в админ-панель и активировать следующие плагины: comments, uprofile.";
}

if ($_REQUEST['update091_091fp01']) {
    // Выполнение SQL запросов на обновление
    echo '<br/>Выполнение SQL запросов:<br/>';
    echo '<table width="80%">';
    echo '<tr><td><b>Команда</b></td><td><b>Результат</b></td></tr>';

    $flag_err = false;
    foreach ($query_list_091_091fp1 as $sql) {
        $res = $mysql->query($sql);
        echo '<tr><td>'.$sql.'</td><td>'.($res ? 'OK' : '<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
        if (!$res) {
            $flag_err = true;
            break;
        }
    }
    echo "</table><br/>\n\n";

    if ($flag_err) {
        //
        echo "<font color='red'><b>Во время обновления БД произошла ошибка!<br/>Обновление в автоматическом режиме невозможно, Вам необходимо обновить БД вручную.</b></font>";
        exit;
    }
    echo "OK<br /><br />\n";
}

if ($_REQUEST['update091fp1_092rc1']) {
    // Выполнение SQL запросов на обновление
    echo '<br/>Выполнение SQL запросов:<br/>';
    echo '<table width="80%">';
    echo '<tr><td><b>Команда</b></td><td><b>Результат</b></td></tr>';

    $flag_err = false;
    foreach ($query_list_091fp1_092rc1 as $sql) {
        $res = $mysql->query($sql);
        echo '<tr><td>'.$sql.'</td><td>'.($res ? 'OK' : '<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
        if (!$res) {
            $flag_err = true;
            break;
        }
    }
    echo "</table><br/>\n\n";

    if ($flag_err) {
        //
        echo "<font color='red'><b>Во время обновления БД произошла ошибка!<br/>Обновление в автоматическом режиме невозможно, Вам необходимо обновить БД вручную.</b></font>";
        exit;
    }

    echo "OK<br /><br />\n";
}

if ($_REQUEST['update092rc1_092']) {
    // Выполнение SQL запросов на обновление
    echo '<br/>Выполнение SQL запросов:<br/>';
    echo '<table width="80%">';
    echo '<tr><td><b>Команда</b></td><td><b>Результат</b></td></tr>';

    $flag_err = false;
    foreach ($query_list_092rc1_092 as $sql) {
        $res = $mysql->query($sql);
        echo '<tr><td>'.$sql.'</td><td>'.($res ? 'OK' : '<font color="red"><b>FAIL</b></font>').'</td></tr>'."\n";
        if (!$res) {
            $flag_err = true;
            break;
        }
    }
    echo "</table><br/>\n\n";

    if ($flag_err) {
        //
        echo "<font color='red'><b>Во время обновления БД произошла ошибка!<br/>Обновление в автоматическом режиме невозможно, Вам необходимо обновить БД вручную.</b></font>";
        exit;
    }

    echo "OK<br /><br />\n";
}

echo "Все операции проведены.<br/><a href='?'>назад</a><br/><br/><br/>После окончания обновления вам <font color=\"red\"><u>необходимо</u></font> удалить файл <b>upgrademe.txt</b> из каталога engine/";

function questionare_0971()
{
    echo "
 <style>BODY {PADDING-RIGHT: 8px; PADDING-LEFT: 8px; PADDING-TOP: 5px; PADDING-BOTTOM: 0px; MARGIN: 0px; COLOR: #333; FONT-FAMILY: 'Trebuchet MS', Verdana, Arial, sans-serif; BACKGROUND-COLOR: #fff; }</style>


 <form method='get' action=''>
 <input type=hidden name='doupgrade' value='1'/>
 <b><u>Перед началом обновления вам необходимо ответить на несколько вопросов:</u></b><br /><br />
 <font color='red'><b>ВНИМАНИЕ: </b> перед началом обновления вам <u>ОБЯЗАТЕЛЬНО</u> необходимо сделать
 резервную копию БД</font><br/><br/>
 <table width='80%' border='1'>
 <tr>
  <td>Выполнить обновление структуры БД 0.9.0 => 0.9.1<br/>
  <small>Данную операцию требуется произвести единожды при обновлении со старых версий.<br/>
  Для апгрейда с версии 0.9.1 beta1 - не требуется</td>
  <td width='10%'><input type=checkbox name='update090_091' value='1' /></td>
 </tr>
 <tr>
  <td>Выполнить обновление структуры БД 0.9.1 => 0.9.1 FixPack #01<br/>
  <small>Данную операцию требуется произвести единожды при установке FixPack #01</td>
  <td width='10%'><input type=checkbox name='update091_091fp01' value='1' /></td>
 </tr>
 <tr>
  <td>Выполнить обновление структуры БД 0.9.1 FixPack #01 => 0.9.2 Release Candidate 1<br/>
  <small>Данную операцию требуется произвести единожды при обновлении с версии 0.9.1 FixPack #1 до версии 0.9.2<br/>После обновления БД вам необходимо зайти в 'настройки' => 'управление базой данных' и выполнить 'обновить счетчик новостей'</td>
  <td width='10%'><input type=checkbox name='update091fp1_092rc1' value='1' /></td>
 </tr>
 <tr>
  <td>Выполнить обновление структуры БД 0.9.2 Release Candidate 1 => 0.9.2 Release<br/>
  <small>Данную операцию требуется произвести единожды при обновлении с версии 0.9.1 Release Candidate 1 до версии 0.9.2</td>
  <td width='10%'><input type=checkbox name='update092rc1_092' value='1' /></td>
 </tr>

 </table><br/>
 <input type='submit' value='Начать преобразование!'>
 </form>
 ";
}
