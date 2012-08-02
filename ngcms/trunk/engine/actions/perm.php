<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: perm.php
// Description: Permission manager
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

@include_once root.'includes/inc/extrainst.inc.php';

//$lang = LoadLang('pm', 'admin');


$pManager = new permissionRuleManager();
$pManager->load();

// Prepare data
$grp = array(
1 =>	array('id' => 1, 'title' => 'Администратор'),
2 =>	array('id' => 2, 'title' => 'Редактор'),
3 =>	array('id' => 3, 'title' => 'Журналист'),
4 =>	array('id' => 4, 'title' => 'Комментатор'),
);


// Show list of current permissions
function showList() {
	global $PERM, $pManager, $twig, $grp, $userROW, $lang;

	// ACCESS ONLY FOR ADMIN
	if ($userROW['status'] > 1) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	$data = array();
	$dvalue = array();
	foreach ($pManager->getList() as $kb => $vb) {
		$dBlock = array(
			'id'			=> $kb,
			'title'			=> $vb['title'],
			'description'	=> $vb['description'],
			'items'		=> array(),
		);

		if (is_array($vb['items'])) {
			foreach ($vb['items'] as $ka => $va) {
				$dArea = array(
					'id'			=> $ka,
					'title'			=> $va['title'],
					'description'	=> $va['description'],
					'items'		=> array(),
				);

				if (is_array($va['items'])) {
					foreach ($va['items'] as $ke => $ve) {
						$dEntry = array(
							'id'			=> $ke,
							'title'			=> $ve['title'],
							'description'	=> $ve['description'],
							'perm'			=> array(),
							'name'			=> $kb.'|'.$ka.'|'.$ke,
						);

						// Avoid PHP bug/feature - it replaces "." into "_". Let's use ':' instead
						$dEntry['name'] = str_replace('.', ':', $dEntry['name']);

						foreach ($grp as $kg) {
							$dEntry['perm'][$kg['id']] = $PERM[$kg['id']][$kb][$ka][$ke];
							$x = $PERM[$kg['id']][$kb][$ka][$ke];
							$dvalue[$dEntry['name'].'|'.$kg['id']] = (!isset($PERM[$kg['id']][$kb][$ka][$ke]) || ($PERM[$kg['id']][$kb][$ka][$ke] === NULL))?-1:($x?1:0);
						}

						$dArea['items'] []= $dEntry;
					}
				}
				$dBlock['items'] []= $dArea;
			}

		}
		$data []= $dBlock;
	}


	// Print template
	$xt = $twig->loadTemplate('skins/default/tpl/perm/list.tpl');
	print $xt->render(array(
		'CONFIG' => $data,
		'PERM' => $PERM,
		'GRP' => $grp,
		'DEFAULT_JSON' => json_encode($dvalue),
		'DEFAULT'	=> $dvalue,
		'token'		=> genUToken('admin.perm'),
		)
	);
}

function updateConfig() {
	global $userROW, $lang, $PERM, $confPerm, $confPermUser, $pManager, $twig, $grp;

	// ACCESS ONLY FOR ADMIN
	if (($userROW['status'] > 1)) {
		msg(array("type" => "error", "text" => $lang['perm.denied']));
		return;
	}

	// Check for security token
	if ((!isset($_REQUEST['token']))||($_REQUEST['token'] != genUToken('admin.perm'))) {
		msg(array("type" => "error", "text" => $lang['error.security.token'], "info" => $lang['error.security.token#desc']));
		return;
	}


	$pList = $pManager->getList();
	$updateList = array();

	//print "<pre>".var_export($_POST, true)."</pre>";
	//print "Scan update..<br/>";
	//print "EX:<pre>".var_export($PERM[2]['#admin']['news'], true)."</pre>";
	foreach ($_POST as $k => $v) {
		// Avoid PHP bug/feature - it replaces '.' into '_'. Let's use ':' instead
		$k = str_replace(':', '.', $k);

		if (!preg_match("#^(.+?)\|(.*?)\|(.+?)\|(\d+)$#", $k, $m))
			continue;

		//print "Rec [$k]<pre>".var_export($m, true)."</pre><br/>";
		if (isset($pList[$m[1]]['items'][$m[2]]['items'][$m[3]])) {
			$markValue = 99;
			if (!isset($PERM[$m[4]][$m[1]][$m[2]][$m[3]]) || ($PERM[$m[4]][$m[1]][$m[2]][$m[3]] === NULL)) {
				$markValue = -1;
			} else {
				$markValue = ($PERM[$m[4]][$m[1]][$m[2]][$m[3]])?1:0;
			}
			if ($markValue != $v) {
				// Save information about updates
				$updateList [] = array(
					'id'		=> $m[1] .' &#8594; '.$m[2].' &#8594; '.$m[3],
					'group'		=> $m[4],
					'title'		=> $pList[$m[1]]['items'][$m[2]]['items'][$m[3]]['title'],
					'old'		=> $markValue,
					'new'		=> $v,
				);

				//print "> $k: ".$markValue.' => '.$v."<br/>";

				// Found changed record
				// - check if new value is equal to default value
				if (
					(($v == -1) && !isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]])) ||
					(($v == 1) && isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) && $confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) ||
					(($v == 0) && isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) && !$confPerm[$m[4]][$m[1]][$m[2]][$m[3]])
				) {
					//print "DELETE OVERRIDEN $k<br/>\n";
					unset($confPermUser[$m[4]][$m[1]][$m[2]][$m[3]]);
					// -- delete overrided $confPermUser record
				} else {
					// -- SAVE new value for $confPermUser record
					//print "SAVE NEW $k -> $v<br/>\n";
					$confPermUser[$m[4]][$m[1]][$m[2]][$m[3]] = ($v == -1)?NULL:($v?true:false);
				}

				//print "(".isset($PERM[$m[4]][$m[1]][$m[2]][$m[3]]).",".($PERM[$m[4]][$m[1]][$m[2]][$m[3]] === NULL).") "; print var_export($PERM[$m[4]][$m[1]][$m[2]][$m[3]]);
			}
		}
	}

	$execResult = saveUserPermissions();

	$xt = $twig->loadTemplate('skins/default/tpl/perm/result.tpl');
	print $xt->render(array(
		'updateList' => $updateList,
		'GRP'	=> $grp,
		'execResult'	=> $execResult,
		)
	);

}


//
//
if (($_SERVER['REQUEST_METHOD'] == "POST") && isset($_POST['save']) && ($_POST['save'] == 1)) {
	updateConfig();
} else {
	showList();
}
