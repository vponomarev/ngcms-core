<?php

//
// Copyright (C) 2006-2017 Next Generation CMS (http://ngcms.ru/)
// Name: dbo.php
// Description: Database managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    die('HAL');
}

// Load language
LoadLang('dbo', 'admin', 'dbo');

function ParseQueries($sql)
{
    $matches = array();
    $output = array();
    $queries = explode(";", $sql);
    $query_count = sizeof($queries);
    unset($sql);

    for ($i = 0; $i < $query_count; $i++) {
        if (($i != ($query_count - 1)) || (strlen($queries[$i]) > 0)) {
            $total_quotes = preg_match_all("/'/", $queries[$i], $matches);
            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $queries[$i], $matches);
            $unescaped_quotes = $total_quotes - $escaped_quotes;

            if (($unescaped_quotes % 2) == 0) {
                $output[] = $queries[$i];
                $queries[$i] = "";
            } else {
                $temp = $queries[$i] . ';';
                $queries[$i] = "";
                $complete_stmt = false;

                for ($j = $i + 1; (!$complete_stmt && ($j < $query_count)); $j++) {
                    $total_quotes = preg_match_all("/'/", $queries[$j], $matches);
                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $queries[$j], $matches);
                    $unescaped_quotes = $total_quotes - $escaped_quotes;

                    if (($unescaped_quotes % 2) == 1) {
                        $output[] = $temp . $queries[$j];
                        $queries[$j] = "";
                        $temp = "";
                        $complete_stmt = true;
                        $i = $j;
                    } else {
                        $temp .= $queries[$j] . ';';
                        $queries[$j] = "";
                    }
                }
            }
        }
    }

    return $output;
}

//
// Modify data request
function systemDboModify()
{
    global $config, $lang, $catz, $notify;

    $db = NGEngine::getInstance()->getDB();

    // Check for permissions
    if (!checkPermission(array('plugin' => '#admin', 'item' => 'dbo'), null, 'modify')) {
        msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
        ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => 0), array('action' => 'modify'), null, array(0, 'SECURITY.PERM'));

        return false;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.dbo'))) {
        msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
        ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => 0), array('action' => 'modify'), null, array(0, 'SECURITY.TOKEN'));

        return false;
    }

    // Update message counters
    if ($_REQUEST['cat_recount']) {
        // Обновляем счётчики в категориях
        $ccount = array();
        $nmap = '';
        $start = 0;
        do {
            $cursor = $db->createCursor("select id, catid, postdate, editdate from " . prefix . "_news where approve=1 limit ".$start.", 10000");
            $qRowCount = 0;
            $start += 10000;
            while ($row = $db->fetchCursor($cursor)) {
                $qRowCount++;
                $ncats = 0;
                foreach (explode(",", $row['catid']) as $key) {
                    if (!$key) {
                        continue;
                    }
                    $ncats++;
                    $nmap .= '(' . $row['id'] . ',' . $key . ',from_unixtime(' . (($row['editdate'] > $row['postdate']) ? $row['editdate'] : $row['postdate']) . ')),';
                    if (!$ccount[$key]) {
                        $ccount[$key] = 1;
                    } else {
                        $ccount[$key] += 1;
                    }
                }
                if (!$ncats) {
                    $nmap .= '(' . $row['id'] . ',0,from_unixtime(' . (($row['editdate'] > $row['postdate']) ? $row['editdate'] : $row['postdate']) . ')),';
                }
            }
        } while ($qRowCount > 0);

        // Update table `news_map`
        $db->createCursor("truncate table " . prefix . "_news_map");

        if (strlen($nmap)) {
            $db->exec("insert into " . prefix . "_news_map (newsID, categoryID, dt) values " . substr($nmap, 0, -1));
        }

        // Update category news counters
        foreach ($catz as $key) {
            $db->exec("update " . prefix . "_category set posts = :posts where id = :id", array('posts' => intval(getIsSet($ccount[$key['id']])), 'id' => $key['id']));
        }

        // Check if we can update comments counters
        $haveComments = $db->tableExists(prefix . "_comments");

        if ($haveComments) {
            $start = 0;
            do {
                $cursor = $db->createCursor("select n.id, count(c.id) as cid from " . prefix . "_news n left join " . prefix . "_comments c on c.post=n.id group by n.id limit :lStart, 10000", array('lStart' => $start));
                $start += 10000;
                $qRowCount = 0;

                while ($row = $db->fetchCursor($cursor)) {
                    $qRowCount++;
                    $db->exec("update " . prefix . "_news set com= :cnt where id= :id", array('cnt' => $row['cid'], 'id' => $row['id']));
                }
            } while ($qRowCount > 0);
        }

        // Обновляем счетчик постов у юзеров
        $db->exec("update " . prefix . "_users set news = 0" . ($haveComments ? ", com = 0" : ""));
        $start = 0;
        do {
            $cursor = $db->createCursor("select author_id, count(*) as cnt from " . prefix . "_news group by author_id limit :lStart, 10000", array('lStart' => $start));
            $start += 10000;
            $qRowCount = 0;

            while ($row = $db->fetchCursor($cursor)) {
                $db->exec("update " . uprefix . "_users set news= :nCount where id = :id", array('nCount' => $row['cnt'], 'id' => $row['author_id']));
            }
        } while ($qRowCount > 0);

        if ($haveComments) {
            // Обновляем счетчик комментариев у юзеров
            foreach ($db->query("select author_id, count(*) as cnt from " . prefix . "_comments group by author_id") as $row) {
                $db->exec("update " . uprefix . "_users set com= :cnt where id = :id", array('cnt' => $row['cnt'], 'id' => $row['author_id']));
            }
        }
        // Обновляем кол-во приложенных файлов/изображений к новостям
        $db->exec("update " . prefix . "_news set num_files = 0, num_images = 0");
        foreach ($db->query("select linked_id, count(id) as cnt from " . prefix . "_files where (storage=1) and (linked_ds=1) group by linked_id") as $row) {
            $db->exec("update " . prefix . "_news set num_files = :cnt where id = :id", array('cnt' => $row['cnt'], 'id' => $row['linked_id']));
        }

        foreach ($db->query("select linked_id, count(id) as cnt from " . prefix . "_images where (storage=1) and (linked_ds=1) group by linked_id") as $row) {
            $db->exec("update " . prefix . "_news set num_images = :cnt where id = :id", array('cnt' => $row['cnt'], 'id' => $row['linked_id']));
        }

        msg(array("text" => $lang['dbo']['msgo_cat_recount']));
    }

    // Delete specific backup file
    if (getIsSet($_REQUEST['delbackup'])) {
        $filename = str_replace('/', '', $_REQUEST['filename']);
        if (!$filename) {
            msg(array("type" => "error", "text" => $lang['dbo']['msge_delbackup']));
        } else {
            @unlink(root . "backups/" . $filename . ".gz");
            sg(array("text" => sprintf($lang['dbo']['msgo_delbackup'], $filename)));
        }
    }

    // MASS: Check/Repair/Optimize tables
    if ($_REQUEST['masscheck'] || $_REQUEST['massrepair'] || $_REQUEST['massoptimize']) {
        $mode = 'check';
        if ($_REQUEST['massrepair']) {
            $mode = 'repair';
        }
        if ($_REQUEST['massoptimize']) {
            $mode = 'optimize';
        }

        $tables = getIsSet($_REQUEST['tables']);
        if (!is_array($tables)) {
            msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
        } else {
            $slist = array();

            for ($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
                if ($db->tableExists($tables[$i])) {
                    $result = $db->record($mode . " table `" . $tables[$i] . "`");
                    if ($result['Msg_text'] == "2 clients are using or haven't closed the table properly") {
                        $result['Msg_text'] = $lang['dbo']['chk_no'];
                    }
                    $slist [] = $tables[$i] . ' &#8594; ' . $result['Msg_text'];
                } else {
                    $slist [] = $tables[$i] . ' &#8594; ' . 'Table doesnot exists';
                }
            }
            msg(array("text" => $lang['dbo']['msgo_' . $mode], 'info' => '<small>' . join("<br/>\n", $slist) . '</small>'));
        }
    }

    // MASS: Delete tables
    if (getIsSet($_REQUEST['massdelete'])) {
        $tables = getIsSet($_REQUEST['tables']);
        if (!$tables) {
            msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
        } else {
            for ($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
                if ($db->tableExists($tables[$i])) {
                    $db->query("drop table `" . $tables[$i] . "`");
                    msg(array("text" => sprintf($lang['dbo']['msgo_delete'], $tables[$i])));
                } else {
                    msg(array("text" => sprintf($lang['dbo']['msgi_noexist'], $tables[$i], 'Table does not exists')));
                }
            }
        }
    }

    // MASS: Backup tables
    if (getIsSet($_REQUEST['massbackup'])) {
        $tables = getIsSet($_REQUEST['tables']);
        if (!$tables) {
            msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
        } else {
            $date = date("Y_m_d_H_i", time());
            $date2 = LangDate("d Q Y - H:i", time());

            $filename = root . "backups/backup_" . $date . (($_REQUEST['gzencode']) ? ".gz" : ".sql");
            dbBackup($filename, $_REQUEST['gzencode']);

            if ($_REQUEST['email_send']) {
                sendEmailMessage($config['admin_mail'], $lang['dbo']['title'], sprintf($lang['dbo']['message'], $date2), $filename);
                @unlink($filename);
                msg(array("text" => $lang['dbo']['msgo_backup_m']));
            } else {
                msg(array("text" => $lang['dbo']['msgo_backup']));
            }
        }
    }
    // MASS: Convert cp1251 to utf8
    if (getIsSet($_REQUEST['massconvert'])) {
        $mode = 'convert';
        $time = microtime(true);
        $msg_error = [];

        $db = $config['dbname'];
        $login = $config['dbuser'];
        $passw = $config['dbpasswd'];
        $host = $config['dbhost'];

        $mysqli = new mysqli($host, $login, $passw, $db);
        if ($mysqli->connect_error) {
            $msg_error[] = secure_html('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error . ' - LINE ' . __LINE__);
        }

        $mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");
        $rs = $mysqli->query("SHOW TABLES;");
        if ($mysqli->errno) {
            $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
        }

        while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $time1 = microtime(true);
            $table_name = $row['Tables_in_' . $db];
            $row_create = $mysqli->query('SHOW CREATE TABLE ' . $table_name);
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
            }

            $row1 = mysqli_fetch_array($row_create, MYSQLI_ASSOC);
            /*if (strpos($row1['Create Table'], 'DEFAULT CHARSET=utf8') !== false) {
                $slist [] = __('dbo')['table'] . ' ' . $table_name . __('dbo')['skipped'];
                continue;
            }*/

            // RENAME TABLE;
            $mysqli->query('RENAME TABLE ' . $table_name . ' TO ' . $table_name . '_tmp_export');
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            // CREATE TABLE SCHEME
            $create_table_scheme = str_ireplace('cp1251', 'utf8', $row1['Create Table']);
            // ENGINE=MyISAM для импортируемых с другой версии таблиц
            $create_table_scheme = str_ireplace('ENGINE=InnoDB', 'ENGINE=MyISAM', $create_table_scheme);
            // $create_table_scheme .= " COLLATE 'utf8_general_ci'"; // Ошибка при соединении `latin1_swedish_ci`
            $mysqli->query($create_table_scheme);
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            $mysqli->query('ALTER TABLE ' . $table_name . ' DISABLE KEYS');
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            $mysqli->query('INSERT INTO ' . $table_name . ' SELECT * FROM ' . $table_name . '_tmp_export');
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            $mysqli->query('DROP TABLE ' . $table_name . '_tmp_export');
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            $time2 = microtime(true);
            $mysqli->query('ALTER TABLE ' . $table_name . ' ENABLE KEYS');
            if ($mysqli->errno) {
                $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
                break;
            }

            $slist [] = 'Enable keys to <b>' . $table_name . '</b>: ' . sprintf("%.4f", (microtime(true) - $time2)) . ' sec. ' .
             'Converted: ' . sprintf("%.4f", (microtime(true) - $time1)) . ' sec.';
        }

        $time3 = microtime(true);
        $mysqli->query("ALTER DATABASE $db DEFAULT CHARACTER SET 'utf8';");
        if ($mysqli->errno) {
            $msg_error[] = secure_html('Select Error (' . $mysqli->errno . ') ' . $mysqli->error . ' - LINE ' . __LINE__);
            return;
        } else {
            $slist [] = "<br>Converted database <b>$db</b> to <b>utf8</b>: " . sprintf("%.4f", (microtime(true) - $time3)) . ' sec.';
        }

        msg(array('type' => 'success', 'title' => $lang['dbo']['msgo_' . $mode]
 , 'message' => join("<br>", $slist) . '<hr>Total time: ' . sprintf("%.4f", (microtime(true) - $time))));
        if (count($msg_error)) {
            msg(array('type' => 'danger', 'title' => $lang['dbo']['msgo_' . $mode], 'message' => join("<br>", $msg_error)));
        }

        mysqli_free_result($rs);
    }

    //MASS: Delete backup files
    if (getIsSet($_REQUEST['massdelbackup'])) {
        $backup_dir = opendir(root . 'backups');
        while ($bf = readdir($backup_dir)) {
            if (($bf == '.') || ($bf == '..')) {
                continue;
            }

            @unlink(root . 'backups/' . $bf);
        }
        msg(array("text" => $lang['dbo']['msgo_massdelb']));
    }

    // RESTORE DB backup
    if (getIsSet($_REQUEST['restore'])) {
        $filename = str_replace('/', '', $_REQUEST['filename']);
        if (!$filename) {
            msg(array("type" => "error", "text" => $lang['dbo']['msge_restore'], "info" => $lang['dbo']['msgi_restore']));
        } else {
            $fp = gzopen(root . 'backups/' . $filename . '.gz', "r");

            // Если архив БД в кодировке 1251, то изменяем набор символов на сеанс соединения.
            // Устанавливает три переменные
            // `character_set_client`, `character_set_connection` и `character_set_results`
            // Установка `character_set_connection` также устанавливает `collation_connection`.
            if (! empty($_POST['cp1251'])) {
                $db->query("SET NAMES 'cp1251'");
            }

            $query = '';
            while (!gzeof($fp)) {
                $query .= gzread($fp, 10000);
            }
            gzclose($fp);
            $queries = ParseQueries($query);

            for ($i = 0; $i < sizeof($queries); $i++) {
                $sql = trim($queries[$i]);

                if (!empty($sql)) {
                    $db->exec($sql);
                }
            }
            msg(array("text" => $lang['dbo']['msgo_restore']));
        }
    }
    return true;
}

//
// List tables
function systemDboForm()
{
    global $lang, $twig, $config, $PHP_SELF, $notify;

    $db = NGEngine::getInstance()->getDB();

    // Check for permissions
    if (!checkPermission(array('plugin' => '#admin', 'item' => 'dbo'), null, 'details')) {
        msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
        ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => 0), array('action' => 'details'), null, array(0, 'SECURITY.PERM'));

        return false;
    }

    $tableList = array();
    foreach ($db->query("SHOW TABLES FROM `" . $config['dbname'] . "` LIKE '" . prefix . "_%'") as $table) {
        $tName = array_pop(array_values($table));
        $info = $db->record("SHOW TABLE STATUS LIKE '" . $tName . "'");

        $tableInfo = array(
            'table'    => $info['Name'],
            'rows'     => $info['Rows'],
            'data'     => Formatsize($info['Data_length'] + $info['Index_length'] + $info['Data_free']),
            'overhead' => ($info['Data_free'] > 0) ? "<span style='color:red;'>" . Formatsize($info['Data_free']) . "</span>" : 0,
        );

        $tableList [] = $tableInfo;
    }

    $tVars = array(
        'php_self' => $PHP_SELF,
        'tables'   => $tableList,
        'restore'  => MakeDropDown(ListFiles(root . 'backups', 'gz'), 'filename', ''),
        'token'    => genUToken('admin.dbo'),
    );

    $xt = $twig->loadTemplate('skins/default/tpl/dbo.tpl');
    return $xt->render($tVars);
}

if (isset($_REQUEST['subaction']) && ($_REQUEST['subaction'] == "modify")) {
    $main_admin = systemDboModify();
}

$main_admin = systemDboForm();
