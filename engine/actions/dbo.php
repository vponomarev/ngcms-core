<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: dbo.php
// Description: Database managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('dbo', 'admin');


function ParseQueries($sql) {

	$matches		=	array();
	$output			=	array();
	$queries		=	explode(";", $sql);
	$query_count	=	sizeof($queries);
	$sql			=	'';

	for ($i = 0; $i < $query_count; $i++) {
		if (($i != ($query_count - 1)) || (strlen($queries[$i]) > 0)) {
			$total_quotes = preg_match_all("/'/", $queries[$i], $matches);
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $queries[$i], $matches);
			$unescaped_quotes = $total_quotes - $escaped_quotes;

			if (($unescaped_quotes % 2) == 0) {
				$output[] = $queries[$i];
				$queries[$i] = "";
			}
			else {
				$temp = $queries[$i].';';
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
					}
					else {
						$temp .= $queries[$j].';';
						$queries[$j] = "";
					}
				}
			}
		}
	}

	return $output;
}

if ($_REQUEST['check']) {
	$table = $_REQUEST['table'];
	if ($mysql->table_exists($table)) {
		$result = $mysql->record("check table `$table`");
		msg(array("text" => sprintf($lang['msgo_check'], $table, $result['Msg_text'])));
	} else {
		msg(array("text" => sprintf($lang['msgi_noexist'], $table, $result['Msg_text'])));
	}
}

if ($_REQUEST['cat_recount']) {
	// Обновляем счётчики в категориях
	$ccount = array();
	foreach ($mysql->select("select catid from ".prefix."_news") as $row) {
		foreach (explode(",",$row['catid']) as $key) {
		        if (!$key) { continue; }
			if (!$ccount[$key]) { $ccount[$key] = 1; } else { $ccount[$key]+=1; }
		}
	}

	foreach ($catz as $key) {
		$mysql->query("update ".prefix."_category set posts = ".intval($ccount[$key['id']])." where id = ".$key['id']);
	}

	foreach ($mysql->select("select n.id, count(c.id) as cid from ".prefix."_news n left join ".prefix."_comments c on c.post=n.id group by n.id") as $row) {
		$mysql->query("update ".prefix."_news set com=".$row['cid']." where id = ".$row['id']);
  	}

  	// Обновляем счетчик постов у юзеров
  	$mysql->query("update ".prefix."_users set news=0");
  	foreach ($mysql->select("select author_id, count(*) as cnt from ".prefix."_news group by author_id") as $row) {
  		$mysql->query("update ".uprefix."_users set news=".$row['cnt']." where id = ".$row['author_id']);
  	}

  	msg(array("text" => $lang['msgo_cat_recount']));
}

if ($_REQUEST['repair']) {
	$table = $_REQUEST['table'];
	if ($mysql->table_exists($table)) {
		$result = $mysql->record("repair table `$table`");
		msg(array("text" => sprintf($lang['msgo_repair'], $table, $result['Msg_text'])));
	} else {
		msg(array("text" => sprintf($lang['msgi_noexist'], $table, $result['Msg_text'])));
	}	
}

if ($_REQUEST['optimize']) {
	$table = $_REQUEST['table'];
	if ($mysql->table_exists($table)) {
		$result = $mysql->record("optimize table `$table`");
		if ($result['Msg_text'] == "Table is already up to date") {
			$result['Msg_text'] = $lang['opt_no'];
		} else {
			$result['Msg_text'] = $lang['opt_ok'];
		}
		msg(array("text" => sprintf($lang['msgo_optimize'], $table, $result['Msg_text'])));
	} else {
		msg(array("text" => sprintf($lang['msgi_noexist'], $table, $result['Msg_text'])));
	}	
}

if ($_REQUEST['delbackup']) {
        $filename = str_replace('/','', $_REQUEST['filename']);
	if (!$filename) {
		msg(array("type" => "error", "text" => $lang['msge_delbackup']));
	}
	else {
		@unlink(root."backups/".$filename.".gz");
		msg(array("text" => sprintf($lang['msgo_delbackup'], $filename)));
	}
}

if ($_REQUEST['masscheck']) {
        $tables = $_REQUEST['tables'];
	if (!$tables) {
		msg(array("type" => "error", "text" => $lang['msge_tables'], "info" => $lang['msgi_tables']));
	}
	else {
		for ($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
			if ($mysql->table_exists($tables[$i])) {
				$result = $mysql->record("check table `".$tables[$i]."`");
				if ($result['Msg_text'] == "2 clients are using or haven't closed the table properly") {
					$result['Msg_text'] = $lang['chk_no'];
				}
				msg(array("text" => sprintf($lang['msgo_check'], $tables[$i], $result['Msg_text'])));
			} else {
				msg(array("text" => sprintf($lang['msgi_noexist'], $tables[$i], $result['Msg_text'])));
			}	
		}
	}
}

if ($_REQUEST['massrepair']) {
        $tables = $_REQUEST['tables'];
	if (!$tables) {
		msg(array("type" => "error", "text" => $lang['msge_tables'], "info" => $lang['msgi_tables']));
	}
	else {
		for($i = 0, $sizeof = sizeof($tables); $i < sizeof($tables); $i++) {
			if ($mysql->table_exists($tables[$i])) {
				$result = $mysql->record("repair table `".$tables[$i]."`");
				msg(array("text" => sprintf($lang['msgo_repair'], $tables[$i], $result['Msg_text'])));
			} else {
				msg(array("text" => sprintf($lang['msgi_noexist'], $tables[$i], $result['Msg_text'])));
			}	
		}
	}
}

if ($_REQUEST['massoptimize']) {
        $tables = $_REQUEST['tables'];
	if (!$tables) {
		msg(array("type" => "error", "text" => $lang['msge_tables'], "info" => $lang['msgi_tables']));
	} else {
		for($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
			if ($mysql->table_exists($tables[$i])) {
				$result = $mysql->record("optimize table `".$tables[$i]."`");
				if ($result['Msg_text'] == "Table is already up to date") {
					$result['Msg_text'] = $lang['opt_no'];
				} else {
					$result['Msg_text'] = $lang['opt_ok'];
				}
				msg(array("text" => sprintf($lang['msgo_optimize'], $tables[$i], $result['Msg_text'])));
			} else {
				msg(array("text" => sprintf($lang['msgi_noexist'], $tables[$i], $result['Msg_text'])));
			}	
		}
	}
}

if ($_REQUEST['massdelete']) {
        $tables = $_REQUEST['tables'];
	if (!$tables) {
		msg(array("type" => "error", "text" => $lang['msge_tables'], "info" => $lang['msgi_tables']));
	}
	else {
		for($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
			if ($mysql->table_exists($tables[$i])) {
				$mysql->query("drop table `".$tables[$i]."`");
				msg(array("text" => sprintf($lang['msgo_delete'], $tables[$i])));
			} else {
				msg(array("text" => sprintf($lang['msgi_noexist'], $tables[$i], $result['Msg_text'])));
			}	
		}
	}
}

if ($_REQUEST['massbackup']) {
        $tables = $_REQUEST['tables'];
	if (!$tables) {
		msg(array("type" => "error", "text" => $lang['msge_tables'], "info" => $lang['msgi_tables']));
	} else {
		$date = date("Y_m_d_H_i", time());
		$date2 = LangDate("d Q Y - H:i", time());

		$filename = root."backups/backup_".$date.(($_REQUEST['gzencode'])?".gz":".sql");
		dbBackup($filename, $_REQUEST['gzencode']);

		if ($_REQUEST['email_send']) {
			zzMail($config['admin_mail'], $lang['title'], sprintf($lang['message'], $date2), $filename);
			@unlink($filename);
			msg(array("text" => $lang['msgo_backup_m']));
		}
		else {
			msg(array("text" => $lang['msgo_backup']));
		}
	}
}

if ($_REQUEST['massdelbackup']) {
	$backup_dir = opendir(root.'backups');
	readdir($backup_dir);
	readdir($backup_dir);
	readdir($backup_dir);
	$i = '0';

	while($bf = readdir($backup_dir)) {
		$bf_arr[] = $bf;
		@unlink (root.'backups/'.$bf_arr[$i]);
		$i++;
	}
	msg(array("text" => $lang['msgo_massdelb']));
}

if ($_REQUEST['restore']) {
        $filename = str_replace('/','', $_REQUEST['filename']);
	if (!$filename) {
		msg(array("type" => "error", "text" => $lang['msge_restore'], "info" => $lang['msgi_restore']));
	}
	else {
		$fp = gzopen(root.'backups/'.$filename.'.gz', "r");

		while (!gzeof($fp)) {
			$query .= gzread($fp, 10000);
		}
		gzclose($fp);
		$queries = ParseQueries($query);

		for ($i = 0; $i < sizeof($queries); $i++) {
			$sql = trim($queries[$i]);

			if (!empty($sql)) {
				$mysql->query($sql);
			}
		}
		msg(array("text" => $lang['msgo_restore']));
	}
}

if (!$action) {	
	foreach($mysql->select("SHOW TABLES FROM `".$config['dbname']."` LIKE '".prefix."_%'") as $table) {
		$info		=	$mysql->record("SHOW TABLE STATUS LIKE '".$table[0]."'");
		$data		=	Formatsize($info['Data_length'] + $info['Index_length'] + $info['Data_free']);
		$overhead	=	($info['Data_free'] > 0) ? "<span style='color:red;'>".Formatsize($info['Data_free'])."</span>" : 0 ;

		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'table'		=>	$info['Name'],
			'rows'		=>	$info['Rows'],
			'data'		=>	$data,
			'overhead'	=>	$overhead
		);

		$tpl -> template('entries', tpl_actions.$mod);
		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	$tpl -> template('table', tpl_actions.$mod);
	$tvars['vars'] = array(
		'php_self'	=>	$PHP_SELF,
		'entries'	=>	$entries,
		'restore'	=>	MakeDropDown(ListFiles(root."backups", "gz"), "filename", ''),
	);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}
