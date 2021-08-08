<?php

//
// Copyright (C) 2006-2010 Next Generation CMS (http://ngcms.ru/)
// Name: static.php
// Description: Static pages display sub-engine
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('static', 'site');

// Params - Static page characteristics
// * id			- page ID
// * altname	- alt. name of the page
function showStaticPage($params)
{
    global $config, $twig, $mysql, $userROW, $parse, $template, $lang, $SYSTEM_FLAGS, $PFILTERS, $SUPRESS_TEMPLATE_SHOW;

    loadActionHandlers('static');

    $limit = '';
    if ((int) $params['id']) {
        $limit = 'id = '.db_squote($params['id']);
    } elseif ($params['altname']) {
        $limit = 'alt_name = '.db_squote($params['altname']);
    }

    if ((!$limit) || (!is_array($row = $mysql->record('select * from '.prefix.'_static where approve = 1 and '.$limit)))) {
        if (!$params['FFC']) {
            error404();
        }

        return false;
    }

    // Save some significant news flags for plugin processing
    $SYSTEM_FLAGS['static']['db.id'] = $row['id'];

    if (is_array($PFILTERS['static'])) {
        foreach ($PFILTERS['static'] as $k => $v) {
            $v->showStaticPre($row['id'], $row, []);
        }
    }

    $content = $row['content'];

    // If HTML code is not permitted - LOCK it
    if (!($row['flags'] & 2)) {
        $content = str_replace('<', '&lt;', $content);
    }

    if ($config['blocks_for_reg']) {
        $content = $parse->userblocks($content);
    }
    if ($config['use_htmlformatter'] && (!($row['flags'] & 1))) {
        $content = $parse->htmlformatter($content);
    }
    if ($config['use_bbcodes']) {
        $content = $parse->bbcodes($content);
    }
    if ($config['use_smilies']) {
        $content = $parse->smilies($content);
    }

    $tvars = [
        'title'    => $row['title'],
        'content'  => $content,
        'postdate' => ($row['postdate'] > 0) ? strftime('%d.%m.%Y %H:%M', $row['postdate']) : '',
    ];

    // Check perms and show modify buttons
    $adminpanelPerms = checkPermission(['plugin' => '#admin', 'item' => 'system'], $userROW, 'admpanel.view');
    $canViewAdminPanel = is_array($userROW) && $adminpanelPerms;

    $staticPerms = checkPermission(['plugin' => '#admin', 'item' => 'static'], null, [
        'view',
        'modify',
    ]);

    $showModifyButtons = $canViewAdminPanel
                            && $staticPerms['view']
                            && $staticPerms['modify'];

    $tvars['flags']['canEdit'] = false;

    if ($showModifyButtons) {
        $tvars['flags']['canEdit'] = true;
        $tvars['edit_static_url'] = admin_url.'/admin.php?mod=static&action=editForm&id='.$row['id'];
    }

    $tvars['print_static_url'] = generatePluginLink('static', 'print', ['id' => $row['id'], 'altname' => $params['altname']], [], true);

    if (is_array($PFILTERS['static'])) {
        foreach ($PFILTERS['static'] as $k => $v) {
            $v->showStatic($row['id'], $row, $tvars, []);
        }
    }

    executeActionHandler('static', $row);

    if (!$row['template']) {
        $templateName = 'static/default';
    } else {
        $templateName = 'static/'.$row['template'];
    }

    // Check for print mode
    if ($params['print'] && file_exists(tpl_dir.$config['theme'].'/static/'.($row['template'] ? $row['template'] : 'default').'.print.tpl')) {
        $templateName .= '.print';
        $SUPRESS_TEMPLATE_SHOW = true;
    }

    // Check for OWN main.tpl for static page
    if (($row['flags'] & 4) && file_exists(tpl_dir.$config['theme'].'/static/'.($row['template'] ? $row['template'] : 'default').'.main.tpl')) {
        $SYSTEM_FLAGS['template.main.name'] = ($row['template'] ? $row['template'] : 'default').'.main';
        $SYSTEM_FLAGS['template.main.path'] = tpl_dir.$config['theme'].'/static';
    }

    // Set meta tags for news page
    $SYSTEM_FLAGS['info']['title']['item'] = secure_html($row['title']);
    $SYSTEM_FLAGS['meta']['description'] = $row['description'];
    $SYSTEM_FLAGS['meta']['keywords'] = $row['keywords'];

    $template['vars']['titles'] .= ' : '.$row['title'];
    $template['vars']['mainblock'] .= $twig->render($templateName.'.tpl', $tvars);

    return true;
}
