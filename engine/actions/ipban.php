<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: ipban.php
// Description: IP BAN configuration procedures
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

$lang = LoadLang('ipban', 'admin');


//
// Add record into IPBAN list
//
function ipban_add() {
	global $mysql, $lang;

	// Check params
	$ip = trim($_REQUEST['ip']);
	//$atype = intval($_REQUEST['atype']);
	$reason = $_REQUEST['lock:rsn'];
	$flags = intval($_REQUEST['lock:open']).intval($_REQUEST['lock:reg']).intval($_REQUEST['lock:login']).intval($_REQUEST['lock:comm']);

	$addr_start	= 0;
	$addr_stop	= 0;
	$letlen		= 0;
	$result = false;
	if (preg_match('#^(\d+)\.(\d+)\.(\d+)\.(\d+)$#', $ip, $m) && ($m[1] < 256) && ($m[2] < 256) && ($m[3] < 256) && ($m[4] < 256)) {
		$result = true;
		$atype = 0;
		$addr_start = ip2long($m[1].".".$m[2].".".$m[3].".".$m[4]);
		$addr_stop  = ip2long($m[1].".".$m[2].".".$m[3].".".$m[4]);
		$letlen		= 0;
	} else if (preg_match('#^(\d+)\.(\d+)\.(\d+)\.(\d+)\/(\d+)\.(\d+)\.(\d+)\.(\d+)$#', $ip, $m) &&
			($m[1] < 256) && ($m[2] < 256) && ($m[3] < 256) && ($m[4] < 256) &&
			($m[5] < 256) && ($m[6] < 256) && ($m[7] < 256) && ($m[8] < 256)) {
		$result = true;
		$atype = 1;
		$laddr = ip2long($m[1].".".$m[2].".".$m[3].".".$m[4]);
		$lmask = ip2long($m[5].".".$m[6].".".$m[7].".".$m[8]);

		// Check mask
		$lbmask = decbin($lmask);
		if (!preg_match('#^1+0+$#', $lbmask, $null)) {
			msg(array("type" => "error", "text" => $lang['msge.fields'], "info" => $lang['msgi.fields']));
			return;
		}

		$addr_start = $laddr & $lmask;
		$addr_stop  = $laddr | (~$lmask);
		$net_len	= $addr_stop-$addr_start;
	}
	if ($result) {
			// OK. Check if record already exists
			if (is_array($mysql->record("select addr from ".prefix."_ipban where addr_start=".db_squote(sprintf("%u", $addr_start))." and addr_stop=".db_squote(sprintf("%u", $addr_stop))))) {
				// Duplicated
				msg(array("type" => "error", "text" => $lang['msge.exist']));
				return;
			}
			$mysql->query("insert into ".prefix."_ipban (addr, atype, addr_start, addr_stop, netlen, flags, createDate, reason, hitcount) values (".db_squote($ip).", ".db_squote($atype).", ".db_squote(sprintf("%u", $addr_start)).", ".db_squote(sprintf("%u", $addr_stop)).", ".db_squote(sprintf("%u", $net_len)).", ".db_squote($flags).", now(), ".db_squote($reason).", 0)");
			msg(array("text" => str_replace('{ip}', $ip, $lang['msg.blocked'])));
	} else {
		msg(array("type" => "error", "text" => $lang['msge.fields'], "info" => $lang['msgi.fields']));
	}
}


//
// Remove record from IPBAN list
//
function ipban_delete() {
	global $mysql, $lang;

	$id = intval($_REQUEST['id']);

	// Fetch record
	if (is_array($row=$mysql->record("select * from ".prefix."_ipban where id = ".$id))) {
		// Record found. Delete it
		$mysql->query("delete from ".prefix."_ipban where id = ".$id);
		msg(array("text" => str_replace('{ip}', $row['addr'], $lang['msg.unblocked'])));
	} else {
		msg(array("type" => "error", "text" => $lang['msg.notfound']));
	}
}


//
// Show all records
//
function ipban_list() {
	global $tpl, $mysql, $lang, $mod;

	$accessMAP = array ('A', 'R', 'U', 'C');

	$tpl -> template('entries', tpl_actions.$mod);
	foreach ($mysql->select("select * from ".prefix."_ipban order by addr") as $row) {
		$accessLine = '';
		for ($i = 0; $i < 4; $i++) {
			$flag = intval(substr($row['flags'], $i, 1));
			switch ($flag) {
				case 1:  $accessLine .= '<font color="blue"><b>'.    $accessMAP[$i].'</b></font>'; break;
				case 2:	 $accessLine .= '<font color="red"><b>'.     $accessMAP[$i].'</b></font>'; break;
				default: $accessLine .= '<font color="#CCCCCC"><b>'. $accessMAP[$i].'</b></font>'; break;
			}
		}

		$tvars['vars'] = array(
			'php_self'	=>	$PHP_SELF,
			'id'		=>	$row['id'],
			'ip'		=>	$row['addr'],
			'whoisip'	=>	array_shift(split('/', $row['addr'])),
			'atype'		=> ($row['atype']?' /net':''),
			'mode'		=>	'',
			'descr'		=>	$row['reason']==''?'-':$row['reason'],
			'type'		=> $accessLine,
			'hitcount'	=>	($row['hitcount']),
		);
		$tpl -> vars('entries', $tvars);
		$entries .= $tpl -> show('entries');
	}

	$tpl -> template('table', tpl_actions.$mod);
	$tvars['vars'] = array('php_self' => $PHP_SELF, 'entries' => $entries, 'iplock' => $_REQUEST['iplock']);
	$tpl -> vars('table', $tvars);
	echo $tpl -> show('table');
}



//
// Main loop
//
switch ($_REQUEST['action']) {
	case 'add':		ipban_add();
					break;
	case 'del':		ipban_delete();
					break;
}

ipban_list();

