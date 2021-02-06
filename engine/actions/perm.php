<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: perm.php
// Description: Permission manager
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('perm', 'admin');

@include_once root.'includes/inc/extrainst.inc.php';

$pManager = new permissionRuleManager();
$pManager->load();

// Preconfigure list of groups from global group list
$grp = [];

foreach ($UGROUP as $id => $v) {
    $grp[$id] = ['id' => $id, 'title' => $v['name']];
}

// Show list of current permissions
function showList($grp)
{
    global $PERM, $pManager, $twig, $userROW, $lang, $catz;

    // ACCESS ONLY FOR ADMIN
    if (!checkPermission(['plugin' => '#admin', 'item' => 'perm'], null, 'details')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']]);

        return;
    }

    $data = [];
    $dvalue = [];
    $nl1 = 0;
    foreach ($pManager->getList() as $kb => $vb) {
        $nl1++;
        $dBlock = [
            'id'          => $kb,
            'title'       => (isset($vb['title']) && $vb['title']) ? $vb['title'] : '',
            'description' => (isset($vb['description']) && $vb['description']) ? $vb['description'] : '',
            'items'       => [],
        ];

        if (is_array($vb['items'])) {
            $nl2 = 0;
            foreach ($vb['items'] as $ka => $va) {
                $nl2++;
                $dArea = [
                    'id'          => $ka,
                    'title'       => (isset($va['title']) && $va['title']) ? $va['title'] : '',
                    'description' => (isset($va['description']) && $va['description']) ? $va['description'] : '',
                    'items'       => [],
                ];

                if (is_array($va['items'])) {
                    $nl3 = 0;
                    foreach ($va['items'] as $ke => $ve) {
                        $nl3++;

                        // Check for type = categories
                        $isCategories = false;
                        if (isset($ve['type']) && (preg_match('#^listCategoriesSelector#', $ve['type'], $match))) {
                            // [[ CATEGORIES ]]
                            $isCategories = true;

                            $dEntry = [
                                'id'               => $ke,
                                'title'            => (isset($ve['title']) && $ve['title']) ? $ve['title'] : '',
                                'description'      => (isset($ve['description']) && $ve['description']) ? $ve['description'] : '',
                                'type'             => 'listCategoriesSelector',
                                'name'             => str_replace('.', ':', $kb.'|'.$ka.'|'.$ke),
                                'generatedOptions' => makeCategoryList(['skipDisabled' => true, 'noHeader' => true, 'doall' => true, 'allmarker' => '*', 'returnOptArray' => true]),
                                'uniqId'           => 'id'.$nl1.'_'.$nl2.'_'.$nl3,
                            ];
                        //$dArea['items'] []= $dEntry;
                            //continue;
                        } else {
                            // [[ NORMAL SELECT ]]
                            $dEntry = [
                                'id'          => $ke,
                                'title'       => (isset($ve['title']) && $ve['title']) ? $ve['title'] : '',
                                'description' => (isset($ve['description']) && $ve['description']) ? $ve['description'] : '',
                                'perm'        => [],
                                'name'        => $kb.'|'.$ka.'|'.$ke,
                                'type'        => '',
                            ];
                        }

                        // Avoid PHP bug/feature - it replaces "." into "_". Let's use ':' instead
                        $dEntry['name'] = str_replace('.', ':', $dEntry['name']);

                        if (is_array($grp)) {
                            foreach ($grp as $kg) {
                                if (isset($PERM[$kg['id']][$kb][$ka][$ke]) && $PERM[$kg['id']][$kb][$ka][$ke]) {
                                    $x = $PERM[$kg['id']][$kb][$ka][$ke];
                                } else {
                                    $x = '';
                                }

                                if ($isCategories) {
                                    $catArray = [];
                                    foreach (explode(',', $x) as $cx) {
                                        $catArray[$cx] = true;
                                    }
                                    $dvalue[$dEntry['name'].'|'.$kg['id']] = $catArray;
                                    $dEntry['perm'][$kg['id']] = $catArray; //$PERM[$kg['id']][$kb][$ka][$ke];
                                } else {
                                    $dEntry['perm'][$kg['id']] = $x; //$PERM[$kg['id']][$kb][$ka][$ke];
                                    $dvalue[$dEntry['name'].'|'.$kg['id']] = (!isset($PERM[$kg['id']][$kb][$ka][$ke]) || ($PERM[$kg['id']][$kb][$ka][$ke] === null)) ? -1 : ($x ? 1 : 0);
                                }
                            }
                        }

                        $dArea['items'][] = $dEntry;
                    }
                }
                $dBlock['items'][] = $dArea;
            }
        }
        $data[] = $dBlock;
    }

    //print "<pre><select size='10' multiple='multiple'>".makeCategoryList(array('skipDisabled' => true, 'noHeader' => true))."</select></pre>";
    // Print template
    $xt = $twig->loadTemplate('skins/default/tpl/perm/list.tpl');

    return $xt->render([
        'CONFIG'       => $data,
        'PERM'         => $PERM,
        'GRP'          => $grp,
        'DEFAULT_JSON' => json_encode($dvalue),
        'DEFAULT'      => $dvalue,
        'token'        => genUToken('admin.perm'),
    ]);
}

function displayPermValue($value, $type)
{
    global $lang;

    if ($type == 'listCategoriesSelector') {
        return $value;
    }

    if ($value == -1) {
        return '--';
    }
    if ($value == 0) {
        return $lang['noa'];
    }
    if ($value == 1) {
        return $lang['yesa'];
    }
}

function updateConfig()
{
    global $userROW, $lang, $PERM, $confPerm, $confPermUser, $pManager, $twig, $grp;
    //print "Incoming POST: <pre>".var_export($_POST, true)."</pre>";
    // ACCESS ONLY FOR ADMIN
    if (!checkPermission(['plugin' => '#admin', 'item' => 'perm'], null, 'modify')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']]);

        return;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.perm'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);

        return;
    }

    $pList = $pManager->getList();
    $updateList = [];

    //print "<pre>".var_export($_POST, true)."</pre>";
    //print "Scan update..<br/>";
    //print "EX:<pre>".var_export($PERM[2]['#admin']['news'], true)."</pre>";
    foreach ($_POST as $k => $v) {
        // Avoid PHP bug/feature - it replaces '.' into '_'. Let's use ':' instead
        $k = str_replace(':', '.', $k);

        if (!preg_match("#^(.+?)\|(.*?)\|(.+?)\|(\d+)$#", $k, $m)) {
            continue;
        }

        //print "Rec [$k]<pre>".var_export($m, true)."</pre><br/>";
        if (isset($pList[$m[1]]['items'][$m[2]]['items'][$m[3]])) {
            $itemType = $pList[$m[1]]['items'][$m[2]]['items'][$m[3]]['type'];
            $itemSubType = '';
            if (preg_match('!^(.+?)#(.+?)$!', $itemType, $null)) {
                $itemType = $null[1];
                $itemSubType = $null[2];
            }
            $itemIsCategories = ($itemType == 'listCategoriesSelector') ? true : false;
            //print "[$itemType,$itemSubType,$itemIsCategories]";
            // TYPE: listCategoriesSelector - own processing
            if ($itemIsCategories) {
                if (is_array($v)) {
                    if (in_array('*', $v)) {
                        $v = '*';
                    } else {
                        $v = implode(',', $v);
                    }
                }
            }

            $markValue = 99;
            if (!isset($PERM[$m[4]][$m[1]][$m[2]][$m[3]]) || ($PERM[$m[4]][$m[1]][$m[2]][$m[3]] === null)) {
                $markValue = -1;
            } else {
                if ($itemIsCategories) {
                    $markValue = ($PERM[$m[4]][$m[1]][$m[2]][$m[3]]);
                } else {
                    $markValue = ($PERM[$m[4]][$m[1]][$m[2]][$m[3]]) ? 1 : 0;
                }
            }
            if ($markValue != $v) {
                // Save information about updates
                $updateList[] = [
                    'id'         => $m[1].' &#8594; '.$m[2].' &#8594; '.$m[3],
                    'group'      => $m[4],
                    'title'      => $pList[$m[1]]['items'][$m[2]]['items'][$m[3]]['title'],
                    'type'       => $itemType,
                    'old'        => $markValue,
                    'new'        => $v,
                    'displayNew' => displayPermValue($v, $itemType),
                    'displayOld' => displayPermValue($markValue, $itemType),
                ];

                //print "> $k: ".$markValue.' => '.$v."<br/>";

                // Found changed record
                // - check if new value is equal to default value
                if ((($v == -1) && !isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]])) ||
                    (($v == 1) && isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) && $confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) ||
                    (($v == 0) && isset($confPerm[$m[4]][$m[1]][$m[2]][$m[3]]) && !$confPerm[$m[4]][$m[1]][$m[2]][$m[3]])
                ) {
                    //print "DELETE OVERRIDEN $k<br/>\n";
                    unset($confPermUser[$m[4]][$m[1]][$m[2]][$m[3]]);
                // -- delete overrided $confPermUser record
                } else {
                    // -- SAVE new value for $confPermUser record
                    if ($itemIsCategories) {
                        $confPermUser[$m[4]][$m[1]][$m[2]][$m[3]] = $v;
                    } else {
                        $confPermUser[$m[4]][$m[1]][$m[2]][$m[3]] = ($v == -1) ? null : ($v ? true : false);
                    }
                    //print "SAVE NEW $k -> $v<br/>\n";
                }

                //print "(".isset($PERM[$m[4]][$m[1]][$m[2]][$m[3]]).",".($PERM[$m[4]][$m[1]][$m[2]][$m[3]] === NULL).") "; print var_export($PERM[$m[4]][$m[1]][$m[2]][$m[3]]);
            }
        }
    }

    $execResult = saveUserPermissions();

    $xt = $twig->loadTemplate('skins/default/tpl/perm/result.tpl');

    return $xt->render([
        'updateList' => $updateList,
        'GRP'        => $grp,
        'execResult' => $execResult,
    ]);
}

//
//
if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['save']) && ($_POST['save'] == 1)) {
    $main_admin = updateConfig();
} else {
    $main_admin = showList($grp);
    //	showList(array('1' => array('id' => 1, 'title' => 'Администратор')));
}
