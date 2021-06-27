<?php

//
// Copyright (C) 2006-2013 Next Generation CMS (http://ngcms.ru/)
// Name: categories.rpc.php
// Description: RPC library for CATEGORIES manipulation
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

// Load library
@include_once root.'includes/classes/upload.class.php';
$lang = LoadLang('categories', 'admin');

// ////////////////////////////////////////////////////////////////////////////
// Processing functions :: show list of categories
// ///////////////////////////////////////////////////////////////////////////
// retMode: 0 - print rendered category management page
//          1 - return rendered category management page
//			2 - return ONLY rendered category entries
function admCategoryList($retMode = 0)
{
    global $mysql, $PHP_SELF, $twig, $config, $lang, $AFILTERS;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'view')) {
        switch ($retMode) {
            case 1:
                return msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 2);
            case 2:
                return false;
            default:
                msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

                return;
        }
    }

    // Determine user's permissions
    $permModify = checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'modify');
    $permDetails = checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'details');

    // Fetch list of categories
    $cList = $mysql->select('select * from '.prefix.'_category order by posorder');
    $cLen = count($cList);

    // Prepare list of categories
    $tEntries = [];
    $tVars = [
        'token'    => genUToken('admin.categories'),
        'php_self' => $PHP_SELF,
        'flags'    => [
            'canView'   => $permDetails,
            'canModify' => $permModify,
        ],
    ];

    foreach ($cList as $num => $row) {
        // Prepare data for template
        $tEntry = [
            'id'        => $row['id'],
            'name'      => $row['name'],
            'alt'       => $row['alt'],
            'alt_url'   => $row['alt_url'],
            'info'      => $row['info'],
            'show_main' => intval(substr($row['flags'], 0, 1)) ? ('<img src="'.skins_url.'/images/yes.png" alt="'.$lang['yesa'].'" title="'.$lang['yesa'].'"/>') : ('<img src="'.skins_url.'/images/no.png" alt="'.$lang['noa'].'"/>'),
            'template'  => ($row['tpl'] != '') ? $row['tpl'] : '--',
            'news'      => ($row['posts'] > 0) ? $row['posts'] : '--',
            'linkView'  => (checkLinkAvailable('news', 'by.category') ?
                generateLink('news', 'by.category', ['category' => $row['alt'], 'catid' => $row['id']], [], false, true) :
                generateLink('core', 'plugin', ['plugin' => 'news', 'handler' => 'by.category'], ['category' => $row['alt'], 'catid' => $row['id']], false, true)),
            'flags'     => [
                'showMain' => intval(substr($row['flags'], 0, 1)) ? 1 : 0,
            ],
        ];

        // Prepare position
        if ($row['poslevel'] > 0) {
            $tEntry['level'] = str_repeat('<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/line.png" />', ($row['poslevel']));
        } else {
            $tEntry['level'] = '';
        }
        $tEntry['level'] = $tEntry['level'].
            '<img alt="-" height="18" width="18" src="'.skins_url.'/images/catmenu/join'.((($num == ($cLen - 1) || ($cList[$num]['poslevel'] > $cList[$num + 1]['poslevel']))) ? 'bottom' : '').'.png" />';
        $tvars['regx']['#\[news\](.*?)\[\/news\]#is'] = ($row['posts'] > 0) ? '$1' : '';

        $tEntries[] = $tEntry;
    }

    $tVars['entries'] = $tEntries;

    switch ($retMode) {
        case 1:
            $xt = $twig->loadTemplate('skins/default/tpl/categories/table.tpl');

            return $xt->render($tVars);
        case 2:
            $xt = $twig->loadTemplate('skins/default/tpl/categories/entries.tpl');

            return $xt->render($tVars);
        default:
            $xt = $twig->loadTemplate('skins/default/tpl/categories/table.tpl');

            return $xt->render($tVars);
    }
}

//
// Reorder categories
// Params:
// 	* mode - 'up' / 'down' -- move category up or down
//  * id                   -- id of category to move
function admCategoryReorder($params = [])
{
    global $catz, $mysql;

    $moveResult = 0;

    $tree[0] = ['parent' => 0, 'children' => [], 'poslevel' => 0];
    foreach ($mysql->select('select * from '.prefix.'_category order by posorder') as $v) {
        $ncat[$v['id']] = $v;
        $tree[$v['id']] = ['children' => [], 'parent' => $v['parent'], 'poslevel' => $v['poslevel']];
    }
    // List children
    foreach ($tree as $k => $v) {
        $wrid = 0;
        if (!$k) {
            continue;
        }
        if (array_key_exists($v['parent'], $tree)) {
            $wrid = $v['parent'];
        } else {
            $tree[$k]['parent'] = 0;
            $wrid = 0;
        }
        $vx = &$tree[$wrid];
        array_push($vx['children'], $k);
    }

    // Check if we need to move category (and this category exists
    if (is_array($params) && isset($params['mode']) && isset($params['id']) && isset($ncat[$params['id']])) {
        // 1. Find parent category
        $xpc = $tree[$params['id']]['parent'];

        // 2. Find position
        $xps = array_search($params['id'], $tree[$xpc]['children']);
        $xpl = count($tree[$xpc]['children']);

        // 3. Move if requested and possible
        if (($xps !== false) && ($params['mode'] == 'up') && ($xps > 0)) {
            $xt = $tree[$xpc]['children'][$xps - 1];
            $tree[$xpc]['children'][$xps - 1] = $tree[$xpc]['children'][$xps];
            $tree[$xpc]['children'][$xps] = $xt;
            $moveResult = 1;
        }

        if (($xps !== false) && ($params['mode'] == 'down') && ($xps < ($xpl - 1))) {
            $xt = $tree[$xpc]['children'][$xps + 1];
            $tree[$xpc]['children'][$xps + 1] = $tree[$xpc]['children'][$xps];
            $tree[$xpc]['children'][$xps] = $xt;
            $moveResult = 1;
        }
    }

    $num = 0;
    $nc = getIsSet($ncat[0]);
    $idx = [];
    $idxD = [];
    $ordr = [];
    $level = 0;
    array_unshift($idx, 0, 0);

    do {
        for ($i = $idx[1]; $i < count($tree[$idx[0]]['children']); $i++) {
            $restart = 0;
            $cscan = $tree[$idx[0]]['children'][$i];

            $ordr[] = [$cscan, $level];
            //print "new order: [] = (".$cscan.", ".$level.")<br/>\n";
            $idx[1]++;
            if (count($tree[$cscan]['children'])) {
                $level++;
                array_unshift($idx, $cscan, 0);
                array_push($idxD, sprintf('%04u', $cscan));
                $restart = 1;
                break;
            }
        }
        if ($restart) {
            continue;
        }
        array_shift($idx);
        array_shift($idx);
        array_pop($idxD);
        $level--;
    } while (count($idx));

    ksort($ordr);

    cacheStoreFile('LoadCategories.dat', '');

    $num = 1;
    foreach ($ordr as $k => $v) {
        list($catID, $level) = $v;
        if (($ncat[$catID]['posorder'] != $num) || ($ncat[$catID]['poslevel'] != $level) || ($ncat[$catID]['parent'] != $tree[$catID]['parent'])) {
            $mysql->query('update '.prefix.'_category set posorder = '.db_squote($num).', poslevel = '.db_squote($level).', parent = '.db_squote($tree[$catID]['parent']).' where id = '.db_squote($catID));
        }
        $num++;
    }

    return $moveResult;
}

function admCategoriesRPCmodify($params)
{
    global $userROW, $mysql, $catmap, $catz;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'categories'], null, 'modify')) {
        // ACCESS DENIED
        return ['status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied'];
    }

    // Scan incoming params
    if (!is_array($params) || !isset($params['mode']) || !isset($params['id']) || !isset($params['token'])) {
        return ['status' => 0, 'errorCode' => 4, 'errorText' => 'Wrong params type'];
    }

    // Check for security token
    if ($params['token'] != genUToken('admin.categories')) {
        return ['status' => 0, 'errorCode' => 5, 'errorText' => 'Wrong security code'];
    }

    // Check if category exists
    if (!isset($catmap[$params['id']])) {
        return ['status' => 0, 'errorCode' => 10, 'errorText' => 'Category does not exist'];
    }
    $row = $catz[$catmap[$params['id']]];

    switch ($params['mode']) {
            // Delete category
        case 'del':
            // Check if category have children
            $refCCount = $mysql->record('select count(*) as cnt from '.prefix.'_category where parent = '.intval($params['id']));
            if ($refCCount['cnt'] > 0) {
                return ['status' => 0, 'errorCode' => 11, 'errorText' => 'Category have children, please delete news from this category first'];
            }

            // Check for news in category
            $refNCount = $mysql->record('select count(*) as cnt from '.prefix.'_news_map where categoryID = '.intval($params['id']));
            if ($refNCount['cnt'] > 0) {
                return ['status' => 0, 'errorCode' => 12, 'errorText' => 'Category have news, please delete news from this category first'];
            }

            // Fine, now we can delete category!
            // * Delete
            $mysql->query('delete from '.prefix.'_category where id = '.intval($params['id']));

            // Delete attached files (if any)
            if ($row['image_id']) {
                $fmanager = new file_managment();
                $fmanager->file_delete(['type' => 'image', 'id' => $row['image_id']]);
            }

            // * Reorder
            admCategoryReorder();
            // * Rewrite page content
            $data = admCategoryList(2);
            if ($data === false) {
                $data = '[permission denied]';
            }

            return ['status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'infoCode' => 1, 'infoText' => 'Category was deleted', 'content' => $data];

            // Move category UP/DOWN
        case 'up':
        case 'down':
            $moveResult = admCategoryReorder(['mode' => $params['mode'], 'id' => intval($params['id'])]);

            // * Rewrite page content
            $data = admCategoryList(2);

            return ['status' => 1, 'errorCode' => 0, 'errorText' => 'Ok', 'infoCode' => intval($moveResult), 'infoText' => '<img src="/engine/skins/default/images/'.$params['mode'].'.png"/> '.$catz[$catmap[$params['id']]]['name'], 'content' => $data];
    }

    return ['status' => 0, 'errorCode' => 999, 'errorText' => 'Params: '.var_export($params, true)];
}

if (function_exists('rpcRegisterAdminFunction')) {
    rpcRegisterAdminFunction('admin.categories.modify', 'admCategoriesRPCmodify');
}
