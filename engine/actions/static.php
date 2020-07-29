<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: static.php
// Description: Manage static pages
// Author: Vitaly Ponomarev
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('static', 'admin');

//
// Show list of static pages
//
function listStatic()
{
    global $mysql, $mod, $userROW, $lang, $config, $twig, $PHP_SELF;

    // Check for permissions
    $perm = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, ['view', 'modify', 'details', 'publish', 'unpublish']);
    if (!$perm['view']) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return;
    }

    // Load admin page based cookies
    $admCookie = admcookie_get();

    // Determine user's permissions
    $permModify = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'modify');
    $permDetails = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'details');

    $per_page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : intval($admCookie['static']['pp']);
    if (($per_page < 2) || ($per_page > 500)) {
        $per_page = 20;
    }

    // - Save into cookies current value
    $admCookie['static']['pp'] = $per_page;
    admcookie_set($admCookie);

    $pageNo = intval(getIsSet($_REQUEST['page']));
    if ($pageNo < 1) {
        $pageNo = 1;
    }

    $query = [];
    $query['sql'] = 'select * from '.prefix.'_static order by title limit '.(($pageNo - 1) * $per_page).', '.$per_page;
    $query['count'] = 'select count(*) as cnt from '.prefix.'_static ';

    $nCount = 0;
    $tEntries = [];
    foreach ($mysql->select($query['sql']) as $row) {
        $nCount++;

        $tEntry = [
            'home'     => home,
            'id'       => $row['id'],
            'alt_name' => $row['alt_name'],
            'template' => ($row['template'] == '') ? '--' : $row['template'],
            'date'     => ($row['postdate'] > 0) ? strftime('%d.%m.%Y %H:%M', $row['postdate']) : '',
        ];

        if (strlen($row['title']) > 70) {
            $row['title'] = substr($row['title'], 0, 70).' ...';
        }

        $link = checkLinkAvailable('static', '') ?
            generateLink('static', '', ['altname' => $row['alt_name'], 'id' => $row['id']], [], false, true) :
            generateLink('core', 'plugin', ['plugin' => 'static'], ['altname' => $row['alt_name'], 'id' => $row['id']], false, true);

        $tEntry['url'] = $row['approve'] ? ('<a href="'.$link.'" target="_blank">'.$link.'</a>') : '';
        $tEntry['title'] = str_replace(["'", '"'], ['&#039;', '&quot;'], $row['title']);
        $tEntry['status'] = ($row['approve']) ? '<img src="'.skins_url.'/images/yes.png" alt="'.$lang['approved'].'" />' : '<img src="'.skins_url.'/images/no.png" alt="'.$lang['unapproved'].'" />';

        $tEntries[] = $tEntry;
    }

    $tVars = [
        'php_self' => $PHP_SELF,
        'per_page' => $per_page,
        'entries'  => $tEntries,
        'token'    => genUToken('admin.static'),
        'perm'     => [
            'details' => $permDetails,
            'modify'  => $permModify,
        ],
    ];

    $cnt = $mysql->record($query['count']);
    $all_count_rec = $cnt['cnt'];

    $countPages = ceil($all_count_rec / $per_page);
    $tVars['pagesss'] = generateAdminPagelist(['current' => $pageNo, 'count' => $countPages, 'url' => admin_url.'/admin.php?mod=static&action=list'.(getIsSet($_REQUEST['per_page']) ? '&per_page='.$per_page : '').'&page=%page%']);

    exec_acts('static_list');

    $xt = $twig->loadTemplate('skins/default/tpl/static/table.tpl');

    return $xt->render($tVars);
}

//
// Mass static pages flags modifier
// $setValue  - what to change in table (SQL string)
// $langParam - name of variable in lang file to show on success
// $tag       - tag param to send to plugins
//
function massStaticModify($setValue, $langParam, $tag = '')
{
    global $mysql, $lang;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'modify')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.static'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);

        return;
    }

    $selected = getIsSet($_REQUEST['selected']);

    if (!$selected) {
        msg(['type' => 'error', 'text' => $lang['msge_selectnews'], 'info' => $lang['msgi_selectnews']]);

        return;
    }

    foreach ($selected as $id) {
        $mysql->query('UPDATE '.prefix."_static SET $setValue WHERE id=".db_squote($id));
    }
    msg(['text' => $lang[$langParam]]);
}

//
// Mass static pages delete
//
function massStaticDelete()
{
    global $mysql, $lang, $PFILTERS;

    // Check for permissions
    if (!checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'modify')) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.static'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);

        return;
    }

    $selected = getIsSet($_REQUEST['selected']);

    if (!$selected) {
        msg(['type' => 'error', 'text' => $lang['msge_selectnews'], 'info' => $lang['msgi_selectnews']]);

        return;
    }

    foreach ($selected as $id) {
        if ($srow = $mysql->record('select * from '.prefix.'_static where id = '.db_squote($id))) {
            if (is_array($PFILTERS['static'])) {
                foreach ($PFILTERS['static'] as $k => $v) {
                    $v->deleteStatic($srow['id'], $srow);
                }
            }
            $mysql->query('delete from '.prefix.'_static where id='.db_squote($id));
        }
    }
    msg(['text' => $lang['msgo_deleted']]);
}

// Return list of available templates
function staticTemplateList()
{
    global $config;

    $result = [''];
    foreach (ListFiles(tpl_dir.$config['theme'].'/static', 'tpl') as $k) {
        if (preg_match('#\.(print|main)$#', $k)) {
            continue;
        }
        $result[] = $k;
    }

    return $result;
}

//
// Add/Edit static page :: FORM
// $operationMode - mode of operation
//	1 - Add `from the scratch`
//	2 - Add `repeat previous attempt` (after fail)
//	3 - Edit `from the scratch` (or after successfull add)
//	4 - Edit `repeat previous attempt` (after tail)
// $sID - static ID
//	0 - autodetect
//  x - exact static ID
function addEditStaticForm($operationMode = 1, $sID = 0)
{
    global $lang, $parse, $mysql, $config, $twig, $mod, $PFILTERS, $tvars, $userROW, $PHP_SELF;
    global $title, $contentshort, $contentfull, $alt_name, $id, $c_day, $c_month, $c_year, $c_hour, $c_minute;

    $perm = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, ['add', 'modify', 'view', 'template', 'template.main', 'html', 'publish', 'unpublish']);

    $permModify = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'modify');
    $permDetails = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, 'details');

    if (!$perm['modify'] && !$perm['details']) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return 0;
    }

    // Init `$editMode` variable
    $editMode = 0;
    $row = [];
    $origRow = [];

    $requestID = ($sID > 0) ? $sID : ((isset($_REQUEST['id']) && $_REQUEST['id']) ? $_REQUEST['id'] : 0);

    // EDIT
    if (($operationMode == 3) || ($operationMode == 4)) {
        if (!$requestID || !is_array($row = $mysql->record('select * from '.prefix.'_static where id = '.db_squote($requestID)))) {
            msgSticker($lang['msge_not_found'], 'error');

            return 0;
        }
        $editMode = 1;
        $origRow = $row;
    }

    // ADD
    if (($operationMode == 1) || ($operationMode == 2)) {
        // ADD mode
        $row['id'] = 0;
    }

    // Populate `repeat previous attempt` data
    if (($operationMode == 2) || ($operationMode == 4)) {
        foreach (['title', 'content', 'alt_name', 'template', 'description', 'keywords'] as $k) {
            if (isset($_REQUEST[$k])) {
                $row[$k] = $_REQUEST[$k];
            }
        }
        $row['approve'] = (isset($_REQUEST['flag_published']) && $_REQUEST['flag_published']) ? 1 : 0;
        $row['flags'] = ((isset($_REQUEST['flag_raw']) && $_REQUEST['flag_raw']) ? 1 : 0) + ((isset($_REQUEST['flag_html']) && $_REQUEST['flag_html']) ? 2 : 0) + ((isset($_REQUEST['flag_template_main']) && $_REQUEST['flag_template_main']) ? 4 : 0);
    }

    // Fill basic variables
    $tVars = [
        'php_self'     => $PHP_SELF,
        'quicktags'    => QuickTags('currentInputAreaID', 'static'),
        'token'        => genUToken('admin.static'),
        'smilies'      => $config['use_smilies'] ? InsertSmilies('content', 20) : '',
        'templateList' => staticTemplateList(),
        'flags'        => [
            'editMode'        => $editMode,
            'canAdd'          => $perm['add'],
            'canModify'       => $perm['modify'],
            'canPublish'      => $perm['publish'],
            'canUnpublish'    => $perm['unpublish'],
            'canHTML'         => $perm['html'],
            'canTemplate'     => $perm['template'],
            'canTemplateMain' => $perm['template.main'],
            'meta'            => $config['meta'],
            'html'            => $perm['html'],
            'isPublished'     => ($editMode && ($origRow['approve'])) ? 1 : 0,
        ],
    ];
    // Fill data entry
    $tVars['data'] = [
        'id'                 => $row['id'],
        'title'              => secure_html(getIsSet($row['title'])),
        'content'            => secure_html(getIsSet($row['content'])),
        'alt_name'           => getIsSet($row['alt_name']),
        'template'           => getIsSet($row['template']),
        'description'        => getIsSet($row['description']),
        'keywords'           => getIsSet($row['keywords']),
        'cdate'              => !empty($row['postdate']) ? date('d.m.Y H:i', $row['postdate']) : '',
        'flag_published'     => getIsSet($row['approve']),
        'flag_raw'           => (getIsSet($row['flags']) % 2) ? 1 : 0,
        'flag_html'          => ((getIsSet($row['flags']) / 2) % 2) ? 1 : 0,
        'flag_template_main' => ((getIsSet($row['flags']) / 4) % 2) ? 1 : 0,
    ];

    if ($editMode && ($origRow['approve'])) {
        $tVars['data']['url'] = (checkLinkAvailable('static', '') ?
            generateLink('static', '', ['altname' => $origRow['alt_name'], 'id' => $origRow['id']], [], false, true) :
            generateLink('core', 'plugin', ['plugin' => 'static'], ['altname' => $origRow['alt_name'], 'id' => $origRow['id']], false, true));
    }

    exec_acts('addstatic');
    exec_acts('editstatic');

    if (getIsSet($PFILTERS['static']) && is_array($PFILTERS['static'])) {
        foreach ($PFILTERS['static'] as $k => $v) {
            if ($editMode) {
                $v->editStaticForm($row['id'], $row, $tVars);
            } else {
                $v->addStaticForm($tVars);
            }
        }
    }

    $xt = $twig->loadTemplate('skins/default/tpl/static/edit.tpl');

    return $xt->render($tVars);

    return 1;
}

//
// Add static page
//
function addStatic()
{
    global $mysql, $parse, $PFILTERS, $lang, $config, $userROW, $PHP_SELF, $tvars;

    $perm = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, ['modify', 'view', 'template', 'template.main', 'html', 'publish', 'unpublish']);

    // Check for modify permissions
    if (!$perm['modify']) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return 0;
    }

    // Check for publish request if it's set
    if (isset($_REQUEST['flag_published']) && isset($_REQUEST['flag_published'])) {
        if (!$perm['publish']) {
            msgSticker([[$lang['perm.denied'], 'title', 1], [$lang['perm.publish'], '', 1]], 'error', 1);

            return 0;
        }
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.static'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);

        return 0;
    }

    $title = $_REQUEST['title'];
    $content = $_REQUEST['content'];
    $content = str_replace("\r\n", "\n", $content);

    $alt_name = strtolower($parse->translit(trim($_REQUEST['alt_name']), 1, 1));

    if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
        msgSticker([[$lang['msge_fields'], 'title', 1], [$lang['msgi_fields'], '', 1]], 'error', 1);

        return 0;
    }

    $SQL['title'] = $title;

    // Check for dup if alt_name is specified
    if ($alt_name) {
        if (is_array($mysql->record('select id from '.prefix.'_static where alt_name = '.db_squote($alt_name).' limit 1'))) {
            msg(['type' => 'error', 'text' => $lang['msge_alt_name'], 'info' => $lang['msgi_alt_name']]);

            return 0;
        }
        $SQL['alt_name'] = $alt_name;
    } else {
        // Generate uniq alt_name if no alt_name specified
        $alt_name = strtolower($parse->translit(trim($title), 1));
        $i = '';
        while (is_array($mysql->record('select id from '.prefix.'_static where alt_name = '.db_squote($alt_name.$i).' limit 1'))) {
            $i++;
        }
        $SQL['alt_name'] = $alt_name.$i;
    }

    if ($config['meta']) {
        $SQL['description'] = $_REQUEST['description'];
        $SQL['keywords'] = $_REQUEST['keywords'];
    }

    $SQL['content'] = $content;

    $SQL['template'] = $_REQUEST['template'];
    $SQL['approve'] = intval($_REQUEST['flag_published']);

    // Variable FLAGS is a bit-variable:
    // 0 = RAW mode		[if set, no conversion "\n" => "<br />" will be done]
    // 1 = HTML enable	[if set, HTML codes may be used in news]

    $SQL['flags'] = (($perm['html'] && isset($_REQUEST['flag_raw']) && $_REQUEST['flag_raw']) ? 1 : 0) + (($perm['html'] && isset($_REQUEST['flag_html']) && $_REQUEST['flag_html']) ? 2 : 0) + (($perm['html'] && isset($_REQUEST['flag_template_main']) && $_REQUEST['flag_template_main']) ? 4 : 0);

    if (is_array($PFILTERS['static'])) {
        foreach ($PFILTERS['static'] as $k => $v) {
            $v->addStatic($tvars, $SQL);
        }
    }

    $vnames = [];
    $vparams = [];
    foreach ($SQL as $k => $v) {
        $vnames[] = $k;
        $vparams[] = db_squote($v);
    }

    $mysql->query('insert into '.prefix.'_static (postdate, '.implode(',', $vnames).') values (unix_timestamp(now()), '.implode(',', $vparams).')');
    $id = $mysql->result('SELECT LAST_INSERT_ID() as id');

    $link = (checkLinkAvailable('static', '') ?
        generateLink('static', '', ['altname' => $SQL['alt_name'], 'id' => $id], [], false, true) :
        generateLink('core', 'plugin', ['plugin' => 'static'], ['altname' => $SQL['alt_name'], 'id' => $id], false, true));

    msg([
        'text' => str_replace('{url}', $link, $lang['msg.added']),
        'info' => str_replace(['{url}', '{url_edit}', '{url_list}'], [$link, $PHP_SELF.'?mod=static&action=editForm&id='.$id, $PHP_SELF.'?mod=static'], $lang['msg.added#descr']),
    ]);

    return $id;
}

//
// Edit static page
//
function editStatic()
{
    global $mysql, $parse, $PFILTERS, $lang, $config, $userROW;

    $perm = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, ['modify', 'view', 'template', 'template.main', 'html', 'publish', 'unpublish']);

    // Check for permissions
    if (!$perm['modify']) {
        msg(['type' => 'error', 'text' => $lang['perm.denied']], 1, 1);

        return -1;
    }

    // Check for security token
    if ((!isset($_REQUEST['token'])) || ($_REQUEST['token'] != genUToken('admin.static'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token'], 'info' => $lang['error.security.token#desc']]);

        return -1;
    }

    $id = intval($_REQUEST['id']);
    $title = $_REQUEST['title'];
    $content = $_REQUEST['content'];
    $alt_name = $parse->translit(trim($_REQUEST['alt_name']), 1, 1);

    // Try to find news that we're trying to edit
    if (!is_array($row = $mysql->record('select * from '.prefix.'_static where id='.db_squote($id)))) {
        msg(['type' => 'error', 'text' => $lang['msge_not_found']]);

        return -1;
    }

    if ((!strlen(trim($title))) || (!strlen(trim($content)))) {
        msgSticker([[$lang['msge_fields'], 'title', 1], [$lang['msgi_fields'], '', 1]], 'error', 1);

        return 0;
    }

    $SQL['title'] = $title;

    // Check for dup if alt_name is specified
    if (is_array($mysql->record('select id from '.prefix.'_static where alt_name = '.db_squote($alt_name).' and id <> '.$row['id'].' limit 1'))) {
        msgSticker([[$lang['msge_alt_name'], 'title', 1], [$lang['msgi_alt_name'], '', 1]], 'error', 1);

        //msg(array("type" => "error", "text" => $lang['msge_alt_name'], "info" => $lang['msgi_alt_name']));
        return 0;
    }
    $SQL['alt_name'] = $alt_name;

    if ($config['meta']) {
        $SQL['description'] = $_REQUEST['description'];
        $SQL['keywords'] = $_REQUEST['keywords'];
    }

    $SQL['content'] = $content;

    $SQL['template'] = $_REQUEST['template'];
    $SQL['approve'] = intval($_REQUEST['flag_published']);
    if (isset($_POST['set_postdate']) && $_POST['set_postdate']) {
        if (preg_match('#^(\d+)\.(\d+)\.(\d+) +(\d+)\:(\d+)$#', $_REQUEST['cdate'], $m)) {
            $SQL['postdate'] = mktime($m[4], $m[5], 0, $m[2], $m[1], $m[3]) + ($config['date_adjust'] * 60);
        }
    }

    $SQL['flags'] = (($perm['html'] && isset($_REQUEST['flag_raw']) && $_REQUEST['flag_raw']) ? 1 : 0) + (($perm['html'] && isset($_REQUEST['flag_html']) && $_REQUEST['flag_html']) ? 2 : 0) + (($perm['html'] && isset($_REQUEST['flag_template_main']) && $_REQUEST['flag_template_main']) ? 4 : 0);

    if (is_array($PFILTERS['static'])) {
        foreach ($PFILTERS['static'] as $k => $v) {
            $v->editStatic($row['id'], $row, $SQL, $tvars);
        }
    }

    $SQLparams = [];
    foreach ($SQL as $k => $v) {
        $SQLparams[] = $k.' = '.db_squote($v);
    }

    $mysql->query('update '.prefix.'_static set '.implode(', ', $SQLparams).' where id = '.db_squote($id));

    $link = (checkLinkAvailable('static', '') ?
        generateLink('static', '', ['altname' => $SQL['alt_name'], 'id' => $id], [], false, true) :
        generateLink('core', 'plugin', ['plugin' => 'static'], ['altname' => $SQL['alt_name'], 'id' => $id], false, true));

    msgSticker($lang['msg.edited']);
    /*
        msg(array(
            "text" => str_replace('{url}',$link , $lang['msg.edited']),
            "info" => str_replace(array('{url}', '{url_edit}', '{url_list}'), array($link, $PHP_SELF.'?mod=static&action=edit&id='.$id, $PHP_SELF.'?mod=static'), $lang['msg.edited#descr'])));
    */

    // Return ID
    return $id;
}

// #=======================================#
// # Action selection                      #
// #=======================================#

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'add':
        if ($id = addStatic()) {
            $main_admin = addEditStaticForm(3, $id);
        } else {
            $main_admin = addEditStaticForm(2);
        }
        break;

    case 'addForm':
        $main_admin = addEditStaticForm(1);
        break;

    case 'edit':
        if (($id = editStatic()) > 0) {
            $main_admin = addEditStaticForm(3, $id);
        } elseif ($id == 0) {
            $main_admin = addEditStaticForm(4);
        } else {
            $main_admin = listStatic();
        }
        break;

    case 'editForm':
        $main_admin = addEditStaticForm(3);
        break;

    default:
        switch ($action) {
            case 'do_mass_approve':
                $main_admin = massStaticModify('approve = 1', 'msgo_approved', 'approve');
                break;
            case 'do_mass_forbidden':
                $main_admin = massStaticModify('approve = 0', 'msgo_forbidden', 'forbidden');
                break;
            case 'do_mass_delete':
                $main_admin = massStaticDelete();
                break;
        }
        $main_admin = listStatic();

    }
