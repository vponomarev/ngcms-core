<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: dbo.php
// Description: Database managment
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

// Load language
LoadLang('dbo', 'admin', 'dbo');


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


//
// Modify data request
function systemDboModify() {
	global $config, $mysql, $lang, $catz;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'dbo'), null, 'modify')) {
		msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => $id), array('action' => 'modify'), null, array(0, 'SECURITY.PERM'));
		return false;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.dbo'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => $id), array('action' => 'modify'), null, array(0, 'SECURITY.TOKEN'));
		return false;
	}

	// Update message counters
	if ($_REQUEST['cat_recount']) {
		// ��������� �������� � ����������
		$ccount = array();
		$nmap = '';
		foreach ($mysql->select("select id, catid, postdate, editdate from ".prefix."_news where approve=1") as $row) {
			foreach (explode(",",$row['catid']) as $key) {
			        if (!$key) { continue; }
			        $nmap .= '('.$row['id'].','.$key.',from_unixtime('.(($row['editdate']>$row['postdate'])?$row['editdate']:$row['postdate']).')),';
				if (!$ccount[$key]) { $ccount[$key] = 1; } else { $ccount[$key]+=1; }
			}
		}

		// Update table `news_map`
		$mysql->query("truncate table ".prefix."_news_map");

		if (strlen($nmap))
			$mysql->query("insert into ".prefix."_news_map (newsID, categoryID, dt) values ".substr($nmap,0,-1));

		// Update category news counters
		foreach ($catz as $key) {
			$mysql->query("update ".prefix."_category set posts = ".intval($ccount[$key['id']])." where id = ".$key['id']);
		}

		// Check if we can update comments counters
		$haveComments = $mysql->table_exists(prefix."_comments")?true:false;

		if ($haveComments) {
			foreach ($mysql->select("select n.id, count(c.id) as cid from ".prefix."_news n left join ".prefix."_comments c on c.post=n.id group by n.id") as $row) {
				$mysql->query("update ".prefix."_news set com=".$row['cid']." where id = ".$row['id']);
			}
		}

	  	// ��������� ������� ������ � ������
	  	$mysql->query("update ".prefix."_users set news = 0".($haveComments?", com = 0":""));
	  	foreach ($mysql->select("select author_id, count(*) as cnt from ".prefix."_news group by author_id") as $row) {
	  		$mysql->query("update ".uprefix."_users set news=".$row['cnt']." where id = ".$row['author_id']);
	  	}

		if ($haveComments) {
		  	// ��������� ������� ������������ � ������
		  	foreach ($mysql->select("select author_id, count(*) as cnt from ".prefix."_comments group by author_id") as $row) {
		  		$mysql->query("update ".uprefix."_users set com=".$row['cnt']." where id = ".$row['author_id']);
		  	}
		}
		// ��������� ���-�� ����������� ������/����������� � ��������
		$mysql->query("update ".prefix."_news set num_files = 0, num_images = 0");
		foreach ($mysql->select("select linked_id, count(id) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) group by linked_id") as $row) {
			$mysql->query("update ".prefix."_news set num_files = ".db_squote($row['cnt'])." where id = ".db_squote($row['linked_id']));
		}

		foreach ($mysql->select("select linked_id, count(id) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) group by linked_id") as $row) {
			$mysql->query("update ".prefix."_news set num_images = ".db_squote($row['cnt'])." where id = ".db_squote($row['linked_id']));
		}

	  	msg(array("text" => $lang['dbo']['msgo_cat_recount']));
	}

	// Delete specific backup file
	if ($_REQUEST['delbackup']) {
	        $filename = str_replace('/','', $_REQUEST['filename']);
		if (!$filename) {
			msg(array("type" => "error", "text" => $lang['dbo']['msge_delbackup']));
		}
		else {
			@unlink(root."backups/".$filename.".gz");
			msg(array("text" => sprintf($lang['dbo']['msgo_delbackup'], $filename)));
		}
	}

	// MASS: Check/Repair/Optimize tables
	if ($_REQUEST['masscheck'] || $_REQUEST['massrepair'] || $_REQUEST['massoptimize']) {
		$mode = 'check';
		if ($_REQUEST['massrepair']) { $mode = 'repair'; }
		if ($_REQUEST['massoptimize']) { $mode = 'optimize'; }

		$tables = $_REQUEST['tables'];
		if (!is_array($tables)) {
			msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
		} else {
			$slist = array();

			for ($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
				if ($mysql->table_exists($tables[$i])) {
					$result = $mysql->record($mode." table `".$tables[$i]."`");
					if ($result['Msg_text'] == "2 clients are using or haven't closed the table properly") {
						$result['Msg_text'] = $lang['dbo']['chk_no'];
					}
					$slist []= $tables[$i].' &#8594; '.$result['Msg_text'];
				} else {
					$slist []= $tables[$i].' &#8594; '.$result['Msg_text'];
				}
			}
			msg(array("text" => $lang['dbo']['msgo_'.$mode], 'info' => '<small>'.join("<br/>\n", $slist).'</small>'));
		}
	}

	// MASS: Delete tables
	if ($_REQUEST['massdelete']) {
	        $tables = $_REQUEST['tables'];
		if (!$tables) {
			msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
		}
		else {
			for($i = 0, $sizeof = sizeof($tables); $i < $sizeof; $i++) {
				if ($mysql->table_exists($tables[$i])) {
					$mysql->query("drop table `".$tables[$i]."`");
					msg(array("text" => sprintf($lang['dbo']['msgo_delete'], $tables[$i])));
				} else {
					msg(array("text" => sprintf($lang['dbo']['msgi_noexist'], $tables[$i], $result['Msg_text'])));
				}
			}
		}
	}

	// MASS: Backup tables
	if ($_REQUEST['massbackup']) {
	        $tables = $_REQUEST['tables'];
		if (!$tables) {
			msg(array("type" => "error", "text" => $lang['dbo']['msge_tables'], "info" => $lang['dbo']['msgi_tables']));
		} else {
			$date = date("Y_m_d_H_i", time());
			$date2 = LangDate("d Q Y - H:i", time());

			$filename = root."backups/backup_".$date.(($_REQUEST['gzencode'])?".gz":".sql");
			dbBackup($filename, $_REQUEST['gzencode']);

			if ($_REQUEST['email_send']) {
				sendEmailMessage($config['admin_mail'], $lang['dbo']['title'], sprintf($lang['dbo']['message'], $date2), $filename);
				@unlink($filename);
				msg(array("text" => $lang['dbo']['msgo_backup_m']));
			}
			else {
				msg(array("text" => $lang['dbo']['msgo_backup']));
			}
		}
	}

	//MASS: Delete backup files
	if ($_REQUEST['massdelbackup']) {
		$backup_dir = opendir(root.'backups');
		while($bf = readdir($backup_dir)) {
			if (($bf == '.')||($bf == '..'))
				continue;

			@unlink (root.'backups/'.$bf);
		}
		msg(array("text" => $lang['dbo']['msgo_massdelb']));
	}

	// RESTORE DB backup
	if ($_REQUEST['restore']) {
	        $filename = str_replace('/','', $_REQUEST['filename']);
		if (!$filename) {
			msg(array("type" => "error", "text" => $lang['dbo']['msge_restore'], "info" => $lang['dbo']['msgi_restore']));
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
			msg(array("text" => $lang['dbo']['msgo_restore']));
		}
	}
}


//
// List tables
function systemDboForm() {
	global $mysql, $lang, $twig, $config;

	// Check for permissions
	if (!checkPermission(array('plugin' => '#admin', 'item' => 'dbo'), null, 'details')) {
	msg(array("type" => "error", "text" => $lang['perm.denied']), 1, 1);
	ngSYSLOG(array('plugin' => '#admin', 'item' => 'dbo', 'ds_id' => $id), array('action' => 'details'), null, array(0, 'SECURITY.PERM'));
	return false;
	}

	$tableList = array();
	foreach($mysql->select("SHOW TABLES FROM `".$config['dbname']."` LIKE '".prefix."_%'") as $table) {
		$info		=	$mysql->record("SHOW TABLE STATUS LIKE '".$table[0]."'");

		$tableInfo = array(
			'table'		=> $info['Name'],
			'rows'		=> $info['Rows'],
			'data'		=> Formatsize($info['Data_length'] + $info['Index_length'] + $info['Data_free']),
			'overhead'	=> ($info['Data_free'] > 0) ? "<span style='color:red;'>".Formatsize($info['Data_free'])."</span>" : 0,
		);

		$tableList []= $tableInfo;

	}

	$tVars = array(
		'php_self'	=> $PHP_SELF,
		'tables'	=> $tableList,
		'restore'	=> MakeDropDown(ListFiles(root.'backups', 'gz'), 'filename', ''),
		'token'		=> genUToken('admin.dbo'),
	);

	$xt = $twig->loadTemplate('skins/default/tpl/dbo.tpl');
	echo $xt->render($tVars);
}

if (isset($_REQUEST['subaction']) && ($_REQUEST['subaction'] == "modify")) {
	systemDboModify();
}

systemDboForm();
